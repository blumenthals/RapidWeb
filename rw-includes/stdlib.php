<?php rcs_id('$Id: stdlib.php,v 1.21 2001/01/15 12:32:57 ahollosi Exp $');

   /*
      Standard functions for Wiki functionality
         ExitWiki($errormsg)
         LinkExistingWikiWord($wikiword)
         LinkUnknownWikiWord($wikiword)
         LinkURL($url, $linktext)
         LinkImage($url, $alt)
         RenderQuickSearch($value)
         RenderFullSearch($value)
         RenderMostPopular()
         CookSpaces($pagearray)
         UpdateRecentChanges($dbi, $pagename, $isnewpage)
         ParseAndLink($bracketlink)
         ExtractWikiPageLinks($content)
         LinkRelatedPages($dbi, $pagename)
         GeneratePage($template, $content, $name, $hash)
   */

   function get_include_contents($filename) {
     global $VARIABLES;
     if (is_file($filename)) {
       ob_start();
       include $filename;
       $contents = ob_get_contents();
       ob_end_clean();
       return $contents;
     }
     return false;
   }

   function ListTemplates($active) {
	global $TemplateName;
      $o = '<option value="">Default</option>';
      $d = opendir("rw-content/templates/$TemplateName/");
      while($e = readdir($d)) {
         if(strpos($e, 'browse') === 0) continue;
         if(strpos($e, 'editpage') === 0) continue;
         if(strpos($e, 'index') === 0) continue;
         if(strpos($e, '.htm') === false) continue;
         if("rw-content/templates/$TemplateName/".$e == $active) {
            $selected = ' selected="selected"';
         } else {
            $selected = '';
         }
         if($e{0} != '.')
            $o .= "<option value='rw-content/templates/$TemplateName/$e'$selected>$e</option>";
      }
      return $o;
   }


   function ExitWiki($errormsg)
   {
      static $exitwiki = 0;
      global $dbi;

      if($exitwiki)		// just in case CloseDataBase calls us
         exit();
      $exitwiki = 1;

      CloseDataBase($dbi);

      if($errormsg <> '') {
         print "<P><hr noshade><h2>" . gettext("RapidWeb Fatal Error") . "</h2>\n";
         print $errormsg;
         print "\n</BODY></HTML>";
      }
      exit;
   }


   function LinkExistingWikiWord($wikiword, $linktext='', $target = '') {
      global $ScriptUrl;
      $enc_word = rawurlencode($wikiword);
      if(empty($linktext))
         $linktext = htmlspecialchars($wikiword);
      if($target) $dtarget = " target='$target'";
      return "<a href=\"$ScriptUrl?$enc_word\"$dtarget>$linktext</a>";
   }

   function LinkUnknownWikiWord($wikiword, $linktext='', $target = '') {
      global $ScriptUrl;
      global $AdminUrl;
      $enc_word = rawurlencode($wikiword);
      if(empty($linktext))
         $linktext = htmlspecialchars($wikiword);
      if($target) $dtarget = " target='$target'";
      return "<u>$linktext</u><a href=\"$AdminUrl?edit=$enc_word\"$dtarget>?</a>";
   }

   function LinkURL($url, $linktext='', $target = '') {
      global $ScriptUrl;
      if(preg_match("/[<>\"]/", $url)) {
         return "<b><u>BAD URL -- remove all of &lt;, &gt;, &quot;</u></b>";
      }
      if(empty($linktext))
         $linktext = htmlspecialchars($url);
      if($target) $dtarget = " target='$target'";
      return "<a href=\"$url\"$dtarget>$linktext</a>";
   }

   function LinkImage($url, $alt='[External Image]') {
      global $ScriptUrl;
      if(ereg('[<>"]', $url)) {
         return "<b><u>BAD URL -- remove all of &lt;, &gt;, &quot;</u></b>";
      }
      return "<img src=\"$url\" ALT=\"$alt\">";
   }


   function RenderQuickSearch($value = '') {
      global $ScriptUrl;
      return "<form action=\"$ScriptUrl\">\n" .
	     "<input type=text size=30 name=search value=\"$value\">\n" .
	     "<input type=submit value=\"". gettext("Search") .
	     "\"></form>\n";
   }

   function RenderFullSearch($value = '') {
      global $ScriptUrl;
      return "<form action=\"$ScriptUrl\">\n" .
	     "<input type=text size=30 name=full value=\"$value\">\n" .
	     "<input type=submit value=\"". gettext("Search") .
	     "\"></form>\n";
   }

   function RenderMostPopular() {
      global $ScriptUrl, $dbi;

      $query = InitMostPopular($dbi, MOST_POPULAR_LIST_LENGTH);
      $result = "<DL>\n";
      while ($qhash = MostPopularNextMatch($dbi, $query)) {
	 $result .= "<DD>$qhash[hits] ... " . LinkExistingWikiWord($qhash['pagename']) . "\n";
      }
      $result .= "</DL>\n";

      return $result;
   }


   function ParseAdminTokens($line) {
      global $ScriptUrl;

      while (preg_match("/%%ADMIN-INPUT-(.*?)-(\w+)%%/", $line, $matches)) {
	 $head = str_replace('_', ' ', $matches[2]);
         $form = "<FORM ACTION=\"$ScriptUrl\" METHOD=POST>"
		."$head: <INPUT NAME=$matches[1] SIZE=20> "
		."<INPUT TYPE=SUBMIT VALUE=\"" . gettext("Go") . "\">"
		."</FORM>";
	 $line = str_replace($matches[0], $form, $line);
      }
      return $line;
   }

   // converts spaces to tabs
   function CookSpaces($pagearray) {
      return preg_replace("/ {3,8}/", "\t", $pagearray);
   }






   function ParseAndLink($bracketlink) {
      global $dbi, $ScriptUrl, $AllowedProtocols, $InlineImages;

      // $bracketlink will start and end with brackets; in between
      // will be either a page name, a URL or both separated by a pipe.
      // After may be the annotation (new window):
      //     [Name|URL] (new window)
      //     [Name] (new window)

      $target = "";

      // strip annotations
      if(preg_match("/\(new window\)/", $bracketlink)) {
         $target = "_blank";
      }

      // strip brackets and leading space
      preg_match("/(\[\s*)(.+?)(\s*\])/", $bracketlink, $match);
      // match the contents
      preg_match("/([^|]+)(\|)?([^|]+)?/", $match[2], $matches);

      if (isset($matches[3])) {
         // named link of the form  "[some link name | http://blippy.com/]"
         $URL = trim($matches[3]);
         $linkname = htmlspecialchars(trim($matches[1]));
         $linktype = 'named';
      } else {
         // unnamed link of the form "[http://blippy.com/] or [wiki page]"
         $URL = trim($matches[1]);
         $linkname = '';
         $linktype = 'simple';
      }

      if (IsWikiPage($dbi, $URL)) {
         $link['type'] = "wiki-$linktype";
         $link['link'] = LinkExistingWikiWord($URL, $linkname, $target);
      } elseif (preg_match("#^($AllowedProtocols):#", $URL)) {
        // if it's an image, embed it; otherwise, it's a regular link
         if (preg_match("/($InlineImages)$/i", $URL)) {
            $link['type'] = "image-$linktype";
            $link['link'] = LinkImage($URL, $linkname);
         } else {
            $link['type'] = "url-$linktype";
            $link['link'] = LinkURL($URL, $linkname, $target);
         }
      } elseif (preg_match("#^phpwiki:(.*)#", $URL, $match)) {
         $link['type'] = "url-wiki-$linktype";
         if(empty($linkname))
            $linkname = htmlspecialchars($URL);
         $link['link'] = LinkUrl("$ScriptUrl$match[1]", $linkname, $target);
      } elseif (preg_match("#^\d+$#", $URL)) {
         $link['type'] = "reference-$linktype";
         $link['link'] = $URL;
      } else {
         $link['type'] = "wiki-unknown-$linktype";
         $link['link'] = LinkUnknownWikiWord($URL, $linkname, $target);
      }

      return $link;
   }


   function ExtractWikiPageLinks($content)
   {
      global $WikiNameRegexp;

      $wikilinks = array();
      $numlines = count($content);
      for($l = 0; $l < $numlines; $l++)
      {
         // remove escaped '['
         $line = str_replace('[[', ' ', $content[$l]);

         // bracket links (only type wiki-* is of interest)
         $numBracketLinks = preg_match_all("/\[\s*([^\]|]+\|)?\s*(.+?)\s*\]/", $line, $brktlinks);
         for ($i = 0; $i < $numBracketLinks; $i++) {
            $link = ParseAndLink($brktlinks[0][$i]);
            if (preg_match("#^wiki#", $link['type']))
               $wikilinks[$brktlinks[2][$i]] = 1;

            $brktlink = preg_quote($brktlinks[0][$i]);
            $line = preg_replace("|$brktlink|", '', $line);
         }

         // BumpyText old-style wiki links
         if (preg_match_all("/!?$WikiNameRegexp/", $line, $link)) {
            for ($i = 0; isset($link[0][$i]); $i++) {
               if($link[0][$i][0] <> '!')
                  $wikilinks[$link[0][$i]] = 1;
            }
         }
      }
      return $wikilinks;
   }


   function LinkRelatedPages($dbi, $pagename)
   {
      // currently not supported everywhere
      if(!function_exists('GetWikiPageLinks'))
         return '';

      $links = GetWikiPageLinks($dbi, $pagename);

      $txt = "<b>";
      $txt .= sprintf (gettext ("%d best incoming links:"), NUM_RELATED_PAGES);
      $txt .= "</b>\n";
      for($i = 0; $i < NUM_RELATED_PAGES; $i++) {
         if(isset($links['in'][$i])) {
            list($name, $score) = $links['in'][$i];
            $txt .= LinkExistingWikiWord($name) . " ($score), ";
         }
      }

      $txt .= "\n<br><b>";
      $txt .= sprintf (gettext ("%d best outgoing links:"), NUM_RELATED_PAGES);
      $txt .= "</b>\n";
      for($i = 0; $i < NUM_RELATED_PAGES; $i++) {
         if(isset($links['out'][$i])) {
            list($name, $score) = $links['out'][$i];
            if(IsWikiPage($dbi, $name))
               $txt .= LinkExistingWikiWord($name) . " ($score), ";
         }
      }

      $txt .= "\n<br><b>";
      $txt .= sprintf (gettext ("%d most popular nearby:"), NUM_RELATED_PAGES);
      $txt .= "</b>\n";
      for($i = 0; $i < NUM_RELATED_PAGES; $i++) {
         if(isset($links['popular'][$i])) {
            list($name, $score) = $links['popular'][$i];
            $txt .= LinkExistingWikiWord($name) . " ($score), ";
         }
      }

      return $txt;
   }

   # GeneratePage() -- takes $content and puts it in the template $template
   # this function contains all the template logic
   #
   # $template ... name of the template (see config.php for list of names)
   # $content ... html content to put into the page
   # $name ... page title
   # $hash ... if called while creating a wiki page, $hash points to
   #           the $pagehash array of that wiki page.

   function _pagecontent($page) {
      //encapsulates transform.php into a proper function, so we can include it as part of an expression.
      global $dbi, $WikiPageStore, $AllowedProtocols, $logo, $FieldSeparator, $datetimeformat, $WikiNameRegexp;
      if(is_array($page)) {
        if(preg_match('/^["\']|\\$/', $page[1])) {
          $pageName = eval("return ".$page[1].";");
        } else {
          $pageName = $page[1];
        }
      } else {
        $pageName = $page;
      }

      if(preg_match('/,/', $pageName)) {
        list($pageName, $tagcontext) = explode(',', $pageName);
        $pageName = trim($pageName);
        $tagcontext = trim($tagcontext);
      }
      $html = "Page $pageName doesn't exist";
      $pagehash = RetrievePage($dbi, $pageName, $WikiPageStore);
      if (is_array($pagehash)) {
          require_once('rw-includes/transformlib.php');
          $p = new Parser($pagehash);
          $html = $p->parse($pagehash['content'], $tagcontext);
      }
      $page = $html;
      return $page;
   }

   function _dotoken ($id, $val, &$page, $FieldSeparator) {
      $page = str_replace("$FieldSeparator#$id$FieldSeparator#",
         $val, $page);
   }
   function _iftoken ($id, $condition, &$page) {
      global $FieldSeparator;

       // line based IF directive
       $lineyes = "$FieldSeparator#IF $id$FieldSeparator#";
       $lineno = "$FieldSeparator#IF !$id$FieldSeparator#";
            // block based IF directive
       $blockyes = "$FieldSeparator#IF:$id$FieldSeparator#";
       $blockyesend = "$FieldSeparator#ENDIF:$id$FieldSeparator#";
       $blockno = "$FieldSeparator#IF:!$id$FieldSeparator#";
       $blocknoend = "$FieldSeparator#ENDIF:!$id$FieldSeparator#";

       if ($condition) {
          $page = str_replace($lineyes, '', $page);
          $page = str_replace($blockyes, '', $page);
          $page = str_replace($blockyesend, '', $page);
          $page = preg_replace("/$blockno(.*?)$blocknoend/s", '', $page);
          $page = preg_replace("/${lineno}[^\n]*\n/", '', $page);
            } else {
          $page = str_replace($lineno, '', $page);
          $page = str_replace($blockno, '', $page);
          $page = str_replace($blocknoend, '', $page);
          $page = preg_replace("/$blockyes(.*?)$blockyesend/s", '', $page);
          $page = preg_replace("/${lineyes}[^\n]*\n/", '', $page);
       }
   }

   function GeneratePage($template, $content, $name, $hash)
   {
      global $ScriptUrl, $AdminUrl, $AllowedProtocols, $templates;
      global $datetimeformat, $dbi, $logo, $FieldSeparator;

      if (!is_array($hash))
         unset($hash);


         global $VARIABLES;

         $VARIABLES = Array();
         $vars = explode(',', $hash['variables']);
         foreach($vars as $v) {
            list($k, $v) = explode('=', $v);
            $VARIABLES[trim($k)] = trim($v);
         }

      if($template == 'BROWSE' and isset($hash['template'])) {
         $page = get_include_contents($hash['template']);
      } else {
         $page = get_include_contents($templates[$template]);
      }
	if(!$page) die("template not loaded");
      $page = str_replace('###', "$FieldSeparator#", $page);

      // valid for all pagetypes
      _iftoken('COPY', isset($hash['copy']), $page);
      _iftoken('LOCK', (isset($hash['flags']) &&
         ($hash['flags'] & FLAG_PAGE_LOCKED)), $page);
      _iftoken('ADMIN', defined('WIKI_ADMIN'), $page);

      if (strlen($hash['meta']) > 1) {
         $meta = str_replace('###', "$FieldSeparator#", htmlspecialchars($hash['meta']));
         _dotoken('META', $meta, $page, $FieldSeparator);
      } else {
         $meta = str_replace('###', "$FieldSeparator#", htmlspecialchars($hash['settings']['default_meta_description']));
        _dotoken('META', $meta, $page, $FieldSeparator);
      }

      if (strlen($hash['keywords']) > 1)
        _dotoken('METAKEYWORDS', htmlspecialchars($hash['keywords']), $page, $FieldSeparator);
      else
        _dotoken('METAKEYWORDS', htmlspecialchars($hash['settings']['default_meta_keywords']), $page, $FieldSeparator);


      _dotoken('SCRIPTURL', $ScriptUrl, $page, $FieldSeparator);
      _dotoken('ADMINURL', $AdminUrl, $page, $FieldSeparator);

      if (strlen($hash['title']) > 1)
          _dotoken('PAGE', htmlspecialchars($hash['title']), $page, $FieldSeparator);
      elseif (strlen($hash['settings']['default_title']) > 1) {
         $title = str_replace('###', "$FieldSeparator#", htmlspecialchars($hash['settings']['default_title']));
          _dotoken('PAGE', $title, $page, $FieldSeparator);
      } else
          _dotoken('PAGE', htmlspecialchars($name), $page, $FieldSeparator);

      _dotoken('PAGENAME', htmlspecialchars($name), $page, $FieldSeparator);
      _dotoken('USERTITLE', htmlspecialchars($hash['title']), $page, $FieldSeparator);
      _dotoken('VARIABLES', htmlspecialchars($hash['variables']), $page, $FieldSeparator);
      _dotoken('TEMPLATESELECT', ListTemplates($hash['template']), $page, $FieldSeparator);

      _dotoken('ALLOWEDPROTOCOLS', $AllowedProtocols, $page, $FieldSeparator);
      _dotoken('LOGO', $logo, $page, $FieldSeparator);

      // invalid for messages (search results, error messages)
      if ($template != 'MESSAGE') {
         _dotoken('PAGEURL', rawurlencode($name), $page, $FieldSeparator);
         _dotoken('LASTMODIFIED',
         date($datetimeformat, $hash['lastmodified']), $page, $FieldSeparator);
         _dotoken('LASTAUTHOR', $hash['author'], $page, $FieldSeparator);
         _dotoken('VERSION', $hash['version'], $page, $FieldSeparator);
       if (strstr($page, "$FieldSeparator#HITS$FieldSeparator#")) {
          _dotoken('HITS', GetHitCount($dbi, $name), $page, $FieldSeparator);
       }
       if (strstr($page, "$FieldSeparator#RELATEDPAGES$FieldSeparator#")) {
          _dotoken('RELATEDPAGES', LinkRelatedPages($dbi, $name), $page, $FieldSeparator);
       }
    }

      // valid only for EditLinks
      if ($template == 'EDITLINKS') {
          for ($i = 1; $i <= NUM_LINKS; $i++) {
            $ref = isset($hash['refs'][$i]) ? $hash['refs'][$i] : '';
            _dotoken("R$i", $ref, $page, $FieldSeparator);
         }
      }
      //Add secondardy WIKI content.
      //Sytax is PAGECONTENT(PAGENAME[, tagcontext])
      $page = preg_replace_callback('/PAGECONTENT\((.*?)\)/', _pagecontent, $page);
      _dotoken('CONTENT', $content, $page, $FieldSeparator);
      print $page;
   }


