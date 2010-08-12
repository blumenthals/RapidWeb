<?php 
   if (!$_SERVER['PHP_AUTH_USER'] or !isset($USERS[$_SERVER['PHP_AUTH_USER']]) or  $USERS[$_SERVER['PHP_AUTH_USER']] != $_SERVER["PHP_AUTH_PW"]) {
      Header("WWW-Authenticate: Basic realm=\"RapidWeb Admin\"");
      Header("HTTP/1.0 401 Unauthorized");
      die(gettext("You entered an invalid login or password."));
   }
?>
