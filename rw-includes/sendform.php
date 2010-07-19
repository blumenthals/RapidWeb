<?php

	if(!defined('RW_CONTACT_EMAIL')) die ("Who do I send this to? Set RW_CONTACT_EMAIL");


	$message = '';
	foreach($_POST as $k => $v) {
		$message .= "$k: $v\n";
	}

	mail(RW_CONTACT_EMAIL, ($_REQUEST['formname'] ? $_REQUEST['formname']." ": "").'Form from your RapidWeb site', $message);

	if(isset($_REQUEST['after'])) {
		$after = $_REQUEST['after'];
	} else {
		$after = 'ThankYou';
	}

	header('Location: ' . $_SERVER['PHP_SELF'].'?'.urlencode($after));

?>
