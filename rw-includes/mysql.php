<?php 

function update_modyllic(\OLB_PDO $dbc) {
    global $mysql_user;
    global $mysql_pwd;
    global $mysql_server;
    global $mysql_db;

    assert('is_object($dbc)');

    $dsn = "mysql:host=$mysql_server:dbname=$mysql_db:username=$mysql_user:password=$mysql_pwd";
    list( $driver, $ndsn, $dbname, $user, $pass ) = Modyllic_Loader_DB::parse_dsn($dsn);
    $gen_class = Modyllic_Generator::dialect_to_class($driver);

    $from = Modyllic_Loader::load( array($dsn) );

    $schemas = array(
        dirname(__FILE__).'/schema.sql'
    );

    foreach(glob(dirname(__FILE__).'/../rw-content/plugins/*/schema.sql') as $file) {
        $schemas[] = $file;
    }

    $to   = Modyllic_Loader::load($schemas);
    $gen = new $gen_class();
    $gen->generate_metatable( $to );

    $diff = new Modyllic_Diff( $from, $to );
    $gen->alter($diff);

    if (! $gen->sql_commands()) {
        echo "No changes\n";
        return(0);
    }

    echo "<pre>";
    foreach ( $gen->sql_header() as $sql ) {
        echo "$sql\n";
        $dbc->exec( $sql );
    }
    $cmds = count($gen->sql_commands());
    try {
        $ii = 0;
        foreach ($gen->sql_commands() as $cmd) {
            echo "$cmd\n";
            $dbc->exec($cmd);
        }
    }
    catch (PDOException $e) {
        print $e->getMessage()."\n";
        print "Full context of command:\n";
        print $cmd."\n";
        exit(1);
    }
    echo "</pre>";

}

define('RAPIDWEB_DB_VERSION', 11);

/** Opens the database connection
 */
function OpenDataBase() {
    global $mysql_server, $mysql_user, $mysql_pwd, $mysql_db;

    $dbc = new OLB_PDO("mysql:host=$mysql_server;dbname=$mysql_db", $mysql_user, $mysql_pwd);
    $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbc->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbc->exec("SET NAMES 'utf8'");

    return $dbc;
}

/** prepare page data hash for storing in mysql */
function MakeDBHash($pagename, $pagehash) {
  if (!isset($pagehash["flags"]))
     $pagehash["flags"] = 0;
  $pagehash["content"] = implode("\n", $pagehash['content']);
  if (!isset($pagehash["refs"]))
     $pagehash["refs"] = array();
  $pagehash["refs"] = serialize($pagehash["refs"]);
  if(!isset($pagehash['pagename'])) $pagehash['pagename'] = $pagename;
  $pagehash['gallery'] = json_encode($pagehash['gallery']);
  $pagehash['plugins'] = json_encode($pagehash['plugins']);

  if(!isset($pagehash['created'])) $pagehash['created'] = time();

  return $pagehash;
}


/** Deserialize components of page data coming from MySQL */
function MakePageHash(\OLB_PDO $dbc, $dbhash) {
  $dbhash['refs'] = unserialize($dbhash['refs']);
  $dbhash['gallery'] = json_decode($dbhash['gallery']);
  if(!$dbhash['plugins'] = json_decode($dbhash['plugins'])) $dbhash['plugins'] = new StdClass;
  $dbhash['settings'] = RetrieveSettings($dbc);
  return $dbhash;
}


/** Return hash of page + attributes or default
*
* @param $dbc the database connection
* @param $pagename the page name to fetch
*
* @returns a page hash
*
* @todo Move into RapidWebPage class
*/
function RetrievePage(\OLB_PDO $dbc, $pagename) {
    $res = $dbc->prepare("select * FROM wiki WHERE pagename = ?");
    if ($res->execute(array($pagename))) {
        if ($dbhash = $res->fetch(PDO::FETCH_ASSOC)) {
            return MakePageHash($dbc, $dbhash);
        }
    }
    return array(
        "version" => 0,
        'lastmodified' => time(),
        'author' => '',
        'plugins' => new StdClass(),
        'settings' => RetrieveSettings($dbc)
    );
}


