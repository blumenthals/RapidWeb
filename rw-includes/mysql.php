<?php 

define('RAPIDWEB_DB_VERSION', 10);

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

/** Upgrade the initial revision of the database
 */
function rw_upgrade_database_0_1() {
    rw_db_canexist(rw_db_query("CREATE TABLE `archive` (
      `pagename` varchar(100) NOT NULL default '',
      `version` int(11) NOT NULL default '1',
      `flags` int(11) NOT NULL default '0',
      `author` varchar(100) default NULL,
      `lastmodified` int(11) NOT NULL default '0',
      `created` int(11) NOT NULL default '0',
      `content` mediumtext NOT NULL,
      `refs` text,
      PRIMARY KEY  (`pagename`)
    )"));

    rw_db_canexist(rw_db_query("CREATE TABLE `hitcount` (
      `pagename` varchar(100) NOT NULL default '',
      `hits` int(11) NOT NULL default '0',
      PRIMARY KEY  (`pagename`)
    )"));

    rw_db_canexist(rw_db_query("CREATE TABLE `wiki` (
      `pagename` varchar(100) NOT NULL default '',
      `version` int(11) NOT NULL default '1',
      `flags` int(11) NOT NULL default '0',
      `author` varchar(100) default NULL,
      `lastmodified` int(11) NOT NULL default '0',
      `created` int(11) NOT NULL default '0',
      `content` mediumtext NOT NULL,
      `refs` text,
      PRIMARY KEY  (`pagename`)
    )"));

    rw_db_query("INSERT IGNORE INTO `wiki` VALUES ('home', 7, 1, 'admin', 1019004589, 1012377328, '!Welcome\n\nStart with your SiteMap', 'a:0:{}');");
    rw_db_query("INSERT IGNORE INTO `wiki` VALUES ('RecentChanges', 2, 1, 'admin', 1019004720, 1012377346, '!Recent Changes\n\n____April 16, 2002\n* [FindPage] ([diff|phpwiki:?diff=FindPage]) ..... admin\r\n* [BackUp] ([diff|phpwiki:?diff=BackUp]) ..... admin\r\n* [RecentChanges] ([diff|phpwiki:?diff=RecentChanges]) ..... admin\r\n* [SiteMap] ([diff|phpwiki:?diff=SiteMap]) ..... admin\n* [home] ([diff|phpwiki:?diff=home]) ..... admin', 'a:0:{}');");
    rw_db_query("INSERT IGNORE INTO `wiki` VALUES ('ContactUs', 1, 1, 'admin', 1012377736, 1012377736, '!Contact Us', 'a:0:{}');");
    rw_db_query("INSERT IGNORE INTO `wiki` VALUES ('FindPage', 3, 1, 'admin', 1019004925, 1012377865, '!Search Our Site\n\nView the SiteMap, RecentChanges, or use the following for a full text search. It will search any page within the website. This takes a few seconds. The results will show all lines on a given page that contain a match.\n\n%%Fullsearch%%\n\n------\n\nSeparate words with a space. All words have to match. To exclude words prepend a \'-\'. Example: \'services -internet\' looks for all pages containing the words \'services\' but not containing the word \'internet\'', 'a:0:{}');");
    rw_db_query("INSERT IGNORE INTO `wiki` VALUES ('BackUp', 7, 1, 'admin', 1019004866, 1012380713, '!!RapidAdminPage\r\n\r\n__\'\'This works only if you are logged in as ADMIN\'\'__\r\n-----------\r\n\r\n! ZIP files of database\r\n\r\n__[ZIP Snapshot | phpwiki:?zip=snapshot]__ : contains only the latest versions\r\n\r\n__[ZIP Dump | phpwiki:?zip=all]__ : contains all archived versions\r\n\r\nThese links lead to zip files*, generated on the fly, which contain the most recent versions of all pages in your !RapidWeb. You will be prompted to save the zip file to your local computer.\r\n\r\nIf you want to view/restore pages from the zip file, you will need [WinZip|http://www.winzip.com] (PC) or [StuffIt Expander|http://www.aladdinsys.com/expander] (MAC) to extract and view pages from the zip file. (*The pages are stored, one per file, as MIME (RFC2045) e-mail (RFC822) messages.)\r\n\r\n-----------\r\n\r\n! Load / Dump Serialized Pages\r\n\r\nHere you can load or dump pages of your site into a server directory of your choice. (Note: The pages are not located on your local computer, as in the Zip option.)\r\n\r\n__Dump__\r\n\r\n%%ADMIN-INPUT-dumpserial-Dump_serialized_pages%%\r\n\r\nYou must use the server path when Dumping (backup) and Loading (restoring) data:\r\n\r\nIn this example, \"yourdomain\" would be your domain name without the last part (.com, .net, .org, etc...)\r\n\r\n%%%__/home/username/data/MM-DD-YY__\r\n\r\nAppend the date to the string like this:\r\n%%%__/home/username/data/06-07-01__\r\n\r\nThen you\'ll have a backup folder created for that date. The backup data will be stored in a secure area of the site (at the root level above public_html). Pages will be written out as \"serialized\" strings of a PHP associative array, meaning they will not be human readable.\r\n\r\nIf the directory does not exist the !RapidWeb will try to create one for you. Ensure that your server has write permissions to the directory!\r\n\r\n__Load__\r\n\r\n%%ADMIN-INPUT-loadserial-Load_serialized_pages%%\r\n\r\nIf you have dumped a set of pages from !RapidWeb, you can reload them here. Note that pages in your database will be overwritten; thus, if you dumped your ContactUs page when you load it from this form it will overwrite the one in your database now.\r\n\r\nIf you want to be selective just delete the pages from the directory you don\'t want to load. (You\'ll need to do this via FTP.)\r\n\r\n-----------', 'a:0:{}');");

   rw_db_canexist(rw_db_query("CREATE TABLE rapidwebinfo (name  varchar(32) not null primary key, value text)"));
   rw_db_query("REPLACE INTO rapidwebinfo (name, value) VALUES ('db_version', 0)");
   rw_db_canexist(rw_db_query("ALTER TABLE wiki add COLUMN `title` text"));
   rw_db_canexist(rw_db_query("ALTER TABLE wiki add COLUMN `keywords` text"));
   rw_db_canexist(rw_db_query("ALTER TABLE wiki add COLUMN `meta` text"));
   rw_db_canexist(rw_db_query("ALTER TABLE archive add COLUMN `meta` text"));
   rw_db_canexist(rw_db_query("ALTER TABLE archive add COLUMN `title` text"));
   rw_db_canexist(rw_db_query("ALTER TABLE archive add COLUMN `keywords` text"));
   rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 1)");
}

   function rw_upgrade_database_1_2() {
      rw_db_canexist(rw_db_query("CREATE TABLE `settings` (
        `name` varchar(100) NOT NULL,
        `value` varchar(255) default NULL,
        PRIMARY KEY  (`name`)
      )"));
      rw_db_query("INSERT IGNORE INTO settings VALUES('default_title', 'Blumenthals.com Rapidweb Website');");
      rw_db_query("INSERT IGNORE INTO settings VALUES('default_meta_keywords', '')");
      rw_db_query("INSERT IGNORE INTO settings VALUES('default_meta_description', '')");
      rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 2)");
   }

   function rw_upgrade_database_2_3() {
      rw_db_canexist(rw_db_query("alter table wiki add variables text"));
      rw_db_canexist(rw_db_query("alter table archive add variables text"));
      rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 3)");
   }

   function rw_upgrade_database_3_4() {
      rw_db_canexist(rw_db_query("alter table wiki add template varchar(100)"));
      rw_db_canexist(rw_db_query("alter table archive add template varchar(100)"));
      rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 4)");
      
   }

   function rw_upgrade_database_4_5() {
      rw_db_query("INSERT IGNORE INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('SiteMap', 1, 0, 'admin', 1227977883, 1227977883, '*[Home]\n*[Services]\n*[Products]\n*[Contact Us]\n*[Privacy Notice]\n*[Links]\n*[Search|FindPage]\n\n*Custom Error Pages:\n**[404-FileNotFound]\n**[403-Restricted]\n**[500-ServerError]\n**[401-AuthorizationRequired]\n\n*[Blumenthals  Olean NY Web Hosting Support options|BlumenthalsSupport]', 'a:0:{}', '', '', '', '', NULL)");
      rw_db_query("INSERT IGNORE INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('401-AuthorizationRequired', 1, 0, 'admin', 1222318755, 1222318755, 'STARTHTML\n<style type='text/css'>\n<!--\nbody, td, th {\n        font-family: Arial, Helvetica, sans-serif;\n       font-size: 12px;\n}\na:link, a:visited, a:active {\n    color: #C82127;\n}\na:hover {\n color: #E62128;\n}\nh1 {\n font-weight: bold;\n    font-size: 14px;\n      color: #C82127;\n}\n.pngimg {\n behavior: url('/rw-global/pngbehavior.htc');\n}\n-->\n</style>\n<center>\n<table border='0' cellspacing='0' cellpadding='0'>\n<tr>\n<td><img src='/rw-global/images/edit/401b. png' alt='401 - Authorization Required' width=271 height=178 class=pngimg></td>\n<td>\n\n<h1>This is a restricted area.</h1>\nWe apologize. The page you are looking<br>\nfor requires the proper authorization.<br><br>\n\nPlease try your request again or try searching<br>\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href='###SCRIPTURL###?Sitemap'>sitemap</a>, start over from the <a href='index.php?home'>home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', '', '', '', NULL, NULL);");
      rw_db_query("INSERT IGNORE INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('403-Restricted', 1, 0, 'admin', 1222318726, 1222318726, 'STARTHTML\n<style type=\"text/css\">\n<!--\nbody, td, th {\n   font-family: Arial, Helvetica, sans-serif;\n       font-size: 12px;\n}\na:link, a:visited, a:active {\n    color: #C82127;\n}\na:hover {\n color: #E62128;\n}\nh1 {\n font-weight: bold;\n    font-size: 14px;\n      color: #C82127;\n}\n.pngimg {\n behavior: url(\"/rw-global/pngbehavior.htc\");\n}\n-->\n</style>\n<center>\n<table border=0 cellspacing=0 cellpadding=0>\n<tr>\n<td><img src=\"/rw-global/images/edit/403b.png\" alt=\"403 - Forbidden; width=303 height=179 class=pngimg></td>\n<td>\n\n<h1>This is a restricted area.</h1>\nWe apologize. The page you are looking for is in<br>\na restricted area and is not available to the public.<br><br>\n\nIf you''re in denial and think this is a conspiracy<br >\nthat cannot be possibly true, please try searching<br>\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href=\"###SCRIPTURL###?Sitemap\">sitemap</a>, start over from the <a href=\"index.php?home\">home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', '', '', '', NULL, NULL)");
      rw_db_query("INSERT IGNORE INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('404-FileNotFound', 8, 1, 'admin', 1222290675, 1012391280, 'STARTHTML\n<style type=\"text/css\">\n<!--\nbody, td, th {\n font-family: Arial, Helvetica, sans-serif;\n       font-size: 12px;\n}\na:link, a:visited, a:active {\n    color: #F28B22;\n}\na:hover {\n color: #F6B618;\n}\nh1 {\n font-weight: bold;\n    font-size: 14px;\n      color: #F28B22;\n}\n.pngimg {\n behavior: url(\"/rw-global/pngbehavior.htc\");\n}\n-->\n</style>\n<center>\n<table border=0 cellspacing=0 cellpadding=0>\n<tr>\n<td><img src=\"/rw-global/images/edit/404b.png\" alt=\"404 - File Not Found\" width=248 height=171 class=pngimg /></td>\n<td>\n\n<h1>Oops, Page Not Found.</h1>\nWe apologize. The page you<br>\nare looking for cannot be found.<br><br>\n\nIf you''re in denial and think this is a conspiracy<br>\nthat cannot be possibly true, please try searching<br>\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href=\"###SCRIPTURL###?Sitemap\">sitemap</a>, start over from the <a href=\"index.php?home\">home page</a>, or select<br>\n from the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', NULL, NULL, NULL, NULL, NULL)");
      rw_db_query("INSERT IGNORE INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('500-ServerError', 1, 0, 'admin', 1222318742, 1222318742, 'STARTHTML\n<style type=\"text/css\">\n<!--\nbody, td, th {\n  font-family: Arial, Helvetica, sans-serif;\n       font-size: 12px;\n}\na:link, a:visited, a:active {\n    color: #F28B22;\n}\na:hover {\n color: #F6B618;\n}\nh1 {\n font-weight: bold;\n    font-size: 14px;\n      color: #F28B22;\n}\n.pngimg {\n behavior: url(\"/rw-global/pngbehavior.htc\");\n}\n-->\n</style>\n<center>\n<table border=0 cellspacing=0 cellpadding=0>\n<tr>\n<td><img src=\"/rw-global/images/edit/500b.png\" alt=\"500 - Internal Server Error\" width=213 height=173 class=pngimg></td>\n<td>\n\n<h1>Whoops, Internal Server Error.</h1>\nWe apologize. The page you are looking for<br>\nis unaccessible due to a little server hiccup.<br><br>\n\nPlease try your request again or try searching\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href=\"###SCRIPTURL###?Sitemap\">sitemap</a>, start over from the <a href=\"index.php?home\">home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', '', '', '', NULL, NULL)");
      rw_db_query("INSERT IGNORE INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('BlumenthalsSupport', 1, 0, 'admin', 1222318661, 1222318661, '!!Blumenthals  Olean NY Web Hosting Support Options\n\nFor the quickest response post on our [Ticket Reporting System|http://tickets.blumenthals.com].\n\nWeb Hosting, Web Design, Email Support:%%%\n[Blumenthals  WebHosting, Web Design - Olean Office|http://www.blumenthals.com]%%%\n201 N Union St. Suite 317%%%\nOlean, NY 14760 %%%\n716-372-4008\n\nBilling & Invoicing Questions:%%%\nBlumenthals.com%%%\n6 Valleybrook Drive%%%\nBradford PA 16701%%%\n814-368-4057', 'a:0:{}', '', '', '', NULL, NULL)");
      rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 5)");
   }


    /** Reset default settings that applied Blumenthals branding erroneously. */
   function rw_upgrade_database_5_6() {
      rw_db_query("UPDATE settings SET value = '' WHERE name = 'default_title' AND value = 'Blumenthals.com Rapidweb Website'"); 
      rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 6)");
   }

    /** Add noindex column */
   function rw_upgrade_database_6_7() { 
      rw_db_query("ALTER TABLE wiki ADD COLUMN noindex tinyint(1)");
      rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 7)");
   }

    /** Make HTTP response code pages noindex */
   function rw_upgrade_database_7_8() { 
      rw_db_query("UPDATE wiki SET noindex = 1 WHERE pagename IN ('500-ServerError', '404-NotFound', '403-Restricted', '401-AuthorizationRequired');");
      rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 8)");
   }

    /** Add 'json' column for gallery */
    function rw_upgrade_database_8_9() {
        rw_db_query("ALTER TABLE wiki ADD COLUMN `json` TEXT");
      rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 9)");

    }

    /** Add 'page type' field for gallery, and rename json column to gallery */
    function rw_upgrade_database_9_10() {
        rw_db_query("ALTER TABLE wiki ADD COLUMN `page_type` varchar(32) NOT NULL DEFAULT 'page'");
        rw_db_query("ALTER TABLE wiki CHANGE COLUMN `json` `gallery` TEXT");
      rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 10)");


    }

   function rw_db_query($sql) {
   if(!$r = mysql_query($sql)) echo("Query failed (".mysql_error()."): $sql");
   return $r;
   }

    /** Hide "Table already exists" errors from upgrades */
   function rw_db_canexist($result) {
      if(!$result) {
         if(preg_match("/Duplicate column name/", mysql_error()) || preg_match("/Table.*already exists/", mysql_error())) {
            return $result;
         } else {
            die(mysql_error());
         }
      } else {
         return $result;
      }   
   }


    /** Close the database handle, a NOOP for MySQL */
   function CloseDataBase($dbi) {
      // NOP function
      // mysql connections are established as persistant
      // they cannot be closed through mysql_close()
   }


    /** prepare page data hash for storing in mysql */
   function MakeDBHash($pagename, $pagehash)
   {
      if (!isset($pagehash["flags"]))
         $pagehash["flags"] = 0;
      $pagehash["content"] = implode("\n", $pagehash['content']);
      if (!isset($pagehash["refs"]))
         $pagehash["refs"] = array();
      $pagehash["refs"] = serialize($pagehash["refs"]);
      if(!isset($pagehash['pagename'])) $pagehash['pagename'] = $pagename;
      $pagehash['gallery'] = json_encode($pagehash['gallery']);
 
      return $pagehash;
   }


   /** Deserialize components of page data coming from MySQL */
   function MakePageHash($dbhash)
   {
      $dbhash['refs'] = unserialize($dbhash['refs']);
      $dbhash['gallery'] = json_decode($dbhash['gallery']);
      $dbhash['settings'] = RetrieveSettings();
      return $dbhash;
   }


   // Return hash of page + attributes or default
   function RetrievePage($dbi, $pagename) {
      $pagename = mysql_real_escape_string($pagename, $dbi['dbc']);
      if ($res = mysql_query("select * from {$dbi['prefix']}wiki where pagename='$pagename'", $dbi['dbc'])) {
         if ($dbhash = mysql_fetch_assoc($res)) {
            return MakePageHash($dbhash);
         }
      }
      return -1;
   }


   // Either insert or replace a key/value (a page)
    function InsertPage($dbi, $pagename, $pagehash) {
        $pagehash = MakeDBHash($pagename, $pagehash);

        $COLUMNS = "author, content, created, flags, lastmodified, pagename, refs, version, title, meta, keywords, variables, noindex, template, page_type, gallery";

        $VALUES = array(
            $pagehash['author'], $pagehash['content'],
            $pagehash['created'], $pagehash['flags'], 
            $pagehash['lastmodified'], $pagehash['pagename'], 
            $pagehash['refs'], $pagehash['version'],
            $pagehash['title'], $pagehash['meta'],
            $pagehash['keywords'], $pagehash['variables'],
            $pagehash['noindex'], (isset($pagehash['template']) ? $pagehash['template'] : 'NULL'),
            $pagehash['page_type'],
            $pagehash['gallery']
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
   
?>
