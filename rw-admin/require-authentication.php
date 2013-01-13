<?php 

session_start();

if (!@$_SESSION['username']) {
    header("Location: ".dirname($_SERVER['SCRIPT_NAME'])."/rw-admin/login.php?continue=".$_SERVER['REQUEST_URL']);
    exit();
}
