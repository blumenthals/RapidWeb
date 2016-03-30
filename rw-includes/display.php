<?php

/** fetch page or get default content
 * calls into Parser for actual transformation of wiki markup to HTML 
 * if we got GET data, the first item is always a page name. 
 * If it wasn't this file would not have been included
 */

if (isset($LinkStyle) and $LinkStyle == 'path') {
    $p = parse_url($_SERVER['REQUEST_URI']);
    if (!empty($p['path'])) {
        $pagename = rawurldecode($p['path']);
        if ($pagename{0} == '/') $pagename = substr($pagename, 1);
    }
} else if (!empty($_SERVER['QUERY_STRING'])) {
    $args = explode('&', rawurldecode($_SERVER['argv'][0]));
    if(!strstr($args[0], '=')) $pagename = $args[0];
} else {
     $pagename = '';
}

if(empty($pagename)) $pagename = "home";

$html = "";
$enc_name = rawurlencode($pagename);
$pagehash = RetrievePage($dbc, $pagename);

if ($pagehash['version'] == 0) {
    if (!@$_SESSION['username']) {
        Header("HTTP/1.1 404 Not Found");
        $pagehash = RetrievePage($dbc, '404-FileNotFound');
    } else {
        $pagehash['content'] = 'This page does not exist yet';
    }
}

// we render the page if it exists, else ask the user to write one.
if (is_array($pagehash)) {
    $html = $RapidWeb->capture('display_page');
}

GeneratePage('BROWSE', $html, $pagename, $pagehash);
