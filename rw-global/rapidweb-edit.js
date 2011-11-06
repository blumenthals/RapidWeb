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
            console.log(data)
            if(data.page) {
                if(data.page.private) {
                    window.location = data.page.private;
                } else if(data.page.public) {
                    window.location = data.page.public;
                }
            }
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

    var inlineEdit = $("<input class='rapidweb-page-title-editor'>")
    inlineEdit.hide()

    $('.rapidweb-page-title').wrapInner('<span style="padding-right: 7px">')
    $('.rapidweb-page-title').prepend(inlineEdit)
    $('.rapidweb-page-title').click(function() {
        $('.rapidweb-page-title>span').css('white-space', 'pre')
        $('.rapidweb-page-title-editor').show().focus()
    })
    $('.rapidweb-page-title-editor').bind('input', function(ev) {
        $('.rapidweb-page-title>span').text($(this).val())
    }).bind('propertychange', function(ev) {
        $('.rapidweb-page-title>span').text($(this).val())
    }).keyup(function(ev) {
        if(ev.keyCode == 13) $(this).blur()
        $('.rapidweb-page-title>span').text($(this).val())
    }).blur(function() {
        $(this).hide()
        $('.rapidweb-page-title>span').css('white-space', 'normal')
        $('.rapidweb-page-title>span').text($(this).val())
    })

    $('.rapidweb-page-title-editor').change(function() {
        $('title').text($(this).val())
    })
    
    bind(pagedata, 'page_type', '#page_type')
    bind(pagedata, 'content', '#page_editor [name=content]')
    bind(pagedata, 'title', '#page_editor [name=title]')
    bind(pagedata, 'title', '.rapidweb-page-title-editor')
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
