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
 * @since		3.2.0-alpha
 */

// Only Admins can access this page
if ( $user_level == 1 ) {

	if ( $user != $_SESSION['user_email'] ) {
		$errors[] = 'You cannot delete yourself';
	}

	$page_name = '';
	$page_content = '';

	// Do we have a page to edit?
	if ( isset( $_GET['passed'] ) && $_GET['passed'] != '' ) {
		
		$user_passed = $_GET['passed'];

		// See if the page exists
		if ( !$user->exists( $user_passed ) ) {
			// It doesn't
			$errors[] = 'User Not Found';
		}
	} else {
		$errors[] = 'No User Selected';
	}

	// The form has been submitted
	if ( $_POST ) {

		// Are you sure?
		if ( $_POST['are_you_sure'] == 'Yes' && $user_passed != $_SESSION['user_email'] ) {
			
			// Delete Page
			$deleted = $user->delete( $user_passed );

			// Was the page deleted?
			if ( $deleted ) {
				// Yup...
				$msgs[] = 'User Deleted. <a href="'. base_url() .'admin/users" title="Users">Return to Users List</a>';
			} else {
				// Nope...
				$errors[] = 'User Not Deleted';
			}
		} else {
			// What do we say to the god of death?
			// Not today.
			$errors[] = 'User Not Deleted. <a href="'. base_url() .'admin/user" title="Users">Return to Users List</a>';
		}
	}
?>
<div id="page-description">
	<h1>Delete User</h1>
	<p>Deleting this user cannot be reversed. Once they're gone, they're gone. No return. No zombies. Gone. Seriously, make sure you want to do this.</p>
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

	// If there are no errors, continue...
	if ( empty( $msgs ) && empty( $errors ) ) {
	?>
	<p>Are you sure you want to delete <strong><?php echo $user_passed; ?></strong>?</p>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<input type="submit" name="are_you_sure" value="Yes">
		<input type="submit" name="are_you_sure" value="Nope">
	</form>
	<?php
	}
}
?>