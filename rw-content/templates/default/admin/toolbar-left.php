<?php if(defined('WIKI_ADMIN')): ?>
  <!--<a href="#" onclick="changeid()" onmouseover="MM_swapImage('edit2','','<?php bloginfo('template_directory'); ?>/../default/admin/element-over.gif',1)" onmouseout="MM_swapImgRestore()"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/element.gif" alt="Edit Template Elements" name="edit2" style='border: none;' id="edit2" /></a>-->
  <a href="###ADMINURL###?edit=###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit','','<?php bloginfo('template_directory'); ?>/../default/admin/edit-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/edit.gif" alt="Edit Page" name="edit" style='border: none;' id="edit" /></a>
  <a href="###ADMINURL###?remove=###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('delete','','<?php bloginfo('template_directory'); ?>/../default/admin/delete-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/delete.gif" alt="Delete Page" name="delete" style='border: none;'></a>
  <!--<a href="/cgi-bin/webdata_pro.pl" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('database','','<?php bloginfo('template_directory'); ?>/../default/admin/data-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/data.gif" alt="Database" name="database" style='border: none;'></a>-->
<?php if (isset($GLOBALS['LinkStyle']) and $GLOBALS['LinkStyle'] == 'path'): ?>
  <a href="<?= $GLOBALS['RapidWeb']->rootURL ?>###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('logout','','<?php bloginfo('template_directory'); ?>/../default/admin/logout-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logout.gif" alt="Logout" name="logout" style='border: none;'></a>
<?php else: ?>
  <a href="index.php?###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('logout','','<?php bloginfo('template_directory'); ?>/../default/admin/logout-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logout.gif" alt="Logout" name="logout" style='border: none;'></a>
<?php endif; ?>
<?php endif; ?>
