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

//defined('BASEPATH') OR exit('No direct script access allowed');

class Data {

	/**
	 * Checks if a json file exists 
	 *
	 * @param	string	the page name being requested
	 * @return	bool
	 */
	public function file_exist( $file ) {
		return file_exists( $file . '.json' );
	}

	/**
	 * Get json content
	 *
	 * @param	string	the file being requested
	 * @return	array
	 */
	public function get_content( $file ) {
		
		if ( !$this->file_exist( $file ) ) {
			return false;
		} else {
			$json = file_get_contents( $file . '.json' );

			return json_decode( $json, true );
		}

	}

	/**
	 * Create json file
	 *
	 * @param	string	the page name being requested
	 * @return	bool
	 */	
	public function create_file( $file_name, $content ) {

		$fp = fopen( $file_name . '.json', 'w' );

		fwrite( $fp, json_encode( $content ) );
		fclose( $fp );

		return true;

	}

	/**
	 * Update json file
	 *
	 * @param strin file name
	 * @param array page content
	 * @return	bool
	 */	
	public function update_file( $file_name, $content=array() ) {

		if( !$this->file_exist( $file_name ) ) {
			return false;
		} else {
			rename($file_name . '.json', PAGES_DIR . $content['page']['name'] . '.json');
			$file_name = PAGES_DIR . $content['page']['name'];
			unset($content['page']['name']);
			
			$content['page']['visible'] = $content['page']['visible'] == 'true' ? true : false; // making boolean
			
			// Get current file contents
			$temp = $this->get_content( $file_name );

			// New Content
			$new = array_replace_recursive( $temp, $content );

			$fp = fopen( $file_name . '.json', 'w' );

			fwrite( $fp, json_encode( $new ) );
			fclose( $fp );

			return true;			
		}

	}

	/**
	 * Delete json file
	 *
	 * @param string file name
	 * @return	bool
	 */	
	public function delete_file( $file_name ) {

		$file = $file_name . '.json';

		if ( $this->file_exist( $file_name ) ) {
			return unlink( $file );			
		} else {
			return false;
		}

	}
}