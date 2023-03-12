var $ = jQuery;

$(document).ready(function() {

    var rt_slideshow_uploader;



    $('#rt_slideshow_add_image_button').click(function(e) {



        console.log('click add image');

        e.preventDefault();

        // Set selected images IDs in the media uploader

        // if(imageIdsHiddenField != ''){

        //     console.log('Selected images');

        //     imageIds = $.map( imageIdsHiddenField.split(","), function( val ){

        //         return parseInt( val )

        //     });

        //     console.log(imageIds);

        //     rt_slideshow_uploader.state().get('selection').reset();

        //     imageIds.forEach(function(id) {

        //         console.log('select-> '+id);

        //         var attachment = wp.media.attachment(id);

        //         attachment.fetch();

        //         rt_slideshow_uploader.state().get('selection').add(attachment ? [attachment] : []);

        //     });

        // }



        // If the uploader object has already been created, reopen the dialog

        if (!rt_slideshow_uploader) {

            rt_slideshow_uploader = wp.media({

                title: 'Select Images',

                button: {

                    text: 'Add to Slideshow'

                },

                multiple: 'add',

                library : { 

                    type : 'image'

                }

            });



            // When a file is selected, grab its ID and add it to the input field

            rt_slideshow_uploader.on('select', function() {

                var imageIds = [];
                var imageIdsHiddenField = $('#rt_slideshow_image_ids').val();
                console.log('current selection');
                console.log(imageIdsHiddenField);

                var selection = rt_slideshow_uploader.state().get('selection');

                if (imageIdsHiddenField != '') {
                    imageIds = $.map( imageIdsHiddenField.split(","), function( val ){

                        return parseInt( val );
        
                    });   
                }

                selection.map(function(attachment) {

                    attachment = attachment.toJSON();

                    imageIds.push(attachment.id);

                    $('#rt_slideshow_image_list').append('<li data-id="' +attachment.id+ '"><span style="background-image:url('+ attachment.sizes.thumbnail.url +')"></span><a href="#" class="rt-slideshow-remove">&times;</a></li>');

                });

                $('#rt_slideshow_image_ids').val(imageIds.join(','));

            });

        }

        // Open the uploader dialog

        rt_slideshow_uploader.open();

    });

});



// Remove image event.
$( '#rt_slideshow_image_list' ).on( 'click', '.rt-slideshow-remove', function( e ) {

    e.preventDefault();

    var button = $(this); // .rt-slideshow-remove
    var container = button.parent().parent(); // #rt_slideshow_image_list
    var elementIndex = container.children('li').index( button.parent() ); // index of li element
    var hiddenField = container.next(); // #rt_slideshow_image_ids
    var hiddenFieldValue = $.map( hiddenField.val().split(","), function( val ){

        return parseInt( val );

    });

    button.parent().remove();

    // remove certain array element.
    if( elementIndex != -1 ) {

        hiddenFieldValue.splice(elementIndex, 1);

    }

    // add the IDs to the hidden field value.
    hiddenField.val( hiddenFieldValue.join() );

    // refresh sortable
    container.sortable( 'refresh' );

});


// reordering the images with drag and drop
$( '#rt_slideshow_image_list' ).sortable({
	items: 'li',
	cursor: '-webkit-grabbing', // mouse cursor
	scrollSensitivity: 40,
	stop: function( event, ui ){
		ui.item.removeAttr( 'style' );

		var sort = []; // array of image IDs
		var container = $(this); // #rt_slideshow_image_list

		// each time after dragging we resort our array
		container.find( 'li' ).each( function( index ){
			sort.push( $(this).attr( 'data-id' ) );
		});
		// add the array value to the hidden input field
		container.next().val( sort.join() );
		console.log(sort);
	}
});