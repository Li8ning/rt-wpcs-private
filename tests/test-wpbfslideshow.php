<?php
/**
 * Class Test_WPBFSlideshow
 *
 * @package WPBFSlideshow
 */

use WPBFSlideshow\WPBFSlideshow;

class Test_WPBFSlideshow extends WP_UnitTestCase {

	/**
	 * Test that the WPBFSlideshow class can be instantiated.
	 *
	 * @see \WPBFSlideshow\WPBFSlideshow
	 * @since 1.0.0
	 */
	public function test_WPBFSlideshow_class_exists() {

		$this->assertTrue( class_exists( '\WPBFSlideshow\WPBFSlideshow' ), 'WPBFSlideshow class does not exists' );

	}

	/**
	 * Test that the WPBFSlideshow class is properly defined as part of the WPBFSlideshow namespace.
	 *
	 * @see \WPBFSlideshow\WPBFSlideshow
	 * @since 1.0.0
	 */
	public function test_WPBFSlideshow_class_is_in_correct_namespace() {

		$reflection = new \ReflectionClass( '\WPBFSlideshow\WPBFSlideshow' );
		$this->assertEquals( 'WPBFSlideshow', $reflection->getNamespaceName(), 'WPBFSlideshow not present in WPBFSlideshow namespace' );

	}

	/**
	 * Tests that the plugin name is set and is a string in the WPBFSlideshow class.
	 *
	 * @see \WPBFSlideshow\WPBFSlideshow
	 * @see WPBFSlideshow::construct
	 * @since 1.0.0
	 */
	public function test_plugin_name_is_set_and_is_string() {

		$wpbfslideshow = new WPBFSlideshow();

		// Access the plugin_name property using reflection.
		$reflection = new \ReflectionClass( $wpbfslideshow );
		$property   = $reflection->getProperty( 'plugin_name' );
		$property->setAccessible( true );
		$plugin_name = $property->getValue( $wpbfslideshow );

		// Assert that the plugin_name is a string and equals 'wpbf-beautiful-slideshow'.
		$this->assertIsString( $plugin_name, 'plugin_name is not string' );
		$this->assertEquals( 'wpbf-beautiful-slideshow', $plugin_name, 'plugin_name is incorrect' );

	}

	/**
	 * Tests that the plugin version is set and is a string in the WPBFSlideshow class.
	 *
	 * @see \WPBFSlideshow\WPBFSlideshow
	 * @see WPBFSlideshow::construct
	 * @since 1.0.0
	 */
	public function test_plugin_version_is_set_and_is_string() {

		$wpbfslideshow = new WPBFSlideshow();

		// Access the version property using reflection.
		$reflection = new \ReflectionClass( $wpbfslideshow );
		$property   = $reflection->getProperty( 'version' );
		$property->setAccessible( true );
		$plugin_version = $property->getValue( $wpbfslideshow );

		// Assert that the plugin_version is a string and equals '1.0.0'.
		$this->assertIsString( $plugin_version, 'plugin version is not string' );
		$this->assertEquals( '1.0.0', $plugin_version, 'plugin version not matching' );

	}

	/**
	 * Tests that the plugin has necessary actions.
	 *
	 * @see WPBFSlideshow::construct
	 * @since 1.0.0
	 */
	public function test_plugin_has_necessary_actions() {

		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		set_current_screen( 'dashboard' );

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertNotEquals( has_action( 'admin_enqueue_scripts', array( $wpbfslideshow, 'enqueue_admin_scripts' ) ), 0, 'The admin_enqueue_scripts is not registered' );
		$this->assertNotEquals( has_action( 'wp_enqueue_scripts', array( $wpbfslideshow, 'enqueue_front_end_scripts' ) ), 0, 'The wp_enqueue_scripts is not registered' );
		$this->assertNotEquals( has_action( 'admin_init', array( $wpbfslideshow, 'register_settings' ) ), 0, 'The admin_init with register_settings callback is not registered' );
		$this->assertNotEquals( has_action( 'admin_menu', array( $wpbfslideshow, 'add_admin_menu' ) ), 0, 'The admin_menu action is not registered' );

		wp_delete_user( $admin_user_id );
		unset( $GLOBALS['current_screen'] );

	}

	/**
	 * Test if the registered setting is successfully unregistered on deactivation.
	 *
	 * @see WPBFSlideshow::deactivate
	 * @see WPBFSlideshow::register_settings
	 * @since 1.0.0
	 */
	public function test_plugin_deactivation() {

		$wpbfslideshow = new WPBFSlideshow();

		// Register settings before deactivation
		$wpbfslideshow->register_settings();

		$wpbf_setting = get_registered_settings();

		// Check if the registered setting has been successfully registered.
		$this->assertContains( 'wpbf-slideshow-settings-group', $wpbf_setting['wpbf_slideshow_image_ids'], 'wpbf-slideshow-settings-group is not registered' );

		// Add the shortcode to a test post.
		$post_id = $this->factory->post->create(
			array(
				'post_content' => '[wpbfslideshow]',
			)
		);

		// Check if the shortcode exists in the post content.
		$this->assertTrue( has_shortcode( get_post_field( 'post_content', $post_id ), 'wpbfslideshow', 'wpbfslideshow shortcode not found' ) );

		$wpbfslideshow->deactivate();

		// Check if the registered setting has been successfully unregistered.
		$wpbf_setting = get_registered_settings();

		$this->assertNotContains( 'wpbf_slideshow_image_ids', $wpbf_setting, 'wpbf_slideshow_image_ids settings is not removed' );

		// Check if the shortcode is removed from the post content.
		$this->assertFalse( has_shortcode( get_post_field( 'post_content', $post_id ), 'wpbfslideshow', 'wpbfslideshow shortcode not removed' ) );

		// Delete the test post.
		wp_delete_post( $post_id );
	}


