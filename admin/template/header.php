<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name = "viewport" content = "initial-scale = 1.0">
		<title><?php echo $title; ?> - Glav.in - A Simple CMS</title>
		<script type="text/javascript" src="<?php echo base_url(); ?>admin/template/js/jquery-1.10.2.min.js"></script>
		<!--[if (gte IE 6)&(lte IE 8)]>
		  <script type="text/javascript" src="js/selectivizr.js"></script>
		<![endif]--> 		
		<!--[if lt IE 9]><script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<link rel="stylesheet" href="<?php echo base_url(); ?>admin/template/styles/normalize.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>admin/template/styles/style.css">
		<script type="text/javascript" src="<?php echo base_url(); ?>admin/template/js/respond.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>admin/template/js/placeMe.min.js"></script>
		<script src="<?php echo base_url(); ?>admin/template/js/ckeditor/ckeditor.js"></script>
		<script>
		    window.onload = function() {
		        CKEDITOR.replace( 'page-content' );
		    };

		    $(function() {

		    	// Correct the user while typing the filename
		    	$('input[name="page_name"]').keydown(function(e) {
		    		var keyVal = ( e.charCode ? e.charCode : ( e.keyCode ? e.keyCode : e.which ) );
					
					if ( ( keyVal == 0x41 && e.ctrlKey === true ) || ( keyVal == 0x63 && e.ctrlKey === true ) || // Allow: Ctrl+A, or Ctrl+C
						( keyVal == 0x56 && e.ctrlKey === true ) || ( keyVal == 0x58 && e.ctrlKey === true ) ) { // or Ctrl+V, or Ctrl+X
						return;
					} else if ( keyVal == 0x20 ) { // If user added a space, replace it with underscore
		    			$('input[name="page_name"]').val($(this).val() + '-');
						return false;
		    		} else if ( keyVal >= 0x41 && keyVal <= 0x5A ) { // Convert uppercase letters to lowercase
						$('input[name="page_name"]').val($(this).val() + String.fromCharCode(keyVal).toLowerCase());
						return false;
					}
					
		    	});	

		    });
			
			// Check Description Length
			$().ready(function(){
				$('input[name="page_description"]').keypress(function(e) {
					var len = $(this).val().length,
						maxlen = 160,
						keyVal = ( e.charCode ? e.charCode : ( e.keyCode ? e.keyCode : e.which ) );
					
					if ( keyVal == 8 || keyVal == 9 || keyVal == 13 || //Allow: Backspace, Tab, Enter
							( keyVal == 0x41 && e.ctrlKey === true ) || ( keyVal == 0x63 && e.ctrlKey === true ) || // Allow: Ctrl+A, or Ctrl+C
								( keyVal == 0x56 && e.ctrlKey === true ) || ( keyVal == 0x58 && e.ctrlKey === true ) ) { // or Ctrl+V, or Ctrl+X
						return;
					} else if ( len > maxlen - 1 ) {
						return false;
					}
				});
			});
		</script>		
	</head>
	<body<?php echo $login_header ? ' class="not_logged_in"' : ''; ?>>
		<?php if($login_header) { ?>
		<div id="login-container">
			<header>
				<h1 id="login-logo"><span class="none">Glav.in</span></h1>
			</header>
		<?php } else { ?>
		<div id="admin-container">
			<header id="admin-header">
				<h1 id="admin-logo"><a href="<?php echo base_url(); ?>admin/pages"><span class="none">Glav.in</span></a></h1>
				<nav id="admin-nav">
					<ul>
						<li><a href="<?php echo base_url(); ?>admin/pages" title="Pages">Pages</a></li>

						
						<?php if ( $user_level < 2 ) { ?>
						<li><a href="<?php echo base_url(); ?>admin/users" title="Users">Users</a></li>
						<?php } ?>

						<?php /* <li><a href="#" title="Settings">Settings</a></li> */ ?>

						<li><a href="<?php echo base_url(); ?>admin/logout" title="Logout">Logout</a></li>
					</ul>
				</nav>
			</header>
			<div id="admin-content">
		<?php } ?>