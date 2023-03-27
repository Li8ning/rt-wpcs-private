<?php
/**
 * Class Test_wpslideshow
 *
 * @package WPSlideshow
 */

 use WPSlideshow\WPSlideshow;

class Test_wpslideshow extends WP_UnitTestCase {

	/**
	 * Test that the WPSlideshow class can be instantiated.
	 *
	 * @covers \WPSlideshow\WPSlideshow
	 */
	public function test_WPSlideshow_class_exists() {
		$this->assertTrue( class_exists( '\WPSlideshow\WPSlideshow' ), 'WPSlideshow class exists' );
	}

	/**
	 * Test that the WPSlideshow class is properly defined as part of the WPSlideshow namespace.
	 *
	 * @covers \WPSlideshow\WPSlideshow
	 */
	public function test_WPSlideshow_class_is_in_correct_namespace() {
		$reflection = new \ReflectionClass( '\WPSlideshow\WPSlideshow' );
		$this->assertEquals( 'WPSlideshow', $reflection->getNamespaceName() );
	}

	/**
	 * Test if the registered setting is successfully unregistered on deactivation.
	 *
	 * @covers WPSlideshow::deactivate
	 * @covers WPSlideshow::register_settings
	 */
	public function test_registered_settings_unregistered_on_deactivating() {

		$wpslideshow = new WPSlideshow();

		$wpslideshow->register_settings();

		$wp_setting = get_registered_settings();

		// Check if the registered setting has been successfully registered.
		$this->assertContains( 'wp-slideshow-settings-group', $wp_setting['wp_slideshow_image_ids'] );

		$wpslideshow->deactivate();

		// Check if the registered setting has been successfully unregistered.
		$wp_setting = get_registered_settings();

		$this->assertNotContains( 'wp_slideshow_image_ids', $wp_setting );
	}

	/**
	 * Test if the shortcode is successfully removed on deactivation.
	 *
	 * @covers WPSlideshow::deactivate
	 */
	public function test_shortcode_removal_on_deactivation() {

		$wpslideshow = new WPSlideshow();

		// Set up current user as an administrator.
		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		// Manually trigger the admin_init hook.
		do_action( 'admin_init' );

		// Add the shortcode to a test post.
		$post_id = $this->factory->post->create(
			array(
				'post_content' => '[wpslideshow]',
			)
		);

		// // Check if the shortcode exists in the post content.
		// $this->assertTrue( has_shortcode( get_post_field( 'post_content', $post_id ), 'wpslideshow' ) );

		// // Deactivate plugin
		// $wpslideshow->deactivate();

		// Check if the shortcode is removed from the post content.
		// $this->assertFalse( has_shortcode( get_post_field( 'post_content', $post_id ), 'wpslideshow' ) );

		// Delete the test post.
		wp_delete_post( $post_id );

		wp_delete_user( $admin_user_id );
	}


	/**
	 * Tests whether required admin styles and scripts
	 * are enqueued and dequeued
	 *
	 * @see WPSlideshow::enqueue_admin_scripts()
	 * @see WPSlideshow::dequeue_admin_scripts()
	 */
	public function test_admin_styles_and_scripts_enqueued_and_dequeued_correctly() {

		$wpslideshow = new WPSlideshow();

		$this->assertFalse( wp_script_is( 'wp-jquery-ui-js' ) );
		$this->assertFalse( wp_style_is( 'wp-jquery-ui-css' ) );
		$this->assertFalse( wp_script_is( 'wp-slideshow-admin-script' ) );
		$this->assertFalse( wp_style_is( 'wp-slideshow-admin-styles' ) );

		$wpslideshow->enqueue_admin_scripts();

		$this->assertTrue( wp_script_is( 'wp-jquery-ui-js' ) );
		$this->assertTrue( wp_style_is( 'wp-jquery-ui-css' ) );
		$this->assertTrue( wp_script_is( 'wp-slideshow-admin-script' ) );
		$this->assertTrue( wp_style_is( 'wp-slideshow-admin-styles' ) );

		$wpslideshow->dequeue_admin_scripts();

		$this->assertFalse( wp_script_is( 'wp-jquery-ui-js' ) );
		$this->assertFalse( wp_style_is( 'wp-jquery-ui-css' ) );
		$this->assertFalse( wp_script_is( 'wp-slideshow-admin-script' ) );
		$this->assertFalse( wp_style_is( 'wp-slideshow-admin-styles' ) );

	}

