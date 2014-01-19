<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Rapidweb LogiRapidweb Loginn</title>
<meta name="author" 		content="Blumenthals.com">
<meta name="copyright" 		content="">
<meta name="language" 		content="en-us">
<meta name="Classification" content="">

<script src="<?php bloginfo('template_directory'); ?>/rollovers.js"></script>
<link href="<?php bloginfo('template_directory'); ?>/style.css" rel="stylesheet" type="text/css">
<link href='<?php bloginfo('template_directory'); ?>/images/favicon.png' rel="shortcut icon" type="image/png" >
<?php $this->do_head(); ?>
</head>
<body onload="MM_preloadImages('<?php bloginfo('template_directory'); ?>/admin/edit-over.gif','<?php bloginfo('template_directory'); ?>/admin/delete-over.gif','<?php bloginfo('template_directory'); ?>/admin/backup-over.gif','<?php bloginfo('template_directory'); ?>/admin/upload-over.gif','<?php bloginfo('template_directory'); ?>/admin/meta_tags-over.gif','<?php bloginfo('template_directory'); ?>/admin/logout-over.gif')">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><table width="775" height="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="154" colspan="2" align="left" valign="middle"><h1><a href="<?php echo $this->rootURL ?>"><img src="<?php bloginfo('template_directory'); ?>/rapidweb-logo.png" hspace="8" border=0 align="absmiddle"></a> <span class="headertitle">Rapidweb Login</span></h1></td>
        </tr>
        <tr>
          <td colspan="2" valign="top" background="<?php bloginfo('template_directory'); ?>/images/centerbg.gif" class="repeatx"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="66" align="right" valign="middle">
                </td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td valign="top" class="rw-menuless-content" align='left'>
                    <div class='rw-login-form'>
                        <?php echo $_SERVER['REQUEST_METHOD'] == 'POST' ? "Bad username or password" : "" ?>
                        <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post'>
                            <?php if(isset($_REQUEST['continue'])): ?>
                                <input name='continue' type='hidden' value='<?php echo htmlentities($_REQUEST['continue']) ?>'>
                            <?php endif ?>
                            <label for='username'>Username</label>
                            <input name='username' type='text' id='username'>
                            <label for='password'>Password</label>
                            <input name='password' type='password' id='password'>
                            <input type='submit'>
                        </form>
                    </div>
                  </td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan='2' class="addtlinfo" align='center'>
            <p>&copy; Copyright 2011. All rights reserved.
              Powered by <a href="http://www.RapidWeb.info">RapidWeb</a>.</p>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
