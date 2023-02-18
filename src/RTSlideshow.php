<?php
/**
 * The main plugin class file.
 *
 * @package RTSlideshow
 */

 namespace RTSlideshow;

 // Exit if accessed directly.
 defined( 'ABSPATH' ) || exit;

 /**
  * Main plugin class.
  */
class RTSlideshow {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'rt-slideshow';
		$this->version     = '1.0.0';

		// Load plugin scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		if ( is_admin() ) {
			// Add admin menu page to manage slideshow
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		}
	}


	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'jquery-ui-css', plugins_url( 'lib/jquery-ui-1.13.2.custom/jquery-ui.min.css', dirname( __FILE__ ) ) );
		wp_enqueue_script( 'jquery-ui-js', plugins_url( 'lib/jquery-ui-1.13.2.custom/jquery-ui.min.js', dirname( __FILE__ ) ) );
	}

	/**
	 * Add settings page to the admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {
		add_menu_page( 'RT Slideshow Settings', 'RT Slideshow', 'manage_options', 'rt-slideshow', array( $this, 'render_settings_page' ), 'dashicons-images-alt2' );
	}
}