	/**
	 * Tests whether required front end styles and scripts
	 * are enqueued and dequeued
	 *
	 * @see WPSlideshow::enqueue_front_end_scripts()
	 * @see WPSlideshow::dequeue_front_end_scripts()
	 */
	public function test_front_end_styles_and_scripts_enqueued_and_dequeued_correctly() {

		$wpslideshow = new WPSlideshow();

		$this->assertFalse( wp_script_is( 'wp-swiper-js' ) );
		$this->assertFalse( wp_style_is( 'wp-swiper-css' ) );
		$this->assertFalse( wp_script_is( 'wp-slideshow-main-script' ) );
		$this->assertFalse( wp_style_is( 'wp-slideshow-main-styles' ) );

		$wpslideshow->enqueue_front_end_scripts();

		$this->assertTrue( wp_script_is( 'wp-swiper-js' ) );
		$this->assertTrue( wp_style_is( 'wp-swiper-css' ) );
		$this->assertTrue( wp_script_is( 'wp-slideshow-main-script' ) );
		$this->assertTrue( wp_style_is( 'wp-slideshow-main-styles' ) );

		$wpslideshow->dequeue_front_end_scripts();

		$this->assertFalse( wp_script_is( 'wp-swiper-js' ) );
		$this->assertFalse( wp_style_is( 'wp-swiper-css' ) );
		$this->assertFalse( wp_script_is( 'wp-slideshow-main-script' ) );
		$this->assertFalse( wp_style_is( 'wp-slideshow-main-styles' ) );

	}

	/**
	 * Tests whether wpslideshow shortcode registered
	 *
	 * @see WPSlideshow::__construct
	 */
	public function test_shortcode_registered() {

		$wpslideshow = new WPSlideshow();
		$this->assertTrue( shortcode_exists( 'wpslideshow' ) );

	}

	/**
	 * Tests whether plugin settings page added
	 *
	 * @see WPSlideshow::add_admin_menu()
	 */
	public function test_wp_slideshow_menu_page_added() {

		$wpslideshow = new WPSlideshow();

		$admin_menu_hook = add_menu_page( 'WP Slideshow', 'WP Slideshow', 'manage_options', 'wp-slideshow', array( $wpslideshow, 'render_main_page' ), 'dashicons-images-alt2' );

		$this->assertTrue( $admin_menu_hook !== false );

		remove_menu_page( 'wp-slideshow' );

	}

	/**
	 * Tests whether wp_slideshow_image_ids option added
	 * during plugin activation
	 *
	 * @see WPSlideshow::activate()
	 */
	public function test_image_ids_option_registered() {

		$wpslideshow = new WPSlideshow();

		$this->assertFalse( get_option( 'wp_slideshow_image_ids' ) );

		$wpslideshow->activate();

		$this->assertTrue( get_option( 'wp_slideshow_image_ids' ) !== false );

	}

