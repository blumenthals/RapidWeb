jQuery(document).ready(function($) {


    $( "#rwgallery_editor .images" ).sortable().bind('sortupdate', function(ev, ui) {
        refreshGallery()
    })

    var refreshGallery = function refreshGallery() {
        pagedata.gallery = $.makeArray($('#rwgallery_editor .image-tile').map(function() {
            return $(this).data('gallery')
        }))
    }

    var createGalleryTile = function createGalleryTile(image) {
        var img = $('<img>')
        img.attr('src', image.thumbnail)
        var tile = $('<div class="gallery-tile image-tile">')
        tile.append(img)
        tile.css('position', 'relative')
        tile.mouseenter(function() {
            $(this).find('.delete-button').show()
        })
        tile.mouseleave(function() {
            $(this).find('.delete-button').hide()
        })
        var button = $('<div class="delete-button">X</div>')
        button.css('position', 'absolute')
        button.css('top', '0')
        button.css('right', '0')
        button.hide()

        button.click(function() {
            // @todo remove from the model, too
            tile.remove()
            refreshGallery()
        })
        // @todo: ajax delete the image (refcounting? eek!) when clicking delete.
        tile.append(button)

        tile.data('gallery', image)

        tile.click(function() {
            if(typeof image.caption === 'undefined') image.caption = ''
            if(typeof image.description === 'undefined') image.description = ''
            var Image = {
                caption: ko.liveObservable(image, 'caption'),
                description: ko.liveObservable(image, 'description'),
                close: function() {
                    dialogBox.dialog('destroy')
                }
            }
            window.a = image
            window.b= Image
            var dialog = _.template($('#photoDetailsEdit').html())
            var dialogBox = $(dialog(image))
            dialogBox.dialog({title: "Image Details"})
            ko.applyBindings(Image);
        })

        return tile
    }


    $('#upload_target').bind('load', function() {
        $('.uploader input[type=file]').prop('disabled', false)
        $('.uploader .spinner').hide()
        $('.uploader form').get(0).reset()
        var text = $('#upload_target').contents().children().text()
        if(!text) return;
        try {
            var data = JSON.parse(text)
            for(var op in data) {
                if(op == '$pushAll') {
                    for(var k in data.$pushAll) {
                        var entries = data.$pushAll[k]
                        for(var i in entries) {
                            pagedata[k].push(entries[i])
                            $('#rwgallery_editor').append(createGalleryTile(entries[i]))
                        }
                    }
                } else if(op == '$insertAll') {
                    var $ip = $('.insertion-point')
                    var index = $('.insertion-point').parent().children().index($ip); // Seriously? JQUI.sortable doesn't do this?!
                    for(var k in data.$insertAll) {
                        var entries = data.$insertAll[k]
                        for(var i in entries) {
                            pagedata[k].splice(index, 0, entries[i])
                            $('.insertion-point').before(createGalleryTile(entries[i]))
                        }
                    }
                } else if(op == 'error') {
                    throw data[op];
                }
            }
        } catch(e) {
            $('.uploader .error').text(e.message)
            $('.uploader .error').show()
        }
        
        /* Clear the file upload widget. Guess what's dumb in the DOM! */
        /*
        $('.uploader input[type=file]').each(function () {
            this.parentNode.innerHTML = this.parentNode.innerHTML
        })
        */
    })

    $('.uploader input[type=file]').bind('change', function() {
        this.form.submit()
        $('.uploader input[type=file]').prop('disabled', true);
        $(this).find('.error').hide()
        $('.uploader .spinner').show();
    })

    $('.uploader form').submit(function() {
    })

    $('.uploader .error').hide()
    $('.uploader .spinner').hide()

    if(!pagedata.gallery) pagedata.gallery = []

    for(var i in pagedata.gallery) {
        var image = pagedata.gallery[i]
        $('#rwgallery_editor .insertion-point').before(createGalleryTile(image))
    }
})
