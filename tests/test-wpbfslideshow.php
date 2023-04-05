<?php
/**
 * Class Test_wpbfslideshow
 *
 * @package WPBFSlideshow
 */

 use WPBFSlideshow\WPBFSlideshow;

class Test_wpbfslideshow extends WP_UnitTestCase {

	/**
	 * Test that the WPBFSlideshow class can be instantiated.
	 *
	 * @see \WPBFSlideshow\WPBFSlideshow
	 */
	public function test_WPBFSlideshow_class_exists() {
		$this->assertTrue( class_exists( '\WPBFSlideshow\WPBFSlideshow' ), 'WPBFSlideshow class exists' );
	}

	/**
	 * Test that the WPBFSlideshow class is properly defined as part of the WPBFSlideshow namespace.
	 *
	 * @see \WPBFSlideshow\WPBFSlideshow
	 */
	public function test_WPBFSlideshow_class_is_in_correct_namespace() {
		$reflection = new \ReflectionClass( '\WPBFSlideshow\WPBFSlideshow' );
		$this->assertEquals( 'WPBFSlideshow', $reflection->getNamespaceName() );
	}

	/**
	 * Tests that the plugin name is set and is a string in the WPBFSlideshow class.
	 *
	 * @see \WPBFSlideshow\WPBFSlideshow
	 */
	public function test_plugin_name_is_set_and_is_string() {

		$wpbfslideshow = new WPBFSlideshow();

		// Access the plugin_name property using reflection.
		$reflection = new \ReflectionClass( $wpbfslideshow );
		$property   = $reflection->getProperty( 'plugin_name' );
		$property->setAccessible( true );
		$plugin_name = $property->getValue( $wpbfslideshow );

		// Assert that the plugin_name is a string and equals 'wpbf-beautiful-slideshow'.
		$this->assertIsString( $plugin_name );
		$this->assertEquals( 'wpbf-beautiful-slideshow', $plugin_name );

	}

	/**
	 * Tests that the plugin version is set and is a string in the WPBFSlideshow class.
	 *
	 * @see \WPBFSlideshow\WPBFSlideshow
	 */
	public function test_plugin_version_is_set_and_is_string() {

		$wpbfslideshow = new WPBFSlideshow();

		// Access the version property using reflection.
		$reflection = new \ReflectionClass( $wpbfslideshow );
		$property   = $reflection->getProperty( 'version' );
		$property->setAccessible( true );
		$plugin_version = $property->getValue( $wpbfslideshow );

		// Assert that the plugin_version is a string and equals '1.0.0'.
		$this->assertIsString( $plugin_version );
		$this->assertEquals( '1.0.0', $plugin_version );

	}

	/**
	 * Tests that the plugin has necessary actions.
	 *
	 * @see WPBFSlideshow::construct
	 */
	public function test_plugin_has_necessary_actions() {

		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		set_current_screen( 'dashboard' );

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertNotEquals( has_action( 'admin_enqueue_scripts', array( $wpbfslideshow, 'enqueue_admin_scripts' ) ), 0, 'The admin_enqueue_scripts is not registered' );
		$this->assertNotEquals( has_action( 'wp_enqueue_scripts', array( $wpbfslideshow, 'enqueue_front_end_scripts' ) ), 0, 'The wp_enqueue_scripts is not registered' );
		$this->assertNotEquals( has_action( 'admin_init', array( $wpbfslideshow, 'register_settings' ) ), 0, 'The admin_init with register_settings callback is not registered' );

		// Assert if admin_menu action is registered if user is admin
		$this->assertNotEquals( has_action( 'admin_menu', array( $wpbfslideshow, 'add_admin_menu' ) ), 0, 'The admin_menu action is not registered' );

		wp_delete_user( $admin_user_id );
		unset( $GLOBALS['current_screen'] );

	}

