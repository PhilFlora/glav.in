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

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings {

	/**
	 * Construct
	 */
	public function __construct( $data, $validate ) {
		$this->data = $data;
		$this->validate = $validate;
	}

	/**
	 * Get Settings
	 *
	 * @param	string	the settings name being requested
	 */
	public function get_settings( $setting ) {

		if ( $setting ) {

			$setting = SETTINGS_DIR . $setting;

			// Does file exist?
			if ( $this->data->file_exist( $setting ) ) {
				return $this->data->get_content( $setting );
			} else {
				return false;
			}
		} else {
			return false;
		}

	}

}