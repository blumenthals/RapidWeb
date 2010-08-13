                <?php if(defined('WIKI_ADMIN')): ?>
                <div class='controls'>
                <form action="###SCRIPTURL###" method=POST>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                          <!--<a href="#" onclick="changeid()" onmouseover="MM_swapImage('edit2','','<?php bloginfo('template_directory'); ?>/admin/element-over.gif',1)" onmouseout="MM_swapImgRestore()"><img src="<?php bloginfo('template_directory'); ?>/admin/element.gif" alt="Edit Template Elements" name="edit2" border="0" id="edit2" /></a>-->
                          <a href="###ADMINURL###?edit=###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit','','<?php bloginfo('template_directory'); ?>/admin/edit-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/admin/edit.gif" alt="Edit Page" name="edit" border="0" id="edit" /></a>
                          <a href="###ADMINURL###?remove=###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('delete','','<?php bloginfo('template_directory'); ?>/admin/delete-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/admin/delete.gif" alt="Delete Page" name="delete" border="0"></a>
                          <!--<a href="/cgi-bin/webdata_pro.pl" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('database','','<?php bloginfo('template_directory'); ?>/admin/data-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/admin/data.gif" alt="Database" name="database" border="0"></a>-->
                          <a href="index.php?###PAGEURL###" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('logout','','<?php bloginfo('template_directory'); ?>/admin/logout-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/admin/logout.gif" alt="Logout" name="logout" border="0"></a>
                        </td>
                        <td align="right">
                          <!--<a onClick="window.open('/rapidflip/admin/','RapidFlip_Administration',' width=680, height=600, resizable=yes,scrollbars=1')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('rapidflip','','<?php bloginfo('template_directory'); ?>/admin/admin_flip-over.gif',1)"><img src="rw-global/images/edit/admin_flip.gif" alt="RapidFlip" name="rapidflip" border="0"></a>-->
                          <a onClick="window.open('###SCRIPTURL###?settings','Settings',' width=551, height=494, resizable=yes')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit_meta_tags','','<?php bloginfo('template_directory'); ?>/admin/meta_tags-over.gif',1)" style="cursor: hand;"><img src="<?php bloginfo('template_directory'); ?>/admin/meta_tags.gif" alt="Edit Default Meta Tags" name="edit_meta_tags" border="0"></a>
                          <a href="###ADMINURL###?BackUp" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('backup','','<?php bloginfo('template_directory'); ?>/admin/backup-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/admin/backup.gif" alt="Backup Pages" name="backup" border="0" /></a>
                        </td>
                      </tr>
                    </table>
                  </form>
                  <hr noshade>
                  </div>
                  <?php endif; ?>
