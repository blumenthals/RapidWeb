<?php // $Id: admin.php,v 1.5 2000/11/13 10:59:27 ahollosi Exp $

   function rcs_id($id) {}   // otherwise this gets in the way

   define('WIKI_ADMIN', true);	// has to be before includes

   include("php/lib/config.php");
   include("php/lib/stdlib.php");

   // set these to your preferences. For heaven's sake
   // pick a good password!
   $wikiadmin   = "admin";
   $adminpasswd = "password";

   // Do not tolerate sloppy systems administration
   if (empty($wikiadmin) || empty($adminpasswd)) {
      echo "Set the administrator account and password first.\n";
      exit;
   }

   // from the manual, Chapter 16
   if (($PHP_AUTH_USER != $wikiadmin  )  ||
       ($PHP_AUTH_PW   != $adminpasswd)) {
      Header("WWW-Authenticate: Basic realm=\"RapidWeb Admin\"");
      Header("HTTP/1.0 401 Unauthorized");
      echo gettext ("You entered an invalid login or password.");
      exit;
   }

   // All requests require the database
   $dbi = OpenDataBase($WikiPageStore);

   if (isset($lock) || isset($unlock)) {
      include ('php/admin/lockpage.php');
   } elseif (isset($zip)) {
      include ('php/lib/ziplib.php');
      include ('php/admin/zip.php');
      ExitWiki('');
   } elseif (isset($dumpserial)) {
      include ('php/admin/dumpserial.php');
   } elseif (isset($loadserial)) {
      include ('php/admin/loadserial.php');
   } elseif (isset($remove)) {
      if (get_magic_quotes_gpc())
         $remove = stripslashes($remove);
      if (function_exists('RemovePage')) {
         $html .= sprintf(gettext ("You are about to remove '%s' permanently!"), htmlspecialchars($remove));
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
   } elseif (isset($removeok)) {
      if (get_magic_quotes_gpc())
	 $removeok = stripslashes($removeok);
      RemovePage($dbi, $removeok);
      $html = sprintf(gettext ("Removed page '%s' succesfully."),
		      htmlspecialchars($removeok));
      GeneratePage('MESSAGE', $html, gettext ("Remove page"), 0);
      ExitWiki('');
   }

   include('index.php');
?>
