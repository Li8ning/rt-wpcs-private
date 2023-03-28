<?php
/**
 * Plugin Name: WP Beautiful Slideshow
 * Plugin URI: https://github.com/rtlearn/wpcs-Li8ning
 * Description: Creates a beautiful slideshow.
 * Version: 1.0.0
 * Author: Dharmrajsinh Jadeja
 * Author URI: https://blog.avgamingindia.com
 * Text Domain: wp-beautiful-slideshow
 * Domain Path: /languages
 *
 * @package WPBFSlideshow
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

require_once plugin_dir_path( __FILE__ ) . 'src/class-wpbfslideshow.php';

// Instantiate the plugin class.
$wpbf_slideshow = new WPBFSlideshow\WPBFSlideshow();

register_activation_hook( __FILE__, array( $wpbf_slideshow, 'activate' ) );
register_deactivation_hook( __FILE__, array( $wpbf_slideshow, 'deactivate' ) );
