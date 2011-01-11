<?php function display_page($settings) {?>
<html>
<head>
<title>Edit Default Meta Tags</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
body, td, th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
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
	width: 140px;
}
.buttons2 {
	width: 160px;
}
.buttons1 {	width: 140px;
}
.buttons1 {	width: 140px;
}
-->
</style>
</head>
<body>
<form method='post'>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top"><table width="500" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="197" height="154"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo-blue.gif" alt="Blumenthals RapidWeb" width="176" height="107"><br></td>
            <td colspan="2"><h1 class="style1">Edit Default<br>
                Meta Tags</h1></td>
          </tr>
          <tr>
            <td height="62" colspan="2" valign="middle">&nbsp;</td>
            <td width="168" align="right" valign="middle"><input type=hidden name=admin_page2 value=submit_settings>
              <input type=submit class="buttons" value="Change Meta Tags"><br>
              <input name="Button" type="button" class="buttons" onClick="window.close()" value="Cancel">
            </td>
          </tr>
          <tr>
            <td height="183" colspan="3" valign="top"><label for=default_title> <br>
              Default Page Title</label>
              :<br>
              <input name=default_title type=text value='<?php echo $settings['default_title'] ?>' size="60">
              <br>
              <br>
              <label for=default_meta_description> Default Meta Description</label>
              :<br>
              <textarea name=default_meta_description cols=60><?php echo $settings['default_meta_description'] ?></textarea>
              <br>
              <br>
              <label for=default_meta_keywords> Default Meta Keywords</label>
              :<br>
              <textarea type=text name=default_meta_keywords cols=60><?php echo $settings['default_meta_keywords'] ?></textarea>
              </br>
            </td>
          </tr>
          <tr>
            <td colspan="3" align="right"><input type=hidden name=admin_page value=submit_settings>
              <input type=submit class="buttons" value="Change Meta Tags"><br>
              <input name="Button" type="button" class="buttons" onClick="window.close()" value="Cancel">
            </td>
          </tr>
          <tr>
            <td height="0"></td>
            <td width="135" height="0"></td>
            <td height="0"></td>
          </tr>
          <tr>
            <td height="1"></td>
            <td height="1" colspan="2"></td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php } ?>
<?php function display_thanks($settings) {?>
<html>
<head>
<title>Edit Default Meta Tags</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
body, td, th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 24px;
	color: #FFFFFF;
}
body {
	background-image: url(/rw-global/images/edit/editpgbg.gif);
	background-repeat: repeat-x;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.buttons {
	width: 140px;
}
.buttons2 {
	width: 160px;
}
-->
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><table width="500" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="197" height="154"><img src="/rw-global/images/edit/logo-blue.gif" alt="Blumenthals RapidWeb" width="176" height="107"><br></td>
          <td colspan="2"><h1 class="style1">Settings<br>
              Sucessfully<br>
              Edited!</h1></td>
        </tr>
        <tr>
          <td height="62" colspan="2" valign="middle">&nbsp;</td>
          <td width="146" height="62" align="right" valign="middle"><input type="button" class="buttons" value="Close Window" onClick="window.close()"></td>
        </tr>
        <tr>
          <td height="183" colspan="3" valign="top"><br>
            <strong>Your settings have been successfully saved.</strong><br>
            <br>
            You may now close this window.</td>
        </tr>
        <tr>
          <td colspan="3" align="right" valign="top"><input type="button" class="buttons" value="Close Window" onClick="window.close()"></td>
        </tr>
        <tr>
          <td height="1"></td>
          <td width="157" height="1"></td>
          <td height="1"></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
<?php } ?>
<?php
        if($_REQUEST['admin_page'] != 'submit_settings') {
		$settings = RetrieveSettings();
		display_page($settings);
	} else {
		$settingshash = array();
		if(isset($_REQUEST['default_title']))
			$settingshash['default_title'] = $_REQUEST['default_title'];
		if(isset($_REQUEST['default_meta_description']))
			$settingshash['default_meta_description'] = $_REQUEST['default_meta_description'];
		if(isset($_REQUEST['default_meta_keywords']))
			$settingshash['default_meta_keywords'] = $_REQUEST['default_meta_keywords'];
		SaveSettings($settingshash);
		display_thanks($settingshash);
	}

?>
