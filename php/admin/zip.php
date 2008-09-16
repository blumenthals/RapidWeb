<?php // $Id: zip.php,v 1.1 2000/11/08 15:30:16 ahollosi Exp $

function MailifyPage ($pagehash, $archive = false)
{
  global $SERVER_ADMIN, $ArchivePageStore;
  
  $from = isset($SERVER_ADMIN) ? $SERVER_ADMIN : 'foo@bar';
  
  $head = "From $from  " . ctime(time()) . "\r\n";
  $head .= "Subject: " . rawurlencode($pagehash['pagename']) . "\r\n";
  $head .= "From: $from (RapidWeb)\r\n";
  $head .= "Date: " . rfc1123date($pagehash['lastmodified']) . "\r\n";
  $head .= "Mime-Version: 1.0 (Produced by RapidWeb 1.1.x)\r\n";

  if ($archive)
    {
      $oldpage = RetrievePage($archive, $pagehash['pagename'], $ArchivePageStore);
      if (is_array($oldpage))
	  return $head . MimeMultipart(array(MimeifyPage($oldpage),
					     MimeifyPage($pagehash)));
    }
  return $head . MimeifyPage($pagehash);
}

/**
 * The main() function which generates a zip archive of a PhpWiki.
 *
 * If $include_archive is false, only the current version of each page
 * is included in the zip file; otherwise all archived versions are
 * included as well.
 */
function MakeWikiZip ($include_archive = false)
{
  global $dbi, $WikiPageStore, $ArchivePageStore;
  
  $pages = GetAllWikiPageNames($dbi);
  $zipname = "rapidweb.zip";
  
  if ($include_archive) {
     $dba = OpenDataBase($ArchivePageStore);
     $zipname = "rapidwebdb.zip";
  }

  $zip = new ZipWriter("Created by PhpWiki", $zipname);

  for (reset($pages); $pagename = current($pages); next($pages))
  {
     set_time_limit(30);	// Reset watchdog.
     $pagehash = RetrievePage($dbi, $pagename, $WikiPageStore);
     if (! is_array($pagehash))
	continue;
     $attrib = array('mtime' => $pagehash['lastmodified'],
		     'is_ascii' => 1);
     if (($pagehash['flags'] & FLAG_PAGE_LOCKED) != 0)
	  $attrib['write_protected'] = 1;

     $content = MailifyPage($pagehash, $dba);
		     
     $zip->addRegularFile( rawurlencode($pagehash['pagename']),
			   $content, $attrib);
  }
  $zip->finish();

  if ($dba)
     CloseDataBase($dba);
}

if(defined('WIKI_ADMIN'))
   MakeWikiZip(($zip == 'all'));

CloseDataBase($dbi);
exit;
?>
