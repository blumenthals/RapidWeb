<?php 

if(!isset($_SERVER['PHP_AUTH_USER]']) && isset($_SERVER['HTTP_AUTHORIZATION'])) {
    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
}

if (!$_SERVER['PHP_AUTH_USER'] or !isset($USERS[$_SERVER['PHP_AUTH_USER']]) or !checkAuth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    Header("WWW-Authenticate: Basic realm=\"RapidWeb Admin\"");
    Header("HTTP/1.0 401 Unauthorized");
    die(gettext("You entered an invalid login or password."));
} else {
    setCookie('loggedIn', json_encode(true));
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
