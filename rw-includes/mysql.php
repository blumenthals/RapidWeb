<?php 

ini_set('include_path', ini_get('include_path').":".dirname(__FILE__)."/modyllic");
require_once "Modyllic/Generator.php";
require_once "Modyllic/Diff.php";
require_once "Modyllic/SQL.php";

function update_modyllic() {

    list( $dsn, $dbname, $user, $pass ) = Modyllic_Schema_Loader::parse_dsn($dsn); // FIXME

    $base_dsn = "mysql:";
    if ( isset($host) ) {
        $base_dsn .= "host=$host;";
    }
    $dsn = $base_dsn . "dbname=$dbname";
        $dbh = new PDO( $dsn, $user, $pass, array( PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION ) );

    $from = Modyllic_Loader::load($dsnFIXME);
    $to = Modyllic_Loader::load(dirname(__FILE__).'');

    $diff = new Modyllic_Diff( $from, $to );

    if ( ! $diff->changeset->has_changes() ) {
        print "-- No changes detected.\n";
       return(0);
    }

    $gen = new Modyllic_Generator_SQL();
    foreach ( $gen->sql_header() as $sql ) {
        $dbh->exec( $sql );
    }
    $gen->alter($diff);
    $cmds = count($gen->sql_commands());
    try {
        $ii = 0;
        foreach ($gen->sql_commands() as $cmd) {
            $dbh->exec($cmd);
        }
    }
    catch (PDOException $e) {
        print $e->getMessage()."\n";
        print "Full context of command:\n";
        print $cmd."\n";
        return(1);
    }

}

define('RAPIDWEB_DB_VERSION', 11);

/** Opens the database connection
 */
function OpenDataBase() {
    global $mysql_server, $mysql_user, $mysql_pwd, $mysql_db;

    if (!($dbc = mysql_pconnect($mysql_server, $mysql_user, $mysql_pwd))) {
        $msg = "Cannot establish connection to database, giving up.";
        $msg .= "<BR>";
        $msg .= sprintf("MySQL error: %s", mysql_error());
        ExitWiki($msg);
    }
    if (!mysql_select_db($mysql_db, $dbc)) {
        $msg =  sprintf("Cannot open database %s, giving up.", $mysql_db);
        $msg .= "<BR>";
        $msg .= sprintf("MySQL error: %s", mysql_error());
        ExitWiki($msg);
    }
    mysql_query("SET NAMES 'utf8'", $dbc);
    $dbi['dbc'] = $dbc;
    $dbi['table'] = 'wiki';

    $db_version = rw_db_get_version();

    if($db_version < RAPIDWEB_DB_VERSION) {
        echo("Database needs upgrade from $db_version to ".RAPIDWEB_DB_VERSION);
        do {
            $last = $db_version;
            $func = 'rw_upgrade_database_'.$db_version.'_'.($db_version + 1);
            if(function_exists($func)) {
                call_user_func($func);
                $db_version = rw_db_get_version();
                if($db_version == $last) die("Upgrade failed, version still at $db_version");
            } else {
                die("Can't upgrade database");
            }
        } while($db_version < RAPIDWEB_DB_VERSION);

        die("Database now at $db_version");
    }

    return $dbi;
}

function rw_db_get_version() {
  $db_version = -1;

  if($r = mysql_query("SELECT value FROM rapidwebinfo WHERE name = 'db_version'")) {
     $row = mysql_fetch_assoc($r);
     if($row) {
        $db_version = $row['value'];
     } else {
        $db_version = 0;
     }
  } else {
     $e = mysql_error();
     if(preg_match("/doesn't exist/", $e)) {
        $db_version = 0;
     } else {
        die("Database error: $e");
     }
  }
  return $db_version;
}

