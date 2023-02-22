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

		// Enqueue required media scripts
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		// Enqueue jQuery UI stylesheets and scripts
		wp_enqueue_style( 'jquery-ui-css', plugins_url( 'lib/jquery-ui-1.13.2.custom/jquery-ui.min.css', dirname( __FILE__ ) ), array(), '1.13.2' );
		wp_enqueue_script( 'jquery-ui-js', plugins_url( 'lib/jquery-ui-1.13.2.custom/jquery-ui.min.js', dirname( __FILE__ ) ), array(), '1.13.2', true );

		// Enqueue plugin scripts
		wp_enqueue_script( 'rt-slideshow-script', plugins_url( 'assets/js/rt-slideshow.js', dirname( __FILE__ ) ), array(), '1.0.0', true );

		// Enqueue plugin stylesheets
		wp_enqueue_style( 'rt-slideshow-styles', plugins_url( 'assets/css/rt-slideshow.css', dirname( __FILE__ ) ), array(), '1.0.0' );
	}

	/**
	 * Add settings page to the admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {
		add_menu_page( 'RT Slideshow Settings', 'RT Slideshow', 'manage_options', 'rt-slideshow', array( $this, 'render_settings_page' ), 'dashicons-images-alt2' );
	}

	/**
	 * Activate the plugin.
	 */
	public function activate() {

		// Store image ids in database
		add_option( 'rt_slideshow_image_ids', array() );

		// Flush rewrite rules to ensure custom post types are registered.
		flush_rewrite_rules();

	}

	/**
	 * Render the settings page.
	 *
	 * @since 1.0.0
	 */
	public function render_settings_page() {
		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Render the settings page.
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
			// Get the saved image IDs
			$image_ids = get_option( 'rt_slideshow_image_ids', array() );

			// Loop through each saved image ID and display them
			foreach ( $image_ids as $image_id ) {
				$image_url = wp_get_attachment_url( $image_id, array( 80, 80 ) );
				if ( $image_url ) {
					?>
					<li data-id="<?php echo esc_url( $image_id ); ?>">
						<span style="background-image:url('<?php echo esc_url( $image_url ); ?>')"></span>
						<a href="#" class="rt-slideshow-remove">&times;</a>
					</li>
					<?php
				}
			}
			?>
			</ul>
			<input type="hidden" name="rt_slideshow_image_ids" id="rt_slideshow_image_ids" value="<?php echo join( ',', $image_ids ); ?>" />
			<input type="button" id="rt_slideshow_add_image_button" class="button button-secondary" value="Add Image">
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>">
		</form>
	</div>
		<?php
	}

}
