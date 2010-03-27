<?php

	require_once('php/lib/transformlib.php');

	$p = new Parser($pagehash);
	$html = $p->parse($pagehash['content']);
?>
