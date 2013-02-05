<?php

require_once 'config.php';
require_once 'wp-compat.php';

function ListTemplates($active) {
    global $TemplateName;
    $o = '<option value="">Default</option>';
    $d = opendir("rw-content/templates/$TemplateName/");
    while ($e = readdir($d)) {
        if (strpos($e, 'browse') === 0) continue;
        if (strpos($e, 'editpage') === 0) continue;
        if (strpos($e, 'index') === 0) continue;
        if (!preg_match('/\.html?$|\.php$/', $e)) continue;
        if ("rw-content/templates/$TemplateName/".$e == $active) {
            $selected = ' selected="selected"';
        } else {
            $selected = '';
        }
        if ($e{0} != '.') $o .= "<option value='rw-content/templates/$TemplateName/$e'$selected>$e</option>";
    }

    return $o;

}


$_rw_head_funcs = array();

function in_head($cb) {
    global $_rw_head_funcs;
    $_rw_head_funcs[] = $cb;
}

// @todo Move this into RapidWeb class
function _dohead(&$page) {
    global $_rw_head_funcs;
    list($top, $rest) = preg_split('/(?=<head)/i', $page, 2);
    if($rest) {
        list($headtag, $rest) = preg_split('/(?<=>)/', $rest, 2);
        $top .= $headtag;
        $page = $top;
    } else {
        $rest = $page;
        $page = '';
    }

    foreach ($_rw_head_funcs as $f) $page .= call_user_func($f);
    $page .= $rest;
}

/** takes $content and puts it in the template $template.
 * this function contains all the template logic.
 *
 * @param $template name of the template (see config.php for list of names)
 * @param $content html content to put into the page
 * @param $name page title
 * @param $hash if called while creating a wiki page, $hash points to the $pagehash array of that wiki page.
 *
 * @todo Move into rapidweb class or a view
 */
