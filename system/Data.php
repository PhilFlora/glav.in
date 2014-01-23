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
 * @since		1.0.0-beta
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
	public function update_file( $file_name, $content=array(), $filetype = 'page' ) {

		if ( !$this->file_exist( $file_name ) ) {
			return false;
		} else {
			
			// Do the needed special actions based on the filetype
			if ( $filetype === 'page' ) {
				rename($file_name . '.json', PAGES_DIR . $content['page']['name'] . '.json');
				$file_name = PAGES_DIR . $content['page']['name'];
				unset($content['page']['name']);
				
				$content['page']['visible'] = $content['page']['visible'] == 'true' ? true : false; // making boolean
			}
			else if ( $filetype === 'user' || $filetype === 'setting' ) {
				// For now no special action is needed for the user/setting
			}
			else {
				return false;
			}
			
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

	/**
	 * Get file paths for all json data
	 *
	 * @return array file paths
	 */	
	public function get_data_filepaths() {
		
		// Get all data directories
		$dirs = array_filter(  glob( BASEPATH . '/data/*' ), 'is_dir' );
		
		// This holds each data directory and its files
		$data_dirs = array();

		// This is the merged files from each data directory
		$files = array();

		// Loop through each data directory and get its files
		foreach( $dirs as $dir ) {
			$data_dirs[] = glob( $dir . '/*.json' );
		}

		// Merge all the files into one array
		foreach( $data_dirs as $dir ) {
			foreach( $dir as $file ) {
				// $files[] = str_replace(BASEPATH, '', $file);
				$files[] = $file;
			}
		}

		return $files;

	}

	/**
	 * Export Data
	 *
	 * @return string url of zipped file
	 */
	public function export() {

		// Create a random file name for our zip file
		// This will be a public file with important data so we need to try
		// and make it hard to find & predict.
		$zip_name = md5( time() . 'glavinsupersecretfilename' . time() ) . '.zip';
		$zip_path = BASEPATH . '/data/' . $zip_name;
		$files    = $this->get_data_filepaths();
		$zip      = new ZipArchive();

		// Make we got what we need
		if( is_array( $files ) ) {

			// Make sure there's something there
			if(  count( $files ) ) {

				$zip->open( $zip_path, ZIPARCHIVE::CREATE );

				foreach( $files as $file ) {
					
					// Path to the file
					$file_path = $file;

					// Strip the full path, we just want the folder & filename
					$file_name = str_replace( BASEPATH, '', $file);

					$zip->addFile( $file_path, $file_name );
				}

				$zip->close();

				// Make sure file exists before sending
				if( file_exists( $zip_path ) ) {
					return base_url() . 'data/' . $zip_name;
				}

			} else {
				return false;
			}

		} else {
			return false;
		}


	}
}