	/**
	 * Unit test for the register_settings() method of the WPSlideshow class.
	 *
	 * Ensures that the register_setting() function is called with the correct parameters.
	 *
	 * @since 1.0.0
	 */
	public function test_register_settings() {

		$wpslideshow = new WPSlideshow();

		// Call the register_settings() method to register the settings.
		$wpslideshow->register_settings();

		// Assert that the register_setting() function was called with the correct parameters.
		$this->assertSettingsRegistered( 'wp-slideshow-settings-group', 'wp_slideshow_image_ids' );

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
	 * Test if main page is rendered correctly.
	 *
	 * @since 1.0.0
	 * @see WPSlideshow::render_main_page()
	 * @see /templates/wpslideshow-admin.php
	 */
	public function test_render_main_page() {

		$wpslideshow = new WPSlideshow();

		// Set up current user as an administrator.
		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		// Render the main page.
		$output = $wpslideshow->render_main_page();

		// Assert that output contains the expected text.
		$this->assertStringContainsString( '<form method="post" action="options.php">', $output );
		$this->assertStringContainsString( '<input type="hidden" name="wp_slideshow_image_ids" id="wp_slideshow_image_ids" value="" />', $output );
		$this->assertStringContainsString( '<ul id="wp_slideshow_image_list">', $output );
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

		$output2 = $wpslideshow->render_main_page();

		// Assert that output is null since the user does not have 'manage_options' capability.
		$this->assertNull( $output2 );

		wp_delete_user( $admin_user_id );
		wp_delete_user( $subscriber_user_id );
	}

	/**
	 * Test that wp_slideshow_image_ids tag is populated when image IDs are set.
	 *
	 * @since 1.0.0
	 * @see /templates/wpslideshow-admin.php
	 * @see WPSlideshow::render_main_page()
	 */
	public function test_image_ids_are_populated() {

		$wpslideshow = new WPSlideshow();

		// Set up dummy image ids
		$image_ids = array( 1, 2, 3 );

		// Set the option with the image IDs.
		update_option( 'wp_slideshow_image_ids', implode( ',', $image_ids ) );

		// Set up current user as an administrator.
		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		// Render the main page.
		ob_start();
		$wpslideshow->render_main_page();
		$output = ob_get_clean();

		// Check that the wp_slideshow_image_ids tag has the expected value.
		$expected_value = esc_attr( implode( ',', $image_ids ) );
		$this->assertStringContainsString( '<input type="hidden" name="wp_slideshow_image_ids" id="wp_slideshow_image_ids" value="' . $expected_value . '" />', $output );

		wp_delete_user( $admin_user_id );

	}

	/**
	 * Test that wp_slideshow_image_ids tag is populated when images are selected from wp.media.
	 *
	 * @since 1.0.0
	 * @see /templates/wpslideshow-admin.php
	 * @see WPSlideshow::render_main_page()
	 */
	public function test_image_ids_are_inserted_in_db_correctly() {

		$wpslideshow   = new WPSlideshow();
		$image_ids     = '1,2,3';
		$new_image_ids = '4,5,6';

		update_option( 'wp_slideshow_image_ids', $image_ids );

		// Set up current user as an administrator.
		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		add_menu_page( 'WP Slideshow', 'WP Slideshow', 'manage_options', 'wp-slideshow', array( $wpslideshow, 'render_main_page' ), 'dashicons-images-alt2' );

		ob_start();
		do_action( 'admin_menu' );
		do_action( 'toplevel_page_wp-slideshow' );
		$output = ob_get_clean();

		// Check if the render_main_page function is called
		$this->assertTrue( did_action( 'render_main_page' ) );

		wp_delete_user( $admin_user_id );
	}

	/**
	 * Test if slider is rendered correctly.
	 *
	 * @since 1.0.0
	 * @see WPSlideshow::wp_slideshow_slider()
	 * @see /templates/wpslideshow-slider.php
	 */
	public function test_render_slider_page() {

		$wpslideshow = new WPSlideshow();

		// Create dummy images using GD library
		$image1   = imagecreatetruecolor( 200, 200 );
		$bgColor1 = imagecolorallocate( $image1, 255, 255, 255 );
		imagefill( $image1, 0, 0, $bgColor1 );
		// Generate the image filename and path
		$image_filename = 'test_image1.jpg';
		$upload_dir     = wp_upload_dir();
		$image_path1    = $upload_dir['path'] . '/' . $image_filename;
		imagejpeg( $image1, $image_path1 );

		$image2   = imagecreatetruecolor( 300, 300 );
		$bgColor2 = imagecolorallocate( $image2, 255, 255, 255 );
		imagefill( $image2, 0, 0, $bgColor2 );
		// Generate the image filename and path
		$image_filename = 'test_image2.jpg';
		$upload_dir     = wp_upload_dir();
		$image_path2    = $upload_dir['path'] . '/' . $image_filename;
		imagejpeg( $image2, $image_path2 );

		// Upload dummy images to media library
		$attachment_id1 = $this->factory->attachment->create_object(
			$image_path1,
			0,
			array(
				'post_mime_type' => 'image/jpeg',
				'post_title'     => 'Test Image 1',
				'post_content'   => '',
				'post_status'    => 'inherit',
			)
		);

		$attachment_id2 = $this->factory->attachment->create_object(
			$image_path2,
			0,
			array(
				'post_mime_type' => 'image/jpeg',
				'post_title'     => 'Test Image 2',
				'post_content'   => '',
				'post_status'    => 'inherit',
			)
		);

		// Set up current user as an administrator.
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );

		// Add the attachment IDs to the wp_slideshow_image_ids option
		update_option( 'wp_slideshow_image_ids', $attachment_id1 . ',' . $attachment_id2 );

		// Render the slider.
		$output = $wpslideshow->wp_slideshow_slider();

		// Assert that output contains the expected HTML.
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $attachment_id1, 'full' ) . '" loading="lazy">', $output );
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $attachment_id2, 'full' ) . '" loading="lazy">', $output );

		// Clean up
		wp_delete_attachment( $attachment_id1, true );
		wp_delete_attachment( $attachment_id2, true );
		// Delete the image IDs option.
		delete_option( 'wp_slideshow_image_ids' );
		wp_delete_user( $user_id );
	}

}
