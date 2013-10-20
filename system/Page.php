<?php
/**
 * Glav.in
 *
 * A very simple CMS
 *
 *
 * @package		Glav.in
 * @author		Matt Sparks
 * @copyright	Copyright (c) 2013, Matt Sparks (http://www.mattsparks.com)
 * @license		http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link		http://glav.in
 * @since		Version 1.0.0-alpha
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Page {

	/**
	 * Construct
	 */
	public function __construct( $data, $validate ) {
		$this->data = $data;
		$this->validate = $validate;
	}

	/**
	 * Contains the name of the latest page loaded with Page->load
	 * 
	 * @var string 
	 */
	private $current_page = '';

	/**
	 * Set current page
	 * 
	 * @param string $page attribute
	 */
	private function set_current_page( $page ) {
		$this->current_page = $page;
	}
	
	/**
	 * Get current page
	 * 
	 * @return string
	 */
	private function get_current_page() {
		return $this->current_page;
	}
	
	/**
	 * Get Pages
	 *
	 * @return	array of all pages with full path
	 */	
	public function get_pages() {
		
		$pages = array();

		foreach ( glob( PAGES_DIR . "*.json" ) as $page ) {
			$pages[] = $page;
		}		

		return $pages;
	}

	/**
	 * Pages List
	 *
	 * @param string optional id attribute
	 * @param string optional class_ul adds class name to <ul>
	 * @param string optional class_li adds class name to <li>
	 * @param string optional class_li_active adds class name to the current page <li>
	 * @return	html list of all pages
	 */	
	public function pages_list( $id = '', $class_ul = '', $class_li = '', $class_li_active = 'active' ) {
		
		$pages = $this->get_pages();
		
		// set ul/li attributes
		$id	  = $id	? ' id="' . $id . '"' : '';
		$class_ul = $class_ul ? ' class="' . $class_ul . '"' : '';
		
		// attribute li
		$li_attr_home = $class_li ? ' class="' . $class_li : '';
		if( $this->get_current_page() == 'home' ) {
			
			// only set class="$class_li_active" if not ''
			if( $li_attr_home ) {
				$li_attr_home .= $class_li_active ? ' ' . $class_li_active . '"' : '"';
			} else {
				$li_attr_home = $class_li_active ? ' class="' . $class_li_active . '"' : '';
			}
			
		} else {
			
			// Close class-tag if get_current_page != 'Home'
			$li_attr_home = $li_attr_home ? $li_attr_home . '"' : '';
			
		}

		// start building the list
		$list  = '<ul' . $id . $class_ul . '>';
		
		// Make homepage first.
		$list .= '<li' . $li_attr_home . '>';
		$list .= '<a href="' . base_url() . '">';
		$list .= 'Home</a></li>';
		
		foreach( $pages as $page ) {
			
			$page_name = basename( $page, '.json' );
			
			if ( $page_name != '404' && $page_name != 'home' ) {
				
				$content = $this->data->get_content( PAGES_DIR . $page_name );
				$page    = $content['page'];
				
				// attribute li
				$li_attr = $class_li ? ' class="' . $class_li : '';
				if( $this->get_current_page() == $page_name ) {
					
					// only set class="$class_li_active" if not ''
					if( $li_attr ) {
						$li_attr .= $class_li_active ? ' ' . $class_li_active . '"' : '"';
					} else {
						$li_attr = $class_li_active ? ' class="' . $class_li_active . '"' : '';
					}
					
				} else {
					
					// Close class-tag if get_current_page != $page_name
					$li_attr = $li_attr ? $li_attr . '"' : '';
					
				}

				// If the page is visible add it to the list.
				if ( true === $page['visible'] ) {
					$list .= '<li' . $li_attr . '>';
					$list .= '<a href="' . base_url() . $page_name . '">';
					$list .= ucwords( $content['page']['title'] );
					$list .= '</a></li>';
				}
			}
		}

		$list .= '</ul>';

		return $list;

	}	

	/**
	 * Load the page
	 *
	 * @param	string	the page name being requested
	 */
	public function load( $page ) {

		// If $page is empty, lets assume the index/home page has been requested.
		if ( ( $page == '' ) || ( $page == 'index.php' ) || ( $page == 'index.html' ) ) {
			$page = 'home';
		} elseif ( !$this->data->file_exist( PAGES_DIR . $page ) ) {
			// If the page can't be found load the 404 page. 
			$page = '404';
		}

		// Set current page
		$this->set_current_page($page);
			
		$content       = $this->data->get_content( PAGES_DIR . $page );
		$page          = $content['page'];
		$template      = $page['template'];
		$template_path = BASEPATH . '/template/' . $template . '.php';

		// If the page isn't visible, set a message.
		if ( false === $page['visible'] ) {
			$page['content'] = 'This page is currently unavailable.';
		}

		// Make sure template exists
		if ( file_exists( $template_path ) ) {
			include($template_path);
		} else {
			exit( '<strong>ERROR:</strong> Template "'.$template.'" not found.' );
		}
	}

	/**
	 * Validate a page
	 *
	 * @param	array containing all our page info and content
	 * @param   string sets mode for validation
	 * @return	array
	 */
	public function validate( $p, $mode = 'create' ) {
		
		$errors = array();

		// Array containing content that can be empty
		$can_be_empty = array( 'page_description' );

		// Check for empty content
		foreach ( $p as $property => $content ) {
			if( !in_array( $property, $can_be_empty ) ) {
				if ( ( $this->validate->is_empty( $content ) ) ) {
					$errors[] = ucwords( str_replace( '_', ' ', $property ) ). ' cannont be empty';
				}
			}
		}

		// Page name cannot contain special characters
		if ( $this->validate->has_special_characters( $p['page_name'] ) ) {
			$errors[] = 'Page Name contains invalid characters';
		}

		// Check to see if page exists only when in "create" mode
		if ( $mode == 'create' ) {
			if ( $this->data->file_exist( PAGES_DIR . $p['page_name'] ) ) {
				$errors[] = 'A page with this URL already exists';
			}
		}

		// Page Description cannot contain more than 160 characters
		if ( strlen( $p['page_description'] ) > 160 ) {
			$errors[] = 'Description cannot be more than 160 characters in length';
		}

		return $errors;
	}

	/**
	 * Filter Page Content
	 *
	 * @param	array containing all our page info and content
	 * @return	array
	 */
	public function filter( $p ) {	

		// Trim everything
		foreach( $p as $property => $content ) {
			$p[$property] = trim( $content );
		}

		// Make page name lowercase
		$p['page_name'] = strtolower( $p['page_name'] );

		// Convert characters to HTML
		$p['page_title'] = htmlentities( $p['page_title'], ENT_QUOTES, 'UTF-8' );
		$p['page_description'] = htmlentities( $p['page_description'], ENT_QUOTES, 'UTF-8' );

		return $p;

	}

	/**
	 * Create a page
	 *
	 * @param	array containing all our page info and content
	 * @return	bool
	 */
	public function create( $p ) {
		$page_name          = $p['page_name'];
		$page_title         = $p['page_title'];
		$page_description   = $p['page_description'];
		$page_content       = $p['page_content'];
		$page_visible       = $p['page_visible'] == 'true' ? true : false; // making boolean
		$page_created       = time();
		$page_file          = PAGES_DIR . str_replace(' ', '_', strtolower($page_name));

		$page = array(
				'page' => array(

						'title'          => $page_title,
						'description'    => $page_description,
						'content'        => $page_content,
						'created'        => $page_created,
						
						// For the time being "page" will be the only option.
						// Eventually users will be able to choose from other templates.
						'template' => 'page',

						'visible'  => $page_visible
					)
			);

		return $this->data->create_file( $page_file, $page );
	}
}
