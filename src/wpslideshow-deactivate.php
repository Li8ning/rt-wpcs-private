<?php
/**
 * Plugin deactivation functions.
 *
 * This file contains functions related to deactivating the plugin. When the plugin is deactivated,
 * the functions in this file will be executed. This file will dequeue styles and scripts and remove
 * registered shortcodes.
 *
 * @link              https://github.com/rtlearn/wpcs-Li8ning
 * @since             1.0.0
 * @package           WPSlideshow
 *
 * @wordpress-plugin
 * Plugin Name:       WP Slideshow
 * Plugin URI:        https://github.com/rtlearn/wpcs-Li8ning
 * Description:       Creates a beautiful slideshow.
 * Version:           1.0.0
 * Author:            Dharmrajsinh Jadeja
 * Author URI:        https://blog.avgamingindia.com
 * License:           GPL-2.0-or-later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'class-wpslideshow.php';

// Create an instance of the WPSlideshow class.
$wp_slideshow = new WPSlideshow\WPSlideshow();

// Dequeue admin styles and scripts
$wp_slideshow->dequeue_admin_scripts();

// Dequeue front end styles and scripts
$wp_slideshow->dequeue_front_end_scripts();

// Unregister settings
unregister_setting( 'wp-slideshow-settings-group', 'wp_slideshow_image_ids' );

// Remove the slideshow shortcode.
remove_shortcode( 'wpslideshow' );
