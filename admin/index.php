<?php

	session_start();
	require_once('lib/output.php');
	require_once('../core/lib/db.php');

	if ( isset($_SESSION['authorised']) ) {
		header("Location: home.php");
	}

	output_header("Login");

	echo '<div class="container">
			<form class="form-signin" role="form">
				<h2 class="form-signin-heading">Please sign in</h2>
				<input type="email" class="form-control" placeholder="Email address" required autofocus>
				<input type="password" class="form-control" placeholder="Password" required>
				<label class="checkbox">
					<input type="checkbox" value="remember-me"> Remember me
				</label>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
			</form>
		</div>';

	output_footer();

?>