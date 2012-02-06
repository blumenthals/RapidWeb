<?php

/** fetch page or get default content
 * calls into Parser for actual transformation of wiki markup to HTML 
 * if we got GET data, the first item is always a page name. 
 * If it wasn't this file would not have been included
 */

if (!empty($_SERVER['QUERY_STRING'])) {
    $args = explode('&', rawurldecode($_SERVER['argv'][0]));
    if(!strstr($args[0], '=')) $pagename = $args[0];
}

if(!isset($pagename)) $pagename = "home";

$html = "";
$enc_name = rawurlencode($pagename);
$pagehash = RetrievePage($dbc, $pagename);

// we render the page if it exists, else ask the user to write one.
if (is_array($pagehash)) {
    include('rw-includes/command.php');
    $html = rw_capture_command('display_page');
}

GeneratePage('BROWSE', $html, $pagename, $pagehash);
