<?php if (!isset($GLOBALS['LinkStyle']) or $GLOBALS['LinkStyle'] != 'path') throw new Exception('This theme requires $LinkStyle to be "path"'); ?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>###PAGE###</title>
<meta name="description" 	content="###META###">
<meta name="keywords" 	 	content="###METAKEYWORDS###">
<meta name="author" 		content="Blumenthals.com">
<meta name="copyright" 		content="">
<meta name="language" 		content="en-us">
<meta name="Classification" content="">
###METANOINDEX###

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
          <td height="154" colspan="2" align="left" valign="middle"><h1><a href="###SCRIPTURL###"><img src="<?php bloginfo('template_directory'); ?>/rapidweb-logo.png" hspace="8" border=0 align="absmiddle"></a> <span class="headertitle">###PAGE###</span></h1></td>
        </tr>
        <tr>
          <td colspan="2" valign="top" background="<?php bloginfo('template_directory'); ?>/images/centerbg.gif" class="repeatx"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="66" align="right" valign="middle">
                  <?php get_template_part('admin/toolbar'); ?>
                </td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="175" class='menu' valign="top"><table width="175" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><img src="<?php bloginfo('template_directory'); ?>/images/menutop.gif" width="175" height="43"></td>
                    </tr>
                    <tr>
                      <td background="<?php bloginfo('template_directory'); ?>/images/menubg.gif">
                        <ul class='rw-menu'>
                          <li><a href="home">Home</a></li>
                          <li><a href="ContactUs">Contact Us</a></li>
                          <li><a href="FindPage">Search</a></li>
                          <li><a href="###ADMINURL###?###PAGEURL###">Admin</a></li>
                          <li><a href="SiteMap">Sitemap</a></li>
                          <li><a href="RecentChanges">Recent Changes</a></li>
                          <li><a href="http://www.rapidweb.info/index.php?TryIt">Editing Help</a></li>
                          <li><a href="BlumenthalsSupport">Support</a></li>
                          PAGECONTENT(navigation, UL)
                         </ul>
                      </td>
                    </tr>
                    <tr>
                      <td><img src="<?php bloginfo('template_directory'); ?>/images/menubottom.gif" width="175" height="43"><br>
                    </tr>
                  </table></td>
                  <td valign="top" class="bodycopy" align='left'><?php $this->the_content(); ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan='2' class="addtlinfo" align='center'>
            <p>&copy; Copyright 2011. All rights reserved.
              Powered by <a href="http://www.RapidWeb.info">RapidWeb</a>.
              Last Edited <nobr>###LASTMODIFIED###</nobr></p>
            <p><small><a href='admin.php?###PAGEURL###'>Admin</a></small></p>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