	/**
	 * Tests whether required admin styles and scripts
	 * are enqueued and dequeued
	 *
	 * @see WPBFSlideshow::enqueue_admin_scripts()
	 * @see WPBFSlideshow::dequeue_admin_scripts()
	 * @since 1.0.0
	 */
	public function test_admin_styles_and_scripts_enqueued_and_dequeued_correctly() {

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertFalse( wp_script_is( 'wpbf-slideshow-admin-script' ), 'wpbf-slideshow-admin-script shall not be enqueued' );
		$this->assertFalse( wp_style_is( 'wpbf-slideshow-admin-styles' ), 'wpbf-slideshow-admin-styles shall not be enqueued' );

		$wpbfslideshow->enqueue_admin_scripts();

		$this->assertTrue( wp_script_is( 'wpbf-slideshow-admin-script' ), 'wpbf-slideshow-admin-script is not enqueued' );
		$this->assertTrue( wp_style_is( 'wpbf-slideshow-admin-styles' ), 'wpbf-slideshow-admin-styles is not enqueued' );

		$wpbfslideshow->dequeue_admin_scripts();

		$this->assertFalse( wp_script_is( 'wpbf-slideshow-admin-script' ), 'wpbf-slideshow-admin-script is not dequeued' );
		$this->assertFalse( wp_style_is( 'wpbf-slideshow-admin-styles' ), 'wpbf-slideshow-admin-styles is not dequeued' );

	}

	/**
	 * Tests whether required front end styles and scripts
	 * are enqueued and dequeued
	 *
	 * @see WPBFSlideshow::enqueue_front_end_scripts()
	 * @see WPBFSlideshow::dequeue_front_end_scripts()
	 * @since 1.0.0
	 */
	public function test_front_end_styles_and_scripts_enqueued_and_dequeued_correctly() {

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertFalse( wp_script_is( 'wpbf-swiper-js' ), 'wpbf-swiper-js shall not be enqueued' );
		$this->assertFalse( wp_style_is( 'wpbf-swiper-css' ), 'wpbf-swiper-css shall not be enqueued' );
		$this->assertFalse( wp_script_is( 'wpbf-slideshow-main-script' ), 'wpbf-slideshow-main-script shall not be enqueued' );
		$this->assertFalse( wp_style_is( 'wpbf-slideshow-main-styles' ), 'wpbf-slideshow-main-styles shall not be enqueued' );

		$wpbfslideshow->enqueue_front_end_scripts();

		$this->assertTrue( wp_script_is( 'wpbf-swiper-js' ), 'wpbf-swiper-js is not enqueued' );
		$this->assertTrue( wp_style_is( 'wpbf-swiper-css' ), 'wpbf-swiper-css is not enqueued' );
		$this->assertTrue( wp_script_is( 'wpbf-slideshow-main-script' ), 'wpbf-slideshow-main-script is not enqueued' );
		$this->assertTrue( wp_style_is( 'wpbf-slideshow-main-styles' ), 'wpbf-slideshow-main-styles is not enqueued' );

		$wpbfslideshow->dequeue_front_end_scripts();

		$this->assertFalse( wp_script_is( 'wpbf-swiper-js' ), 'wpbf-swiper-js is not dequeued' );
		$this->assertFalse( wp_style_is( 'wpbf-swiper-css' ), 'wpbf-swiper-css is not dequeued' );
		$this->assertFalse( wp_script_is( 'wpbf-slideshow-main-script' ), 'wpbf-slideshow-main-script is not dequeued' );
		$this->assertFalse( wp_style_is( 'wpbf-slideshow-main-styles' ), 'wpbf-slideshow-main-styles is not dequeued' );

	}

	/**
	 * Tests whether wpbfslideshow shortcode registered
	 *
	 * @see WPBFSlideshow::__construct
	 * @since 1.0.0
	 */
	public function test_shortcode_registered() {

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertTrue( shortcode_exists( 'wpbfslideshow' ), 'wpbfslideshow shortcode does not exists' );

		remove_shortcode( 'wpbfslideshow' );

	}

	/**
	 * Tests whether plugin settings page added
	 *
	 * @see WPBFSlideshow::add_admin_menu()
	 * @since 1.0.0
	 */
	public function test_wpbf_slideshow_menu_page_added() {

		$wpbfslideshow = new WPBFSlideshow();

		$wpbfslideshow->add_admin_menu();

		$this->assertEquals( 'wpbf-slideshow', $GLOBALS['admin_page_hooks']['wpbf-slideshow'], 'wpbf-sldideshow page not found' );

		remove_menu_page( 'wpbf-slideshow' );

	}

