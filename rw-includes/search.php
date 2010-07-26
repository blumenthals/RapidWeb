<?php
   // Title search: returns pages having a name matching the search term

   if(get_magic_quotes_gpc())
      $search = stripslashes($search);

   $html = "<P><B>"
	   . sprintf(gettext ("Searching for \"%s\" ....."),
		     htmlspecialchars($search))
	   . "</B></P>\n";

   // quote regexp chars
   $search = preg_quote($search);

   // search matching pages
   $query = InitTitleSearch($dbi, $search);
   $found = 0;
   while ($page = TitleSearchNextMatch($dbi, $query)) {
      $found++;
      $html .= LinkExistingWikiWord($page) . "<br>\n";
   }

   $html .= "<hr noshade>\n"
	    . sprintf(gettext ("%d pages match your query."), $found)
	    . "\n";

   GeneratePage('MESSAGE', $html, gettext ("Title Search Results"), 0);
?>
