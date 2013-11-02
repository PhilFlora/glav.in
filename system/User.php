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

require_once( SYSTEM_DIR . 'password.php' );

class User {

	/**
	 * Construct
	 */
	function __construct( $data, $validate, $password_options ) {
		$this->data = $data;
		$this->validate = $validate;
		$this->password_options = $password_options;
	}

	/**
	 * Create
	 *
	 * @param string user's email address
	 * @param string user's password
	 * @param string user's user level
	 * @return	bool
	 */	
	public function create( $email, $password, $user_level ) {

		// Check to see if the user already exists
		if ( $this->data->file_exist( USERS_DIR . $email ) ) {
			return false;
		} else {
			// User doesn't exist, populate array
			$user = array(
				'user' => array(
					'email' => $email,
					'password' => password_hash( $password, PASSWORD_BCRYPT, $this->password_options ),
					'user_level' => $user_level,
					'token'	=> ''
				)
			);

			// Create User
			return $this->data->create_file( USERS_DIR . $email, $user );

		}
	}

	/**
	 * Update
	 *
	 * @param string user's email address
	 * @param string user's password
	 * @param string user's user level
	 * @return	bool
	 */	
	public function update( $user, $u ) {

		// If a password is set, hash it
		if ( array_key_exists('password', $u['user']) ) {
			$u['user']['password'] = password_hash( $u['user']['password'], PASSWORD_BCRYPT, $this->password_options );
		}

		return $this->data->update_file( USERS_DIR . $user, $u, 'user' );

	}	

	/**
	 * Get Users
	 *
	 * @return	array of all users
	 */	
	public function get_users() {
		
		$users = array();

		foreach ( glob( USERS_DIR . "*.json" ) as $user ) {

			// Remove Path
			$user = str_replace( USERS_DIR, '', $user );

			// Remove extention
			$user = str_replace( '.json', '', $user );

			// Add User to Array
			$users[] = $user;
		}		

		return $users;
	}

	/**
	 * Validate a user
	 *
	 * @param	array containing all our user info and content
	 * @param   string sets mode for validation
	 * @return	array
	 */
	public function validateInput( $u, $mode = 'create' ) {
		
		$errors = array();

		// Array containing content that can be empty
		$can_be_empty = array( '' );

		// Check for empty content
		foreach ( $u as $property => $content ) {
			if( !in_array( $property, $can_be_empty ) ) {
				if ( ( $this->validate->is_empty( $content ) ) ) {
					$errors[] = ucwords( str_replace( '_', ' ', $property ) ). ' cannot be empty';
				}
			}
		}

		// Is this a valid email address
		if ( !$this->validate->is_valid_email( $u['user_email'] ) ) {
			$errors[] = 'Please enter a valid email address';
		}

		// Check to see if user exists only when in "create" mode
		if ( $mode == 'create' ) {
			if ( $this->data->file_exist( USERS_DIR . $u['user_email'] ) ) {
				$errors[] = 'A user with this email address already exists';
			}
		}

		return $errors;
	}

	/**
	 * Filter User Content
	 *
	 * @param	array containing all our users' info and content
	 * @return	array
	 */
	public function filter( $u ) {	

		// Trim everything
		foreach( $u as $property => $content ) {
			$u[$property] = trim( $content );
		}

		return $u;

	}	

	/**
	 * Delete
	 *
	 * @param string user's email address
	 * @return	bool
	 */
	 public function delete( $email ) {

	 	if ( $this->exists( $email ) ) {

	 		// Check to make sure that there will be at least
	 		// one user if this user is deleted. This shouldn't
	 		// happen since a user can't delete themselves, but
	 		// better safe that sorry.
	 		if ( ( count( $this->get_users() ) - 1 ) > 0 ) {
	 			// Make sure user isn't deleting themselves
	 			if ( $_SESSION['user_email'] != $email ) {
	 				return $this->data->delete_file( USERS_DIR . $email );			
	 			} else {
	 				return false;
	 			}
	 		} else {
	 			return false;
	 		}
	 		
	 	} else {
	 		return false;
	 	}

	 }		

	/**
	 * Exist
	 *
	 * @param string user's email address
	 * @return	bool
	 */	
	public function exists( $email ) {
		return $this->data->file_exist( USERS_DIR . $email );
	}

	/**
	 * Validate
	 *
	 * @param string user's email address
	 * @param string user's password
	 * @param string redirect url
	 * @return	bool
	 */	
	public function validate( $email, $password ) {

		// Make sure user exists
		if ( !$this->exists( $email ) ) {
			return false;
		} else {
			// Get Contents
			$user_data = $this->data->get_content( USERS_DIR . $email );
			$user = $user_data['user'];
			$pass = password_hash( $password, PASSWORD_DEFAULT, $this->password_options );

			// If they passed the correct password, start session
			if ( $user['password'] == $pass ) {
				$this->start_session( $user );
				return true;
			}
		}

	}

	/**
	 * Start Session
	 *
	 * @param array user's information
	 */	
	public function start_session( $user ) {

		if ( !isset( $_SESSION ) ) {
			// User confirmed. Start session.
			session_start();
		}
		// Load session info
		$_SESSION['user_email'] = $user['email'];
		$_SESSION['user_level'] = $user['user_level'];
		$_SESSION['time_logged_in'] = time();

		// Adding some randomization
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
			
	}

	/**
	 * Logout User
	 */	
	public function logout() {

		// Start session if needed
		if ( !isset( $_SESSION ) ) {
			session_start();
		}

		// Empty session array
		$_SESSION = array();

		// Destroy Session
		session_destroy();
	}	

	/**
	 * Is the user logged in?
	 *
	 * @param string page from which the method was called
	 * @return	bool
	 */	
	public function is_logged_in( $page='' ) {

		// Start session if needed
		if ( !isset( $_SESSION ) ) {
			session_start();
		}

		// See if there's a session
		if ( isset( $_SESSION['HTTP_USER_AGENT'] ) ) {
			if ( $_SESSION['HTTP_USER_AGENT'] != md5( $_SERVER['HTTP_USER_AGENT'] ) ) {
				return false;
			} else {
				return true;	
			}			
		} 
	}
}