	/**
	 * Test if the registered setting is successfully unregistered on deactivation.
	 *
	 * @see WPBFSlideshow::deactivate
	 * @see WPBFSlideshow::register_settings
	 */
	public function test_plugin_deactivation() {

		$wpbfslideshow = new WPBFSlideshow();

		// Register settings before deactivation
		$wpbfslideshow->register_settings();

		$wpbf_setting = get_registered_settings();

		// Check if the registered setting has been successfully registered.
		$this->assertContains( 'wpbf-slideshow-settings-group', $wpbf_setting['wpbf_slideshow_image_ids'] );

		// Add the shortcode to a test post.
		$post_id = $this->factory->post->create(
			array(
				'post_content' => '[wpbfslideshow]',
			)
		);

		// Check if the shortcode exists in the post content.
		$this->assertTrue( has_shortcode( get_post_field( 'post_content', $post_id ), 'wpbfslideshow' ) );

		$wpbfslideshow->deactivate();

		// Check if the registered setting has been successfully unregistered.
		$wpbf_setting = get_registered_settings();

		$this->assertNotContains( 'wpbf_slideshow_image_ids', $wpbf_setting );

		// Check if the shortcode is removed from the post content.
		$this->assertFalse( has_shortcode( get_post_field( 'post_content', $post_id ), 'wpbfslideshow' ) );

		// Delete the test post.
		wp_delete_post( $post_id );
	}


	/**
	 * Tests whether required admin styles and scripts
	 * are enqueued and dequeued
	 *
	 * @see WPBFSlideshow::enqueue_admin_scripts()
	 * @see WPBFSlideshow::dequeue_admin_scripts()
	 */
	public function test_admin_styles_and_scripts_enqueued_and_dequeued_correctly() {

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertFalse( wp_script_is( 'wpbf-slideshow-admin-script' ) );
		$this->assertFalse( wp_style_is( 'wpbf-slideshow-admin-styles' ) );

		$wpbfslideshow->enqueue_admin_scripts();

		$this->assertTrue( wp_script_is( 'wpbf-slideshow-admin-script' ) );
		$this->assertTrue( wp_style_is( 'wpbf-slideshow-admin-styles' ) );

		$wpbfslideshow->dequeue_admin_scripts();

		$this->assertFalse( wp_script_is( 'wpbf-slideshow-admin-script' ) );
		$this->assertFalse( wp_style_is( 'wpbf-slideshow-admin-styles' ) );

	}

	/**
	 * Tests whether required front end styles and scripts
	 * are enqueued and dequeued
	 *
	 * @see WPBFSlideshow::enqueue_front_end_scripts()
	 * @see WPBFSlideshow::dequeue_front_end_scripts()
	 */
	public function test_front_end_styles_and_scripts_enqueued_and_dequeued_correctly() {

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertFalse( wp_script_is( 'wpbf-swiper-js' ) );
		$this->assertFalse( wp_style_is( 'wpbf-swiper-css' ) );
		$this->assertFalse( wp_script_is( 'wpbf-slideshow-main-script' ) );
		$this->assertFalse( wp_style_is( 'wpbf-slideshow-main-styles' ) );

		$wpbfslideshow->enqueue_front_end_scripts();

		$this->assertTrue( wp_script_is( 'wpbf-swiper-js' ) );
		$this->assertTrue( wp_style_is( 'wpbf-swiper-css' ) );
		$this->assertTrue( wp_script_is( 'wpbf-slideshow-main-script' ) );
		$this->assertTrue( wp_style_is( 'wpbf-slideshow-main-styles' ) );

		$wpbfslideshow->dequeue_front_end_scripts();

		$this->assertFalse( wp_script_is( 'wpbf-swiper-js' ) );
		$this->assertFalse( wp_style_is( 'wpbf-swiper-css' ) );
		$this->assertFalse( wp_script_is( 'wpbf-slideshow-main-script' ) );
		$this->assertFalse( wp_style_is( 'wpbf-slideshow-main-styles' ) );

	}

	/**
	 * Tests whether wpbfslideshow shortcode registered
	 *
	 * @see WPBFSlideshow::__construct
	 */
	public function test_shortcode_registered() {

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertTrue( shortcode_exists( 'wpbfslideshow' ) );

		remove_shortcode( 'wpbfslideshow' );

	}

