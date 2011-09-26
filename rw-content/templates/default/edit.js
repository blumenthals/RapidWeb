jQuery(document).ready(function($) {
        $('#page_type').change(function(ev) {
                var editortoshow = '#'+this.value+'_editor';
                $('.rapidweb-editor').not('#'+this.value+'_editor').hide();
                $(editortoshow).show();
        })
})
