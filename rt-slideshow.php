<?php
/**
 * Plugin Name: RT Slideshow
 * Plugin URI: https://github.com/rtlearn/wpcs-Li8ning
 * Description: Creates a beautiful slideshow.
 * Version: 1.0.0
 * Author: Dharmrajsinh Jadeja
 * Author URI: https://blog.avgamingindia.com
 * Text Domain: rt-slideshow
 * Domain Path: /languages
 *
 * @package RTSlideshow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'src/RTSlideshow.php';

// Instantiate the plugin class
$rt_slideshow = new RTSlideshow\RTSlideshow();
