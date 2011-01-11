<!-- $Id: loadserial.php,v 1.1 2000/11/08 15:30:16 ahollosi Exp $ -->
<?php
   /*
      Load a set of pages that have been serialized with 
      wiki_dumpserial.php.
   */

   $directory = $loadserial;
   $html = "Loading serialized pages from '$directory'.<p>\n";
   require('rw-includes/ziplib.php');

   if (! file_exists($directory)) {
      echo "No such directory '$directory'.<br>\n";
      exit;
   }
   
   $handle = opendir($directory);

   while ($file = readdir($handle)) {

      if ($file == "." || $file == "..")
         continue;

      $html .= "Reading '$file'...<br>\n";

      $data = implode("", file("$directory/$file"));

      if(substr($data, 0, 4) == 'From') {
	$pagehash = ParseMimeifiedPages($data);
	$pagehash = $pagehash[0];
      } else {
        $pagehash = unserialize($data);
      }

      // at this point there needs to be some form of verification
      // that we are about to insert a page.

      $pagename = rawurldecode($file);
      $html .= "inserting file '".htmlspecialchars($pagename)."' into the database...<br>\n";
      InsertPage($dbi, $pagename, $pagehash);
   }
   closedir($handle); 

   $html .= "<p><b>Load complete.</b>";
   GeneratePage('MESSAGE', $html, 'Load serialized pages', 0);
   ExitWiki('');
?>
