<?php
/**
 * Plugin uninstallation script.
 *
 * This file is responsible for cleaning up any data or settings created by the plugin during its lifetime.
 * It is executed when the user uninstalls the plugin from the WordPress admin panel.
 *
 * @package RTSlideshow
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Remove the menu page.
remove_menu_page( 'rt-slideshow' );

// Delete the saved image IDs option.
delete_option( 'rt_slideshow_image_ids' );

// Remove the slideshow shortcode.
remove_shortcode( 'rtslideshow' );
