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
 * @since		1.0.0-alpha
 */

$page_name = '';
$page_title = '';
$page_description = '';
$page_content = '';
$errors = array();

// If any message has been set after a redirect
if ( isset($_SESSION['edit_msgs']) && !empty($_SESSION['edit_msgs']) ) {
	$msgs = $_SESSION['edit_msgs']; // assign it to the $msgs variable
	unset($_SESSION['edit_msgs']);
}

// Do we have a page to edit?
if ( isset( $_GET['passed'] ) && $_GET['passed'] != '' ) {
	
	$page_passed = $_GET['passed'];

	// See if the page exists
	if ( !$data->file_exist( PAGES_DIR . $page_passed ) ) {
		$errors[] = 'Page Not Found';
	} else {
		$content          = $data->get_content( PAGES_DIR . $page_passed );
		$page_name        = $page_passed;
		$page_title       = $content['page']['title'];
		$page_description = $content['page']['description'];
		$page_content     = $content['page']['content'];
		$page_layout      = $content['page']['layout'];
		$page_visible     = $content['page']['visible'];
	}
} else {
	$errors[] = 'No Page Selected <a href="'. base_url() .'admin/pages" title="Pages">Return to Pages List</a>';
}

// The form has been submitted
if ( $_POST ) {

	// Edited Variables
	$e_page_name = isset( $_POST['page_name'] ) ? $_POST['page_name'] : '';
	$e_page_title = isset( $_POST['page_title'] ) ? $_POST['page_title'] : '';
	$e_page_description = isset( $_POST['page_description'] ) ? $_POST['page_description'] : '';
	$e_page_content = isset( $_POST['page_content'] ) ? $_POST['page_content'] : '';
	$e_page_layout = isset( $_POST['page_layout'] ) ? $_POST['page_layout'] : '';
	$e_page_visible = isset( $_POST['page_visible'] ) ? $_POST['page_visible'] : '';

	$p = array(
			'page_name'          => $e_page_name,
			'page_title'         => $e_page_title,
			'page_description'   => $e_page_description,
			'page_content'       => $e_page_content,
			'page_layout'        => $e_page_layout, 
			'page_visible'       => $e_page_visible
		);

	$errors = $page->validate( $p, 'edit' );	

	// Page name cannot be set to home or 404 when editing another page
	if ( ( ( $e_page_name == 'home' ) && ( $page_passed != 'home' ) ) || ( $e_page_name == '404' ) && ( $page_passed != '404' ) ) {
		$errors[] = 'Invalid Page Name';
	}

	// Home & 404 cannot have their page names changed
	if ( ( ( $page_passed == 'home' ) && ( $e_page_name != 'home' ) ) || ( ( $page_passed == '404' ) && ( $e_page_name != '404' ) ) ) {
		$errors[] = 'Page Name cannot be changed.';
	}

	// Existing page cannot overwrite another existing page
	if ( $page_passed != $e_page_name ) {
		if ( $data->file_exist( PAGES_DIR . $e_page_name ) ) {
			$errors[] = 'A page with this URL already exists';
		}
	}

	$p = $page->filter( $p );	
	
	$content = array(
		'page' => array(
			'name' => $p['page_name'],
			'title' => $p['page_title'],
			'description' => $p['page_description'],
			'content' => $p['page_content'],
			'layout' => $p['page_layout'],
			'visible' => $p['page_visible']
		)
	);
	
	// If there's no errors update the page
	if ( empty( $errors ) ) {
		if ( $data->update_file( PAGES_DIR . $page_passed, $content, 'page' ) ) {
			$msgs[] = 'Page Updated. <a href="'. base_url() . $content['page']['name'] .'" title="Pages">View Page</a> or <a href="'. base_url() .'admin/pages" title="Pages">Return to Pages List</a>';
			
			$_SESSION['edit_msgs'] = $msgs; // We keep the messages in a session variable
			
			/* Replace temporarily the header redirect with a Javascript redirect
			header('Location: ' . $content['page']['name']);  // and then redirect
			die();
			*/
			
			echo '<script type="text/javascript">window.location.replace("' . $content['page']['name'] . '");</script>'; //and then we redirect
		}
	}
}		
?>
<div id="page-description">
	<h1>Edit Page</h1>
	<p>Edit your existing page below.</p>
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

	// Don't show the form if a page hasn't been passed.
	// It looks wacky.
	if ( isset( $_GET['passed'] ) && $_GET['passed'] != ''  && $data->file_exist(PAGES_DIR . $page_passed) ) {
	?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<input type="text" placeholder="Page Title" name="page_title"  value="<?php echo $page_title ? $page_title : ''; ?>" />
		<input type="text" placeholder="Description" name="page_description"  value="<?php echo $page_description ? $page_description : ''; ?>" />
		<p>
			<strong>Page Address:</strong> <?php echo base_url(); ?>
			<?php
			if ( ( $page_passed != 'home' ) && ( $page_passed != '404' ) ) { 
			?>
			<input type="text" placeholder="page_name" name="page_name" value="<?php echo $page_passed ? $page_passed : ''; ?>" />
			<?php
			} else {
			?>
			<input type="hidden" placeholder="page_name" name="page_name" value="<?php echo $page_passed ? $page_passed : ''; ?>" />
			<?php
			}
			?>
		</p>
		<textarea name="page_content" placeholder="Page Content" id="page-content"><?php echo $page_content ? $page_content : ''; ?></textarea>
		<p>
			Layout
			<select name="page_layout">
				<?php
					$layouts = $page->get_layouts();

					foreach( $layouts as $layout ) {
						echo '<option value="'.$layout.'"';
						echo $page_layout == $layout ? ' selected="true"' : '';
						echo '>';
						echo $layout;
						echo '</option>';
					}
				?>
			</select>
		</p>		
		<p>
			Is this page visible to the public?
			<select name="page_visible">
				<option value="true"<?php echo $page_visible ? ' selected="true"' : ''; ?>>Yes</option>
				<option value="false"<?php echo $page_visible ? '' : ' selected="true"'; ?>>No</option>
			</select>
		</p>	
		<input type="submit" value="Submit">
	</form>
	<?php
	}
	?>