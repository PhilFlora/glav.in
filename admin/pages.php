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
?>
	<div class="form-element">
		<a href="create_page" title="Create Page" id="create-page-btn" class="btn">Create Page</a>
	</div>
	<div id="pages-list" class="list">
		<?php
			
			// Get all of the pages
			$pages = $page->get_pages();

			// Put home first
			echo '<div class="list-item">';
				echo '<div class="list-item-text">Home</div>';
				echo '<div class="list-item-actions">';
					echo '<nav>';
						echo '<ul>';
							echo '<li><a href="edit_page/home" title="Edit" class="icon icon-edit"><span class="hidden">Edit Page</span></a></li>';							
						echo '</ul>';
					echo '</nav>';
				echo '</div>';
			echo '</div>';

			// List the rest of the pages
			foreach( $pages as $page ) {
				$page_name = basename( $page, '.json' );

				if ( $page_name != '404' && $page_name != 'home' ) {

					echo '<div class="list-item">';
						echo '<div class="list-item-text">' . str_replace('_', ' ', ucfirst($page_name)) . '</div>';
						echo '<div class="list-item-actions">';
							echo '<nav>';
								echo '<ul>';
									echo '<li><a href="edit_page/' . $page_name . '" title="Edit" class="icon icon-edit"><span class="hidden">Edit Page</span></a></li>';
									echo '<li><a href="delete_page/' . $page_name . '" title="Delete" class="icon icon-delete"><span class="hidden">Delete Page</span></a></li>';
								echo '</ul>';
							echo '</nav>';
						echo '</div>';
					echo '</div>';

				}
			}
		?>
	</div>