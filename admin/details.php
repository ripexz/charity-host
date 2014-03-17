<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];

	output_admin_header("Your details", $_SESSION["charity_name"], "admin");
	echo '<div>';

	if (isset($_POST['submit'])) {
		//do stuff
	}

	echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'">
			<button type="submit" name="submit" value="submit" class="btn btn-lg btn-primary btn-top-right">Submit</button>
			<div class="form-group">
				<label for="email">Email address</label>
				<input autocomplete="off" id="email" name="email" type="email" class="form-control" placeholder="Email address" value="'.$_SESSION['admin_email'].'">
			</div>
			<div class="form-group">
				<label for="email_2">Repeat email</label>
				<input autocomplete="off" id="email_2" name="email_2" type="email" class="form-control" placeholder="Confirm email" value="'.$_SESSION['admin_email'].'">
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input autocomplete="off" id="password" name="password" type="password" class="form-control" placeholder="Leave blank to keep your current password">
			</div>
			<div class="form-group">
				<label for="password_2">Repeat password</label>
				<input autocomplete="off" id="password_2" name="password_2" type="password" class="form-control" placeholder="Leave blank to keep your current password">
			</div>
		</form>';

	echo '</div>';
	output_admin_footer();

?>