<!-- $Id: pageinfo.php,v 1.5 2000/11/01 11:31:41 ahollosi Exp $ -->
<!-- Display the internal structure of a page. Steve Wainstead, June 2000 -->
<?php
   if (get_magic_quotes_gpc()) {
      $info = stripslashes($info);
   }

   $encname = htmlspecialchars($info);
   $enter = ("Enter a page name");
   $go = ("Go");
   $html = "<form action=\"$ScriptUrl\" METHOD=GET>\n" .
	   "<input name=\"info\" value=\"$encname\">" .
	   " $enter\n" .
	   "<input type=submit value=$go><br>\n" .
	   "<input type=checkbox name=showpagesource";

   if (isset($showpagesource) && ($showpagesource == "on")) {
      $html .= " checked";
   }
   $html .= "> ";
   $html .= ("Show the page source and references");
   $html .= "\n</form>\n";

   // don't bother unless we were asked
   if (! $info) {
      GeneratePage('MESSAGE', $html, ("PageInfo"), 0);
      exit;
   }

   function ViewpageProps($name, $pagestore)
   {
      global $dbi, $showpagesource, $datetimeformat, $FieldSeparator;

      $pagehash = RetrievePage($dbi, $name, $pagestore);
      if ($pagehash == -1) {
         $table = sprintf (("Page name '%s' is not in the database"),
		$name) . "\n";
      }
      else {
	 $table = "<table border=1 bgcolor=white>\n";

	 while (list($key, $val) = each($pagehash)) {
	    if ($key > 0 || !$key) #key is an array index
	       continue;
            if ((gettype($val) == "array") && ($showpagesource == "on")) {
               $val = implode($val, "$FieldSeparator#BR#$FieldSeparator\n");
	       $val = htmlspecialchars($val);
	       $val = str_replace("$FieldSeparator#BR#$FieldSeparator", "<br>", $val);
            }
	    elseif (($key == 'lastmodified') || ($key == 'created'))
	       $val = date($datetimeformat, $val);
	    else
	       $val = htmlspecialchars($val);

            $table .= "<tr><td>$key</td><td>$val</td></tr>\n";
	 }

	 $table .= "</table>";
      }
      return $table;
   }

   $html .= "<P><B>";
   $html .= ("Current version");
   $html .= "</B></p>";
   $html .= ViewPageProps($info);

   $html .= "<P><B>";
   $html .= ("Archived version");
   $html .= "</B></p>";
   // $dbi = OpenDataBase($ArchivePageStore);
   $html .= ViewPageProps($info, $ArchivePageStore);

   GeneratePage('MESSAGE', $html, ("PageInfo").": '$info'", 0);
?>
