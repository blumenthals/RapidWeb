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

   if (isset($edit)) {
      include "php/lib/editpage.php";
   } elseif (isset($links)) {
      include "php/lib/editlinks.php";
   } elseif (isset($copy)) {
      include "php/lib/editpage.php";
   } elseif (isset($search)) {
      include "php/lib/search.php";
   } elseif (isset($full)) {
      include "php/lib/fullsearch.php";
   } elseif (isset($post)) {
      include "php/lib/savepage.php";
   } elseif (isset($info)) {
      include "php/lib/pageinfo.php";
   } elseif (isset($diff)) {
      include "php/lib/diff.php";
   } else {
      include "php/lib/display.php"; // defaults to FrontPage
   }

   CloseDataBase($dbi);

?>
