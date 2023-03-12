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

		// Load plugin scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_end_scripts' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add shortcode to display slideshow.
		add_shortcode( 'rtslideshow', array( $this, 'rt_slideshow_shortcode' ) );

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

		// Enqueue jQuery UI stylesheets and scripts.
		wp_enqueue_style( 'jquery-ui-css', plugins_url( 'lib/jquery-ui-1.13.2.custom/jquery-ui.min.css', dirname( __FILE__ ) ), array(), '1.13.2' );
		wp_enqueue_script( 'jquery-ui-js', plugins_url( 'lib/jquery-ui-1.13.2.custom/jquery-ui.min.js', dirname( __FILE__ ) ), array(), '1.13.2', true );

		// Enqueue plugin admin scripts.
		wp_enqueue_script( 'rt-slideshow-admin-script', plugins_url( 'assets/js/rt-slideshow-admin.js', dirname( __FILE__ ) ), array(), '1.0.0', true );

		// Enqueue plugin admin stylesheets.
		wp_enqueue_style( 'rt-slideshow-admin-styles', plugins_url( 'assets/css/rt-slideshow-admin.css', dirname( __FILE__ ) ), array(), '1.0.0' );

	}

	/**
	 * Enqueue front end scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_front_end_scripts() {

		// Enqueue jQuery UI stylesheets and scripts.
		wp_enqueue_style( 'swiper-css', plugins_url( 'lib/swiper-9.1.0/swiper-bundle.min.css', dirname( __FILE__ ) ), array(), '9.1.0' );
		wp_enqueue_script( 'jquery-ui-js', plugins_url( 'lib/swiper-9.1.0/swiper-bundle.min.js', dirname( __FILE__ ) ), array(), '9.1.0', true );

		// Enqueue plugin main scripts.
		wp_enqueue_script( 'rt-slideshow-main-script', plugins_url( 'assets/js/rt-slideshow-main.js', dirname( __FILE__ ) ), array(), '1.0.0', true );

		// Enqueue plugin main stylesheets.
		wp_enqueue_style( 'rt-slideshow-main-styles', plugins_url( 'assets/css/rt-slideshow-main.css', dirname( __FILE__ ) ), array(), '1.0.0' );

	}



	/**
	 * Add settings page to the admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {

		add_menu_page( 'RT Slideshow', 'RT Slideshow', 'manage_options', 'rt-slideshow', array( $this, 'render_main_page' ), 'dashicons-images-alt2' );

	}

	/**
	 * Activate the plugin.
	 */
	public function activate() {

		// Store image ids in database.
		add_option( 'rt_slideshow_image_ids', array() );

		// Flush rewrite rules to ensure custom post types are registered.
		flush_rewrite_rules();

	}

	/**
	 * Sanitize options value.
	 */
	public function register_settings() {
		register_setting( 'rt-slideshow-settings-group', 'rt_slideshow_image_ids' );
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
		include_once dirname( __FILE__ ) . '/rtslideshow-admin.php';

	}

	/**
	 * Render the main page.
	 *
	 * @since 1.0.0
	 */
	public function rt_slideshow_shortcode() {

		ob_start();
		// Render the main page.
		include dirname( __FILE__ ) . '/rtslideshow-slider.php';
		$content = ob_get_clean();
		return $content;

	}

}
