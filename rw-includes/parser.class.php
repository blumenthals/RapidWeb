<?php

class Parser {
    private $pagehash;
    private $stack;
    private $nforms;

    function __construct($pagehash) {
         $this->pagehash = $pagehash;
         $this->tagdata["emailform"] = array(array($this, 'StartEmailForm'), "</form>");
         $this->nforms = 0;
    }

    // expects $pagehash and $html to be set
    function tokenize($str, $pattern, &$orig) {
         global $FieldSeparator;
         // Find any strings in $str that match $pattern and
         // store them in $orig, replacing them with tokens
         // starting at number $this->ntokens - returns tokenized string
         $new = '';      
         while (preg_match("/^(.*?)($pattern)/", $str, $matches)) {
            $linktoken = $FieldSeparator . $FieldSeparator . ($this->ntokens++) . $FieldSeparator;
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
         "td" => array("<td>", "</td>"),
    );

    function AddOutputWrapper($tag, $data = null) {
      if(is_array($this->tagdata[$tag][0])) {
         $o = call_user_func($this->tagdata[$tag][0], $data);
      } else {
         $d = $this->tagdata[$tag];
         $o = $d[0];
      }

      $this->tagstack[] = $tag;
      return($o);
    }

    function CloseOutputWrapper($tag, $data = null) {
      $d = $this->tagdata[$tag];

      $o = '';
      while($t = array_pop($this->tagstack)) {
         if(is_array($this->tagdata[$t][1])) {
           $o .= call_user_func($this->tagdata[$t][1], $data);
         } else {
           $d = $this->tagdata[$t];
         }
         $o .= $d[1];
         if($t == $tag) break;
      }
      return($o);
    }

    function InOutputWrapper($tag) {
        return in_array($tag, $this->tagstack);
    }

    function StartEmailForm($matches) {
      $err = '';
      $args = rw_parse_intent($matches[1]);
      if(!$args['to'] && !defined('RW_CONTACT_EMAIL')) {
         $err = 'This form has nobody to receive it';
      }
      $args = array();
      $args['sendform'] = 1;
      $args['frompage'] = $this->pagehash['pagename'];
      $args['formno'] = $this->nforms++;
      $args = rw_make_query_string($args);
      return "$err<form action='{$_SERVER['PHP_SELF']}?$args' method=POST>";
    }

    function StartLinks($tmpline) {
      global $FieldSeparator, $AllowedProtocols;
      //////////////////////////////////////////////////////////
      // New linking scheme: links are in brackets. This will
      // emulate typical HTML linking as well as Wiki linking.
	
      // First need to protect [[. 
      $this->oldn = $this->ntokens;
      $tmpline = $this->tokenize($tmpline, '\[\[', $this->replacements, $this->ntokens);
      while ($this->oldn < $this->ntokens)
         $this->replacements[$this->oldn++] = '[';

      // Now process the [\d+] links which are numeric references	
      $this->oldn = $this->ntokens;
      $tmpline = $this->tokenize($tmpline, '\[\s*\d+\s*\]', $this->replacements, $this->ntokens);
      while ($this->oldn < $this->ntokens) {
         $num = (int) substr($this->replacements[$this->oldn], 1);
         if (! empty($embedded[$num]))
            $this->replacements[$this->oldn] = $embedded[$num];
         $this->oldn++;
      }

      // match anything else between brackets 
      $this->oldn = $this->ntokens;
      $tmpline = $this->tokenize($tmpline, '\[.+?\]( ?\(new window\))?', $this->replacements, $this->ntokens);
      while ($this->oldn < $this->ntokens) {
        $link = ParseAndLink($this->replacements[$this->oldn]);	
        $this->replacements[$this->oldn] = $link['link'];
        $this->oldn++;
      }

      //////////////////////////////////////////////////////////
      // replace all URL's with tokens, so we don't confuse them
      // with Wiki words later. Wiki words in URL's break things.
      // URLs preceeded by a '!' are not linked

      $tmpline = $this->tokenize($tmpline, "!?\b($AllowedProtocols):[^\s<>\[\]\"'()]*[^\s<>\[\]\"'(),.?]", $this->replacements, $this->ntokens);
        while ($this->oldn < $this->ntokens) {
            if($this->replacements[$this->oldn][0] == '!') {
                $this->replacements[$this->oldn] = substr($this->replacements[$this->oldn], 1);
            } else {
                $this->replacements[$this->oldn] = LinkURL($this->replacements[$this->oldn]);
            }
            $this->oldn++;
        }

        return $tmpline;

    }

    function FinishLinks($tmpline) {
      global $FieldSeparator;
      ///////////////////////////////////////////////////////
      // Replace tokens

      for ($i = 0; $i < $this->ntokens; $i++)
	  $tmpline = str_replace($FieldSeparator.$FieldSeparator.$i.$FieldSeparator, $this->replacements[$i], $tmpline);

      return $tmpline;

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

function parse($str, $tagcontext = null) {
    global $FieldSeparator, $AllowedProtocols;

    if(!is_array($str)) $str = explode("\n", $str);

    $this->stack = new Stack;
    $this->tagcontext = strtolower($tagcontext);

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

    foreach($str as $tmpline) {
        unset($tokens);
        $this->ntokens = 0;
        $this->replacements = array();

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
        } elseif (preg_match("/^EMAIL\s*FORM\s*(.*)/", $tmpline, $matches)) {
            $html .= $this->AddOutputWrapper('emailform', $matches);
            continue;
        } elseif (preg_match("/^END\s*EMAIL\s*FORM.*/", $tmpline, $matches)) {
            $html .= $this->CloseOutputWrapper('emailform', $matches);
            continue;
        }

        if (preg_match("/^START\s*TABLE(.*)/", $tmpline, $matches)) {
            $html .= $this->AddOutputWrapper("table");
            continue;
        } elseif(preg_match("/^END\s*TABLE/", $tmpline, $matches)) {
            $html .= $this->CloseOutputWrapper("table");
            continue;
        } elseif($this->InOutputWrapper("table")) {
            $tmpline = $this->StartLinks($tmpline);
            $t = explode('|', $tmpline);
            foreach($t as $k => $v) {
                $v = $this->DoInlineMarkup($v);
                $t[$k] = "<td>$v</td>";
            }
            $tmpline = $this->FinishLinks(join($t, ''));
            $html .= "<tr>$tmpline</tr>";
            continue;
        }

		if (preg_match("/^START\s*HTML.*/", $tmpline, $matches)) { //Block HTML
			$htmlmode = true;
			continue;	
		} elseif ($htmlmode == true) {
			if (preg_match("/END\s*HTML.*/", $tmpline, $matches)) {
				$htmlmode = false;
				continue;
			}
			$html .= $tmpline;
			continue;
		} elseif (!strlen($tmpline) || $tmpline == "\r") { // this is a blank line, send <p>
            $html .= $this->SetHTMLOutputMode('', ZERO_LEVEL, 0);
            continue;
        } elseif (preg_match("/(^\|)(.*)/", $tmpline, $matches)) { // HTML mode
            $html .= $this->SetHTMLOutputMode("", ZERO_LEVEL, 0);
            $html .= $matches[2];
            continue;
        }

        $tmpline = $this->StartLinks($tmpline);
        $tmpline = $this->DoInlineMarkup($tmpline);

        if (preg_match("/(^\t+)(.*?)(:\t)(.*$)/", $tmpline, $matches)) { /* this is a dictionary list (<dl>) item */
            $numtabs = strlen($matches[1]);
            $html .= $this->SetHTMLOutputMode('dl', NESTED_LEVEL, $numtabs);
            $tmpline = '';
            if(trim($matches[2])) $tmpline = '<dt>' . $matches[2];
            $tmpline .= '<dd>' . $matches[4];
        } elseif (preg_match("/(^\t+)(\*|\d+|#)/", $tmpline, $matches)) { /* this is part of a list (<ul>, <ol>) */
            $numtabs = strlen($matches[1]);
            if ($matches[2] == '*') {
                $listtag = 'ul';
            } else {
                $listtag = 'ol'; // a rather tacit assumption. oh well.
            }
            $tmpline = preg_replace("/^(\t+)(\*|\d+|#)/", "", $tmpline);
            $html .= $this->SetHTMLOutputMode($listtag, NESTED_LEVEL, $numtabs);
            $html .= '<li>';


            /* tabless markup for unordered, ordered, and dictionary lists 
             * ul/ol list types can be mixed, so we only look at the last 
             * character. Changes e.g. from "**#*" to "###*" go unnoticed. and 
             * wouldn't make a difference to the HTML layout anyway.
             */

        } elseif (preg_match("/^([#*]*\*)[^#]/", $tmpline, $matches)) { /* unordered lists <UL>: "*" */
            // this is part of an unordered list
            $numtabs = strlen($matches[1]);
            $tmpline = preg_replace("/^([#*]*\*)/", '', $tmpline);
            $html .= $this->SetHTMLOutputMode('ul', NESTED_LEVEL, $numtabs);
            $html .= '<li>';

        } elseif (preg_match("/^([#*]*\#)/", $tmpline, $matches)) { /* ordered lists <OL>: "#" */
            $numtabs = strlen($matches[1]);
            $tmpline = preg_replace("/^([#*]*\#)/", "", $tmpline);
            $html .= $this->SetHTMLOutputMode('ol', NESTED_LEVEL, $numtabs);
            $html .= '<li>';

        } elseif (preg_match("/(^;+)(.*?):(.*$)/", $tmpline, $matches)) { /* definition lists <DL>: ";text:text" */
            $numtabs = strlen($matches[1]);
            $html .= $this->SetHTMLOutputMode('dl', NESTED_LEVEL, $numtabs);
            $tmpline = '';
            if(trim($matches[2])) $tmpline = '<dt>' . $matches[2];
            $tmpline .= '<dd>' . $matches[3];
        } elseif (preg_match("/^(!{1,3})[^!]/", $tmpline, $whichheading)) { /* lines starting with !,!!,!!! are headings */
            if($whichheading[1] == '!') $heading = 'h3';
            elseif($whichheading[1] == '!!') $heading = 'h2';
            elseif($whichheading[1] == '!!!') $heading = 'h1';
            $tmpline = preg_replace("/^!+/", '', $tmpline);
            $html .= $this->SetHTMLOutputMode($heading, ZERO_LEVEL, 0);

        } else {
             // it's ordinary output if nothing else
             $html .= $this->SetHTMLOutputMode('p', ZERO_LEVEL, 0);
        }

        $tmpline = str_replace('%%Search%%', $quick_search_box, $tmpline);
        $tmpline = str_replace('%%Fullsearch%%', $full_search_box, $tmpline);
        if(defined('WIKI_ADMIN') && strstr($tmpline, '%%ADMIN-')) $tmpline = ParseAdminTokens($tmpline);


        $tmpline = $this->FinishLinks($tmpline);


        $html .= $tmpline . "\n";
    }

    $html .= $this->SetHTMLOutputMode('', ZERO_LEVEL, 0);

    return $html;
}

   /*
      Wiki HTML output can, at any given time, be in only one mode.
      It will be something like Unordered List, Preformatted Text,
      plain text etc. When we change modes we have to issue close tags
      for one mode and start tags for another.

      $tag ... HTML tag to insert
      $tagtype ... ZERO_LEVEL - close all open tags before inserting $tag
		   NESTED_LEVEL - close tags until depths match
      $level ... nesting level (depth) of $tag
		 nesting is arbitrary limited to 10 levels
   */

   function SetHTMLOutputMode($tag, $tagtype, $level)
   {
      $retvar = '';

      if ($tagtype == ZERO_LEVEL) {
         // empty the stack until $level == 0;
         if ($tag == $this->stack->top()) {
            return; // same tag? -> nothing to do
         }
         while ($this->stack->cnt() > 0) {
            $closetag = $this->stack->pop();
            if($this->stack->cnt() > 0 || $closetag != $this->tagcontext) 
              $retvar .= "</$closetag>\n";
         }

         if ($tag) {
            if($this->stack->cnt() > 0 || $tag != $this->tagcontext) 
              $retvar .= "<$tag>\n";
            $this->stack->push($tag);
         }


      } elseif ($tagtype == NESTED_LEVEL) {
         if ($level < $this->stack->cnt()) {
            // $tag has fewer nestings (old: tabs) than stack,
	    // reduce stack to that tab count
            while ($this->stack->cnt() > $level) {
               $closetag = $this->stack->pop();
               if ($closetag == false) {
                  //echo "bounds error in tag stack";
                  break;
               }
               if($this->stack->cnt() > 0 || $closetag != $this->tagcontext) 
                 $retvar .= "</$closetag>\n";
            }

	    // if list type isn't the same,
	    // back up one more and push new tag
	    if ($tag != $this->stack->top()) {
	       $closetag = $this->stack->pop();
	       $retvar .= "</$closetag><$tag>\n";
	       $this->stack->push($tag);
	    }

         } elseif ($level > $this->stack->cnt()) {
            // we add the diff to the stack
            // stack might be zero
            while ($this->stack->cnt() < $level) {
	       if($this->stack->cnt() > 0 || $tag != $this->tagcontext) 
                  $retvar .= "<$tag>\n";
               $this->stack->push($tag);
               if ($this->stack->cnt() > 10) {
                  // arbitrarily limit tag nesting
                  ExitWiki(("Stack bounds exceeded in SetHTMLOutputMode"));
               }
            }

         } else { // $level == $this->stack->cnt()
            if ($tag == $this->stack->top()) {
               return; // same tag? -> nothing to do
            } else {
	       // different tag - close old one, add new one
               $closetag = $this->stack->pop();
               $retvar .= "</$closetag>\n";
               $retvar .= "<$tag>\n";
               $this->stack->push($tag);
            }
         }


      } else { // unknown $tagtype
         ExitWiki ("Passed bad tag type value in SetHTMLOutputMode");
      }

      return $retvar;
   }
   // end SetHTMLOutputMode
}
// end class definition
