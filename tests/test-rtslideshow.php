<?php
/**
 * Class Test_rtslideshow
 *
 * @package RTSlideshow
 */

 use RTSlideshow\RTSlideshow;

class Test_rtslideshow extends WP_UnitTestCase {

	/**
	 * Tests whether required admin styles and scripts
	 * are enqueued and dequeued
	 *
	 * @see RTSlideshow::enqueue_admin_scripts()
	 * @see RTSlideshow::dequeue_admin_scripts()
	 */
	public function test_admin_styles_and_scripts_enqueued_and_dequeued_correctly() {

		$rtslideshow = new RTSlideshow();

		$this->assertFalse( wp_script_is( 'rt-jquery-ui-js' ) );
		$this->assertFalse( wp_style_is( 'rt-jquery-ui-css' ) );
		$this->assertFalse( wp_script_is( 'rt-slideshow-admin-script' ) );
		$this->assertFalse( wp_style_is( 'rt-slideshow-admin-styles' ) );

		$rtslideshow->enqueue_admin_scripts();

		$this->assertTrue( wp_script_is( 'rt-jquery-ui-js' ) );
		$this->assertTrue( wp_style_is( 'rt-jquery-ui-css' ) );
		$this->assertTrue( wp_script_is( 'rt-slideshow-admin-script' ) );
		$this->assertTrue( wp_style_is( 'rt-slideshow-admin-styles' ) );

		$rtslideshow->dequeue_admin_scripts();

		$this->assertFalse( wp_script_is( 'rt-jquery-ui-js' ) );
		$this->assertFalse( wp_style_is( 'rt-jquery-ui-css' ) );
		$this->assertFalse( wp_script_is( 'rt-slideshow-admin-script' ) );
		$this->assertFalse( wp_style_is( 'rt-slideshow-admin-styles' ) );

	}

	/**
	 * Tests whether required front end styles and scripts
	 * are enqueued and dequeued
	 *
	 * @see RTSlideshow::enqueue_front_end_scripts()
	 * @see RTSlideshow::dequeue_front_end_scripts()
	 */
	public function test_front_end_styles_and_scripts_enqueued_and_dequeued_correctly() {

		$rtslideshow = new RTSlideshow();

		$this->assertFalse( wp_script_is( 'rt-swiper-js' ) );
		$this->assertFalse( wp_style_is( 'rt-swiper-css' ) );
		$this->assertFalse( wp_script_is( 'rt-slideshow-main-script' ) );
		$this->assertFalse( wp_style_is( 'rt-slideshow-main-styles' ) );

		$rtslideshow->enqueue_front_end_scripts();

		$this->assertTrue( wp_script_is( 'rt-swiper-js' ) );
		$this->assertTrue( wp_style_is( 'rt-swiper-css' ) );
		$this->assertTrue( wp_script_is( 'rt-slideshow-main-script' ) );
		$this->assertTrue( wp_style_is( 'rt-slideshow-main-styles' ) );

		$rtslideshow->dequeue_front_end_scripts();

		$this->assertFalse( wp_script_is( 'rt-swiper-js' ) );
		$this->assertFalse( wp_style_is( 'rt-swiper-css' ) );
		$this->assertFalse( wp_script_is( 'rt-slideshow-main-script' ) );
		$this->assertFalse( wp_style_is( 'rt-slideshow-main-styles' ) );

	}

	/**
	 * Tests whether rtslideshow shortcode registered
	 *
	 * @see RTSlideshow::__construct
	 */
	public function test_shortcode_registered() {

		$rtslideshow = new RTSlideshow();
		$this->assertTrue( shortcode_exists( 'rtslideshow' ) );

	}

	/**
	 * Tests whether plugin settings page added
	 *
	 * @see RTSlideshow::add_admin_menu()
	 */
	public function test_rt_slideshow_menu_page_added() {

		$rtslideshow = new RTSlideshow();

		$admin_menu_hook = add_menu_page( 'RT Slideshow', 'RT Slideshow', 'manage_options', 'rt-slideshow', array( $rtslideshow, 'render_main_page' ), 'dashicons-images-alt2' );

		$this->assertTrue( $admin_menu_hook !== false );

		remove_menu_page( 'rt-slideshow' );

	}

	/**
	 * Tests whether rt_slideshow_image_ids option added
	 * during plugin activation
	 *
	 * @see RTSlideshow::activate()
	 */
	public function test_image_ids_option_registered() {

		$rtslideshow = new RTSlideshow();

		$this->assertFalse( get_option( 'rt_slideshow_image_ids' ) );

		$rtslideshow->activate();

		$this->assertTrue( get_option( 'rt_slideshow_image_ids' ) !== false );

	}

	/**
	 * Unit test for the register_settings() method of the RTSlideshow class.
	 *
	 * Ensures that the register_setting() function is called with the correct parameters.
	 *
	 * @since 1.0.0
	 */
	public function test_register_settings() {

		$rtslideshow = new RTSlideshow();

		// Call the register_settings() method to register the settings.
		$rtslideshow->register_settings();

		// Assert that the register_setting() function was called with the correct parameters.
		$this->assertSettingsRegistered( 'rt-slideshow-settings-group', 'rt_slideshow_image_ids' );

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
	 * @see RTSlideshow::render_main_page()
	 */
	public function test_render_main_page() {

		$rtslideshow = new RTSlideshow();

		// Set up current user as an administrator.
		$admin_user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user_id );

		// Render the main page.
		ob_start();
		$rtslideshow->render_main_page();
		$output = ob_get_clean();

		// Assert that output contains the expected text.
		$this->assertStringContainsString( '<form method="post" action="options.php">', $output );
		$this->assertStringContainsString( '<input type="hidden" name="rt_slideshow_image_ids" id="rt_slideshow_image_ids" value="" />', $output );
		$this->assertStringContainsString( '<ul id="rt_slideshow_image_list">', $output );
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

		ob_start();
		$rtslideshow->render_main_page();
		$output = ob_get_clean();

		// Assert that output does not contain the expected text since the user does not have 'manage_options' capability.
		$this->assertStringNotContainsString( '<form method="post" action="options.php">', $output );
		$this->assertStringNotContainsString( '<input type="hidden" name="rt_slideshow_image_ids" id="rt_slideshow_image_ids" value="" />', $output );
		$this->assertStringNotContainsString( '<ul id="rt_slideshow_image_list">', $output );
		$this->assertStringNotContainsString( '<input type="submit" class="button-primary" value="Save Changes">', $output );

		wp_delete_user( $admin_user_id );
		wp_delete_user( $subscriber_user_id );

	}

	/**
	 * Test if slider is rendered correctly.
	 *
	 * @since 1.0.0
	 * @see RTSlideshow::rt_slideshow_slider()
	 */
	public function test_render_slider_page() {

		$rtslideshow = new RTSlideshow();

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

		// Add the attachment IDs to the rt_slideshow_image_ids option
		update_option( 'rt_slideshow_image_ids', $attachment_id1 . ',' . $attachment_id2 );

		// Render the slider.
		$output = $rtslideshow->rt_slideshow_slider();

		// Assert that output contains the expected HTML.
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $attachment_id1, 'full' ) . '" loading="lazy">', $output );
		$this->assertStringContainsString( '<img src="' . wp_get_attachment_image_url( $attachment_id2, 'full' ) . '" loading="lazy">', $output );

		// Clean up
		wp_delete_attachment( $attachment_id1, true );
		wp_delete_attachment( $attachment_id2, true );
		// Delete the image IDs option.
		delete_option( 'rt_slideshow_image_ids' );
		wp_delete_user( $user_id );
	}

}
