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

}
