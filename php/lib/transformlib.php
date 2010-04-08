<?php rcs_id('$Id: transform.php,v 1.8 2001/01/04 18:34:15 ahollosi Exp $');

   class Parser {
      private $pagehash;

      function __construct($pagehash) {
         $this->pagehash = $pagehash;
      }
      // expects $pagehash and $html to be set
      function tokenize($str, $pattern, &$orig, &$ntokens) {
         global $FieldSeparator;
         // Find any strings in $str that match $pattern and
         // store them in $orig, replacing them with tokens
         // starting at number $ntokens - returns tokenized string
         $new = '';      
         while (preg_match("/^(.*?)($pattern)/", $str, $matches)) {
            $linktoken = $FieldSeparator . $FieldSeparator . ($ntokens++) . $FieldSeparator;
            $new .= $matches[1] . $linktoken;
            $orig[] = $matches[2];
            $str = substr($str, strlen($matches[0]));
         }
         $new .= $str;
         return $new;
      }

      private $tagstack = Array();
      private $tagdata = array(
         "columns" => array("<table class='rw-columns'><tr>", "</tr></table>"), 
         "column" => array("<td class='rw-column'>", "</td>"),
         "table" => array("<table>", "</table>"), 
         "tr" => array("<tr>", "</tr>"),
         "td" => array("<td>", "</td>")
      );

   function AddOutputWrapper($tag) {
      $d = $this->tagdata[$tag];

      $this->tagstack[] = $tag;
      return($d[0]);
   }

   function CloseOutputWrapper($tag) {
      $d = $this->tagdata[$tag];

      $o = '';
      while($t = array_pop($this->tagstack)) {
         $d = $this->tagdata[$t];
         $o .= $d[1];
         if($t == $tag) break;
      }
      return($o);
   }

   function InOutputWrapper($tag) {
      return in_array($tag, $this->tagstack);
   }

   function DoInlineMarkup($tmpline) {
      //////////////////////////////////////////////////////////
      // escape HTML metachars
      $tmpline = str_replace('&', '&amp;', $tmpline);
      $tmpline = str_replace('>', '&gt;', $tmpline);
      $tmpline = str_replace('<', '&lt;', $tmpline);

      // four or more dashes to <hr>
      $tmpline = preg_replace("/^-{4,}/", '<hr>', $tmpline);

      // %%% are linebreaks
      $tmpline = str_replace('%%%', '<br>', $tmpline);

      // bold italics (old way)
      $tmpline = preg_replace("|(''''')(.*?)(''''')|",
                              "<strong><em>\\2</em></strong>", $tmpline);

      // bold (old way)
      $tmpline = preg_replace("|(''')(.*?)(''')|",
                              "<strong>\\2</strong>", $tmpline);

      // bold
      $tmpline = preg_replace("|(__)(.*?)(__)|",
                              "<strong>\\2</strong>", $tmpline);

      // italics
      $tmpline = preg_replace("|('')(.*?)('')|",
                              "<em>\\2</em>", $tmpline);


      return $tmpline;
   }

   function parse($str) {
      global $FieldSeparator, $AllowedProtocols;

   // Prepare replacements for references [\d+]
   for ($i = 1; $i < (NUM_LINKS + 1); $i++) {
      if (! empty($this->pagehash['refs'][$i])) {
         if (preg_match("/($InlineImages)$/i", $this->pagehash['refs'][$i])) {
            // embed images
            $embedded[$i] = LinkImage($this->pagehash['refs'][$i]);
         } else {
            // ordinary link
            $embedded[$i] = LinkURL($this->pagehash['refs'][$i], "[$i]");
         }
      }
   }


   // only call these once, for efficiency
   $quick_search_box  = RenderQuickSearch();
   $full_search_box   = RenderFullSearch();
   $most_popular_list = RenderMostPopular();


   // Loop over all lines of the page and apply transformation rules
   $numlines = count($this->pagehash["content"]);

   for ($index = 0; $index < $numlines; $index++) {
      unset($tokens);
      unset($replacements);
      $ntokens = 0;
      $replacements = array();
      
      $tmpline = $str[$index];

      // Handle column tables
      if (preg_match("/^START\s*COLUMNS.*/", $tmpline, $matches)) {
         $html .= $this->AddOutputWrapper('columns');
         $html .= $this->AddOutputWrapper('column');
         continue;
      } elseif ($this->InOutputWrapper('columns') and preg_match("/^NEW\s*COLUMN.*/", $tmpline, $matches)) {
         $html .= $this->CloseOutputWrapper("column");
         $html .= $this->AddOutputWrapper("column");
         continue;
      } elseif (preg_match("/^END\s*COLUMNS.*/", $tmpline, $matches)) {
         $html .= $this->CloseOutputWrapper("columns");
         continue;
      }

      if (preg_match("/^START\s*TABLE(.*)/", $tmpline, $matches)) {
         $html .= $this->AddOutputWrapper("table");
         continue;
      } elseif(preg_match("/^END\s*TABLE/", $tmpline, $matches)) {
         $html .= $this->CloseOutputWrapper("table");
         continue;
      } elseif($this->InOutputWrapper("table")) {
         $t = explode('|', $tmpline);
         foreach($t as $k => $v) {
            $t[$k] = "<td>".$this->DoInlineMarkup($v)."</td>";
         }
         $html .= "<tr>".join($t, '')."</tr>";
         continue;
      }

		//Block HTML
		if (preg_match("/^STARTHTML.*/", $tmpline, $matches)) {
			$htmlmode = true;
			continue;	
		}

		elseif ($htmlmode == true) {
			if (preg_match("/ENDHTML.*/", $tmpline, $matches)) {
				$htmlmode = false;
				continue;
			}
			$html .= $tmpline;
			continue;
		}

      elseif (!strlen($tmpline) || $tmpline == "\r") {
         // this is a blank line, send <p>
         $html .= SetHTMLOutputMode('', ZERO_LEVEL, 0);
         continue;
      }


      elseif (preg_match("/(^\|)(.*)/", $tmpline, $matches)) {
         // HTML mode
         $html .= SetHTMLOutputMode("", ZERO_LEVEL, 0);
         $html .= $matches[2];
         continue;
      }



      //////////////////////////////////////////////////////////
      // New linking scheme: links are in brackets. This will
      // emulate typical HTML linking as well as Wiki linking.
	
      // First need to protect [[. 
      $oldn = $ntokens;
      $tmpline = $this->tokenize($tmpline, '\[\[', $replacements, $ntokens);
      while ($oldn < $ntokens)
         $replacements[$oldn++] = '[';

      // Now process the [\d+] links which are numeric references	
      $oldn = $ntokens;
      $tmpline = $this->tokenize($tmpline, '\[\s*\d+\s*\]', $replacements, $ntokens);
      while ($oldn < $ntokens) {
	 $num = (int) substr($replacements[$oldn], 1);
         if (! empty($embedded[$num]))
            $replacements[$oldn] = $embedded[$num];
	 $oldn++;
      }

      // match anything else between brackets 
      $oldn = $ntokens;
      $tmpline = $this->tokenize($tmpline, '\[.+?\]( ?\(new window\))?', $replacements, $ntokens);
      while ($oldn < $ntokens) {
	$link = ParseAndLink($replacements[$oldn]);	
	$replacements[$oldn] = $link['link'];
	$oldn++;
      }

      //////////////////////////////////////////////////////////
      // replace all URL's with tokens, so we don't confuse them
      // with Wiki words later. Wiki words in URL's break things.
      // URLs preceeded by a '!' are not linked

      $tmpline = $this->tokenize($tmpline, "!?\b($AllowedProtocols):[^\s<>\[\]\"'()]*[^\s<>\[\]\"'(),.?]", $replacements, $ntokens);
      while ($oldn < $ntokens) {
        if($replacements[$oldn][0] == '!')
	   $replacements[$oldn] = substr($replacements[$oldn], 1);
	else
	   $replacements[$oldn] = LinkURL($replacements[$oldn]);
        $oldn++;
      }

      $tmpline = $this->DoInlineMarkup($tmpline);

      //////////////////////////////////////////////////////////
      // unordered, ordered, and dictionary list  (using TAB)

      if (preg_match("/(^\t+)(.*?)(:\t)(.*$)/", $tmpline, $matches)) {
         // this is a dictionary list (<dl>) item
         $numtabs = strlen($matches[1]);
         $html .= SetHTMLOutputMode('dl', NESTED_LEVEL, $numtabs);
	 $tmpline = '';
	 if(trim($matches[2]))
            $tmpline = '<dt>' . $matches[2];
	 $tmpline .= '<dd>' . $matches[4];

      } elseif (preg_match("/(^\t+)(\*|\d+|#)/", $tmpline, $matches)) {
         // this is part of a list (<ul>, <ol>)
         $numtabs = strlen($matches[1]);
         if ($matches[2] == '*') {
            $listtag = 'ul';
         } else {
            $listtag = 'ol'; // a rather tacit assumption. oh well.
         }
         $tmpline = preg_replace("/^(\t+)(\*|\d+|#)/", "", $tmpline);
         $html .= SetHTMLOutputMode($listtag, NESTED_LEVEL, $numtabs);
         $html .= '<li>';


      //////////////////////////////////////////////////////////
      // tabless markup for unordered, ordered, and dictionary lists
      // ul/ol list types can be mixed, so we only look at the last
      // character. Changes e.g. from "**#*" to "###*" go unnoticed.
      // and wouldn't make a difference to the HTML layout anyway.

      // unordered lists <UL>: "*"
      } elseif (preg_match("/^([#*]*\*)[^#]/", $tmpline, $matches)) {
         // this is part of an unordered list
         $numtabs = strlen($matches[1]);
         $tmpline = preg_replace("/^([#*]*\*)/", '', $tmpline);
         $html .= SetHTMLOutputMode('ul', NESTED_LEVEL, $numtabs);
         $html .= '<li>';

      // ordered lists <OL>: "#"
      } elseif (preg_match("/^([#*]*\#)/", $tmpline, $matches)) {
         // this is part of an ordered list
         $numtabs = strlen($matches[1]);
         $tmpline = preg_replace("/^([#*]*\#)/", "", $tmpline);
         $html .= SetHTMLOutputMode('ol', NESTED_LEVEL, $numtabs);
         $html .= '<li>';

      // definition lists <DL>: ";text:text"
      } elseif (preg_match("/(^;+)(.*?):(.*$)/", $tmpline, $matches)) {
         // this is a dictionary list item
         $numtabs = strlen($matches[1]);
         $html .= SetHTMLOutputMode('dl', NESTED_LEVEL, $numtabs);
	 $tmpline = '';
	 if(trim($matches[2]))
            $tmpline = '<dt>' . $matches[2];
	 $tmpline .= '<dd>' . $matches[3];


      //////////////////////////////////////////////////////////
      // remaining modes: headings, normal text	

      } elseif (preg_match("/^(!{1,3})[^!]/", $tmpline, $whichheading)) {
	 // lines starting with !,!!,!!! are headings
	 if($whichheading[1] == '!') $heading = 'h3';
	 elseif($whichheading[1] == '!!') $heading = 'h2';
	 elseif($whichheading[1] == '!!!') $heading = 'h1';
	 $tmpline = preg_replace("/^!+/", '', $tmpline);
	 $html .= SetHTMLOutputMode($heading, ZERO_LEVEL, 0);

      } else {
         // it's ordinary output if nothing else
         $html .= SetHTMLOutputMode('p', ZERO_LEVEL, 0);
      }

      $tmpline = str_replace('%%Search%%', $quick_search_box, $tmpline);
      $tmpline = str_replace('%%Fullsearch%%', $full_search_box, $tmpline);
      $tmpline = str_replace('%%Mostpopular%%', $most_popular_list, $tmpline);
      if(defined('WIKI_ADMIN') && strstr($tmpline, '%%ADMIN-'))
         $tmpline = ParseAdminTokens($tmpline);


      ///////////////////////////////////////////////////////
      // Replace tokens

      for ($i = 0; $i < $ntokens; $i++)
	  $tmpline = str_replace($FieldSeparator.$FieldSeparator.$i.$FieldSeparator, $replacements[$i], $tmpline);


      $html .= $tmpline . "\n";
   }

   $html .= SetHTMLOutputMode('', ZERO_LEVEL, 0);

      return $html;
   }
}

$p = new Parser($pagehash);
$html = $p->parse($pagehash['content']);

?>
