<?php
	if(!defined('RW_CONTACT_EMAIL')) die ("Who do I send this to? Set RW_CONTACT_EMAIL");
	if($_SERVER['REQUEST_METHOD'] != 'POST') die('POST only');

	if($_REQUEST['confirm']) {
		rw_template('BROWSE', 'Confirm your submission');
		?>
		<form action='<?php echo $_SERVER['PHP_SELF'] ?>?after=ThankYouInquiry&sendform=1' method='POST' target='_top'>
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

		mail(RW_CONTACT_EMAIL, ($_REQUEST['formname'] ? $_REQUEST['formname']." ": "").'Form from your RapidWeb site', $message);

		if(isset($_REQUEST['after'])) {
			$after = $_REQUEST['after'];
		} else {
			$after = 'ThankYou';
		}

		header('Location: ' . $_SERVER['PHP_SELF'].'?'.urlencode($after));

	}

?>
