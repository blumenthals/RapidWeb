<?php

require_once "../rw-includes/config.php";
require_once "../rw-includes/stdlib.php";
session_start();

$_SESSION['username'] = '';

header('Location: '.dirname($_SERVER['SCRIPT_NAME'])."/..");
exit();
