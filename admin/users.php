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
?>
<div id="page-description">
	<h1>Users</h1>
	<p>Below is a list of all of your site's users. From here you are able to edit and delete existing users. To create a new user, click the button in the upper right.</p>
	<a href="create_user" title="Create User" id="create-page-btn" class="btn">Create User</a>
</div><!-- end page-description -->
<div id="admin-content-body">
	<ul id="admin-pages-list">
		<?php
			
			// Get all of the users
			$users = $user->get_users();

			// List the rest of the pages
			foreach( $users as $user ) {
				echo '<li>' . $user;
				echo ' <a href="edit_user/' . $user . '" class="action-btn">Edit</a>';

				// You can't delete yourself
				if ( $user != $_SESSION['user_email'] ) {
					echo ' <a href="delete_user/' . $user . '" class="action-btn">Delete</a>';
				}
				
				echo '</li>';
			}
		?>
	</ul>
<?php
} else {
?>
<div class="error">You cannot access this page</div>
<?php
}
?>