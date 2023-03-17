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
	 */
	public function test_render_main_page() {

		$rtslideshow = new RTSlideshow();

		// Set up current user as an administrator.
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );

		// Render the main page.
		ob_start();
		$rtslideshow->render_main_page();
		$output = ob_get_clean();

		// Assert that output contains the expected text.
		$this->assertStringContainsString( '<form method="post" action="options.php">', $output );
		$this->assertStringContainsString( '<input type="hidden" name="rt_slideshow_image_ids" id="rt_slideshow_image_ids" value="" />', $output );
		$this->assertStringContainsString( '<ul id="rt_slideshow_image_list">', $output );
		$this->assertStringContainsString( '<input type="submit" class="button-primary" value="Save Changes">', $output );
	}

}
