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

/*
 *---------------------------------------------------------------
 * LET'S GO ADMIN STYLE!
 *---------------------------------------------------------------
 *
 * Load the configuration file and the bootstrap file. As well as
 * all the other tools and helpers we'll need.
 */
	require_once( '../config.php' );
	require_once( SYSTEM_DIR . 'bootstrap.php' );

	$errors = array();
	$msgs   = array();
	$title  = 'Admin Panel';

	$is_logged_in = $user->is_logged_in() ? true : false;	

/*
 *---------------------------------------------------------------
 * IS THE USER LOGGED IN?
 *---------------------------------------------------------------
 *
 * Check to see if the user is logged in. If not, ask them to.
 * After that, figure out where to send them.
 */
	if ( isset( $_GET['page'] ) ) {
		
		// For some setups, the $_GET['page'] variable is appending
		// '.php'. If it's there, remove it.
		$requested_page = str_replace('.php', '', $_GET['page']);

		switch( $requested_page ) {
			case 'login':
				$title  = 'Login';
				break;
			case 'logout':
				$title  = 'Logout';
				break;
			case 'reset_password':
				$title  = 'Reset Password';
				break;
			case 'pages':
				$title  = 'Pages';
				break;
			case 'edit_page':
				$title  = 'Edit Page';
				break;
			case 'delete_page':
				$title  = 'Delete Page';
				break;
			case 'create_page':
				$title  = 'Create Page';
				break;
			case 'users':
				$title  = 'Users';
				break;				
			case 'edit_user':
				$title  = 'Edit User';
				break;		
			case 'create_user':
				$title  = 'Create User';
				break;		
			case 'delete_user':
				$title  = 'Delete User';
				break;
			case 'settings':
				$title  = 'Site Settings';
				break;				
			default:
				$title = 'Admin Panel';
		}
		
		if ( $requested_page != 'login' && $requested_page != 'reset_password' ) {
			if ( $user->is_logged_in() ) {

				// Set User Level (int)
				$user_level = $_SESSION['user_level'];			

				if ( $requested_page == '' || $requested_page == 'index' ) {
					$include = 'pages.php';
				} else {
					$include = $requested_page . '.php';
				}
			} else {
				$title = 'Login';
				$include = 'login.php';
			}
		} else {
			$include = $requested_page . '.php';	
		}
		
		require_once( ADMIN_DIR . '/template/header.php' );
		
		if( file_exists($include) ) {
			include( $include );
		} else {
			echo '<h2>Page Not Found</h2>';
		}
		
		require_once( ADMIN_DIR . '/template/footer.php' );
	}
	else {
		die('Error! Page has not been specified');
	}
?>