<?php 

   /*
      Database functions:
      OpenDataBase($dbname)
      CloseDataBase($dbi)
      MakeDBHash($pagename, $pagehash)
      MakePageHash($dbhash)
      RetrievePage($dbi, $pagename, $pagestore)
      InsertPage($dbi, $pagename, $pagehash)
      SaveCopyToArchive($dbi, $pagename, $pagehash)
      IsWikiPage($dbi, $pagename)
      IsInArchive($dbi, $pagename)
      RemovePage($dbi, $pagename)
      IncreaseHitCount($dbi, $pagename)
      GetHitCount($dbi, $pagename)
      MakeSQLSearchClause($search, $column)
      InitTitleSearch($dbi, $search)
      TitleSearchNextMatch($dbi, $res)
      InitFullSearch($dbi, $search)
      FullSearchNextMatch($dbi, $res)
      InitMostPopular($dbi, $limit)
      MostPopularNextMatch($dbi, $res)
      GetAllWikiPageNames($dbi)
      GetWikiPageLinks($dbi, $pagename)
      SetWikiPageLinks($dbi, $pagename, $linklist)
   */

	define('RAPIDWEB_DB_VERSION', 6);

   // open a database and return the handle
   // ignores MAX_DBM_ATTEMPTS

   function OpenDataBase($dbname) {
      global $mysql_server, $mysql_user, $mysql_pwd, $mysql_db;

      if (!($dbc = mysql_pconnect($mysql_server, $mysql_user, $mysql_pwd))) {
         $msg = gettext ("Cannot establish connection to database, giving up.");
	 $msg .= "<BR>";
	 $msg .= sprintf(gettext ("MySQL error: %s"), mysql_error());
	 ExitWiki($msg);
      }
      if (!mysql_select_db($mysql_db, $dbc)) {
         $msg =  sprintf(gettext ("Cannot open database %s, giving up."), $mysql_db);
	 $msg .= "<BR>";
	 $msg .= sprintf(gettext ("MySQL error: %s"), mysql_error());
	 ExitWiki($msg);
      }
      $dbi['dbc'] = $dbc;
      $dbi['table'] = $dbname;

	$db_version = rw_db_get_version();

	if($db_version < RAPIDWEB_DB_VERSION) {
		echo("Database needs upgrade from $db_version to ".RAPIDWEB_DB_VERSION);
		do {
			$last = $db_version;
			$func = 'rw_upgrade_database_'.$db_version.'_'.($db_version + 1);
			if(function_exists($func)) {
				call_user_func('rw_upgrade_database_'.$db_version.'_'.($db_version + 1));
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
   function rw_upgrade_database_0_1() {
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
		rw_db_query("INSERT INTO settings VALUES('default_title', 'Blumenthals.com Rapidweb Website');");
		rw_db_query("INSERT INTO settings VALUES('default_meta_keywords', '')");
		rw_db_query("INSERT INTO settings VALUES('default_meta_description', '')");
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
		rw_db_query("INSERT INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('SiteMap', 1, 0, 'admin', 1227977883, 1227977883, '*[Home]\n*[Services]\n*[Products]\n*[Contact Us]\n*[Privacy Notice]\n*[Links]\n*[Search|FindPage]\n\n*Custom Error Pages:\n**[404-FileNotFound]\n**[403-Restricted]\n**[500-ServerError]\n**[401-AuthorizationRequired]\n\n*[Blumenthals  Olean NY Web Hosting Support options|BlumenthalsSupport]', 'a:0:{}', '', '', '', '', NULL)");
		rw_db_query("INSERT INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('401-AuthorizationRequired', 1, 0, 'admin', 1222318755, 1222318755, 'STARTHTML\n<style type='text/css'>\n<!--\nbody, td, th {\n        font-family: Arial, Helvetica, sans-serif;\n       font-size: 12px;\n}\na:link, a:visited, a:active {\n    color: #C82127;\n}\na:hover {\n color: #E62128;\n}\nh1 {\n font-weight: bold;\n    font-size: 14px;\n      color: #C82127;\n}\n.pngimg {\n behavior: url('/rw-global/pngbehavior.htc');\n}\n-->\n</style>\n<center>\n<table border='0' cellspacing='0' cellpadding='0'>\n<tr>\n<td><img src='/rw-global/images/edit/401b. png' alt='401 - Authorization Required' width=271 height=178 class=pngimg></td>\n<td>\n\n<h1>This is a restricted area.</h1>\nWe apologize. The page you are looking<br>\nfor requires the proper authorization.<br><br>\n\nPlease try your request again or try searching<br>\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href='###SCRIPTURL###?Sitemap'>sitemap</a>, start over from the <a href='index.php?home'>home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', '', '', '', NULL, NULL);");
		rw_db_query("INSERT INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('403-Restricted', 1, 0, 'admin', 1222318726, 1222318726, 'STARTHTML\n<style type=\"text/css\">\n<!--\nbody, td, th {\n   font-family: Arial, Helvetica, sans-serif;\n       font-size: 12px;\n}\na:link, a:visited, a:active {\n    color: #C82127;\n}\na:hover {\n color: #E62128;\n}\nh1 {\n font-weight: bold;\n    font-size: 14px;\n      color: #C82127;\n}\n.pngimg {\n behavior: url(\"/rw-global/pngbehavior.htc\");\n}\n-->\n</style>\n<center>\n<table border=0 cellspacing=0 cellpadding=0>\n<tr>\n<td><img src=\"/rw-global/images/edit/403b.png\" alt=\"403 - Forbidden; width=303 height=179 class=pngimg></td>\n<td>\n\n<h1>This is a restricted area.</h1>\nWe apologize. The page you are looking for is in<br>\na restricted area and is not available to the public.<br><br>\n\nIf you''re in denial and think this is a conspiracy<br >\nthat cannot be possibly true, please try searching<br>\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href=\"###SCRIPTURL###?Sitemap\">sitemap</a>, start over from the <a href=\"index.php?home\">home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', '', '', '', NULL, NULL)");
		rw_db_query("INSERT INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('404-FileNotFound', 8, 1, 'admin', 1222290675, 1012391280, 'STARTHTML\n<style type=\"text/css\">\n<!--\nbody, td, th {\n font-family: Arial, Helvetica, sans-serif;\n       font-size: 12px;\n}\na:link, a:visited, a:active {\n    color: #F28B22;\n}\na:hover {\n color: #F6B618;\n}\nh1 {\n font-weight: bold;\n    font-size: 14px;\n      color: #F28B22;\n}\n.pngimg {\n behavior: url(\"/rw-global/pngbehavior.htc\");\n}\n-->\n</style>\n<center>\n<table border=0 cellspacing=0 cellpadding=0>\n<tr>\n<td><img src=\"/rw-global/images/edit/404b.png\" alt=\"404 - File Not Found\" width=248 height=171 class=pngimg /></td>\n<td>\n\n<h1>Oops, Page Not Found.</h1>\nWe apologize. The page you<br>\nare looking for cannot be found.<br><br>\n\nIf you''re in denial and think this is a conspiracy<br>\nthat cannot be possibly true, please try searching<br>\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href=\"###SCRIPTURL###?Sitemap\">sitemap</a>, start over from the <a href=\"index.php?home\">home page</a>, or select<br>\n from the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', NULL, NULL, NULL, NULL, NULL)");
		rw_db_query("INSERT INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('500-ServerError', 1, 0, 'admin', 1222318742, 1222318742, 'STARTHTML\n<style type=\"text/css\">\n<!--\nbody, td, th {\n  font-family: Arial, Helvetica, sans-serif;\n       font-size: 12px;\n}\na:link, a:visited, a:active {\n    color: #F28B22;\n}\na:hover {\n color: #F6B618;\n}\nh1 {\n font-weight: bold;\n    font-size: 14px;\n      color: #F28B22;\n}\n.pngimg {\n behavior: url(\"/rw-global/pngbehavior.htc\");\n}\n-->\n</style>\n<center>\n<table border=0 cellspacing=0 cellpadding=0>\n<tr>\n<td><img src=\"/rw-global/images/edit/500b.png\" alt=\"500 - Internal Server Error\" width=213 height=173 class=pngimg></td>\n<td>\n\n<h1>Whoops, Internal Server Error.</h1>\nWe apologize. The page you are looking for<br>\nis unaccessible due to a little server hiccup.<br><br>\n\nPlease try your request again or try searching\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href=\"###SCRIPTURL###?Sitemap\">sitemap</a>, start over from the <a href=\"index.php?home\">home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', '', '', '', NULL, NULL)");
		rw_db_query("INSERT INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES ('BlumenthalsSupport', 1, 0, 'admin', 1222318661, 1222318661, '!!Blumenthals  Olean NY Web Hosting Support Options\n\nFor the quickest response post on our [Ticket Reporting System|http://tickets.blumenthals.com].\n\nWeb Hosting, Web Design, Email Support:%%%\n[Blumenthals  WebHosting, Web Design - Olean Office|http://www.blumenthals.com]%%%\n201 N Union St. Suite 317%%%\nOlean, NY 14760 %%%\n716-372-4008\n\nBilling & Invoicing Questions:%%%\nBlumenthals.com%%%\n6 Valleybrook Drive%%%\nBradford PA 16701%%%\n814-368-4057', 'a:0:{}', '', '', '', NULL, NULL)");
		rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 5)");
	}

	function rw_upgrade_database_5_6() {
		rw_db_query("UPDATE settings SET value = '' WHERE name = 'default_title' AND value = 'Blumenthals.com Rapidweb Website'"); 
		rw_db_query("REPLACE INTO rapidwebinfo (name,value) VALUES ('db_version', 6)");
	}

	function rw_upgrade_database_6_7() { }

   function rw_db_query($sql) {
	if(!$r = mysql_query($sql)) echo("Query failed (".mysql_error()."): $sql");
	return $r;
   }

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


   function CloseDataBase($dbi) {
      // NOP function
      // mysql connections are established as persistant
      // they cannot be closed through mysql_close()
   }


   // prepare $pagehash for storing in mysql
   function MakeDBHash($pagename, $pagehash)
   {
      if (!isset($pagehash["flags"]))
         $pagehash["flags"] = 0;
      $pagehash["content"] = implode("\n", $pagehash["content"]);
      if (!isset($pagehash["refs"]))
         $pagehash["refs"] = array();
      $pagehash["refs"] = serialize($pagehash["refs"]);
      if(!isset($pagehash['pagename'])) $pagehash['pagename'] = $pagename;
 
      return $pagehash;
   }


   // convert mysql result $dbhash to $pagehash
   function MakePageHash($dbhash)
   {
      // unserialize/explode content
      $dbhash['refs'] = unserialize($dbhash['refs']);
      $dbhash['content'] = explode("\n", $dbhash['content']);

      $dbhash['settings'] = RetrieveSettings();
      return $dbhash;
   }


   // Return hash of page + attributes or default
   function RetrievePage($dbi, $pagename, $pagestore) {
      $pagename = mysql_real_escape_string($pagename, $dbi['dbc']);
      if ($res = mysql_query("select * from $pagestore where pagename='$pagename'", $dbi['dbc'])) {
         if ($dbhash = mysql_fetch_array($res)) {
            return MakePageHash($dbhash);
         }
      }
      return -1;
   }


   // Either insert or replace a key/value (a page)
   function InsertPage($dbi, $pagename, $pagehash)
   {
      global $WikiPageStore; // ugly hack

      if ($dbi['table'] == $dbi['prefix']."wiki") { // HACK
         $linklist = ExtractWikiPageLinks($pagehash['content']);
	 SetWikiPageLinks($dbi, $pagename, $linklist);
      }

      $pagehash = MakeDBHash($pagename, $pagehash);

      $COLUMNS = "author, content, created, flags, " .
                 "lastmodified, pagename, refs, version, title, meta, keywords, variables, template";

			$VALUES = array($pagehash[author], $pagehash[content],
				$pagehash[created], $pagehash[flags], 
				$pagehash[lastmodified], $pagehash[pagename], 
				$pagehash[refs], $pagehash[version],
				$pagehash[title], $pagehash[meta],
				$pagehash[keywords], $pagehash[variables]);
			if(isset($pagehash['template'])) {
				array_push($VALUES, $pagehash[template]);
			} else {
				array_push($VALUES, 'NULL');
			}

			foreach($VALUES as $k => $v) {
				if($v === null || $v === 'NULL') {
					$VALUES[$k] = 'NULL';
				} else {
					$VALUES[$k] = "'".mysql_real_escape_string($v, $dbi['dbc'])."'";
				}
			}
			$VALUES = join($VALUES, ', ');
      if (!mysql_query($q = "replace into ${$dbi[prefix]}wiki ($COLUMNS) values ($VALUES)",
      			$dbi['dbc'])) {
            $msg = sprintf(gettext ("Error writing page '%s'"), $pagename);
	    $msg .= "<BR>";
	    $msg .= sprintf(gettext ("MySQL error: %s"), mysql_error()." in $q");
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
      else
        return false;
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
      global $WikiPageStore, $ArchivePageStore;
      global $WikiLinksStore, $HitCountStore, $WikiScoreStore;

      $pagename = addslashes($pagename);
      $msg = gettext ("Cannot delete '%s' from table '%s'");
      $msg .= "<br>\n";
      $msg .= gettext ("MySQL error: %s");

      if (!mysql_query("delete from $WikiPageStore where pagename='$pagename'", $dbi['dbc']))
         ExitWiki(sprintf($msg, $pagename, $WikiPageStore, mysql_error()));

      if (!mysql_query("delete from $ArchivePageStore where pagename='$pagename'", $dbi['dbc']))
         ExitWiki(sprintf($msg, $pagename, $ArchivePageStore, mysql_error()));

      if (!mysql_query("delete from $WikiLinksStore where frompage='$pagename'", $dbi['dbc']))
         ExitWiki(sprintf($msg, $pagename, $WikiLinksStore, mysql_error()));

      if (!mysql_query("delete from $HitCountStore where pagename='$pagename'", $dbi['dbc']))
         ExitWiki(sprintf($msg, $pagename, $HitCountStore, mysql_error()));

      if (!mysql_query("delete from $WikiScoreStore where pagename='$pagename'", $dbi['dbc']))
         ExitWiki(sprintf($msg, $pagename, $WikiScoreStore, mysql_error()));
   }


   function IncreaseHitCount($dbi, $pagename)
   {
      global $HitCountStore;

      $res = mysql_query("update $HitCountStore set hits=hits+1 where pagename='$pagename'", $dbi['dbc']);

      if (!mysql_affected_rows($dbi['dbc'])) {
	 $res = mysql_query("insert into $HitCountStore (pagename, hits) values ('$pagename', 1)", $dbi['dbc']);
      }

      return $res;
   }

   function GetHitCount($dbi, $pagename)
   {
      global $HitCountStore;

      $res = mysql_query("select hits from $HitCountStore where pagename='$pagename'", $dbi['dbc']);
      if (mysql_num_rows($res))
         $hits = mysql_result($res, 0);
      else
         $hits = "0";

      return $hits;
   }

   function MakeSQLSearchClause($search, $column)
   {
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

   function InitMostPopular($dbi, $limit) {
      global $HitCountStore;
      $res = mysql_query("select * from $HitCountStore order by hits desc, pagename limit $limit", $dbi["dbc"]);
      
      return $res;
   }

   function MostPopularNextMatch($dbi, $res) {
      if ($hits = mysql_fetch_array($res))
	 return $hits;
      else
         return 0;
   }

   function GetAllWikiPageNames($dbi) {
      global $WikiPageStore;
      $res = mysql_query("select pagename from $WikiPageStore", $dbi["dbc"]);
      $rows = mysql_num_rows($res);
      for ($i = 0; $i < $rows; $i++) {
	 $pages[$i] = mysql_result($res, $i);
      }
      return $pages;
   }
   
   
   ////////////////////////////////////////
   // functionality for the wikilinks table

   // takes a page name, returns array of scored incoming and outgoing links
   function GetWikiPageLinks($dbi, $pagename) {
      global $WikiLinksStore, $WikiScoreStore, $HitCountStore;

      $pagename = addslashes($pagename);
      $res = mysql_query("select topage, score from $WikiLinksStore, $WikiScoreStore where topage=pagename and frompage='$pagename' order by score desc, topage");
      $rows = mysql_num_rows($res);
      for ($i = 0; $i < $rows; $i++) {
	 $out = mysql_fetch_array($res);
	 $links['out'][] = array($out['topage'], $out['score']);
      }

      $res = mysql_query("select frompage, score from $WikiLinksStore, $WikiScoreStore where frompage=pagename and topage='$pagename' order by score desc, frompage");
      $rows = mysql_num_rows($res);
      for ($i = 0; $i < $rows; $i++) {
	 $out = mysql_fetch_array($res);
	 $links['in'][] = array($out['frompage'], $out['score']);
      }

      $res = mysql_query("select distinct pagename, hits from $WikiLinksStore, $HitCountStore where (frompage=pagename and topage='$pagename') or (topage=pagename and frompage='$pagename') order by hits desc, pagename");
      $rows = mysql_num_rows($res);
      for ($i = 0; $i < $rows; $i++) {
	 $out = mysql_fetch_array($res);
	 $links['popular'][] = array($out['pagename'], $out['hits']);
      }

      return $links;
   }


   // takes page name, list of links it contains
   // the $linklist is an array where the keys are the page names
   function SetWikiPageLinks($dbi, $pagename, $linklist) {
      global $WikiLinksStore, $WikiScoreStore;

      $frompage = addslashes($pagename);

      // first delete the old list of links
      mysql_query("delete from $WikiLinksStore where frompage='$frompage'",
		$dbi["dbc"]);

      // the page may not have links, return if not
      if (! count($linklist))
         return;
      // now insert the new list of links
      while (list($topage, $count) = each($linklist)) {
         $topage = addslashes($topage);
	 if($topage != $frompage) {
            mysql_query("insert into $WikiLinksStore (frompage, topage) " .
                     "values ('$frompage', '$topage')", $dbi["dbc"]);
	 }
      }

      // update pagescore
      mysql_query("delete from $WikiScoreStore", $dbi["dbc"]);
      mysql_query("insert into $WikiScoreStore select w1.topage, count(*) from $WikiLinksStore as w1, $WikiLinksStore as w2 where w2.topage=w1.frompage group by w1.topage", $dbi["dbc"]);
   }

/* more mysql queries:

orphans:
select pagename from wiki left join wikilinks on pagename=topage where topage is NULL;
*/
?>