	/**
	 * Tests whether plugin settings page added
	 *
	 * @see WPBFSlideshow::add_admin_menu()
	 */
	public function test_wpbf_slideshow_menu_page_added() {

		$wpbfslideshow = new WPBFSlideshow();

		$wpbfslideshow->add_admin_menu();

		$this->assertEquals( 'wpbf-slideshow', $GLOBALS['admin_page_hooks']['wpbf-slideshow'] );

		remove_menu_page( 'wpbf-slideshow' );

	}

	/**
	 * Tests whether wpbf_slideshow_image_ids option added
	 * during plugin activation
	 *
	 * @see WPBFSlideshow::activate()
	 */
	public function test_image_ids_option_registered() {

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertFalse( get_option( 'wpbf_slideshow_image_ids' ) );

		$wpbfslideshow->activate();

		$this->assertTrue( get_option( 'wpbf_slideshow_image_ids' ) !== false );

	}

	/**
	 * Unit test for the register_settings() method of the WPBFSlideshow class.
	 *
	 * Ensures that the register_setting() function is called with the correct parameters.
	 *
	 * @since 1.0.0
	 */
	public function test_register_settings() {

		$wpbfslideshow = new WPBFSlideshow();

		// Call the register_settings() method to register the settings.
		$wpbfslideshow->register_settings();

		// Assert that the register_setting() function was called with the correct parameters.
		$this->assertSettingsRegistered( 'wpbf-slideshow-settings-group', 'wpbf_slideshow_image_ids' );

	}

	/**
	 * Helper method to assert that a setting was registered.
	 *
	 * @param string $settings_group The settings group to which the setting belongs.
	 *
	 * @param string $setting_name The name of the setting to check.
	 */
	private function assertSettingsRegistered( $settings_group, $setting_name ) {

		// Get the registered settings from WordPress.
		$registered_settings = get_registered_settings();

		// Check if the setting is registered.
		$this->assertArrayHasKey( $setting_name, $registered_settings );
		$this->assertEquals( $settings_group, $registered_settings[ $setting_name ]['group'] );

	}

	/**
	 * Test if plugin admin page is rendered correctly.
	 *
	 * @since 1.0.0
	 * @see WPBFSlideshow::render_main_page()
	 * @see /templates/wpbfslideshow-admin.php
	 */
	public function test_render_plugin_admin_page() {

		$wpbfslideshow = new WPBFSlideshow();

		// Set up current user as an administrator.
		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		// Render the main page.
		ob_start();
		$wpbfslideshow->render_main_page();
		$output = ob_get_clean();

		// Assert that output contains the expected text.
		$this->assertStringContainsString( '<form method="post" action="options.php" id="wpbf-slideshow-admin-form">', $output );
		$this->assertStringContainsString( '<input type="hidden" name="wpbf_slideshow_image_ids" id="wpbf_slideshow_image_ids" value="" />', $output );
		$this->assertStringContainsString( '<ul id="wpbf_slideshow_image_list">', $output );
		$this->assertStringContainsString( '<input type="submit" class="button-primary" value="Save Changes">', $output );

		// Update user role to non-administrator and check if they can still access the page.
		$subscriber_user_id = $this->factory->user->create( array( 'role' => 'subscriber' ) );
		wp_update_user(
			array(
				'ID'   => $subscriber_user_id,
				'role' => 'subscriber',
			)
		);
		wp_set_current_user( $subscriber_user_id );

		$output2 = $wpbfslideshow->render_main_page();

		// Assert that output is null since the user does not have 'manage_options' capability.
		$this->assertNull( $output2 );

		wp_delete_user( $admin_user_id );
		wp_delete_user( $subscriber_user_id );
	}

	/**
	 * Test that wpbf_slideshow_image_ids tag is populated when image IDs are set.
	 *
	 * @since 1.0.0
	 * @see /templates/wpbfslideshow-admin.php
	 * @see WPBFSlideshow::render_main_page()
	 */
	public function test_image_ids_are_populated() {

		$wpbfslideshow = new WPBFSlideshow();

		// Set up dummy image ids
		$image_ids = array( 1, 2, 3 );

		// Set the option with the image IDs.
		update_option( 'wpbf_slideshow_image_ids', implode( ',', $image_ids ) );

		// Set up current user as an administrator.
		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		// Render the main page.
		ob_start();
		$wpbfslideshow->render_main_page();
		$output = ob_get_clean();

		// Check that the wp_slideshow_image_ids tag has the expected value.
		$expected_value = esc_attr( implode( ',', $image_ids ) );
		$this->assertStringContainsString( '<input type="hidden" name="wpbf_slideshow_image_ids" id="wpbf_slideshow_image_ids" value="' . $expected_value . '" />', $output );

		wp_delete_user( $admin_user_id );

	}

