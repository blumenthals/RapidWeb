<?php

	require_once('rw-includes/transformlib.php');

	$p = new Parser($pagehash);
	$html = $p->parse($pagehash['content']);
?>
