<?php
/**
 * The main plugin page file.
 *
 * @package RTSlideshow
 */

?>

<div class="wrap">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">

		<?php

		// Output security fields.
		settings_fields( 'rt-slideshow-settings-group' );

		// Output setting sections.
		do_settings_sections( 'rt-slideshow-settings-group' );

		?>

		<ul id="rt_slideshow_image_list">

		<?php

		// Get the saved image IDs.
		$get_image_ids = get_option( 'rt_slideshow_image_ids', array() );
		$image_ids = [];
		if ( ! empty( $get_image_ids ) ) {
			$image_ids = explode( ',', get_option( 'rt_slideshow_image_ids', array() ) );

			// Loop through each saved image ID and display them.
			foreach ( $image_ids as $image_id ) {

				$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );

				if ( $image_url ) {

					?>

					<li data-id="<?php echo esc_attr( $image_id ); ?>">

						<span style="background-image:url('<?php echo esc_url( $image_url ); ?>')"></span>

						<a href="#" class="rt-slideshow-remove">&times;</a>

					</li>

					<?php

				}
			}
		}
		?>

		</ul>

		<input type="hidden" name="rt_slideshow_image_ids" id="rt_slideshow_image_ids" value="<?php echo esc_attr( join( ',', $image_ids ) ); ?>" />

		<input type="button" id="rt_slideshow_add_image_button" class="button button-secondary" value="Add Image">

		<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>">

	</form>

</div>
