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

/*
 *---------------------------------------------------------------
 * DEFINE CONSTANTS
 *---------------------------------------------------------------
 */
	define( "BASEPATH",     __DIR__ );
	define( "SYSTEM_DIR",   BASEPATH . '/system/' );
	define( "ADMIN_DIR",    BASEPATH . '/admin/' );
	define( "PAGES_DIR",    BASEPATH . '/data/pages/' );
	define( "SETTINGS_DIR", BASEPATH . '/data/settings/' );
	define( "USERS_DIR",    BASEPATH . '/data/users/' );

/*
 *---------------------------------------------------------------
 * PASSWORD SALT
 *---------------------------------------------------------------
 *
 * Set password salt. This is generated on execution of
 * install.php
 */
	$settings = json_decode( file_get_contents( SETTINGS_DIR . 'site.json' ), true );
	$salt = $settings['site']['salt'];

	$password_options = array(
		'cost' => 11,
		'salt' => $salt,
	);

?>