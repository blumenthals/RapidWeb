var selectEditor = function() {
	var editortoshow = '#'+this.value+'_editor';
	$('.rapidweb-editor').not('#'+this.value+'_editor').hide();
	$(editortoshow).show();
}
jQuery(document).ready(function($) {
    selectEditor.call($('#page_type').change(selectEditor).get(0))

    $('.details-box .details-box-show').click(function() {
        $(this).hide()
        $(this).closest('.details-box').find('.details-box-hide').show();
        $(this).closest('.details-box').find('.details').slideDown()
    })
    $('.details-box .details-box-hide').click(function() {
        $(this).hide()
        $(this).closest('.details-box').find('.details-box-show').show()
        $(this).closest('.details-box').find('.details').slideUp()
    })
    $('.details-box .details-box-hide, .details-box .details').hide();
})
