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

$logging_in = false;

if ( $_POST ) {

	$email     = clean( $_POST['email'] );
	$password  = clean( $_POST['password'] );

	if ( !$user->validate( $email, $password ) ) {
		$errors[] = 'Invalid Login Information. Need to <a href="reset_password" title="Reset Password">reset your password?</a>';
	} else {
		// Once the user has been validated, we need to refresh the page
		// to redirect to the admin panel.
		$logging_in = true;

		echo '<meta http-equiv="refresh" content="3; url=pages">';
	}
}
?>
<div id="login-content">
	<?php
	// Print out any messages or errors
	foreach( $msgs as $msg ) {
		echo '<div class="msg">' . $msg . '</div>';
	}

	foreach( $errors as $error ) {
		echo '<div class="error">' . $error . '</div>';
	}

	// Should we show the login prompt or are we logging in?
	if ( !$logging_in ) {
	?>			
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div class="form-element">
			<input type="text" placeholder="Email Address" name="email" />
		</div>
		<div class="form-element">
			<input type="password" placeholder="Password" name="password" />
		</div>
		<div class="form-element">
			<input type="submit" value="Submit">
		</div>
	</form>
	<?php
	} else {
	?>
	<div id="logging-in"><span class="hidden">Logging In...</span></div>
	<?php	
	}
	?>