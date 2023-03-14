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

require_once plugin_dir_path( __FILE__ ) . 'class-rtslideshow.php';

// Create an instance of the RTSlideshow class.
$rt_slideshow = new RTSlideshow\RTSlideshow();

// Dequeue admin styles and scripts
$rt_slideshow->dequeue_admin_scripts();

// Dequeue front end styles and scripts
$rt_slideshow->dequeue_front_end_scripts();

// Remove the menu page.
remove_menu_page( 'rt-slideshow' );

// Delete the saved image IDs option.
delete_option( 'rt_slideshow_image_ids' );

// Remove the slideshow shortcode.
remove_shortcode( 'rtslideshow' );
