const $ = jQuery;
$(document).ready(function() {
    var rt_slideshow_uploader;

    $('#rt_slideshow_add_image_button').click(function(e) {

        console.log('click add image');
        e.preventDefault();

        // Initiliaze variables
        let counter = 1;
        let imageIds = [];
        // Set selected images IDs in the media uploader
        if($('#rt_slideshow_image_ids').val() != ''){
            console.log('Selected images');
            imageIds = $.map( $('#rt_slideshow_image_ids').val().split(","), function( val ){
                return parseInt( val )
            });
            console.log(imageIds);
            rt_slideshow_uploader.state().get('selection').reset();
            imageIds.forEach(function(id) {
                console.log('select-> '+id);
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                rt_slideshow_uploader.state().get('selection').add(attachment ? [attachment] : []);
                counter++;
            });
            console.log('count'+counter);
        }

        // If the uploader object has already been created, reopen the dialog
        if (!rt_slideshow_uploader) {
            rt_slideshow_uploader = wp.media({
                title: 'Select Images',
                button: {
                    text: 'Add to Slideshow'
                },
                multiple: 'add',
                library : { 
                    type : 'image',
                    count : counter
                }
            });

            // When a file is selected, grab its ID and add it to the input field
            rt_slideshow_uploader.on('select', function() {
                console.log('images selected');
                var selection = rt_slideshow_uploader.state().get('selection');
                var ids = [];
                console.log('already selected');
                console.log(imageIds);
                console.log('count'+count);
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    console.log($.inArray(attachment.id,imageIds));
                    // if($.inArray(attachment.id,$('#rt_slideshow_image_ids').val()) == -1){

                    // }
                    ids.push(attachment.id);
                    $('#rt_slideshow_image_list').append('<li data-id="' +attachment.id+ '"><span style="background-image:url('+ attachment.url +')"></span><a href="#" class="rt-slideshow-remove">&times;</a></li>');
                });
                console.log('newly selected');
                console.log(ids);
                $('#rt_slideshow_image_ids').val(ids.join(','));
            });
        }

        // Open the uploader dialog
        rt_slideshow_uploader.open();
    });
});

// remove image event
$( '#rt_slideshow_image_list' ).on( 'click', '.rt-slideshow-remove', function( e ) {

    e.preventDefault();

    const button = $(this)
    const imageId = button.parent().data( 'id' )
    const container = button.parent().parent()
    const hiddenField = container.next()
    const hiddenFieldValue = $.map( hiddenField.val().split(","), function( val ){
        return parseInt( val )
    });
    const i = hiddenFieldValue.indexOf( imageId )

    button.parent().remove();

    // remove certain array element
    if( i != -1 ) {
        hiddenFieldValue.splice(i, 1);
    }

    // add the IDs to the hidden field value 
    hiddenField.val( hiddenFieldValue.join() );

    // refresh sortable
    // container.sortable( 'refresh' );

});