<?php

require_once( 'config.php' );
require_once( SYSTEM_DIR . 'bootstrap.php' );

class SettingsTest extends PHPUnit_Framework_TestCase {

	function __construct() {
		$this->data = new Data();
		$this->validate = new Validation();
		$this->settings = new Settings( $this->data, $this->validate );
	}
	
	/**
	 * @expectation get_pages() returns an array
	 */
	public function testGetSettingsReturnsArray() {	    
	    $this->assertTrue( is_array( $this->settings->get_settings( 'site' ) ) );
	}

	/**
	 * @expectation get_pages() returns an false when nothing passed
	 */
	public function testGetSettingsReturnsFalseWhenNothingPassed() {	    
	    $this->assertFalse( $this->settings->get_settings( '' ) );
	}

	/**
	 * @expectation get_pages() returns an false when file isn't found
	 */
	public function testGetSettingsReturnsFalseWhenFileIsntFound() {	    
	    $this->assertFalse( $this->settings->get_settings( 'thisisntreal' ) );
	}	

}