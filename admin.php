<?php // $Id: admin.php,v 1.5 2000/11/13 10:59:27 ahollosi Exp $

   function rcs_id($id) {}   // otherwise this gets in the way

   define('WIKI_ADMIN', true);	// has to be before includes

   include("php/lib/config.php");
   include("php/lib/stdlib.php");

   // set these to your preferences. For heaven's sake
   // pick a good password!
   $USERS = array(
      'admin' => 'rwdev',
      'user' => 'rwuser'
   );

   // from the manual, Chapter 16
   if (!$_SERVER['PHP_AUTH_USER'] or $USERS[$_SERVER['PHP_AUTH_USER']] != $_SERVER["PHP_AUTH_PW"]) {
      Header("WWW-Authenticate: Basic realm=\"RapidWeb Admin\"");
      Header("HTTP/1.0 401 Unauthorized");
      echo gettext ("You entered an invalid login or password.");
      exit;
   }

   // All requests require the database
   $dbi = OpenDataBase($WikiPageStore);

   if(isset($_REQUEST['lock']) || isset($_REQUEST['unlock'])) {
      $lock = $_REQUEST['lock'];
      $lock = $_REQUEST['unlock'];
      include ('php/admin/lockpage.php');
   } elseif (isset($_REQUEST['zip'])) {
      $zip = $_REQUEST['zip'];
      include ('php/lib/ziplib.php');
      include ('php/admin/zip.php');
      ExitWiki('');
   } elseif (isset($_REQUEST['dumpserial'])) {
      $dumpserial = $_REQUEST['dumpserial'];
      include ('php/admin/dumpserial.php');
   } elseif (isset($_REQUEST['loadserial'])) {
      $loadserial = $_REQUEST['loadserial'];
      include ('php/admin/loadserial.php');
   } elseif (isset($_REQUEST['remove'])) {
      if (get_magic_quotes_gpc()) {
         $remove = stripslashes($_REQUEST['remove']);
      } else {
         $remove = $_REQUEST['remove'];
      }
      if (function_exists('RemovePage')) {
        $html .= sprintf(gettext ("You are about to remove '%s' permanently!"), 
          htmlspecialchars($remove));
        $html .= "\n<P>";
        $url = rawurlencode($remove);
        $html .= sprintf(gettext ("Click %shere%s to remove the page now."),
          "<A HREF=\"$ScriptUrl?removeok=$url\">", "</A>");
        $html .= "\n<P>";
        $html .= gettext ("Otherwise press the \"Back\" button of your browser.");
      } else {
         $html = gettext ("Function not yet implemented.");
      }
      GeneratePage('MESSAGE', $html, gettext ("Remove page"), 0);
      ExitWiki('');
   } elseif (isset($_REQUEST['removeok'])) {
      if (get_magic_quotes_gpc()) {
        $removeok = stripslashes($_REQUEST['removeok']);
      } else {
        $removeok = $_REQUEST['removeok'];
      }
      RemovePage($dbi, $removeok);
      $html = sprintf(gettext ("Removed page '%s' succesfully."),
        htmlspecialchars($removeok));
      GeneratePage('MESSAGE', $html, gettext ("Remove page"), 0);
      ExitWiki('');
   }

   include('index.php');
?>
