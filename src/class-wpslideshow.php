<?php
/**
 * The main plugin class file.
 *
 * @package WPSlideshow
 */

namespace WPSlideshow;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 */
class WPSlideshow {

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

		$this->plugin_name = 'wp-slideshow';
		$this->version     = '1.0.0';

		// Load plugin scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_end_scripts' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add shortcode to display slideshow.
		add_shortcode( 'wpslideshow', array( $this, 'wp_slideshow_slider' ) );

		if ( is_admin() ) {

			// Add admin menu page to manage slideshow.
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		}

	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_admin_scripts() {

		// Enqueue required media scripts.
		if ( ! did_action( 'wp_enqueue_media' ) ) {

			wp_enqueue_media();

		}

		// Enqueue plugin admin scripts.
		wp_enqueue_script( 'wp-slideshow-admin-script', plugins_url( 'assets/js/wp-slideshow-admin.js', dirname( __FILE__ ) ), array(), '1.0.0', true );

		// Enqueue plugin admin stylesheets.
		wp_enqueue_style( 'wp-slideshow-admin-styles', plugins_url( 'assets/css/wp-slideshow-admin.css', dirname( __FILE__ ) ), array(), '1.0.0' );

	}

	/**
	 * Enqueue front end scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_front_end_scripts() {

		// Enqueue jQuery UI stylesheets and scripts.
		wp_enqueue_style( 'wp-swiper-css', plugins_url( 'lib/swiper-9.1.0/swiper-bundle.min.css', dirname( __FILE__ ) ), array(), '9.1.0' );
		wp_enqueue_script( 'wp-swiper-js', plugins_url( 'lib/swiper-9.1.0/swiper-bundle.min.js', dirname( __FILE__ ) ), array(), '9.1.0', true );

		// Enqueue plugin main scripts.
		wp_enqueue_script( 'wp-slideshow-main-script', plugins_url( 'assets/js/wp-slideshow-main.js', dirname( __FILE__ ) ), array(), '1.0.0', true );

		// Enqueue plugin main stylesheets.
		wp_enqueue_style( 'wp-slideshow-main-styles', plugins_url( 'assets/css/wp-slideshow-main.css', dirname( __FILE__ ) ), array(), '1.0.0' );

	}

	/**
	 * Dequeue admin scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function dequeue_admin_scripts() {

		// Remove admin styles and scripts.
		// Dequeue plugin admin scripts.
		wp_dequeue_script( 'wp-slideshow-admin-script' );

		// Dequeue plugin admin stylesheets.
		wp_dequeue_style( 'wp-slideshow-admin-styles' );

	}

	/**
	 * Dequeue front end scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function dequeue_front_end_scripts() {

		// Remove front end styles and scripts.
		// Dequeue jQuery UI stylesheets and scripts.
		wp_dequeue_style( 'wp-swiper-css' );
		wp_dequeue_script( 'wp-swiper-js' );

		// Dequeue plugin main scripts.
		wp_dequeue_script( 'wp-slideshow-main-script' );

		// Dequeue plugin main stylesheets.
		wp_dequeue_style( 'wp-slideshow-main-styles' );

	}

	/**
	 * Add plugin settings page to the admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {

		add_menu_page( 'WP Slideshow', 'WP Slideshow', 'manage_options', 'wp-slideshow', array( $this, 'render_main_page' ), 'dashicons-images-alt2' );

	}

	/**
	 * Activate the plugin.
	 *
	 * This function will be executed when the plugin is activated. It will register required option
	 * and shortcode.
	 *
	 * @since 1.0.0
	 */
	public function activate() {

		// Store image ids in database.
		add_option( 'wp_slideshow_image_ids', array() );

		// Flush rewrite rules to ensure custom post types are registered.
		flush_rewrite_rules();

	}

	/**
	 * Deactivate the plugin.
	 *
	 * This function will be executed when the plugin is deactivated. It will dequeue styles and scripts
	 * and remove registered shortcodes.
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {

		include_once dirname( __FILE__ ) . '/wpslideshow-deactivate.php';

	}

	/**
	 * Sanitize options value.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {

		register_setting( 'wp-slideshow-settings-group', 'wp_slideshow_image_ids' );

	}


	/**
	 * Render the main page.
	 *
	 * @since 1.0.0
	 */
	public function render_main_page() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {

			return;

		}
		// Render the main page.
		require plugin_dir_path( __FILE__ ) . '../templates/wpslideshow-admin.php';
	}

	/**
	 * Render the slider.
	 *
	 * @since 1.0.0
	 */
	public function rt_slideshow_slider() {

		ob_start();
		// Render the slider.
		require_once plugin_dir_path( __FILE__ ) . '../templates/wpslideshow-slider.php';
		$content = ob_get_clean();
		return $content;

	}

}
