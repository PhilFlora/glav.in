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
?>
<div id="page-description">
	<h1>Settings</h1>
	<p>Below is a list of all of your site's settings.</p>
</div><!-- end page-description -->
<div id="admin-content-body" class="settings-page">
	<h2>General Settings</h2>
	<input type="text" name="site_name" placeholder="Site Name">
	<h2>Display Settings</h2>
	<h3>Display Page Title As:</h3>
	<div class="settings-radio">
		<input type="radio" name="display_page_title" value="1"> Page Title<br>
		<input type="radio" name="display_page_title" value="2"> Page Title | Site Name<br>
		<input type="radio" name="display_page_title" value="3"> Site Name | Page Title<br>
	</div>