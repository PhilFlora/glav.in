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
$page_content = '';
$errors = array();

// If any message has been set after a redirect
if ( isset($_SESSION['edit_msgs']) && !empty($_SESSION['edit_msgs']) ) {
	$msgs = $_SESSION['edit_msgs']; // assign it to the $msgs variable
	unset($_SESSION['edit_msgs']);
}

// Do we have a page to edit?
if ( isset( $_GET['passed'] ) && $_GET['passed'] != '' ) {
	
	$page = $_GET['passed'];

	// See if the page exists
	if ( !$data->file_exist( PAGES_DIR . $page ) ) {
		$errors[] = 'Page Not Found';
	} else {
		$content      = $data->get_content(PAGES_DIR . $page);
		$page_name    = $page;
		$page_title   = $content['page']['title'];
		$page_content = $content['page']['content'];
		$page_visible = $content['page']['visible'];
	}
} else {
	$errors[] = 'No Page Selected <a href="'. base_url() .'admin/pages" title="Pages">Return to Pages List</a>';
}

// The form has been submitted
if ( $_POST ) {

	// Validate the File Name
	if ($page_name != 'home') {
		// First check if the user provided a File Name
		if ( !isset($_POST['page_name']) || empty($_POST['page_name']) ){
			$errors[] = 'The Page URL cannot be blank';
		} else if ( !preg_match( '/^[A-Za-z0-9-\(\)_]+$/', $_POST['page_name']) ) { // Then make sure that it contains only valid characters
			$errors[] = 'The Page URL contains invalid characters'; 
		} else if ( $data->file_exist(PAGES_DIR . trim( $_POST['page_name'] ) ) && $_POST['page_name'] != $page_name ) { // Check to make sure there isn't already a page
			$errors[] = 'A page with this URL already exists'; // with this name. If so, send error.
		} else {
			$page_name = strtolower( $_POST['page_name'] );
		}
	}
	
	
	// Validate the Page Title
	if ( !isset( $_POST['page_title'] ) || empty( $_POST['page_title'] ) ) {
		$errors[] = 'Page Title cannot be blank';
	} else {
		$page_title = htmlentities( $_POST['page_title'], ENT_QUOTES, 'UTF-8' );
	}
	
	// Validate the Page Content
	if ( !isset( $_POST['page_content'] ) || empty ( $_POST['page_content'] ) ) {
		$errors[] = 'Page Content cannot be empty';
	} else {
		$page_content = $_POST['page_content'];
	}
	
	
	// Validate Page Visible
	if ( !isset($_POST['page_visible']) || empty ($_POST['page_visible']) || ( $_POST['page_visible'] != 'true' && $_POST['page_visible'] != 'false' ) ) {
		$page_visible = 'true';
	} else {
		$page_visible = $_POST['page_visible'];
	}
	
	$content = array(
		'page' => array(
			'name' => trim($page_name),
			'title' => trim($page_title),
			'content' => $page_content,
			'visible' => $page_visible
		)				
	);
	
	// If there's no errors update the page
	if ( empty( $errors ) ) {
		if ( $data->update_file( PAGES_DIR . $page, $content ) ) {
			$msgs[] = 'Page Updated. <a href="'. base_url() . $page .'" title="Pages">View Page</a> or <a href="'. base_url() .'admin/pages" title="Pages">Return to Pages List</a>';
			
			$_SESSION['edit_msgs'] = $msgs; // We keep the messages in a session variable
			header('Location: ' . $content['page']['name']);  // and then redirect
			die();
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
	if ( isset( $_GET['passed'] ) && $_GET['passed'] != '' ) {
	?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<input type="text" placeholder="Page Title" name="page_title"  value="<?php echo $page_title ? $page_title : ''; ?>" />
		<p>
			<strong>Page Address:</strong> <?php echo base_url(); ?>
			<?php
			if ( $page_name != 'home' ) { 
			?>
			<input type="text" placeholder="page_name" name="page_name" value="<?php echo $page ? $page : ''; ?>" />
			<?php
			}
			?>
		</p>
		<textarea name="page_content" placeholder="Page Content" id="page-content"><?php echo $page_content ? $page_content : ''; ?></textarea>
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