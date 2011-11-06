<?php 
	require_once '../rw-config.php'; 
    require_once "../rw-includes/templating.php";
	require_once '../rw-includes/stdlib.php'; 
	require_once '../rw-includes/wp-compat.php'; 
	require_once 'require-authentication.php'; 
?>
<html>
<head>
<title>RapidWeb File Upload Form</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
body, td, th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
#instructions {
    line-height: 12px;
}
.style1 {
	font-size: 24px;
	color: #FFFFFF;
}
body {
	background-image: url(<?php bloginfo('template_directory'); ?>/../default/admin/editpgbg.gif);
	background-repeat: repeat-x;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.buttons {
	width: 80px;
}
-->
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top">
<form method='POST' enctype='multipart/form-data'>
        <table width="500" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="187" height="154"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo-blue.gif" alt="Blumenthals RapidWeb" width="176" height="107"><br></td>
            <td colspan="2" align="right"><h1 class="style1">File &amp; Image<br>Upload Page</h1></td>
          </tr>
<?php
	if(!empty($_FILES)):

	$file = $_FILES['upfile'];
	$temp = explode('/', $_SERVER['SCRIPT_NAME']);
	array_pop($temp);
	array_pop($temp);
	$targeturl = join('/', $temp).'/images/upload/'.basename($file['name']);
	$targeturlfull = 'http://'.$_SERVER['HTTP_HOST'].$targeturl;
	if(!move_uploaded_file($file['tmp_name'], '../images/upload/'.basename($file['name']))) {
		die("Upload unsuccessful");
	}
	?>
        <tr>
          <td height="62" colspan="2" valign="middle"><form>
              <input type="button" onclick="window.open('<?php echo $targeturlfull; ?>','Uploaded Image',' width=500,height=500, resizable=yes')" value="View <?php echo basename($file['name']); ?>">
          </form></td>
		  <td width="168" align="right" valign="middle"><form>
              <input type="button" class="buttons2" onclick="parent.location='upload.php'" value="Upload Another Image"><br>
              <input type="button" class="buttons2" onclick="window.close()" value="Close Upload Window"></form></td>
        </tr>
	<tr>
		<td colspan=3>
	<p>The File or Image '<?php echo basename($file['name']) ?>' that you selected was successfully uploaded.</p>

	<p>You can now copy and paste the following URL into your RapidWeb Page:</p>

	<p><strong>BASIC:</strong><br>
		<code> [<?php echo htmlspecialchars('http://'.$_SERVER['HTTP_HOST'].$targeturl); ?>] </code></p>

	<p><center>OR</center></p>

	<p><strong>ADVANCED:</strong><br>
		<code><?php echo htmlspecialchars("|<img src='".'http://'.$_SERVER['HTTP_HOST'].$targeturl."' align='right' alt='{$_POST['note']}'>"); ?></code></p>

	<p><center>OR</center></p>

	<p><strong>PDF File:</strong><br>
		<code>[<?php echo $_POST['note']; ?> (pdf)|<?php echo htmlspecialchars('http://'.$_SERVER['HTTP_HOST'].$targeturl); ?>]</code></p>

	<p><strong>PDF File in a new window:</strong><br>
		<code>[<?php echo $_POST['note']; ?> (pdf)|<?php echo htmlspecialchars('http://'.$_SERVER['HTTP_HOST'].$targeturl); ?>] (new window)</code></p>
		</td>
	</tr>
<?php else: ?>
        <tr>
          <td height="62" colspan="2" valign="middle" id='instructions'>
            <strong>To Upload:<br>
              </strong>1. Select the file with the browse button.<br>
              2. Fill in the description (1-5 words).<br>
              3. Click the Upload Button. </td>
            <td width="197" height="62" align="right" valign="middle"><input type=submit class="buttons" value=Upload File><br>
            <input type=reset class="buttons" value=Clear Fields><input type="button" class="buttons2" onClick="window.close()" value="Cancel"></td>
          </tr>
          <tr>
            <td height="98" colspan="3"><br>
              File to upload:<br>
              <input name=upfile type=file size="45">
              <br>
              <br>
              File Description:<br>
              <input name=note type=text size="70" style="width:100%">
              <input type=hidden name=fileupload size = 40 value=images/upload>
              <br><br>
              <center>
              <input type="button" class="buttons2" onClick="window.close()" value="Cancel">
              <input type=reset class="buttons" value=Clear fields>
              <input type=submit class="buttons" value=Upload file></center></td>
          </tr>
<?php endif; ?>
          <tr>
            <td height="1"></td>
            <td width="116" height="1"></td>
            <td height="1"></td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
</body>
</html>
