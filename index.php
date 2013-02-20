<?php

header('Content-Type: text/html; charset=UTF-8');

/*
The main page, i.e. the main loop.
This file is always called first.
*/

require_once "rw-includes/config.php";
require_once "rw-includes/stdlib.php";
require_once "rw-includes/templating.php";

$now = new DateTime("now", new DateTimeZone("GMT"));
header('Expires: '.$now->format(DateTime::RFC2822));

/// @todo this is super horrible.
if (preg_match('!^text/json(; .*)?$!', $_SERVER['CONTENT_TYPE'])) {
    try {
        $request = json_decode(file_get_contents('php://input'));
        $RapidWeb->dispatchCommand($request);
        exit();
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        throw $e;
    }
}

if ($RapidWeb->isAuthenticated()) {
    /* Backward compatibility */
    define("WIKI_ADMIN", true);
}

if (isset($_REQUEST['lock']) || isset($_REQUEST['unlock']) and $RapidWeb->mustAuthenticate()) {
    include ('rw-admin/lockpage.php');
    ExitWiki('');
} elseif (isset($_REQUEST['zip']) and $RapidWeb->mustAuthenticate()) {
    $zip = $_REQUEST['zip'];
    include ('rw-includes/ziplib.php');
    include ('rw-admin/zip.php');
    ExitWiki('');
} elseif (isset($_REQUEST['dumpserial']) and $RapidWeb->mustAuthenticate()) {
    $dumpserial = $_REQUEST['dumpserial'];
    include ('rw-admin/dumpserial.php');
} elseif (isset($_REQUEST['loadserial']) and $RapidWeb->mustAuthenticate()) {
    $loadserial = $_REQUEST['loadserial'];
    include ('rw-admin/loadserial.php');
} elseif (isset($_REQUEST['remove']) and $RapidWeb->mustAuthenticate()) {
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
} elseif (isset($_REQUEST['removeok']) and $RapidWeb->mustAuthenticate()) {
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

if (get_magic_quotes_gpc()) {
    function strip_r(&$req) {
        foreach($req as $k => $v) {
            if (is_array($v)) {
                strip_r($v);
            } else if (is_string($v)) {
                $req[$k] = stripslashes($v);
            }
        }
    }
    strip_r($_REQUEST);
}

// Backward Compatibility
if (isset($_REQUEST['full'])) {
    $_REQUEST['searchtype'] = 'full';
    $_REQUEST['s'] = $_REQUEST['full'];
}

try {
    // Allow choice of submit buttons to determine type of search:
    if (isset($_REQUEST['searchtype']) && ($_REQUEST['searchtype'] == 'full'))
        $full = $_REQUEST['s'];
    elseif (isset($_REQUEST['s']))     // default to title search
        $search = $_REQUEST['s'];

    if (isset($_REQUEST['edit']) && defined('WIKI_ADMIN')) {
        $edit = $_REQUEST['edit'];
        include "rw-includes/editpage.php";
    } elseif (isset($_REQUEST['links']) && defined('WIKI_ADMIN')) {
        $links = $_REQUEST['links'];
        include "rw-includes/editlinks.php";
    } elseif (isset($_REQUEST['settings']) && defined('WIKI_ADMIN')) {
        $settings = $_REQUEST['settings'];
        include "rw-includes/settings.php";
    } elseif (isset($_REQUEST['copy']) && defined('WIKI_ADMIN')) {
        $links = $_REQUEST['copy'];
        include "rw-includes/editpage.php";
    } elseif (isset($search)) {
        include "rw-includes/search.php";
    } elseif (isset($full)) {
        include "rw-includes/fullsearch.php";
    } elseif (isset($_REQUEST['info'])) {
        $info = $_REQUEST['info'];
        include "rw-includes/pageinfo.php";
    } elseif (isset($_REQUEST['sendform'])) {
        include "rw-includes/sendform.php";
    } elseif (isset($_REQUEST['command'])) {
        include "rw-includes/command.php";
        $RapidWeb->dispatch($_REQUEST['command']);
    } elseif (isset($_REQUEST['logout'])) {
        $RapidWeb->deAuthenticate();

        header("Location: ".dirname($_SERVER['SCRIPT_NAME']));

        exit();
    } elseif (isset($_REQUEST['login'])) {
        session_start();

        $s = dirname($_SERVER['SCRIPT_NAME']);
        if ($s == '/') $s = '';

        if (!@$_SESSION['username']) {
            header("Location: $s/rw-admin/login.php?continue=".$_SERVER['REQUEST_URL']);
            exit();
        }
    } elseif ($res = $RapidWeb->route()) {
        /// @todo This is horrible. Fix!
        if ($res instanceof Rapidweb\Action) {
            $res->execute(new Rapidweb\Request($_REQUEST, $_SERVER, $_FILES), new Rapidweb\Response());
        } elseif ($res instanceof Rapidweb\Response) {
            $res->send();
        }
    } else {
        include "rw-includes/display.php"; // defaults to 'home'
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    throw $e;
}
