<?php

require_once "../rw-includes/config.php";
require_once "../rw-includes/stdlib.php";
require_once "../rw-includes/templating.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' and checkAuth($_REQUEST['username'], $_REQUEST['password'])) {
    $_SESSION['username'] = $_REQUEST['username'];
    if ($_REQUEST['continue']) {
        header("Location: ".$_REQUEST['continue']);
    } else {
        header("Location: ..");
    }
} else {
    $VARIABLES = array();
    $view = new OldTemplate(null, $RapidWeb);
    $view->render(_rw_pathfind('login'));
}
