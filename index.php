<?php
   /*
      The main page, i.e. the main loop.
      This file is always called first.
   */

   if (!defined('WIKI_ADMIN')) { // index.php not included by admin.php?
      include "rw-includes/config.php";
      include "rw-includes/templating.php";
      include "rw-includes/stdlib.php";

      // All requests require the database
      $dbi = OpenDataBase($WikiPageStore);
   }

	if(get_magic_quotes_gpc()) {
		foreach($_REQUEST as $k => $v) $_REQUEST[$k] = stripslashes($v);
	}

   // Allow choice of submit buttons to determine type of search:
   if (isset($searchtype) && ($searchtype == 'full'))
      $full = $searchstring;
   elseif (isset($searchstring))     // default to title search
      $search = $searchstring;

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
   } elseif (isset($_REQUEST['search'])) {
      $search = $_REQUEST['search'];
      include "rw-includes/search.php";
   } elseif (isset($_REQUEST['full'])) {
      $full = $_REQUEST['full'];
      include "rw-includes/fullsearch.php";
   } elseif (isset($_REQUEST['post']) && defined('WIKI_ADMIN')) {
      $post = $_REQUEST['post'];
      $content = $_REQUEST['content'];
      include "rw-includes/savepage.php";
   } elseif (isset($_REQUEST['info'])) {
      $info = $_REQUEST['info'];
      include "rw-includes/pageinfo.php";
   } elseif (isset($_REQUEST['diff']) && defined('WIKI_ADMIN')) {
      $diff = $_REQUEST['diff'];
      include "rw-includes/diff.php";
   } elseif (isset($_REQUEST['sendform'])) {
      include "rw-includes/sendform.php";
   } else {
      include "rw-includes/display.php"; // defaults to FrontPage
   }

   CloseDataBase($dbi);

?>
