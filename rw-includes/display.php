<?php
   // display.php: fetch page or get default content
   // calls transform.php for actual transformation of wiki markup to HTML
 
   // if we got GET data, the first item is always a page name
   // if it wasn't this file would not have been included

   if (!empty($_SERVER['QUERY_STRING'])) {
      list($pagename, $_) = explode('&', rawurldecode($_SERVER['QUERY_STRING']));
   } else { 
      $pagename = gettext("home");
   }

   $html = "";
   $enc_name = rawurlencode($pagename);
   $pagehash = RetrievePage($dbi, $pagename, $WikiPageStore);

   // we render the page if it exists, else ask the user to write one.
   if (is_array($pagehash)) {
      // transform.php returns $html containing all the HTML markup
      include("rw-includes/transform.php");
   } else {
      $html .= sprintf(gettext(""),
		       "$pagename<a href='$ScriptUrl?edit=$enc_name'>?</a>");
   }

   GeneratePage('BROWSE', $html, $pagename, $pagehash);
   flush();

   IncreaseHitCount($dbi, $pagename);
?>
