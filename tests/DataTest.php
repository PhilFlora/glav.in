<?php

require_once( 'config.php' );
require_once( SYSTEM_DIR . 'bootstrap.php' );

class DataTest extends PHPUnit_Framework_TestCase {

	function __construct() {
		$this->data = new Data();

		// Setup Fake Data
		$this->filename = PAGES_DIR . 'test';

		$this->p = array(
			'page' => array(
				'name'    => 'test',
				'title'   => 'Test Page',
				'content' => '<p>Test</p>',
				'visible' => true,
			),
		);

		$this->nofile = PAGES_DIR . 'pickles';
	}
	
	/**
	 * @expectation create_file() returns true
	 */
	public function testCreateFileReturnsTrue() {
	    $this->assertTrue( $this->data->create_file( $this->filename, $this->p ) );
	}

	/**
	 * @expectation update_file() returns true
	 */
	public function testUpdateFileReturnsTrue() {
	    $this->assertTrue( $this->data->update_file( $this->filename, $this->p ) );
	}

	/**
	 * @expectation update_file() returns false
	 */
	public function testUpdateFileReturnsFalse() {
	    $this->assertFalse( $this->data->update_file( $this->nofile, $this->p ) );
	}	

	/**
	 * @expectation file_exist() returns true
	 */
	public function testFileExisteturnsTrue() {
	    $this->assertTrue( $this->data->file_exist( $this->filename ) );
	}

	/**
	 * @expectation file_exist() returns false
	 */
	public function testFileExisteturnsFalse() {
	    $this->assertFalse( $this->data->file_exist( $this->nofile ) );
	}

	/**
	 * @expectation get_content() returns array when data is found
	 */
	public function testGetContentReturnsArrayWhenDataIsFound() {
	    $this->assertTrue( is_array( $this->data->get_content( $this->filename ) ) );
	}

	/**
	 * @expectation get_content() returns false when no data is found
	 */
	public function testGetContentReturnsFalseWhenNoDataIsFound() {
	    $this->assertFalse( $this->data->get_content( $this->nofile ) );
	}	

	/**
	 * @expectation delete_file() returns true
	 */
	public function testDeleteFileReturnsTrue() {
	    $this->assertTrue( $this->data->delete_file( $this->filename ) );
	}

	/**
	 * @expectation delete_file() returns false when no file is found
	 */
	public function testDeleteFileReturnsFalseWhenNoFileIsFound() {
	    $this->assertFalse( $this->data->delete_file( $this->filename ) );
	} 

}