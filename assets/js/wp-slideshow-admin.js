var $ = jQuery;

$(document).ready(function () {

    var wp_slideshow_uploader;

    $('#wp_slideshow_add_image_button').click(function (e) {

        e.preventDefault();

        // If the uploader object has already been created, reopen the dialog
        if (!wp_slideshow_uploader) {

            wp_slideshow_uploader = wp.media({

                title: 'Select Images',

                button: {

                    text: 'Add to Slideshow'

                },

                multiple: 'add',

                library: {

                    type: 'image'

                }

            });



            // When a file is selected, grab its ID and add it to the input field
            wp_slideshow_uploader.on('select', function () {

                var imageIds = [];
                var imageIdsHiddenField = $('#wp_slideshow_image_ids').val();

                var selection = wp_slideshow_uploader.state().get('selection');

                if (imageIdsHiddenField != '') {
                    imageIds = $.map(imageIdsHiddenField.split(","), function (val) {

                        return parseInt(val);

                    });
                }

                selection.map(function (attachment) {

                    attachment = attachment.toJSON();

                    imageIds.push(attachment.id);

                    $('#wp_slideshow_image_list').append('<li data-id="' + attachment.id + '"><span style="background-image:url(' + attachment.sizes.thumbnail.url + ')"></span><a href="#" class="wp-slideshow-remove">&times;</a></li>');

                });

                $('#wp_slideshow_image_ids').val(imageIds.join(','));

            });

        }

        // Open the uploader dialog
        wp_slideshow_uploader.open();

    });

    // Remove image event.
    $('#wp_slideshow_image_list').on('click', '.wp-slideshow-remove', function (e) {

        e.preventDefault();

        var button = $(this); // .wp-slideshow-remove
        var container = button.parent().parent(); // #wp_slideshow_image_list
        var elementIndex = container.children('li').index(button.parent()); // index of li element
        var hiddenField = container.next(); // #wp_slideshow_image_ids
        var hiddenFieldValue = $.map(hiddenField.val().split(","), function (val) {

            return parseInt(val);

        });

        button.parent().remove();

        // Remove certain array element.
        if (elementIndex != -1) {

            hiddenFieldValue.splice(elementIndex, 1);

        }

        // Add the IDs to the hidden field value.
        hiddenField.val(hiddenFieldValue.join());

        // Refresh sortable
        container.sortable('refresh');

    });


    // Reordering the images with drag and drop
    $('#wp_slideshow_image_list').sortable({
        items: 'li',
        cursor: '-webkit-grabbing', // mouse cursor
        scrollSensitivity: 40,
        stop: function (event, ui) {

            ui.item.removeAttr('style');

            var sort = []; // array of image IDs
            var container = $(this); // #wp_slideshow_image_list

            // each time after dragging we resort our array
            container.find('li').each(function (index) {
                sort.push($(this).attr('data-id'));
            });
            // add the array value to the hidden input field
            container.next().val(sort.join());

        }
    });

});