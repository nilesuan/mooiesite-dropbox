<?php

class MD_Userhooks_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'MD_Userhooks') );
	}

	function test_class_access() {
		$this->assertTrue( ()->userhooks instanceof MD_Userhooks );
	}
}
