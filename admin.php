<?php

define('WIKI_ADMIN', true);	// has to be before includes

require_once("rw-includes/config.php");
require_once("rw-includes/stdlib.php");
require_once("rw-includes/templating.php");

require('rw-admin/require-authentication.php');

$now = new DateTime("now", new DateTimeZone("GMT"));
header('Expires: '.$now->format(DateTime::RFC2822));

/// @todo this is super horrible.
if(preg_match('!^text/json(; .*)?$!', $_SERVER['CONTENT_TYPE'])) {
    try {
        $request = json_decode(file_get_contents('php://input'));
        $RapidWeb->dispatchCommand($request);
        exit();
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        throw $e;
    }
}

if(isset($_REQUEST['lock']) || isset($_REQUEST['unlock'])) {
    include ('rw-admin/lockpage.php');
    ExitWiki('');
} elseif (isset($_REQUEST['zip'])) {
    $zip = $_REQUEST['zip'];
    include ('rw-includes/ziplib.php');
    include ('rw-admin/zip.php');
    ExitWiki('');
} elseif (isset($_REQUEST['dumpserial'])) {
    $dumpserial = $_REQUEST['dumpserial'];
    include ('rw-admin/dumpserial.php');
} elseif (isset($_REQUEST['loadserial'])) {
    $loadserial = $_REQUEST['loadserial'];
    include ('rw-admin/loadserial.php');
} elseif (isset($_REQUEST['remove'])) {
    if (get_magic_quotes_gpc()) {
        $remove = stripslashes($_REQUEST['remove']);
    } else {
        $remove = $_REQUEST['remove'];
    }
    if (function_exists('RemovePage')) {
        $html .= sprintf(gettext ("You are about to remove '%s' permanently!"), 
            htmlspecialchars($remove));
        $html .= "\n<P>";
        $url = rawurlencode($remove);
        $html .= sprintf(gettext ("Click %shere%s to remove the page now."),
            "<A HREF=\"$ScriptUrl?removeok=$url\">", "</A>");
        $html .= "\n<P>";
        $html .= gettext ("Otherwise press the \"Back\" button of your browser.");
    } else {
        $html = gettext ("Function not yet implemented.");
    }
    GeneratePage('MESSAGE', $html, gettext ("Remove page"), 0);
    ExitWiki('');
} elseif (isset($_REQUEST['removeok'])) {
    if (get_magic_quotes_gpc()) {
        $removeok = stripslashes($_REQUEST['removeok']);
    } else {
        $removeok = $_REQUEST['removeok'];
    }
    RemovePage($dbc, $removeok);
    $html = sprintf(gettext ("Removed page '%s' succesfully."),
        htmlspecialchars($removeok));
    GeneratePage('MESSAGE', $html, gettext ("Remove page"), 0);
    ExitWiki('');
}

include('index.php');
