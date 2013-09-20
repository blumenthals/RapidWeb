jQuery.fn.rapidwebEditor = function(options) {
    var saveURL = this.find('link[rel=rapidweb-admin]').attr('href')

    var RapidwebPage = Backbone.Model.extend({});
    var RapidwebEditor = Backbone.View.extend({
        initialize: function (options) {
            this.model.on('change', this.updateFields, this);
            this.updateFields(this.model);
        },
        updateFields: function (model) {
            for (var i in Object.keys(model.changed)) {
                this.$('[name=' + i + ']').not('[type=checkbox]').val(model.changed[i]);
            }
            
            this.$('[name=noindex]').get(0).checked = model.changed.noindex;
        },
        events: {
            "change [name=metakeywords]": function (ev) {
                this.model.set('keywords', $(ev.target).val());
            },
            "change .details [name]": function (ev) {
                if (ev.target.nodeName.toLowerCase() == 'input' && ev.target.getAttribute('type') == 'checkbox') return;
                this.model.set(ev.target.getAttribute('name'), ev.target.value);
            },
            "change .details [type=checkbox]": function (ev) {
                this.model.set(ev.target.getAttribute('name'), ev.target.checked);
            },
            "click [name=save]": function(ev) {
                ev.preventDefault();
                savePage();
            }
        }
    });

    var model = new RapidwebPage();

    window.v = new RapidwebEditor({el: $('body'), model: model});

    model.set(pagedata);

    model.attributes = pagedata; // Compatibility shim!

    function savePage() {
        $.ajax({
            url: saveURL, 
            processData: false ,
            data: JSON.stringify({command: 'savePage', page: model.toJSON()}), 
            type: 'POST',
            headers: {'Content-Type': 'text/json'}
        }).success(function(data) {
            if (data.location) {
                window.location = data.location;
            }
        })
    }

    var selectEditor = function() {
        var editortoshow = '#'+this.value+'_editor'
        $('.rapidweb-editor').not('#'+this.value+'_editor').hide()
        $(editortoshow).show()
    }

    function modelBind(obj, field, control) {
        $(control).change(function() {
            obj[field] = $(control).val()
        })
        $(control).val(obj[field])
        try {
            $(control).trigger('change')
        } catch(e) {
        }
    }

    function modelBindCheckbox(obj, field, control) {
        $(control).change(function() {
            obj[field] = $(control).prop('checked')
        })
        $(control).prop('checked', obj[field])
        try {
            $(control).trigger('change')
        } catch(e) {
        }
    }

    $('#page_type').change(selectEditor)

    modelBind(pagedata, 'page_type', '#page_type')
    modelBind(pagedata, 'title', '.rapidweb-page-title-editor')
    modelBind(pagedata, 'title', 'title')
    modelBind(pagedata, 'content', '#page_editor [name=content]')

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

    return this

}

jQuery(document).ready(function($) {
    $(document).rapidwebEditor();
})