function GeneratePage($template, $content, $name, $hash, $return = false) {
    global $ScriptUrl, $AllowedProtocols, $templates;
    global $datetimeformat, $dbi, $logo, $FieldSeparator;
    global $RapidWeb;

    if (!is_array($hash)) unset($hash);
    if(!$hash['pagename']) $hash['pagename'] = $name;
    if(!$hash['content']) $hash['content'] = '';

    global $VARIABLES;

    $VARIABLES = Array();
    $vars = explode(',', $hash['variables']);
    foreach ($vars as $v) {
        list($k, $v) = explode('=', $v);
        $VARIABLES[trim($k)] = trim($v);
    }

    if ($template == 'BROWSE' and @$hash['template']) {
        $view = new OldTemplate(new RapidWebPage($hash), $RapidWeb);
        ob_start();
        $view->render($hash['template']);
        $page = ob_get_contents();
        ob_end_clean();
    } elseif($template == 'EDITPAGE') {
        $view = new EditPage(new RapidWebPage($hash), $RapidWeb);
        $view->render($templates[$template]);
        return;
    } else {
        $view = new OldTemplate(new RapidWebPage($hash), $RapidWeb);
        ob_start();
        $view->render($templates[$template]);
        $page = ob_get_contents();
        ob_end_clean();
    }

    if (!$page) die("template not loaded");
    $page = str_replace('###', "$FieldSeparator#", $page);

    /// @todo move into OldTemplate
    // valid for all pagetypes
    _iftoken('COPY', isset($hash['copy']), $page);
    _iftoken('LOCK', (isset($hash['flags']) &&
     ($hash['flags'] & FLAG_PAGE_LOCKED)), $page);
    _iftoken('ADMIN', defined('WIKI_ADMIN'), $page);

    if (strlen($hash['meta']) > 1) {
        $meta = str_replace('###', "$FieldSeparator#", htmlspecialchars($hash['meta']));
        _dotoken('META', $meta, $page, $FieldSeparator);
    } else {
        $meta = str_replace('###', "$FieldSeparator#", htmlspecialchars($hash['settings']['default_meta_description']));
        _dotoken('META', $meta, $page, $FieldSeparator);
    }

    if (strlen($hash['keywords']) > 1) {
        _dotoken('METAKEYWORDS', htmlspecialchars($hash['keywords']), $page, $FieldSeparator);
    } else {
        _dotoken('METAKEYWORDS', htmlspecialchars($hash['settings']['default_meta_keywords']), $page, $FieldSeparator);
    }

    _dotoken('NOINDEX', $hash['noindex'] ? 'checked="checked" ' : '', $page, $FieldSeparator);
    _dotoken('METANOINDEX', $hash['noindex'] ? '<meta name="robots" content="noindex">' : '', $page, $FieldSeparator);

    _dotoken('SCRIPTURL', $ScriptUrl, $page, $FieldSeparator);
    _dotoken('ADMINURL', $ScriptUrl, $page, $FieldSeparator);

    if (strlen($hash['title']) > 1) {
        _dotoken('PAGE', htmlspecialchars($hash['title']), $page, $FieldSeparator);
    } elseif (strlen($hash['settings']['default_title']) > 1) {
        $title = str_replace('###', "$FieldSeparator#", htmlspecialchars($hash['settings']['default_title']));
        _dotoken('PAGE', $title, $page, $FieldSeparator);
    }

    _dotoken('PAGE', htmlspecialchars($name), $page, $FieldSeparator);

    _dotoken('PAGENAME', htmlspecialchars($name), $page, $FieldSeparator);
    _dotoken('USERTITLE', htmlspecialchars($hash['title']), $page, $FieldSeparator);
    _dotoken('VARIABLES', htmlspecialchars($hash['variables']), $page, $FieldSeparator);
    _dotoken('TEMPLATESELECT', ListTemplates($hash['template']), $page, $FieldSeparator);

    _dotoken('ALLOWEDPROTOCOLS', $AllowedProtocols, $page, $FieldSeparator);
    _dotoken('LOGO', $logo, $page, $FieldSeparator);

    // invalid for messages (search results, error messages)
    if ($template != 'MESSAGE') {
        _dotoken('PAGEURL', rawurlencode($name), $page, $FieldSeparator);
        _dotoken('LASTMODIFIED',
        date($datetimeformat, $hash['lastmodified']), $page, $FieldSeparator);
        _dotoken('LASTAUTHOR', $hash['author'], $page, $FieldSeparator);
        _dotoken('VERSION', $hash['version'], $page, $FieldSeparator);
    }

    // valid only for EditLinks
    if ($template == 'EDITLINKS') {
        for ($i = 1; $i <= NUM_LINKS; $i++) {
            $ref = isset($hash['refs'][$i]) ? $hash['refs'][$i] : '';
            _dotoken("R$i", $ref, $page, $FieldSeparator);
        }
    }

    //Add secondardy WIKI content.
    //Sytax is PAGECONTENT(PAGENAME[, tagcontext])
    $page = preg_replace_callback('/PAGECONTENT\((.*?)\)/', '_pagecontent', $page);
    _dotoken('CONTENT', $content, $page, $FieldSeparator);
    _dotoken('JSON', json_encode($hash), $page, $FieldSeparator);

    _dohead($page);
    if ($return) {
        return $page;
    } else {
        print $page;
    }
}

define('TEMPLATEFSBASE', realpath(dirname(__FILE__))."/../rw-content/templates/");
define('TEMPLATEPATH', TEMPLATEFSBASE."$TemplateName/");

// @todo move into RapidWeb class
function _rw_pathfind($name) {
    global $RapidWeb;
    return rw_pathsearch(
        $RapidWeb->filter('template_path', array(TEMPLATEPATH, TEMPLATEFSBASE."default/")),
        $RapidWeb->filter('template_component', $name)
    );
}

// Template files (filenames are relative to script position)
$templates = array(
    "BROWSE" => _rw_pathfind('browse'),
    "EDITPAGE" => _rw_pathfind('editpage'),
    "EDITLINKS" => _rw_pathfind('editlinks'),
    "MESSAGE" => _rw_pathfind('browse'),
    "functions.php" => _rw_pathfind('functions.php')
);

define('RAPIDWEB', true);

function rw_apply_template() {
    $content = ob_get_contents();
    ob_end_clean();
    GeneratePage($GLOBALS['RW_TEMPLATE_ARGS'][0], $content, $GLOBALS['RW_TEMPLATE_ARGS'][1], array());
}

function rw_template($template, $name) {
    $GLOBALS['RW_TEMPLATE_ARGS'] = array($template, $name);
    ob_start();
}

if ($templates['functions.php']) include($templates['functions.php']);
