<?php
/**
 * COUPLE NOTES:
 *
 * I (Matt) have had a hell of a time trying to get sessions to work
 * nicely with PHPUnit. I haven't had any luck. Anyone know how to fix
 * this problem?
 */

require_once( 'config.php' );
require_once( SYSTEM_DIR . 'bootstrap.php' );

class UserTest extends PHPUnit_Framework_TestCase {

	function __construct() {

		$password_options = array(
			'cost' => 11,
			'salt' => '45tygrfE#$%t6hgr4332@23r4t5y$$%G###',
		);

		$this->data = new Data();
		$this->validate = new Validation();
		$this->user = new User( $this->data, $this->validate, $password_options );

		$this->fakeuser = 'fakeuser@example.com';
		$this->fakepass = 'test';
	}
	
	/**
	 * @expectation create() returns true when user is created
	 */	
	public function testCreateUserReturnsTrue() {
			
			// Create a fake user for testing
			$user = $this->user->create(
				$this->fakeuser,
				$this->fakepass,
				1
			);

			$this->assertTrue( $user );
	}

	/**
	 * @expectation create() returns false when user exists
	 */	
	public function testCreateUserReturnsFalseWhenUserExists() {
			
			// Create a fake user for testing
			$user = $this->user->create(
				'fakeuser@example.com',
				'test',
				1
			);

			$this->assertFalse( $user );
	}	


    /**
     * @  runInSeparateProcess
     * @   preserveGlobalState disabled
     *
     * CANNOT GET THIS TO WORK. SOMEONE ELSE WANNA TAKE A CRACK AT IT?
     */
	// public function testValidateUserReturnsTrue() {
	// 	$this->assertTrue($this->user->validate($this->fakeuser, $this->fakepass));
	// }

	/**
	 * @expectation validate() returns false with incorrect values
	 */	
	public function testValidateUserReturnsFalseWithIncorrectValues() {
		$this->assertFalse( $this->user->validate( 'pickles@example.com', 'ilovepickles23' ) );
	}

	/**
	 * @expectation delete() returns true when user is deleted
	 */
	public function testDeleteUserReturnsTrue() {
		$this->assertTrue( $this->user->delete( $this->fakeuser ) );
	}
    
	/**
	 * @expectation delete() returns false when user doesn't exist
	 */
	public function testDeleteUserReturnsFalseWhenUserDoesntExist() {
		$this->assertFalse( $this->user->delete( $this->fakeuser ) );
	}

	/**
	 * @expectation exist() returns false when no user found
	 */
	public function testExistReturnsFalseWhenNoUserFound() {
	    $this->assertFalse( $this->user->exists( $this->fakeuser ) );
	}	
}