	/**
	 * Test that the wpbfslider shortcode outputs correctly when images are present in the slider.
	 *
	 * @since 1.0.0
	 * @see WPBFSlideshow::wpbf_slideshow_slider()
	 * @see /templates/wpbfslideshow-slider.php
	 */
	public function test_shortcode_outputs_correctly_when_images_are_present() {

		$wpbfslideshow = new WPBFSlideshow();

		$wpbfslideshow->activate();

		$this->assertTrue( shortcode_exists( 'wpbfslideshow' ) );

		// Insert images in database
		$image_id1 = $this->insert_attachement_into_media( 'test_image1.jpg', 'Test Image 1' );
		$image_id2 = $this->insert_attachement_into_media( 'test_image2.jpg', 'Test Image 2' );

		// Add the attachment IDs to the wp_slideshow_image_ids option
		update_option( 'wpbf_slideshow_image_ids', $image_id1 . ',' . $image_id2 );

		$shortcode_output = do_shortcode( '[wpbfslideshow]' );

		// Assert that output contains the expected HTML.
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $image_id1, 'full' ) . '" loading="lazy">', $shortcode_output );
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $image_id2, 'full' ) . '" loading="lazy">', $shortcode_output );

		// Clean up
		wp_delete_attachment( $image_id1, true );
		wp_delete_attachment( $image_id2, true );
		remove_shortcode( 'wpbfslideshow' );
		// Delete the image IDs option.
		delete_option( 'wpbf_slideshow_image_ids' );

	}

	/**
	 * Inserts a dummy attachment into the WordPress media library.
	 *
	 * This method creates a dummy image using the GD library, saves it to the server, and then
	 * uploads it to the WordPress media library. The attachment ID of the uploaded image is
	 * returned.
	 *
	 * @param string $image_file_name The filename of the dummy image to create.
	 * @param string $image_post_title The post title of the attachment to create.
	 *
	 * @return int The attachment ID of the uploaded image.
	 */
	private function insert_attachement_into_media( $image_file_name, $image_post_title ) {

		// Create dummy images using GD library
		$image   = imagecreatetruecolor( 200, 200 );
		$bgColor = imagecolorallocate( $image, 255, 255, 255 );
		imagefill( $image, 0, 0, $bgColor );
		// Generate the image filename and path
		$image_filename = $image_file_name;
		$upload_dir     = wp_upload_dir();
		$image_path     = $upload_dir['path'] . '/' . $image_filename;
		imagejpeg( $image, $image_path );

		// Upload dummy images to media library
		$attachment_id = $this->factory->attachment->create_object(
			$image_path,
			0,
			array(
				'post_mime_type' => 'image/jpeg',
				'post_title'     => $image_post_title,
				'post_content'   => '',
				'post_status'    => 'inherit',
			)
		);

		return $attachment_id;

	}

	/**
	 * Test that the wpbfslider shortcode outputs nothing when no slide data is provided.
	 *
	 * @since 1.0.0
	 * @see WPBFSlideshow::wpbf_slideshow_slider()
	 * @see /templates/wpbfslideshow-slider.php
	 */
	public function test_empty_shortcode_output() {

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertTrue( shortcode_exists( 'wpbfslideshow' ) );

		// Set wpbf_slideshow_image_ids to an empty string.
		update_option( 'wpbf_slideshow_image_ids', '' );

		$shortcode_output = do_shortcode( '[wpbfslideshow]' );

		$this->assertEmpty( $shortcode_output );

		remove_shortcode( 'wpbfslideshow' );

		// Delete the image IDs option.
		delete_option( 'wpbf_slideshow_image_ids' );

	}

}
