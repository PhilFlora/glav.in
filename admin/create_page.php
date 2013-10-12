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
$page_visible = 'true';
	
if($_POST) {
	
	// Validate the File Name
	if ( !isset($_POST['page_name']) || empty($_POST['page_name']) ) // First check if the user provided a File Name
	{
		$errors[] = 'The Page URL cannot be blank';
	}
	else if ( !preg_match( '/^[A-Za-z0-9-\(\)_]+$/', $_POST['page_name']) ) // Then make sure that it contains only valid characters
	{
		$errors[] = 'The Page URL contains invalid characters'; 
	}
	else if( $data->file_exist(PAGES_DIR . trim( $_POST['page_name'] ) ) ) // Check to make sure there isn't already a page
	{
		$errors[] = 'A page with this URL already exists'; // with this name. If so, send error.
	}
	else
	{
		$page_name = strtolower( $_POST['page_name'] );
	}
	
	
	// Validate the Page Title
	if ( !isset($_POST['page_title']) || empty($_POST['page_title']) )
	{
		$errors[] = 'Page Title cannot be blank';
	}
	else
	{
		$page_title = htmlentities( $_POST['page_title'], ENT_QUOTES, 'UTF-8' );
	}

	
	// Validate the Page Content
	if ( !isset($_POST['page_content']) || empty ($_POST['page_content']) )
	{
		$errors[] = 'Page Content cannot be empty';
	}
	else 
	{
		$page_content = $_POST['page_content'];
	}

	
	// Validate Page Visible
	if ( !isset($_POST['page_visible']) || empty ($_POST['page_visible']) || ( $_POST['page_visible'] != 'true' && $_POST['page_visible'] != 'false' ) ) {
		$page_visible = 'true';
	}
	else {
		$page_visible = $_POST['page_visible'];
	}
	
	
	$p = array(
			'page_name'    => $page_name,
			'page_title'   => $page_title,
			'page_content' => $page_content,
			'page_visible' => $page_visible
		);

		
	// If there's no errors create the page
	if(empty($errors))
	{
		if($page->create($p)) 
		{
			$msgs[] = 'Page Created. <a href="'. base_url() .'admin/pages" title="Pages">Return to Pages List</a>';
		} 
		else 
		{
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
	foreach($msgs as $msg)
	{
		echo '<div class="msg">' . $msg . '</div>';
	}

	foreach($errors as $errors)
	{
		echo '<div class="error">' . $errors . '</div>';
	}
	?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<input type="text" placeholder="Page Title" name="page_title" value="<?php echo $page_title ? $page_title : ''; ?>" />
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