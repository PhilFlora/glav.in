<?php
/**
* Glav.in
*
* A very simple CMS
*
*
* @package Glav.in
* @author Matt Sparks
* @copyright Copyright (c) 2013, Matt Sparks (http://www.mattsparks.com)
* @license http://opensource.org/licenses/MIT The MIT License (MIT)
* @link http://glav.in
* @since Version 1.0.0-alpha
*/

/*
 *---------------------------------------------------------------
 * GETTING THINGS SETUP
 *---------------------------------------------------------------
 */

$title = 'Install';

$errors      = array();
$msgs        = array();
$chmod_error = array();

$login_header = true;
$created      = false;

$server = '';
$chmod_error_message = '';

require_once( 'config.php' );
require_once( SYSTEM_DIR . 'bootstrap.php' );

/*
 *---------------------------------------------------------------
 * CHECK FOR USERS, IF FOUND STOP INSTALL
 *---------------------------------------------------------------
 */

$users = $user->get_users();

if( !empty( $users ) ) {
    exit( "ERROR: Users already exist." );
}

/*
 *---------------------------------------------------------------
 * DETERMINE SERVER SOFTWARE
 *---------------------------------------------------------------
 */

// What are we running on?
if( strpos( $_SERVER['SERVER_SOFTWARE'], 'Apache' ) !== FALSE ) {
    $server = 'apache';
} elseif ( strpos( $_SERVER['SERVER_SOFTWARE'], 'nginx' ) !== FALSE ) {
    $server = 'nginx';
}

if ( $server == 'apache' ) {
    // Make sure mod_rewrite is enabled. If it's not kill the script and alert the user.
    $die_message = 'ERROR: mod_rewrite is not enabled on this server. This must be enabled for Glav.in to run.';

    if ( function_exists( 'apache_get_modules' ) ) {
            
        if ( !in_array( 'mod_rewrite', apache_get_modules() ) ) {
            die( $die_message );
        }
            
    } else {

        $mod_rewrite = getenv( 'HTTP_MOD_REWRITE' ) == 'On' ? true : false ;

        if ( !$mod_rewrite ) {
            die( $die_message );
        }
            
    }
}

/*
 *---------------------------------------------------------------
 * DIRECTORY PERMISSIONS
 *---------------------------------------------------------------
 */

// Check data dir permissions
$data_permissions = array(
    'data/',
    'data/users',
    'data/pages',
    'data/settings',
);

foreach ( $data_permissions as $dir ) {
    if ( fileperms( $dir ) != 0777 ) {
        if( !chmod( $dir, 0777 ) ) {
            $chmod_error[] = $dir;
        }
    }
}

if( !empty( $chmod_error ) ) {
    $chmod_error_message = 'ERROR: Unable to change directory permissions for: ';
    
    foreach( $chmod_error as $dir ) {
        $chmod_error_message .= $dir . ', ';
    }

    die( $chmod_error_message );
}

/*
 *---------------------------------------------------------------
 * GENERATING PASSWORD SALT
 *---------------------------------------------------------------
 */

// Make sure a salt doesn't already exist
$settings  = json_decode( file_get_contents( SETTINGS_DIR . 'site.json' ), true );
$site_salt = $settings['site']['salt'];

// No salt, generate one
if( empty($site_salt) ) {

    $salt = generate_salt();

    if( $salt ) {

        $s = array(
            'site' => array(
                'salt' => $salt,
            )
        );

        if( !$data->update_file(SETTINGS_DIR . 'site', $s, 'setting') ) {
            die( "ERROR: Unable to save password salt." );
        }
    } else {
        die( "ERROR: Unable to generate password salt." );
    }

}

/*
 *---------------------------------------------------------------
 * VALIDATE USER INPUT, CREATE USER, DELETE INSTALL.PHP
 *---------------------------------------------------------------
 */
if ( $_POST ) {

    // Are fields empty?
    if ( ( $validate->is_empty( $_POST['admin_email_address'] ) ) || ( $validate->is_empty( $_POST['admin_password'] ) ) ) {
            $errors[] = "Fields Cannot Be Empty";
    }

    // Is this a valid email address?
    if ( !$validate->is_valid_email( $_POST['admin_email_address'] ) ) {
        $errors[] = "Please use a valid email address";
    }

    // If there are no errors, create the user.
    if ( empty( $errors ) ) {
        $email      = $_POST['admin_email_address'];
        $password   = $_POST['admin_password'];
        $user_level = 0; // The only owner will be the first user

        $created = $user->create( $email, $password, $user_level );

        // If the user was created, set message and delete the install file
        if ( $created ) {
            $msgs[] = 'User created! <a href="' . base_url() . 'admin/" title="Login">Go Login!</a>';
            unlink( realpath( __FILE__ ) );
        } else {
            $errors[] = 'User not created';
        }
    }
}

require_once( ADMIN_DIR . '/template/header.php' );
?>
<div id="login-content">
<?php
foreach( $msgs as $msg ) {
    echo '<div class="msg">' . $msg . '</div>';
}

foreach( $errors as $errors ) {
    echo '<div class="error">' . $errors . '</div>';
}

if ( !$created ) {
?>
<p>Thanks for installing Glav.in! Please create an admin account.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="email" name="admin_email_address" placeholder="Email Address" />
<input type="password" name="admin_password" placeholder="Password" />
<input type="submit" value="Submit" />
</form>
<?php
}

require_once( ADMIN_DIR . '/template/footer.php' );
?>