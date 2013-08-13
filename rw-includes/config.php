<?php

set_include_path(get_include_path().PATH_SEPARATOR.__DIR__."/modyllic");
require_once "Modyllic/AutoLoader.php";
Modyllic_Autoloader::install();

// essential internal stuff -- skip it. Go down to Part One. There
// are four parts to this file that interest you, all labeled Part
// One, Two, Three and Four.

set_magic_quotes_runtime(0);
error_reporting(E_ALL ^ E_NOTICE);

// end essential internal stuff

require_once dirname(__FILE__).'/../rw-config.php';

if(!isset($USERS)) die("Please configure RapidWeb before using");

// Select your language - default language "C": English
// other languages available: Dutch "nl", Spanish "es", German "de",
// and Swedish "sv"
if(!isset($LANG)) $LANG="C";

$WhichDatabase = 'mysql';

// MySQL settings -- see INSTALL.mysql for details on using MySQL
$ArchivePageStore = "archive";
require_once "mysql.php";

// logo image (path relative to index.php)
$logo = "images/logo.gif";
// signature image which is shown after saving an edited page
$SignatureImg = "/rw-global/images/edit/thankyou.gif";

// date & time formats used to display modification times, etc.
// formats are given as format strings to PHP date() function
$datetimeformat = "F j, Y";	// may contain time of day
$dateformat = "F j, Y";	// must not contain time

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

function rw_pathsearch($paths, $basename, $suffixes = array('.php', '.html')) {
    foreach($paths as $path) {
        foreach($suffixes as $suffix) {
            $name = "$path/$basename$suffix";
            if(file_exists($name)) return $name;
        }
    }
    return false;
}

if (!isset($ScriptUrl) || empty($ScriptUrl)) {
    $port = ($_SERVER['SERVER_PORT'] == 80) ? '' : ":".$_SERVER['SERVER_PORT'];
    $proto = ($_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
    $ScriptUrl = "$proto://".$_SERVER['SERVER_NAME']."$port".$_SERVER['SCRIPT_NAME'];
}

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

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));

spl_autoload_extensions('.class.php,.php');

spl_autoload_register('spl_autoload');
