jQuery.fn.rapidwebEditor = function(options) {
    var saveURL = this.find('link[rel=rapidweb-admin]').attr('href')

    var savePage = function savePage() {
        $.ajax({
            url: saveURL, 
            processData: false ,
            data: JSON.stringify({command: 'savePage', page: pagedata}), 
            type: 'POST',
            headers: {'Content-Type': 'text/json'}
        }).success(function(data) {
            if(data.location) window.location = data.location;
        })
    }

    var selectEditor = function() {
        var editortoshow = '#'+this.value+'_editor'
        $('.rapidweb-editor').not('#'+this.value+'_editor').hide()
        $(editortoshow).show()
    }

    var bind = function bind(obj, field, control) {
        $(control).change(function() {
            obj[field] = $(control).val()
        })
        $(control).val(obj[field])
        $(control).trigger('change')
    }

    var bindCheckbox = function bindCheckbox(obj, field, control) {
        $(control).change(function() {
            obj[field] = $(control).prop('checked')
        })
        $(control).prop('checked', obj[field])
        $(control).trigger('change')
    }

    $('#page_type').change(selectEditor)
    
    bind(pagedata, 'page_type', '#page_type')
    bind(pagedata, 'content', '#page_editor [name=content]')
    bind(pagedata, 'title', '#page_editor [name=title]')
    bind(pagedata, 'meta', '#page_editor [name=meta]')
    bind(pagedata, 'keywords', '#page_editor [name=keywords]')
    bind(pagedata, 'variables', '#page_editor [name=variables]')
    bind(pagedata, 'template', '#page_editor [name=template]')
    bindCheckbox(pagedata, 'noindex', '#page_editor [name=noindex]')

    this.find('.details-box .details-box-show').click(function() {
        $(this).hide()
        $(this).closest('.details-box').find('.details-box-hide').show()
        $(this).closest('.details-box').find('.details').slideDown()
    })

    this.find('.details-box .details-box-hide').click(function() {
        $(this).hide()
        $(this).closest('.details-box').find('.details-box-show').show()
        $(this).closest('.details-box').find('.details').slideUp()
    })

    this.find('.details-box .details-box-hide, .details-box .details').hide()

    this.find('[name=save]').click(function(ev) {
        ev.preventDefault();
        savePage();
    })

    return this

}

jQuery(document).ready(function($) {
    $(document).rapidwebEditor();
})