jQuery(document).ready(function($) {
    $( "#rwgallery_editor" ).sortable().bind('sortupdate', function(ev, ui) {
        console.log(ev, ui)
        refreshGallery()
    })


    var refreshGallery = function refreshGallery() {
        pagedata.gallery = $.makeArray($('#rwgallery_editor .image-tile').map(function() {
            console.log($(this).data('gallery'))
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

        return tile
    }

    $('#upload_target').bind('load', function() {
        $('#upload-tile input[type=file]').prop('disabled', false)
        $('#upload-tile .spinner').hide()
        $('#upload-tile form').get(0).reset()
        var text = $('#upload_target').contents().text()
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
                    var index = $('#upload-tile').parent().children().index('#upload-tile'); // Seriously? JQUI.sortable doesn't do this?!
                    for(var k in data.$insertAll) {
                        var entries = data.$insertAll[k]
                        for(var i in entries) {
                            console.log("Adding", entries[i])
                            pagedata[k].splice(index, 0, entries[i])
                            $('#upload-tile').after(createGalleryTile(entries[i]))
                        }
                    }
                } else if(op == 'error') {
                    $('#upload-tile .error').text(data[op])
                    $('#upload-tile .error').show()
                }

            }
        } catch(e) {
            $('#upload-tile .error').text(e.message)
            $('#upload-tile .error').show()
        }
        
        /* Clear the file upload widget. Guess what's dumb in the DOM! */
        /*
        $('#upload-tile input[type=file]').each(function () {
            this.parentNode.innerHTML = this.parentNode.innerHTML
        })
        */
    })

    $('#upload-tile input[type=file]').bind('change', function() {
        this.form.submit()
        $('#upload-tile input[type=file]').prop('disabled', true);
        $(this).find('.error').hide()
        $('#upload-tile .spinner').show();
    })

    $('#upload-tile form').submit(function() {
    })

    $('#upload-tile .error').hide()
    $('#upload-tile .spinner').hide()

    console.log("Gallery", pagedata.gallery)
    for(var i in pagedata.gallery) {
        var image = pagedata.gallery[i]
        $('#rwgallery_editor').append(createGalleryTile(image))
    }
})
