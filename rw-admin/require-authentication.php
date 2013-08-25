<?php 

session_start();

if (!@$_SESSION['username']) {
    header("Location: ".$RapidWeb->rootURL."rw-admin/login.php?continue=".$_SERVER['REQUEST_URI']);
    exit();
}
