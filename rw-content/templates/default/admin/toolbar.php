<?php if(defined('WIKI_ADMIN')): ?>
<script>
  if(typeof(jQuery) === 'undefined') {
	var el = document.createElement('script')
	el.src = 'http://code.jquery.com/jquery-latest.min.js'
	document.getElementsByTagName('head').item(0).appendChild(el)
  }
window.onload = function() {
	  jQuery.noConflict()
	  jQuery('a.editsm').hide()
	}
</script>


<div class="admin_mode">
  <div class="left">
    <a class="edit" title="Edit Page" href="###ADMINURL###?edit=###PAGEURL###"></a>
    <a class="elements" title="Edit Template Elements" onclick="jQuery('a.editsm').toggle()"></a>
    <!--<a class="meta" title="Edit Default Meta Tags" onClick="window.open('###SCRIPTURL###?settings','Settings',' width=551, height=494, resizable=yes')"></a>-->
  </div>
  <div class="right">
    <?php if (isset($GLOBALS['LinkStyle']) and $GLOBALS['LinkStyle'] == 'path'): ?>
      <a class="logoutsm" title="Logout" href="<?= $GLOBALS['RapidWeb']->rootURL ?>?logout"></a>
    <?php else: ?>
      <a class="logoutsm" title="Logout" href="index.php?###PAGEURL###"></a>
    <?php endif; ?>
    <!--
	<?php if (isset($GLOBALS['LinkStyle']) and $GLOBALS['LinkStyle'] == 'path'): ?>
      <a class="backupsm" title="Backup Website" href="<?= $GLOBALS['RapidWeb']->rootURL ?>admin/Backup"></a>
    <?php else: ?>
      <a class="backupsm" title="Backup Website" href="###ADMINURL###?Backup"></a>
    <?php endif; ?>
    -->

    <a class="deletesm" title="Delete Page" href="###ADMINURL###?remove=###PAGEURL###"></a>
  </div>
</div>
<div style="clear:both;"></div>
<?php endif; ?>