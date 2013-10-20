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
$page_visible = 'true';
$created = false;
	
if ( $_POST ) {

	$page_name = isset( $_POST['page_name'] ) ? $_POST['page_name'] : '';
	$page_title = isset( $_POST['page_title'] ) ? $_POST['page_title'] : '';
	$page_description = isset( $_POST['page_description'] ) ? $_POST['page_description'] : '';
	$page_content = isset( $_POST['page_content'] ) ? $_POST['page_content'] : '';
	$page_visible = isset( $_POST['page_visible'] ) ? $_POST['page_visible'] : '';

	$p = array(
			'page_name'          => $page_name,
			'page_title'         => $page_title,
			'page_description'   => $page_description,
			'page_content'       => $page_content,
			'page_visible'       => $page_visible
		);

	$errors = $page->validate( $p, 'create' );	

	$p = $page->filter( $p );

	// If there's no errors create the page
	if ( empty( $errors ) ) {
		if ( $page->create( $p ) ) {
			$created = true;
			$msgs[] = 'Page Created! <a href="'. base_url() . $page_name .'" title="Pages">View Page</a> or <a href="'. base_url() .'admin/pages" title="Pages">Return to Pages List</a>';
		} else {
			$errors[] = 'Something went wrong. The page wasn\'t created.';
		}
	}
}
?>
<div id="page-description">
	<h1>Create Page</h1>
	<p>Fill out the form below to create a new page.</p>
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

	// Don't show form if the page has been created
	if ( !$created ) {
	?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<input type="text" placeholder="Page Title" name="page_title" value="<?php echo $page_title ? $page_title : ''; ?>" />
		<input type="text" placeholder="Description" name="page_description" value="<?php echo $page_description ? $page_description : ''; ?>" />
		<p>
			<strong>Page Address:</strong> <?php echo base_url(); ?><span id="create-uri">
			<input type="text" placeholder="page_name" name="page_name" value="<?php echo $page_name ? $page_name : ''; ?>" />
		</p>
		<textarea name="page_content" placeholder="Page Content" id="page-content"><?php echo $page_content ? $page_content : ''; ?></textarea>
		<p>
			Is this page visible to the public?
			<select name="page_visible">
				<option value="true">Yes</option>
				<option value="false">No</option>
			</select>
		</p>
		<input type="submit" value="Submit">
	</form>
	<?php
	}
	?>