<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>###PAGE###</title>
<meta name="description" 	content="###META###">
<meta name="keywords" 	 	content="###METAKEYWORDS###">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="author" 		content="Blumenthals.com">
<meta name="copyright" 		content="">
<meta name="language" 		content="en-us">
<meta name="Classification" content="">

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/rollovers.js"></script>
<link href="<?php bloginfo('template_directory'); ?>/style.css" rel="stylesheet" type="text/css"/>
</head>
<body onLoad="MM_preloadImages('/rw-global/images/edit/edit-over.gif','/rw-global/images/edit/delete-over.gif','/rw-global/images/edit/backup-over.gif','/rw-global/images/edit/upload-over.gif','/rw-global/images/edit/meta_tags-over.gif','/rw-global/images/edit/logout-over.gif')">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><table width="775" height="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="154" colspan="2" align="left" valign="middle"><h1><a href="###SCRIPTURL###"><img src="<?php bloginfo('template_directory'); ?>/images/logo-orange.gif" hspace="8" border=0 align="absmiddle"></a> <span class="headertitle">###PAGE###</span></h1></td>
        </tr>
        <tr>
          <td colspan="2" valign="top" background="<?php bloginfo('template_directory'); ?>/images/centerbg.gif" class="repeatx"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class='controls'>
                <td height="76" align="right" valign="middle">
                ###IF:ADMIN###
                <form action="###SCRIPTURL###" method=POST>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                        <a onClick="window.open('upload/upload.cgi','ImageUpload',' width=551, height=494, resizable=yes')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('upload','','/rw-global/images/edit/upload-over.gif',1)"><img src="/rw-global/images/edit/upload.gif" alt="Upload an Image from your computer" name="upload" width="102" height="49" border="0"></a>
                        <a onClick="window.open('###SCRIPTURL###?settings','Settings',' width=551, height=494, resizable=yes')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit_meta_tags','','/rw-global/images/edit/meta_tags-over.gif',1)"><img src="/rw-global/images/edit/meta_tags.gif" alt="Edit Default Meta Tags" name="edit_meta_tags" width="102" height="49" border="0"></a>
                        <td align="right">
                        <a href="###ADMINURL###?edit=###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit','','/rw-global/images/edit/edit-over.gif',1)"><img src="/rw-global/images/edit/edit.gif" alt="Edit Page" name="edit" border="0"></a>
                        <a href="###ADMINURL###?remove=###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('delete','','/rw-global/images/edit/delete-over.gif',1)"><img src="/rw-global/images/edit/delete.gif" alt="Delete Page" name="delete" border="0"></a>
                        <a href="###ADMINURL###?BackUp" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('backup','','/rw-global/images/edit/backup-over.gif',1)"><img src="/rw-global/images/edit/backup.gif" alt="Backup Pages" name="backup" border="0"></a>
                        <!--<a href="/cgi-bin/webdata_pro.pl" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('database','','/rw-global/images/edit/data-over.gif',1)"><img src="/rw-global/images/edit/data.gif" alt="Database" name="database" border="0"></a>-->
                        <a href="index.php?###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('logout','','/rw-global/images/edit/logout-over.gif',1)"><img src="/rw-global/images/edit/logout.gif" alt="Logout" name="logout" border="0"></a>                        </td>
                      </tr>
                    </table>
                </form>
                ###ENDIF:ADMIN###
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
                <td valign="top" class="bodycopy">###CONTENT###</td>
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
