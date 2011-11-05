<?php header('Content-Type: text/html; charset=UTF-8');
/*
  The main page, i.e. the main loop.
  This file is always called first.
*/

    require_once "rw-includes/config.php";
    require_once "rw-includes/templating.php";
    require_once "rw-includes/stdlib.php";

	if(get_magic_quotes_gpc()) {
		foreach($_REQUEST as $k => $v) $_REQUEST[$k] = stripslashes($v);
	}

   // Backward Compatibility
   if(isset($_REQUEST['full'])) {
      $_REQUEST['searchtype'] = 'full';
      $_REQUEST['s'] = $_REQUEST['full'];
   }

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
        rw_do_command($_REQUEST['command']);
    } else {
      include "rw-includes/display.php"; // defaults to 'home'
   }

   CloseDataBase($dbi);

?>