// Either insert or replace a key/value (a page)
function InsertPage($dbc, $pagename, $pagehash) {
    $pagehash = MakeDBHash($pagename, $pagehash);

    $VALUES = array(
        $pagehash['author'], $pagehash['content'],
        $pagehash['created'], $pagehash['flags'], 
        $pagehash['lastmodified'], $pagehash['pagename'], 
        $pagehash['refs'], $pagehash['version'],
        $pagehash['title'], $pagehash['meta'],
        $pagehash['keywords'], $pagehash['variables'],
        $pagehash['noindex'], (isset($pagehash['template']) ? $pagehash['template'] : NULL),
        $pagehash['page_type'],
        $pagehash['gallery'],
        $pagehash['plugins'],
        $pagehash['head'], $pagehash['foot']
    );

    $res = $dbc->prepare("REPLACE INTO wiki (author, content, created, flags, lastmodified, pagename, refs, version, title, meta, keywords, variables, noindex, template, page_type, gallery, plugins, head, foot) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$res->execute($VALUES)) {
        $msg = sprintf("Error writing page '%s'", $pagename);
        ExitWiki($msg);
   }
}

function SaveSettings(\OLB_PDO $dbc, $settingshash) {
   foreach($settingshash as $key => $value) {
      $stmt = $dbc->prepare("REPLACE INTO settings (name, value) VALUES (?, ?);");
      $stmt->execute(array($key, $value));
   }
}

function RetrieveSettings(\OLB_PDO $dbc) {
  if ($settings = $dbc->query("SELECT name, value FROM settings")) {
    $settingshash = array();
    while($row = $settings->fetch(PDO::FETCH_ASSOC)) {
       $settingshash[$row['name']] = $row['value'];
    }
    return $settingshash;
  }
  else {
    return false;
  }
}

// for archiving pages to a seperate dbm
function SaveCopyToArchive($dbi, $pagename, $pagehash) {
  global $ArchivePageStore;
  $adbi = OpenDataBase($ArchivePageStore);
  InsertPage($adbi, $pagename, $pagehash);
}


function IsWikiPage(\OLB_PDO $dbc, $pagename) {
    $res = $dbc->prepare("SELECT count(*) AS count FROM wiki WHERE pagename = ?");
    if ($res->execute(array($pagename))) {
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }
    return 0;
}

function IsInArchive(\OLB_PDO $dbc, $pagename) {
    try {
        $stmt = $dbc->prepare("SELECT count(*) AS count FROM archive WHERE pagename = ?");
        $stmt->execute(array($pagename));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['count'];
    } catch(Exception $e) {
        return 0;
    }
}

function RemovePage(\OLB_PDO $dbc, $pagename) {
    $stmt = $dbc->prepare("DELETE FROM wiki WHERE pagename = ?");
    $stmt->execute(array($pagename));
}

function MakeSQLSearchClause($search, $column) {
    // This could be refactored
    $search = addslashes(preg_replace("/\s+/", " ", $search));
    $term = strtok($search, ' ');
    $clause = '';
    while($term) {
        $word = "$term";
        if ($word[0] == '-') {
            $word = substr($word, 1);
            $clause .= "not ($column like '%$word%') ";
        } else {
            $clause .= "($column like '%$word%') ";
        }
        if ($term = strtok(' ')) {
            $clause .= 'and ';
        }
    }
    return $clause;
}

// setup for title-search
function InitTitleSearch(\OLB_PDO $dbc, $search) {
    $clause = MakeSQLSearchClause($search, 'pagename');
    $res = $dbc->exec("SELECT pagename FROM wiki WHERE $clause ORDER BY pagename");
    return $res;
}


// iterating through database
function TitleSearchNextMatch(\OLB_PDO $dbc, $res) {
  if($o = $res->fetch(PDO::FETCH_ASSOC)) {
     return $o->pagename;
  }
  else {
     return 0;
  }
}


// setup for full-text search
function InitFullSearch(\OLB_PDO $dbc, $search) {
    $clause = MakeSQLSearchClause($search, 'content');
    $res = $dbc->query("SELECT * FROM wiki WHERE $clause");
    return $res;
}

// iterating through database
function FullSearchNextMatch(\OLB_PDO $dbc, $res) {
    if($hash = $res->fetch(\OLB_PDO::FETCH_ASSOC)) {
        return MakePageHash($dbc, $hash);
    } else {
        return 0;
    }
}

function GetAllWikiPageNames(\OLB_PDO $dbc) {
    $res = $dbc->query("SELECT pagename FROM wiki");
    $rows = $res->numRows();
    for ($i = 0; $i < $rows; $i++) {
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $pages[$i] = $row['pagename'];
    }
    return $pages;
}
