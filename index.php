<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- $Id: index.php,v 1.5 2000/11/08 15:34:06 ahollosi Exp $ -->
<?php
   /*
      The main page, i.e. the main loop.
      This file is always called first.
   */

   if (!defined('WIKI_ADMIN')) { // index.php not included by admin.php?
      include "php/lib/config.php";
      include "php/lib/stdlib.php";

      // All requests require the database
      $dbi = OpenDataBase($WikiPageStore);
   }

   // Allow choice of submit buttons to determine type of search:
   if (isset($searchtype) && ($searchtype == 'full'))
      $full = $searchstring;
   elseif (isset($searchstring))     // default to title search
      $search = $searchstring;

   if (isset($_REQUEST['edit']) && defined('WIKI_ADMIN')) {
      $edit = $_REQUEST['edit'];
      include "php/lib/editpage.php";
   } elseif (isset($_REQUEST['links']) && defined('WIKI_ADMIN')) {
      $links = $_REQUEST['links'];
      include "php/lib/editlinks.php";
   } elseif (isset($_REQUEST['settings']) && defined('WIKI_ADMIN')) {
      $settings = $_REQUEST['settings'];
      include "php/lib/settings.php";
   } elseif (isset($_REQUEST['copy']) && defined('WIKI_ADMIN')) {
      $links = $_REQUEST['copy'];
      include "php/lib/editpage.php";
   } elseif (isset($_REQUEST['search'])) {
      $search = $_REQUEST['search'];
      include "php/lib/search.php";
   } elseif (isset($_REQUEST['full'])) {
      $full = $_REQUEST['full'];
      include "php/lib/fullsearch.php";
   } elseif (isset($_REQUEST['post']) && defined('WIKI_ADMIN')) {
      $post = $_REQUEST['post'];
      $content = $_REQUEST['content'];
      include "php/lib/savepage.php";
   } elseif (isset($_REQUEST['info'])) {
      $info = $_REQUEST['info'];
      include "php/lib/pageinfo.php";
   } elseif (isset($_REQUEST['diff']) && defined('WIKI_ADMIN')) {
      $diff = $_REQUEST['diff'];
      include "php/lib/diff.php";
   } else {
      include "php/lib/display.php"; // defaults to FrontPage
   }

   CloseDataBase($dbi);

?>
