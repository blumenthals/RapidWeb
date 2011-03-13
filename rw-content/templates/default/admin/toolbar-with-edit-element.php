<?php if(defined('WIKI_ADMIN')): ?>
<script>
	if(typeof(jQuery) === 'undefined') {
		var el = document.createElement('script')
		el.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js'
		document.getElementsByTagName('head').item(0).appendChild(el)
		el.onload = function() {
			jQuery.noConflict()
			jQuery('.rw-edit-element').hide()
		}
	}
</script>
		
<div class='controls'>
	<div style='float: right'> <?php include 'toolbar-right.php' ?> </div>
	<div style='display: inline-block'> <?php include 'toolbar-left.php' ?> <a href="#" onclick="jQuery('.rw-edit-element').toggle()" onMouseOver="MM_swapImage('edit2','','rw-content/templates/mlfcu/../default/admin/element-over.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="rw-content/templates/mlfcu/../default/admin/element.gif" alt="Edit Template Elements" name="edit2" style='border: none; padding:0px; margin:0px;' id="edit2" /></a></div>
</div>
<?php endif; ?>
