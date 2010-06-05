<?php

   // essential internal stuff -- skip it. Go down to Part One. There
   // are four parts to this file that interest you, all labeled Part
   // One, Two, Three and Four.

   set_magic_quotes_runtime(0);
   error_reporting(E_ALL ^ E_NOTICE);

   if (!function_exists('rcs_id')) {
      function rcs_id($id) { echo "<!-- $id -->\n"; };
   }
   rcs_id('$Id: config.php,v 1.24 2001/01/31 07:38:10 ahollosi Exp $');
   // end essential internal stuff

   include('rw-config.php');

	if(!isset($USERS)) die("Please configure RapidWeb before using");

	// Select your language - default language "C": English
	// other languages available: Dutch "nl", Spanish "es", German "de",
	// and Swedish "sv"
	if(!isset($LANG)) $LANG="C";

	$WhichDatabase = 'mysql';

	// MySQL settings -- see INSTALL.mysql for details on using MySQL
	$WikiPageStore = "wiki";
	$ArchivePageStore = "archive";
	$WikiLinksStore = "wikilinks";
	$WikiScoreStore = "wikiscore";
	$HitCountStore = "hitcount";
	include "rw-includes/mysql.php";

	// logo image (path relative to index.php)
	$logo = "images/logo.gif";
	// signature image which is shown after saving an edited page
	$SignatureImg = "/rw-global/images/edit/thankyou.gif";

	// date & time formats used to display modification times, etc.
	// formats are given as format strings to PHP date() function
	$datetimeformat = "F j, Y";	// may contain time of day
	$dateformat = "F j, Y";	// must not contain time

	// this defines how many page names to list when displaying
	// the MostPopular pages; the default is to show the 20 most popular pages
	define("MOST_POPULAR_LIST_LENGTH", 20);

	// this defines how many page names to list when displaying related pages
	define("NUM_RELATED_PAGES", 5);

	// number of user-defined external references, i.e. "[1]"
	define("NUM_LINKS", 12);

	// allowed protocols for links - be careful not to allow "javascript:"
	// within a named link [name|uri] one more protocol is defined: phpwiki
	$AllowedProtocols = "http|https|mailto|ftp|news|gopher";

	// URLs ending with the following extension should be inlined as images
	$InlineImages = "png|jpg|gif";

	// Perl regexp for WikiNames
	// (?<!..) & (?!...) used instead of '\b' because \b matches '_' as well
	$WikiNameRegexp = "(?<![A-Za-z0-9])([A-Z][a-z]+){2,}(?![A-Za-z0-9])";



	/////////////////////////////////////////////////////////////////////
	// Part Four:
	// Original pages and layout
	/////////////////////////////////////////////////////////////////////

	// need to define localization function first -- skip this
	if (!function_exists ('gettext')) {
		$lcfile = "php/locale/$LANG/LC_MESSAGES/phpwiki.php";
		if (file_exists($lcfile)) { include($lcfile); }
		else { $locale = array(); }

		function gettext ($text) { 
			global $locale;
			 if (!empty ($locale[$text])) return $locale[$text];
			 return $text;
		}
	} else {
		putenv ("LANG=$LANG");
		bindtextdomain ("phpwiki", "./php/locale");
		textdomain ("phpwiki");
	}
	// end of localization function

	function rw_pathsearch($paths, $basename, $suffixes = array('.php', '.html')) {
		foreach($paths as $path) {
			foreach($suffixes as $suffix) {
				$name = "$path/$basename$suffix";
				if(file_exists($name)) return $name;
			}
		}
		return false;
	}

	define('TEMPLATEFSBASE', realpath(dirname(__FILE__))."/../rw-content/templates/");
	define('TEMPLATEPATH', TEMPLATEFSBASE."$TemplateName/");

	// Template files (filenames are relative to script position)
	$templates = array(
		"BROWSE" => rw_pathsearch(array(TEMPLATEPATH, TEMPLATEFSBASE."default/"), gettext('browse')),
		"EDITPAGE" => rw_pathsearch(array(TEMPLATEPATH, TEMPLATEFSBASE."default/"), gettext('editpage')),
		"EDITLINKS" => rw_pathsearch(array(TEMPLATEPATH, TEMPLATEFSBASE."default/"), gettext('editlinks')),
		"MESSAGE" => rw_pathsearch(array(TEMPLATEPATH, TEMPLATEFSBASE."default/"), gettext('browse')),
		"functions.php" => rw_pathsearch($p=array(TEMPLATEPATH, TEMPLATEFSBASE."default/"), 'functions.php', array(''))
	);

	define('RAPIDWEB', true);

   //////////////////////////////////////////////////////////////////////
   // you shouldn't have to edit anyting below this line

	if (!isset($ScriptUrl) || empty($ScriptUrl)) {
		$port = ($_SERVER['SERVER_PORT'] == 80) ? '' : ":".$_SERVER['SERVER_PORT'];
		$ScriptUrl = "http://".$_SERVER['SERVER_NAME']."$port".$_SERVER['SCRIPT_NAME'];
	}

	if (!isset($AdminUrl) || empty($AdminUrl)) {
		$port = ($_SERVER['SERVER_PORT'] == 80) ? '' : ":".$_SERVER['SERVER_PORT'];
		$AdminUrl = str_replace('index.php', 'admin.php', $ScriptUrl);
	}

	if (defined('WIKI_ADMIN') && !empty($AdminUrl))
		$ScriptUrl = $AdminUrl;

	$LogoImage = "<img src=\"$logo\" border=0 ALT=\"[PhpWiki!]\">";
	$LogoImage = "<a href=\"$ScriptUrl\">$LogoImage</a>";

	$FieldSeparator = "\263";

	if (isset($PHP_AUTH_USER)) {
		$remoteuser = $PHP_AUTH_USER;
	} else {

		// Apache won't show REMOTE_HOST unless the admin configured it
		// properly. We'll be nice and see if it's there.

		getenv('REMOTE_HOST') ? ($remoteuser = getenv('REMOTE_HOST'))
			: ($remoteuser = getenv('REMOTE_ADDR'));
	}

	// constants used for HTML output. HTML tags may allow nesting
	// other tags always start at level 0
	define("ZERO_LEVEL", 0);
	define("NESTED_LEVEL", 1);

	// constants for flags in $pagehash
	define("FLAG_PAGE_LOCKED", 1);
?>
