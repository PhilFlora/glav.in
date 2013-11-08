<?php

require_once( 'config.php' );
require_once( SYSTEM_DIR . 'bootstrap.php' );

class PageTest extends PHPUnit_Framework_TestCase {

	function __construct() {
		$this->data = new Data();
		$this->validate = new Validation();
		$this->settings = new Settings( $this->data, $this->validate );
		$this->page = new Page( $this->data, $this->validate, $this->settings );

		$this->p = array(
				'page_name'          => 'test',
				'page_title'         => 'Test',
				'page_description'   => 'Test',
				'page_content'       => 'Test',
				'page_layout'        => 'page',
				'page_visible'       => true,
			);

		$this->empty = array(
				'page_name'          => '',
				'page_title'         => '',
				'page_description'   => '',
				'page_content'       => '',
				'page_layout'        => '',
				'page_visible'       => '',
			);
	}
	
	/**
	 * @expectation get_pages() returns an array
	 */
	public function testGetPagesReturnsArray() {	    
	    $this->assertTrue( is_array( $this->page->get_pages() ) );
	}

	/**
	 * @expectation get_pages() returns a non-empty array
	 */
	public function testGetPagesNotEmpty() {
		$this->assertNotEmpty( $this->page->get_pages() );
	}

	/**
	 * @expectation get_layouts() returns an array
	 */
	public function testGetLayoutsReturnsArray() {	    
	    $this->assertTrue( is_array( $this->page->get_layouts() ) );
	}

	/**
	 * @expectation get_layouts() returns a non-empty array
	 */
	public function testGetLayoutsNotEmpty() {
		$this->assertNotEmpty( $this->page->get_layouts() );
	}	

	/**
	 * @expectation pages_list() returns a string
	 */
	public function testPagesListReturnsString() {
		$this->assertTrue( is_string( $this->page->pages_list() ) );
	}

	/**
	 * @expectation pages_list() returns a non-empty string
	 */
	public function testPagesListNotEmpty() {
		$this->assertNotEmpty( $this->page->pages_list() );
	}

	/**
	 * @expectation validate() returns array
	 */
	public function testValidateReturnsArray() {
		$this->assertTrue( is_array( $this->page->validate( $this->p ) ) );
	}

	/**
	 * @expectation validate() returns empty array when there's no errors
	 */
	public function testValidateReturnsEmptyArray() {
		$this->assertEmpty( $this->page->validate( $this->p ) );
	}

	/**
	 * @expectation validate() returns array containing errors
	 */
	public function testValidateReturnsNonEmptyArray() {
		$this->assertNotEmpty( $this->page->validate( $this->empty ) );
	}	

	/**
	 * @expectation filter() returns array
	 */
	public function testFilterReturnsArray() {
		$this->assertTrue( is_array( $this->page->filter( $this->p ) ) );
	}

	/**
	 * @expectation filter() trims whitespace
	 */
	public function testFilterTrimsWhitespace() {
		$this->spaces = array(
				'page_name'          => ' test',
				'page_title'         => 'Test ',
				'page_description'   => ' Test',
				'page_content'       => 'Test ',
				'page_layout'        => ' page',
				'page_visible'       => true,
			);

		$filtered = $this->page->filter( $this->spaces );

		$this->assertEquals( 0, preg_match( '/\s/', $filtered['page_name'] ) );
	}

	/**
	 * @expectation filter() makes page_name lowercase
	 */
	public function testFilterMakesPageNameLowercase() {
		$this->upper = array(
				'page_name'          => 'TEST',
				'page_title'         => 'Test',
				'page_description'   => 'Test',
				'page_content'       => 'Test',
				'page_layout'        => 'page',
				'page_visible'       => true,
			);

		$filtered = $this->page->filter( $this->upper );

		$this->assertEquals( 0, preg_match( '/[A-Z]/', $filtered['page_name'] ) );
	}

	/**
	 * @expectation filter() converts HTML
	 */
	public function testFilterConvertsHTML() {
		$this->html = array(
				'page_name'          => 'TEST',
				'page_title'         => 'Test',
				'page_description'   => '&',
				'page_content'       => 'Test',
				'page_layout'        => 'page',
				'page_visible'       => true,
			);

		$filtered = $this->page->filter( $this->html );

		$this->assertEquals( 1, preg_match( '/&amp;/', $filtered['page_description'] ) );
	}	

	/**
	 * @expectation create() returns true
	 */
    public function testCreateReturnsTrue() {
		$this->assertTrue( $this->page->create( $this->p ) );
    }

	/**
	 * @expectation create() returns false when nothing is passed
	 */
    public function testCreateReturnsFalseWhenNothingIsPassed() {
		$this->assertFalse( $this->page->create( '' ) );
    }    

	/**
	 * @expectation delete() returns true
	 */
    public function testDeleteReturnsTrue() {
		$this->assertTrue( $this->page->delete( $this->p['page_name'] ) );
    }  

	/**
	 * @expectation delete() returns false when page isn't found
	 */
    public function testDeleteReturnsFalse() {
		$this->assertFalse( $this->page->delete( 'doesntexist' ) );
    }  

	/**
	 * load()
	 */
    public function testLoad() {
    	// haven't figured out the best way to test this
    }   

}