	/**
	 * Tests whether wpbf_slideshow_image_ids option added
	 * during plugin activation
	 *
	 * @see WPBFSlideshow::activate()
	 * @since 1.0.0
	 */
	public function test_image_ids_option_registered() {

		$wpbfslideshow = new WPBFSlideshow();

		$this->assertFalse( get_option( 'wpbf_slideshow_image_ids' ), 'wpbf_slideshow_image_ids option should not be registered' );

		$wpbfslideshow->activate();

		$this->assertTrue( get_option( 'wpbf_slideshow_image_ids' ) !== false, 'wpbf_slideshow_image_ids is not registered' );

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
	 * @since 1.0.0
	 * @param string $settings_group The settings group to which the setting belongs.
	 *
	 * @param string $setting_name The name of the setting to check.
	 */
	private function assertSettingsRegistered( $settings_group, $setting_name ) {

		// Get the registered settings from WordPress.
		$registered_settings = get_registered_settings();

		// Check if the setting is registered.
		$this->assertArrayHasKey( $setting_name, $registered_settings, 'register settings function does not have required key' );
		$this->assertEquals( $settings_group, $registered_settings[ $setting_name ]['group'], 'registered settings function was not called with the correct parameters' );

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
		$this->assertStringContainsString( '<form method="post" action="options.php" id="wpbf-slideshow-admin-form">', $output, 'Output does not match the string' );
		$this->assertStringContainsString( '<input type="hidden" name="wpbf_slideshow_image_ids" id="wpbf_slideshow_image_ids" value="" />', $output, 'Output does not match the string' );
		$this->assertStringContainsString( '<ul id="wpbf_slideshow_image_list">', $output, 'Output does not match the string' );
		$this->assertStringContainsString( '<input type="submit" class="button-primary" value="Save Changes">', $output, 'Output does not match the string' );

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
		$this->assertNull( $output2, 'Output should be null' );

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
		$this->assertStringContainsString( '<input type="hidden" name="wpbf_slideshow_image_ids" id="wpbf_slideshow_image_ids" value="' . $expected_value . '" />', $output, 'Output does not match the string' );

		wp_delete_user( $admin_user_id );

	}

	/**
	 * Test that the wpbfslideshow shortcode outputs correctly when images are present in the slider.
	 *
	 * @since 1.0.0
	 * @see WPBFSlideshow::wpbf_slideshow_slider()
	 * @see /templates/wpbfslideshow-slider.php
	 */
	public function test_shortcode_outputs_correctly_when_images_are_present() {

		$wpbfslideshow = new WPBFSlideshow();

		$wpbfslideshow->activate();

		$this->assertTrue( shortcode_exists( 'wpbfslideshow' ), 'wpbfslideshow shortcode does not exists' );

		$image_path1 = plugin_dir_path( __FILE__ ) . '../assets/img/Bane1.jpg';
		$image_path2 = plugin_dir_path( __FILE__ ) . '../assets/img/Abbadon1.jpg';

		// Insert images in database
		$image_id1 = $this->insert_attachement_into_media( $image_path1 );
		$image_id2 = $this->insert_attachement_into_media( $image_path2 );

		// Add the attachment IDs to the wp_slideshow_image_ids option
		update_option( 'wpbf_slideshow_image_ids', $image_id1 . ',' . $image_id2 );

		$shortcode_output = do_shortcode( '[wpbfslideshow]' );

		// Assert that output contains the expected HTML.
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $image_id1, 'full' ) . '" loading="lazy">', $shortcode_output, 'Output does not match the string' );
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $image_id2, 'full' ) . '" loading="lazy">', $shortcode_output, 'Output does not match the string' );

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
	 * This method uploads a dummy image into WordPress media library.
	 * The attachment ID of the uploaded image is returned.
	 *
	 * @since 1.0.0
	 * @param string $image_file_name The filename of the dummy image to create.
	 *
	 * @return int The attachment ID of the uploaded image.
	 */
	private function insert_attachement_into_media( $image_path ) {

		// Upload dummy images to media library
		$attachment = $this->factory->attachment->create_upload_object( $image_path );
		$this->assertNotEquals( 0, $attachment, 'Failed to create attachment object from dummy image file.' );

		wp_update_attachment_metadata( $attachment, wp_generate_attachment_metadata( $attachment, $image_path ) );

		return $attachment;

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

		$this->assertTrue( shortcode_exists( 'wpbfslideshow' ), 'wpbfslideshow shortcode does not exists' );

		// Set wpbf_slideshow_image_ids to an empty string.
		update_option( 'wpbf_slideshow_image_ids', '' );

		$shortcode_output = do_shortcode( '[wpbfslideshow]' );

		$this->assertEmpty( $shortcode_output, 'Output should be empty' );

		remove_shortcode( 'wpbfslideshow' );

		// Delete the image IDs option.
		delete_option( 'wpbf_slideshow_image_ids' );

	}

}
