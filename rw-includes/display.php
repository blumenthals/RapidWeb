<?php
   // display.php: fetch page or get default content
   // calls transform.php for actual transformation of wiki markup to HTML
 
   // if we got GET data, the first item is always a page name
   // if it wasn't this file would not have been included

   if (!empty($_SERVER['QUERY_STRING'])) {
      $args = explode('&', rawurldecode($_SERVER['argv'][0]));
      if(!strstr($args[0], '=')) $pagename = $args[0];
   }

   if(!isset($pagename)) $pagename = gettext("home");

   $html = "";
   $enc_name = rawurlencode($pagename);
   $pagehash = RetrievePage($dbi, $pagename, $WikiPageStore);

   // we render the page if it exists, else ask the user to write one.
   if (is_array($pagehash)) {
       include('rw-includes/command.php');
       switch($pagehash['page_type']) {
       case 'gallery':
           $html = rw_capture_command('display_gallery');
           break;
       case 'page':
       default:
          $html = rw_capture_command('display_page');
          break;
       }
   } else {
      $html .= sprintf(gettext(""),
		       "$pagename<a href='$ScriptUrl?edit=$enc_name'>?</a>");
   }

   GeneratePage('BROWSE', $html, $pagename, $pagehash);
   flush();

   IncreaseHitCount($dbi, $pagename);
?>
