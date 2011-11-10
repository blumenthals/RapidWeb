<?php

   // editpage relies on $pagename and $ScriptUrl

   if ($edit) {
      $pagename = rawurldecode($edit);
      if (get_magic_quotes_gpc()) {
         $pagename = stripslashes($pagename);
      }
      $banner = htmlspecialchars($pagename);
      $pagehash = RetrievePage($dbi, $pagename);
   } else {
      ExitWiki("No page name passed into editpage!");
   }


   if (is_array($pagehash)) {

      if (($pagehash['flags'] & FLAG_PAGE_LOCKED) && !defined('WIKI_ADMIN')) {
	 $html = "<p>";
	 $html .= "This page can only be edited by the administrator.";
	 $html .= "\n<p>";
	 $html .= "Proper Authorization Required.";
	 $html .= "\n";
	 GeneratePage('MESSAGE', $html, sprintf ("Problem while editing %s", $pagename), 0);
	 ExitWiki ("");
      }

      if ($pagehash["version"] > 1) {
	 if(IsInArchive($dbi, $pagename))
           $pagehash["copy"] = 1;
      }
   } else {
      $textarea = sprintf("Create %s here.",
				htmlspecialchars($pagename));
      unset($pagehash);
      $pagehash["version"] = 0;
      $pagehash["lastmodified"] = time();
      $pagehash["author"] = '';
   }

   GeneratePage('EDITPAGE', htmlspecialchars($pagehash['content']), $pagename, $pagehash);   
