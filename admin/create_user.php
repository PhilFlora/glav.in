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
 * @since		4.0.0-alpha
 */

// Only Owner/Admins can access this page
if ( $user_level < 2 ) {

	// Setting Variables
	$user_email = '';
	$user_password = '';
	$user_level = 2;
	$created = false;
		
	if ( $_POST ) {

		$user_email    = isset( $_POST['user_email'] ) ? $_POST['user_email'] : '';
		$user_password = isset( $_POST['user_password'] ) ? $_POST['user_password'] : '';
		$user_level    = isset( $_POST['user_level'] ) ? $_POST['user_level'] : '';

		// Make sure someone isn't trying to make themself the owner
		if ( $user_level <= 0 ) {
			$errors[] = 'Nice Try. There can be only one owner.';
			$user_level = 2;
		} 

		// Create User Array
		$u = array(
				'user_email'    => $user_email,
				'user_password' => $user_password,
				'user_level'    => $user_level,
			);

		// Validate Input
		$errors = $user->validateInput( $u, 'create' );	

		// Filter Input
		$u = $user->filter( $u );

		// If there's no errors create the user
		if ( empty( $errors ) ) {
			if ( $user->create( $u['user_email'], $u['user_password'], $u['user_level'] ) ) {
				$created = true;
				$msgs[] = 'User Created! <a href="'. base_url() .'admin/users" title="Users">Return to Users List</a>';
			} else {
				$errors[] = 'Something went wrong. The user wasn\'t created.';
			}
		}
	}
?>
<div id="page-description">
	<h1>Create User</h1>
	<p>Fill out the form below to create a new user.</p>
</div><!-- end page-description -->
<div id="admin-content-body">
	<?php
	// Print out any messages or errors
	foreach( $msgs as $msg ) {
		echo '<div class="msg">' . $msg . '</div>';
	}

	foreach( $errors as $errors ) {
		echo '<div class="error">' . $errors . '</div>';
	}

	// Don't show form if the user has been created
	if ( !$created ) {
	?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<input type="email" placeholder="Email Address" name="user_email" value="<?php echo $user_email ? $user_email : ''; ?>" />
		<input type="password" placeholder="Password" name="user_password" value="<?php echo $user_password ? $user_password : ''; ?>" />
		<p>
			User Level
			<select name="user_level">
				<option value="2">Contributor</option>
				<option value="1">Admin</option>
			</select>
		</p>
		<input type="submit" value="Submit">
	</form>
	<?php
	}
}
?>