function rw_db_query($sql) {
  if(!$r = mysql_query($sql)) echo("Query failed (".mysql_error()."): $sql");
  return $r;
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
function MakePageHash($dbhash) {
  $dbhash['refs'] = unserialize($dbhash['refs']);
  $dbhash['gallery'] = json_decode($dbhash['gallery']);
  if(!$dbhash['plugins'] = json_decode($dbhash['plugins'])) $dbhash['plugins'] = new StdClass;
  $dbhash['settings'] = RetrieveSettings();
  return $dbhash;
}


/** Return hash of page + attributes or default
*
* @param $dbi the database connection information hash
* @param $pagename the page name to fetch
*
* @returns a page hash
*
* @todo Move into RapidWebPage class
*/
function RetrievePage($dbi, $pagename) {
  $pagename = mysql_real_escape_string($pagename, $dbi['dbc']);
  if ($res = mysql_query("select * from {$dbi['prefix']}wiki where pagename='$pagename'", $dbi['dbc'])) {
     if ($dbhash = mysql_fetch_assoc($res)) {
        return MakePageHash($dbhash);
     }
  }
  return array(
      "version" => 0,
      'lastmodified' => time(),
      'author' => '',
      'plugins' => new StdClass(),
      'settings' => RetrieveSettings()
  );
}


// Either insert or replace a key/value (a page)
function InsertPage($dbi, $pagename, $pagehash) {
    $pagehash = MakeDBHash($pagename, $pagehash);

    $COLUMNS = "author, content, created, flags, lastmodified, pagename, refs, version, title, meta, keywords, variables, noindex, template, page_type, gallery, plugins";

    $VALUES = array(
        $pagehash['author'], $pagehash['content'],
        $pagehash['created'], $pagehash['flags'], 
        $pagehash['lastmodified'], $pagehash['pagename'], 
        $pagehash['refs'], $pagehash['version'],
        $pagehash['title'], $pagehash['meta'],
        $pagehash['keywords'], $pagehash['variables'],
        $pagehash['noindex'], (isset($pagehash['template']) ? $pagehash['template'] : 'NULL'),
        $pagehash['page_type'],
        $pagehash['gallery'],
        $pagehash['plugins']
    );

    foreach($VALUES as $k => $v) {
        if($v === null || $v === 'NULL') {
            $VALUES[$k] = 'NULL';
        } else {
            $VALUES[$k] = "'".mysql_real_escape_string($v, $dbi['dbc'])."'";
        }
    }
    $VALUES = join($VALUES, ', ');
    if (!mysql_query($q = "replace into ${$dbi[prefix]}wiki ($COLUMNS) values ($VALUES)", $dbi['dbc'])) {
        $msg = sprintf("Error writing page '%s'", $pagename);
        $msg .= "<BR>";
        $msg .= sprintf("MySQL error: %s", mysql_error()." in $q");
        ExitWiki($msg);
   }
}

function SaveSettings($settingshash) {
   foreach($settingshash as $key => $value) {
      $key = addslashes($key);
      $value = addslashes($value);
      mysql_query("REPLACE INTO settings (name, value) VALUES ('$key', '$value');");
   }
}

function RetrieveSettings() {
  if ($settings = mysql_query("SELECT name, value FROM settings")) {
    $settingshash = array();
    while(list($key, $value) = mysql_fetch_row($settings)) {
       $settingshash[$key] = $value;
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


function IsWikiPage($dbi, $pagename) {
  $pagename = addslashes($pagename);
  if ($res = mysql_query("select count(*) from $dbi[table] where pagename='$pagename'", $dbi['dbc'])) {
     return(mysql_result($res, 0));
  }
  return 0;
}

function IsInArchive($dbi, $pagename) {
  global $ArchivePageStore;

  $pagename = addslashes($pagename);
  if ($res = mysql_query("select count(*) from $ArchivePageStore where pagename='$pagename'", $dbi['dbc'])) {
     return(mysql_result($res, 0));
  }
  return 0;
}


function RemovePage($dbi, $pagename) {
  global $ArchivePageStore;

  $pagename = addslashes($pagename);
  $msg = ("Cannot delete '%s' from table '%s'");
  $msg .= "<br>\n";
  $msg .= ("MySQL error: %s");

  if (!mysql_query("delete from {$dbi['prefix']}wiki where pagename='$pagename'", $dbi['dbc']))
     ExitWiki(sprintf($msg, $pagename, mysql_error()));

  if (!mysql_query("delete from $ArchivePageStore where pagename='$pagename'", $dbi['dbc']))
     ExitWiki(sprintf($msg, $pagename, $ArchivePageStore, mysql_error()));
}


function MakeSQLSearchClause($search, $column) {
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
if ($term = strtok(' '))
   $clause .= 'and ';
  }
  return $clause;
}

// setup for title-search
function InitTitleSearch($dbi, $search) {
  $clause = MakeSQLSearchClause($search, 'pagename');
  $res = mysql_query("select pagename from $dbi[table] where $clause order by pagename", $dbi["dbc"]);

  return $res;
}


// iterating through database
function TitleSearchNextMatch($dbi, $res) {
  if($o = mysql_fetch_object($res)) {
     return $o->pagename;
  }
  else {
     return 0;
  }
}


// setup for full-text search
function InitFullSearch($dbi, $search) {
  $clause = MakeSQLSearchClause($search, 'content');
  $res = mysql_query("select * from $dbi[table] where $clause", $dbi["dbc"]);

  return $res;
}

// iterating through database
function FullSearchNextMatch($dbi, $res) {
  if($hash = mysql_fetch_array($res)) {
     return MakePageHash($hash);
  }
  else {
     return 0;
  }
}

function GetAllWikiPageNames($dbi) {
  $res = mysql_query("select pagename from {$dbi['prefix']}wiki", $dbi["dbc"]);
  $rows = mysql_num_rows($res);
  for ($i = 0; $i < $rows; $i++) {
    $pages[$i] = mysql_result($res, $i);
  }
  return $pages;
}
