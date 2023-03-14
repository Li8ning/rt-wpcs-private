<?php
/**
 * Class RTSlideshow_Activate_Test
 *
 * @package RTSlideshow
 */

class RTSlideshow_Activate_Test extends WP_UnitTestCase {

	protected $rt_slideshow;

	public function setUp(): void {
		parent::setUp();
		$this->rt_slideshow = new RTSlideshow();
	}

	public function test_image_ids_option_registered() {
		$this->assertTrue( get_option( 'rt_slideshow_image_ids' ) !== false );
	}

	public function test_deactivation_hook_registered() {
		$this->assertTrue( has_action( 'deactivate_rt-slideshow/rt-slideshow.php', array( $this->rt_slideshow, 'deactivate' ) ) );
	}

	public function test_uninstall_hook_registered() {
		$this->assertTrue( has_action( 'uninstall_rt-slideshow/rt-slideshow.php', array( $this->rt_slideshow, 'uninstall' ) ) );
	}

	public function test_rewrite_rules_flushed() {
		global $wp_rewrite;
		$initial_structure = $wp_rewrite->permalink_structure;
		$this->rt_slideshow->activate();
		$this->assertNotEquals( $initial_structure, $wp_rewrite->permalink_structure );
	}
}
