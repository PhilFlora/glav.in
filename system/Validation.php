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

class Validation {

	/**
	 * Checks if input is empty
	 *
	 * @param	string	input
	 * @return	bool
	 */
	public function is_empty( $input ) {
		return empty( $input );
	}

	/**
	 * Checks if input is contains special characters
	 *
	 * @param	string	input
	 * @return	bool
	 */
	public function has_special_characters( $input ) {
		return preg_match( '/[^a-zA-Z0-9\s]/', $input );
	}

	/**
	 * Checks if email address is valid
	 *
	 * @param	string	email being checked
	 * @return	bool
	 */
	public function is_valid_email( $email ) {
		if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return false;
		} else {
			return true;
		}
	}

}