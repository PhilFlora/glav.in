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
 * @since		3.0.0-alpha
 */

if ( !$data->file_exist( SETTINGS_DIR . 'site' ) ) {
	$errors[] = 'Site Settings Not Found';
} else {
	$content             = $data->get_content( SETTINGS_DIR . 'site' );
	$site_name           = $content['site']['site_name'];
	$site_tagline        = $content['site']['site_tagline'];
	$display_page_title  = $content['site']['display_page_title'];
	$default_page_layout = $content['site']['default_page_layout'];
}

if ( $_POST ) {

	$site_name           = isset( $_POST['site_name'] ) ? $_POST['site_name'] : '';
	$site_tagline        = isset( $_POST['site_tagline'] ) ? $_POST['site_tagline'] : '';
	$display_page_title  = isset( $_POST['display_page_title'] ) ? $_POST['display_page_title'] : '';
	$default_page_layout = isset( $_POST['default_page_layout'] ) ? $_POST['default_page_layout'] : '';

	// Get Array ready
	$s = array(
		'site' => array(
			'site_name' => $site_name,
			'site_tagline' => $site_tagline,
			'display_page_title' => $display_page_title,
			'default_page_layout' => $default_page_layout,
		)
	);

	if ( $display_page_title == '' || $default_page_layout == '' ) {
		$errors[] = 'Fields cannot be empty';
	}

	// If there's no errors update settings
	if ( empty( $errors ) ) {
		if ( $data->update_file( SETTINGS_DIR . 'site', $s, 'setting' ) ) {
			$msgs[] = "Settings Updated";
		} else {
			$errors[] = "Settings Not Updated";
		}
	}

}

// Print out any messages or errors
foreach( $msgs as $msg ) {
	echo '<div class="msg">' . $msg . '</div>';
}

foreach( $errors as $errors ) {
	echo '<div class="error">' . $errors . '</div>';
}
?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<h3 class="form-element-title">General Settings</h3>
	<div class="form-element">
		<input type="text" name="site_name" placeholder="Site Name" value="<?php echo $site_name ? $site_name : ''; ?>">
	</div>
	<div class="form-element">
		<input type="text" name="site_tagline" placeholder="Site Tagline" value="<?php echo $site_tagline ? $site_tagline : ''; ?>">
	</div>
	<h3 class="form-element-title">Display Page Title As:</h3>
	<div class="form-element-radio">
		<input type="radio" name="display_page_title" value="1" <?php echo $display_page_title == 1 ? ' checked="checked"' : ''; ?>> Page Title<br>
		<input type="radio" name="display_page_title" value="2" <?php echo $display_page_title == 2 ? ' checked="checked"' : ''; ?>> Page Title | Site Name<br>
		<input type="radio" name="display_page_title" value="3" <?php echo $display_page_title == 3 ? ' checked="checked"' : ''; ?>> Page Title | Site Name - Tagline<br>
		<input type="radio" name="display_page_title" value="4" <?php echo $display_page_title == 4 ? ' checked="checked"' : ''; ?>> Site Name | Page Title<br>
		<input type="radio" name="display_page_title" value="5" <?php echo $display_page_title == 5 ? ' checked="checked"' : ''; ?>> Site Name - Tagline | Page Title<br>
	</div>
	<h3 class="form-element-title">Default Layout</h3>
	<div class="form-element-select">
		<select name="default_page_layout">
		<?php
			$layouts = $page->get_layouts();

			foreach( $layouts as $layout ) {
				echo '<option value="'.$layout.'"';
				echo $default_page_layout == $layout ? ' selected="true"' : '';
				echo '>';
				echo $layout;
				echo '</option>';
			}
		?>
		</select>
	</div>
	<div class="form-element">
		<input type="submit" value="Save">
	</div>
</form>