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

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/rollovers.js"></script>
<link href="<?php bloginfo('template_directory'); ?>/style.css" rel="stylesheet" type="text/css">
<link href='<?php bloginfo('template_directory'); ?>/images/favicon.png' rel="shortcut icon" type="image/png" >
</head>
<body onLoad="MM_preloadImages('<?php bloginfo('template_directory'); ?>/admin/edit-over.gif','<?php bloginfo('template_directory'); ?>/admin/delete-over.gif','<?php bloginfo('template_directory'); ?>/admin/backup-over.gif','<?php bloginfo('template_directory'); ?>/admin/upload-over.gif','<?php bloginfo('template_directory'); ?>/admin/meta_tags-over.gif','<?php bloginfo('template_directory'); ?>/admin/logout-over.gif')">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><table width="775" height="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="154" colspan="2" align="left" valign="middle"><h1><a href="###SCRIPTURL###"><img src="<?php bloginfo('template_directory'); ?>/images/logo-orange.gif" hspace="8" border=0 align="absmiddle"></a> <span class="headertitle">###PAGE###</span></h1></td>
        </tr>
        <tr>
          <td colspan="2" valign="top" background="<?php bloginfo('template_directory'); ?>/images/centerbg.gif" class="repeatx"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="76" align="right" valign="middle">
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
                          <li><a href="###SCRIPTURL###?home">Home</a></li>
                          <li><a href="###SCRIPTURL###?ContactUs">Contact Us</a></li>
                          <li><a href="###SCRIPTURL###?FindPage">Search</a></li>
                          <li><a href="###ADMINURL###?###PAGEURL###">Admin</a></li>
                          <li><a href="###SCRIPTURL###?SiteMap">Sitemap</a></li>
                          <li><a href="###SCRIPTURL###?RecentChanges">Recent Changes</a></li>
                          <li><a href="http://www.rapidweb.info/index.php?TryIt">Editing Help</a></li>
                          <li><a href="###SCRIPTURL###?BlumenthalsSupport">Support</a></li>
                          PAGECONTENT(navigation, UL)
                         </ul>
                      </td>
                    </tr>
                    <tr>
                      <td><img src="<?php bloginfo('template_directory'); ?>/images/menubottom.gif" width="175" height="43"><br>
                        <span class="addtlinfo">Last Edited:<br>
                        ###LASTMODIFIED### by ###LASTAUTHOR###<br>
                        <br>
                        Related Pages:<br>
                        ###RELATEDPAGES###</span></td>
                    </tr>
                  </table></td>
                <td valign="top" class="bodycopy" align='left'>###CONTENT###</td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan='2' align="right" class="addtlinfo">&copy; Copyright 2008. All rights reserved.  Powered by <a href="http://www.RapidWeb.info">RapidWeb</a></td>
        </tr>
      </table></td>
  </tr>
</table>
<center><small><a href='admin.php?###PAGEURL###'>Admin</a></small></center>
</body>
</html>
