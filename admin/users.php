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
<a href="create_user" title="Create User" id="create-page-btn" class="btn">Create User</a>
<div class="list">
<?php
	
	// Get all of the users
	$users = $user->get_users();

	// List the rest of the pages
	foreach( $users as $user => $level ) {

		echo '<div class="list-item">';
			echo '<div class="list-item-text">' . $user . ' (' . $level['user_level_display'] . ')</div>';
			echo '<div class="list-item-actions">';
				echo '<nav>';
					echo '<ul>';
					// User can only edit users will a lower level
					if ( ( $user_level < $level['user_level_int'] ) || $user == $_SESSION['user_email'] || ( $user_level == 0 ) ) {							
						
						echo '<li><a href="edit_user/' . $user . '" title="Edit" class="icon icon-edit"><span class="hidden">Edit Page</span></a></li>';
						
						// You can't delete yourself
						if ( $user != $_SESSION['user_email'] ) {
							echo '<li><a href="delete_user/' . $user . '" title="Delete" class="icon icon-delete"><span class="hidden">Delete Page</span></a></li>';
						}

					}
					echo '</ul>';
				echo '</nav>';
			echo '</div>';
		echo '</div>';

	}
?>
</div>
<?php
} else {
?>
<div class="error">You cannot access this page</div>
<?php
}
?>