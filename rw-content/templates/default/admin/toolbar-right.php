<?php if(defined('WIKI_ADMIN')): ?>
<!--<a onClick="window.open('/rapidflip/admin/','RapidFlip_Administration',' width=680, height=600, resizable=yes,scrollbars=1')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('rapidflip','','<?php bloginfo('template_directory'); ?>/admin/admin_flip-over.gif',1)"><img src="rw-global/images/edit/admin_flip.gif" alt="RapidFlip" name="rapidflip" border="0"></a>-->
<a onClick="window.open('###SCRIPTURL###?settings','Settings',' width=551, height=494, resizable=yes')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit_meta_tags','','<?php bloginfo('template_directory'); ?>/admin/meta_tags-over.gif',1)" style="cursor: hand;"><img src="<?php bloginfo('template_directory'); ?>/admin/meta_tags.gif" alt="Edit Default Meta Tags" name="edit_meta_tags" border="0"></a>
<a href="###ADMINURL###?BackUp" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('backup','','<?php bloginfo('template_directory'); ?>/admin/backup-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/admin/backup.gif" alt="Backup Pages" name="backup" border="0" /></a>
<?php endif; ?>