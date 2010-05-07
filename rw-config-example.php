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


   /////////////////////////////////////////////////////////////////////
   // Part One:
   // Constants and settings. Edit the values below for your site.
   /////////////////////////////////////////////////////////////////////

   // set these to your preferences. For heaven's sake
   // pick a good password!
   $USERS = array(
      'admin' => 'rwdev',
      'user' => 'rwuser'
   );

   // URL of index.php e.g. http://yoursite.com/phpwiki/index.php
   // you can leave this empty - it will be calculated automatically
   $ScriptUrl = "";
   // URL of admin.php e.g. http://yoursite.com/phpwiki/admin.php
   // you can leave this empty - it will be calculated automatically
   // if you fill in $ScriptUrl you *MUST* fill in $AdminUrl as well!
   $AdminUrl = "";

   // Select your language - default language "C": English
   // other languages available: Dutch "nl", Spanish "es", German "de",
   // and Swedish "sv"
   $LANG="C";

   /////////////////////////////////////////////////////////////////////
   // Part Two:
   // Database section
   // set your database here and edit the according section below.
   // For PHP 4.0.4 and later you must use "dba" if you are using 
   // DBM files for storage. "dbm" uses the older deprecated interface.
   // The option 'default' will choose either dbm or dba, depending on
   // the version of PHP you are running.
   /////////////////////////////////////////////////////////////////////

   $WhichDatabase = 'mysql'; // use one of "dbm", "dba", "mysql",
                           // "pgsql", "msql", or "file"

   // DBM and DBA settings (default)
   if ($WhichDatabase == 'dbm' or $WhichDatabase == 'dba' or
       $WhichDatabase == 'default') {
      $DBMdir = "/home/mark-jo/public_html/tolleytown/data";
      $WikiPageStore = "wiki";
      $ArchivePageStore = "archive";
      $WikiDB['wiki']      = "$DBMdir/pagesdb";
      $WikiDB['archive']   = "$DBMdir/archivedb";
      $WikiDB['wikilinks'] = "$DBMdir/linksdb";
      $WikiDB['hottopics'] = "$DBMdir/hottopicsdb";
      $WikiDB['hitcount']  = "$DBMdir/hitcountdb";
      // try this many times if the dbm is unavailable
      define("MAX_DBM_ATTEMPTS", 20);

      // for PHP3 use dbmlib, else use dbalib for PHP4
      if ($WhichDatabase == 'default') {
         if ( floor(phpversion()) == 3) {
            $WhichDatabase = 'dbm';
         } else {
            $WhichDatabase = 'dba';
         }
      }

      if ($WhichDatabase == 'dbm') {
          include "php/lib/dbmlib.php"; 
      } else {
          include "php/lib/dbalib.php";
      }

   // MySQL settings -- see INSTALL.mysql for details on using MySQL
   } elseif ($WhichDatabase == 'mysql') {
      $WikiPageStore = "wiki";
      $ArchivePageStore = "archive";
      $WikiLinksStore = "wikilinks";
      $WikiScoreStore = "wikiscore";
      $HitCountStore = "hitcount";
      $mysql_server = 'localhost';
      $mysql_user = 'aria_rw';
      $mysql_pwd = 'B2QU9eBbRh1T';
      $mysql_db = 'aria_rw';
      include "php/lib/mysql.php";

   // PostgreSQL settings -- see INSTALL.pgsql for more details
   } elseif ($WhichDatabase == 'pgsql') {
      $pg_dbhost    = "localhost";
      $pg_dbport    = "5432";
      $WikiDataBase  = "wiki"; // name of the database in Postgresql
      $WikiPageStore = "wiki";
      $ArchivePageStore = "archive";
      $WikiLinksPageStore = "wikilinks";
      $HotTopicsPageStore = "hottopics";
      $HitCountPageStore = "hitcount";
      include "phpwiki/lib/pgsql.php";

   } elseif ($WhichDatabase == 'file') {
      $DBdir = "/tmp/wiki";
      $WikiPageStore = "wiki";
      $ArchivePageStore = "archive";
      $WikiDB['wiki']      = "$DBdir/pages";
      $WikiDB['archive']   = "$DBdir/archive";
      $WikiDB['wikilinks'] = "$DBdir/links";
      $WikiDB['hottopics'] = "$DBdir/hottopics";
      $WikiDB['hitcount']  = "$DBdir/hitcount";
      include "phpwiki/lib/db_filesystem.php";

    } else die("Invalid '\$WhichDatabase' in php/lib/config.php"); 


   /////////////////////////////////////////////////////////////////////
   // Part Three:
   // Miscellaneous
   /////////////////////////////////////////////////////////////////////

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
         if (!empty ($locale[$text]))
           return $locale[$text];
         return $text;
      }
   } else {
      putenv ("LANG=$LANG");
      bindtextdomain ("phpwiki", "./php/locale");
      textdomain ("phpwiki");
   }
   // end of localization function

   // Template files (filenames are relative to script position)
   $templates = array(
   	"BROWSE" =>    gettext("php/templates/browse.html"),
	"EDITPAGE" =>  gettext("php/templates/editpage.html"),
	"EDITLINKS" => gettext("php/templates/editlinks.html"),
	"MESSAGE" =>   gettext("php/templates/browse.html")
	);

   /* WIKI_PGSRC -- specifies the source for the initial page contents
    * of the Wiki.  The setting of WIKI_PGSRC only has effect when
    * the wiki is accessed for the first time (or after clearing the
    * database.) WIKI_PGSRC can either name a directory or a zip file.
    * In either case WIKI_PGSRC is scanned for files --- one file per page.
    *
    * If the files appear to be MIME formatted messages, they are
    * scanned for application/x-phpwiki content-types.  Any suitable
    * content is added to the wiki.
    * The files can also be plain text files, in which case the page name
    * is taken from the file name.
    */

   define('WIKI_PGSRC', gettext("./php/pgsrc")); // Default (old) behavior.
   //define('WIKI_PGSRC', './php/wiki.zip'); // New style.

   // DEFAULT_WIKI_PGSRC is only used when the language is *not*
   // the default (English) and when reading from a directory:
   // in that case some English pages are inserted into the wiki as well
   // DEFAULT_WIKI_PGSRC defines where the English pages reside 
   define('DEFAULT_WIKI_PGSRC', "./php/pgsrc");



   //////////////////////////////////////////////////////////////////////
   // you shouldn't have to edit anyting below this line

   if (empty($ScriptUrl)) {
      $port = ($_SERVER['SERVER_PORT'] == 80) ? '' : ":".$_SERVER['SERVER_PORT'];
      $ScriptUrl = "http://".$_SERVER['SERVER_NAME']."$port".$_SERVER['SCRIPT_NAME'];
   }

   if (empty($AdminUrl)) {
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