function wp_nav_menu($args) { ?>
	<div class='<?php echo $args['container_class']; ?>'>
	<?php
	$doc = new DOMDocument();
	$doc->loadHTML($p= _pagecontent('navigation'));	
	$uls = $doc->getElementsByTagName('ul');
	$theUl = $uls->item(0);
	$theUl->setAttribute('class', 'menu');
	$uls = $theUl->getElementsByTagName('ul');
	for($i = 0; $i < $uls->length; $i++) {
		$uls->item($i)->setAttribute('class', 'sub-menu');
	} 
	$lis = $theUl->getElementsByTagName('li');
	for($i = 0; $i < $lis->length; $i++) {
		$lis->item($i)->setAttribute('class', 'menu-item');
	} 
	echo $doc->saveXML($theUl);
	?>
	</div>
<?php }

$rw_have_posts = true;

function have_posts() {
	global $rw_have_posts;
	return $rw_have_posts;
}

function the_post() {
	global $rw_have_posts;
	$rw_have_posts = 0;
}

function the_content() {
	echo '###CONTENT###';
}

function bloginfo($arg) {
	global $templates, $TemplateName;
	if($arg == 'template_directory') {
		echo "rw-content/templates/$TemplateName/";
	} else if($arg == 'stylesheet_directory') {
		echo "rw-content/templates/$TemplateName/";
	} else if($arg == 'name') {
		echo RW_SITE_TITLE;
	} else {
		echo "unsupported bloginfo";
	}
}

function single_post_title($arg = '') {
	echo '###PAGE###';
}


function add_action($name, $args) {
	// Not supported yet
}

function get_template_part($slug, $name) {
	die('not implemented');
}

if($templates['functions.php']) include($templates['functions.php']);


?>
