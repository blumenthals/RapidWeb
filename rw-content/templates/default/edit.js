var selectEditor = function() {
	var editortoshow = '#'+this.value+'_editor';
	$('.rapidweb-editor').not('#'+this.value+'_editor').hide();
	$(editortoshow).show();
}
jQuery(document).ready(function($) {
        selectEditor.call($('#page_type').change(selectEditor).get(0))
})
