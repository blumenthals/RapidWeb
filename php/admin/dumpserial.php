<!-- $Id: dumpserial.php,v 1.1 2000/11/08 15:30:16 ahollosi Exp $ -->

<?php
   /*
      Write out all pages from the database to a user-specified
      directory as serialized data structures.
   */

   $directory = $dumpserial;
   $pages = GetAllWikiPagenames($dbi);

   // see if we can access the directory the user wants us to use
   if (! file_exists($directory)) {
      if (! mkdir($directory, 0755))
         ExitWiki("Cannot create directory '$directory'<br>\n");
      else
         $html = "Created directory '$directory' for the page dump...<br>\n";
   } else {
      $html = "Using directory '$directory'<br>\n";
   }

   $numpages = count($pages);
   for ($x = 0; $x < $numpages; $x++) {
      $pagename = htmlspecialchars($pages[$x]);
      $filename = rawurlencode($pages[$x]);
      $html .= "<br>$pagename ... ";
      if($pagename != $filename)
         $html .= "<small>saved as $filename</small> ... ";

      $data = serialize(RetrievePage($dbi, $pages[$x], $WikiPageStore));
      if ($fd = fopen("$directory/$filename", "w")) {
         $num = fwrite($fd, $data, strlen($data));
         $html .= "<small>$num bytes written</small>\n";
      } else {
         ExitWiki("<b>couldn't open file '$directory/$filename' for writing</b>\n");
      }
   }

   $html .= "<p><b>Dump complete.</b>";
   GeneratePage('MESSAGE', $html, 'Dump serialized pages', 0);
   ExitWiki('');
?>
