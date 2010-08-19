<?php
	if($_SERVER['REQUEST_METHOD'] != 'POST') die('POST only');
	if($_REQUEST['frompage']) {
		$page= RetrievePage($dbi, $_REQUEST['frompage'], 'wiki');
		if(!$page) die('frompage not found');
		if (preg_match_all("/^EMAIL\s*FORM\s*(.*)/", join("\n", $page['content']), $matches)) {
			if(isset($_REQUEST['formno'])) {
				$line = $matches[1][(int)$_REQUEST['formno']];
			} else {
				$line = $matches[1][0];
			}
		}
		$args = rw_parse_intent($line);
		$args['frompage'] = $_REQUEST['frompage'];
		if(isset($_REQUEST['formno'])) $args['formno'] = $_REQUEST['formno'];
	} else {
		$args = array();
		// Fix up to handle old forms that use just 'after':
		if(isset($_REQUEST['after'])) $_REQUEST['afterward'] = $_REQUEST['after'];

		// Use afterward from request if not using frompage
		if(isset($_REQUEST['afterward'])) $args['afterward'] = $_REQUEST['afterward'];

		// Use confirm from request if not using frompage
		if(isset($_REQUEST['confirm'])) $args['confirm'] = $_REQUEST['confirm'];
	}

	if(!isset($args['afterward'])) $args['afterward'] = 'ThankYou';

	if($_REQUEST['sendform'] == 'confirmed') unset($args['confirm']);

	if(isset($args['confirm'])) {
		rw_template('BROWSE', 'Confirm your submission');
		$qs = array('sendform' => 'confirmed', 'frompage' => $args['frompage'], 'formno' => $args['formno']); 
		$qs = rw_make_query_string($qs);
		?>
		<form action='<?php echo "{$_SERVER['PHP_SELF']}?$qs" ?>' method='POST' target='_top'>
			<h2>Please confirm</h2>
			<dl>
			<?php
				foreach($_POST as $k => $v): 
			?>
				<dt><?php echo htmlspecialchars($k); ?></dt>
				<dd><?php echo htmlspecialchars($v); ?><input type='hidden' name='<?php echo htmlspecialchars($k) ?>' value='<?php echo htmlspecialchars($v); ?>'></dd>
			<?php endforeach; ?>
			<input type='submit' value='Submit'>
			</dl>
		</form>
		<?php
		rw_apply_template();
	} else {
		$message = '';
		foreach($_POST as $k => $v) {
			$message .= "$k: $v\n";
		}

		if(!isset($args['to'])) {
			if(defined('RW_CONTACT_EMAIL')) {
				$args['to'] = RW_CONTACT_EMAIL;
			} else {
				die('Who do I send this to?');
			}
		}
		
		mail($args['to'], ($_REQUEST['formname'] ? $_REQUEST['formname']." ": "").'Form from your RapidWeb site '.$args['frompage'], $message);

		if($args['debug']) {
			print_r($args);
			print_r($message);
			print_r($_REQUEST);
		} else {
			header('Location: ' . $_SERVER['PHP_SELF'].'?'.urlencode($args['afterward']));
		}

	}

?>
