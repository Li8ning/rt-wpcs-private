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
	 * @covers \WPBFSlideshow\WPBFSlideshow
	 */
	public function test_WPBFSlideshow_class_exists() {
		$this->assertTrue( class_exists( '\WPBFSlideshow\WPBFSlideshow' ), 'WPBFSlideshow class exists' );
	}

	/**
	 * Test that the WPBFSlideshow class is properly defined as part of the WPBFSlideshow namespace.
	 *
	 * @covers \WPBFSlideshow\WPBFSlideshow
	 */
	public function test_WPBFSlideshow_class_is_in_correct_namespace() {
		$reflection = new \ReflectionClass( '\WPBFSlideshow\WPBFSlideshow' );
		$this->assertEquals( 'WPBFSlideshow', $reflection->getNamespaceName() );
	}

	/**
	 * Test if the registered setting is successfully unregistered on deactivation.
	 *
	 * @covers WPBFSlideshow::deactivate
	 * @covers WPBFSlideshow::register_settings
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

	}

	/**
	 * Tests whether plugin settings page added
	 *
	 * @see WPBFSlideshow::add_admin_menu()
	 */
	public function test_wpbf_slideshow_menu_page_added() {

		$wpbfslideshow = new WPBFSlideshow();

		$admin_menu_hook = add_menu_page( 'WPBF Slideshow', 'WPBF Slideshow', 'manage_options', 'wpbf-slideshow', array( $wpbfslideshow, 'render_main_page' ), 'dashicons-images-alt2' );

		$this->assertTrue( $admin_menu_hook !== false );

		// Stop here and mark this test as incomplete.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);

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
	 * Test that wpbf_slideshow_image_ids tag is populated when images are selected from wp.media.
	 *
	 * @since 1.0.0
	 * @see /templates/wpbfslideshow-admin.php
	 * @see WPBFSlideshow::render_main_page()
	 */
	public function test_image_ids_are_inserted_in_db_correctly() {

		$wpbfslideshow   = new WPBFSlideshow();
		$image_ids     = '1,2,3';
		$new_image_ids = '4,5,6';

		update_option( 'wpbf_slideshow_image_ids', $image_ids );

		// Set up current user as an administrator.
		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		add_menu_page( 'WPBF Slideshow', 'WPBF Slideshow', 'manage_options', 'wpbf-slideshow', array( $wpbfslideshow, 'render_main_page' ), 'dashicons-images-alt2' );

		ob_start();
		do_action( 'admin_menu' );
		do_action( 'toplevel_page_wpbf-slideshow' );
		$output = ob_get_clean();

		// Stop here and mark this test as incomplete.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);

		// Check if the render_main_page function is called
		$this->assertTrue( did_action( 'render_main_page' ) );

		wp_delete_user( $admin_user_id );
	}

	/**
	 * Test if slider is rendered correctly.
	 *
	 * @since 1.0.0
	 * @see WPBFSlideshow::wpbf_slideshow_slider()
	 * @see /templates/wpbfslideshow-slider.php
	 */
	public function test_render_slider_page() {

		$wpbfslideshow = new WPBFSlideshow();

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
		update_option( 'wpbf_slideshow_image_ids', $attachment_id1 . ',' . $attachment_id2 );

		// Render the slider.
		$output = $wpbfslideshow->wpbf_slideshow_slider();

		// Assert that output contains the expected HTML.
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $attachment_id1, 'full' ) . '" loading="lazy">', $output );
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $attachment_id2, 'full' ) . '" loading="lazy">', $output );

		// Clean up
		wp_delete_attachment( $attachment_id1, true );
		wp_delete_attachment( $attachment_id2, true );
		// Delete the image IDs option.
		delete_option( 'wpbf_slideshow_image_ids' );
		wp_delete_user( $user_id );
	}

}
