<?php
   /* $Id: lockpage.php,v 1.1 2000/11/08 15:30:16 ahollosi Exp $ */


   $page = isset($_REQUEST['lock']) ? $_REQUEST['lock'] : $_REQUEST['unlock'];
   $pagename = rawurldecode($page);
   $pagehash = RetrievePage($dbi, $pagename, $WikiPageStore);
   if (! is_array($pagehash))
      ExitWiki("Unknown page '".htmlspecialchars($pagename)."'\n");
   if (isset($_REQUEST['lock'])) {
      $pagehash['flags'] |= FLAG_PAGE_LOCKED;
      InsertPage($dbi, $pagename, $pagehash);
      // echo htmlspecialchars($page) . " locked\n";
      header("Location: {$RapidWeb->rootURL}?$page");
      exit();
   } elseif(isset($_REQUEST['unlock'])) {
      $pagehash['flags'] &= ~FLAG_PAGE_LOCKED;
      InsertPage($dbi, $pagename, $pagehash);
      // echo htmlspecialchars($page) . " unlocked\n";
      header("Location: {$RapidWeb->rootURL}?$page");
      exit();
   }
?>
