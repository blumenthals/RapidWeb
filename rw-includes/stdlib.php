<?php

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


function ExitWiki($errormsg) {
  static $exitwiki = 0;
  global $dbi;

  if($exitwiki) exit();
  $exitwiki = 1;

  if($errormsg <> '') {
     print "<P><hr noshade><h2>" . ("RapidWeb Fatal Error") . "</h2>\n";
     print $errormsg;
     print "\n</BODY></HTML>";
  }
  exit;
}


function LinkExistingWikiWord($wikiword, $linktext='', $target = '') {
    global $RapidWeb;
    return $RapidWeb->linkExistingWikiWord($wikiword, $linktext, $target);
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
  if(preg_match('/[<>"]/', $url)) {
     return "<b><u>BAD URL -- remove all of &lt;, &gt;, &quot;</u></b>";
  }
  return "<img src=\"$url\" ALT=\"$alt\">";
}


function RenderQuickSearch($value = '') {
  global $ScriptUrl;
  return "<form action=\"$ScriptUrl\">\n" .
     "<input type=text size=30 name=search value=\"$value\">\n" .
     "<input type=submit value=\"". ("Search") .
     "\"></form>\n";
}

function RenderFullSearch($value = '') {
  global $ScriptUrl;
  return "<form action=\"$ScriptUrl\">\n" .
     "<input type=text size=30 name=full value=\"$value\">\n" .
     "<input type=submit value=\"". ("Search") .
     "\"></form>\n";
}

function ParseAdminTokens($line) {
  global $ScriptUrl;

  while (preg_match("/%%ADMIN-INPUT-(.*?)-(\w+)%%/", $line, $matches)) {
 $head = str_replace('_', ' ', $matches[2]);
     $form = "<FORM ACTION=\"$ScriptUrl\" METHOD=POST>"
    ."$head: <INPUT NAME=$matches[1] SIZE=20> "
    ."<INPUT TYPE=SUBMIT VALUE=\"" . ("Go") . "\">"
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
  global $dbc, $ScriptUrl, $AllowedProtocols, $InlineImages;

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

  if (IsWikiPage($dbc, $URL)) {
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


/** encapsulates transform into a proper function, so we can include it as part of an expression.
*/
function _pagecontent($page) {
  global $dbc, $AllowedProtocols, $logo, $FieldSeparator, $datetimeformat, $WikiNameRegexp;
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
  $pagehash = RetrievePage($dbc, $pageName);
  if (is_array($pagehash)) {
      $p = new \Rapidweb\Parser($pagehash);
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

function rw_make_query_string($args) {
  $o = array();
  foreach($args as $k => $v) {
     $o[] = "$k=$v";
  }
  return join('&', $o);
}

function rw_parse_intent($line) {
  $o = array();
  $expect = 'key';
  $key = '';
  $value = '';
  $item = '';
  while(!empty($line)) {
     if(preg_match('/^\s*"([^"]+)"/', $line, $matches)) {
        $item = $matches[1];
     } elseif(preg_match('/^\s*([^[:space:]]+)/', $line, $matches)) {
        $item = $matches[1];
     } elseif(preg_match('/^\s+/', $line, $matches)) {
     } else {
        echo "Can't figure out what you meant at ".htmlspecialchars($line);
        return array();
     }
     $line = substr($line, strlen($matches[0]));
     if($expect == 'key') {
        $expect = 'value';
        $key = $item;
     } elseif($expect == 'value') {
        $expect = 'key';
        $value = $item;
        $o[$key] = $value;
     }
  }
  return $o;
}

function checkAuth($user, $pass) {
    // Yep! Three variables named something like user.
    global $USERS;
    global $USER;
    $arr = $USERS[$_SERVER['PHP_AUTH_USER']];

    // Handle old rapidweb users that were just $user => $pass
    if (!is_array($arr)) {
        $arr = array('password' => $arr);
    }

    /// @todo move this into \Rapidweb\User
    $USER = \Rapidweb\User::fromArray($user, $arr);
    if ($USER->password == $pass) return true;
    if (crypt($pass, $USER->crypt) == $USER->crypt) return true;

    $USER = null;
    return false;
}

/// @todo move to its own startup file
// All requests require the database
$dbc = OpenDataBase();
$RapidWeb = new RapidWeb();
$RapidWeb->add_plugins_directory(dirname(__FILE__)."/../rw-content/plugins");
$RapidWeb->initialize();
