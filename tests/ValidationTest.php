<?php

require_once( 'config.php' );
require_once( SYSTEM_DIR . 'Validation.php' );

class ValidationTest extends PHPUnit_Framework_TestCase {

	function __construct() {
		$this->validate = new Validation();
		$this->empty = '';
		$this->not_empty = "notempty";
		$this->email = 'test@example.com';
		$this->not_valid_email = 'totallynotspam!!@blam!@.com';
	}
	
	/**
	 * @expectation is_empty() returns true when input is empty
	 */
	public function testIsEmptyReturnsTrueWhenInputIsEmpty() {	    
	    $this->assertTrue( $this->validate->is_empty( $this->empty ) );
	}

	/**
	 * @expectation is_empty() returns false when input is not empty
	 */
	public function testIsEmptyReturnsTrueWhenInputIsNotEmpty() {	    
	    $this->assertFalse( $this->validate->is_empty( $this->not_empty ) );
	}

	/**
	 * @expectation has_special_characters returns true when input contains
	 * special characters.
	 */
	public function testHasSpecialCharactersReturnsTrueWithSpecialCharacters() {
		$this->assertEquals( 1, $this->validate->has_special_characters( '!!!!' ) );
	}

	/**
	 * @expectation has_special_characters returns false when input contains
	 * no special characters.
	 */
	public function testHasSpecialCharactersReturnsFalseWithoutSpecialCharacters() {
		$this->assertEquals( 0, $this->validate->has_special_characters( 'nothing special' ) );
	}

	/**
	 * @expectation is_valid_email() returns true with valid email
	 */
	public function testIsValidEmailReturnsTrueWithValidEmail() {
		$this->assertTrue( $this->validate->is_valid_email( $this->email ) );
	}

	/**
	 * @expectation is_valid_email() returns false with invalid email
	 */
	public function testIsValidEmailReturnsFalseWithInvalidEmail() {
		$this->assertFalse( $this->validate->is_valid_email( $this->not_valid_email ) );
	}	

}