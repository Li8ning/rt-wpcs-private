<?php
/**
 * Class Test_rtslideshow
 *
 * @package RTSlideshow
 */

 use RTSlideshow\RTSlideshow;

class Test_rtslideshow extends WP_UnitTestCase {
	
	public function test_construct() {

		$rtslidehsow = new RTSlideshow();
		$this->assertEquals( 'rt-slideshow', $rtslidehsow->plugin_name );

	}

}
