<?php

	session_start();
	if ( isset($_SESSION['authorised']) ) {
		header("Location: home.php");
	}

	require_once('lib/output.php');
	require_once('../core/lib/db.php');

	output_header("Registration");

	echo '<div class="container">
			<form class="form-register" role="form">
				<h2 class="form-register-heading">Registration</h2>
				<h2>Charity details</h2>
				<input type="text" class="form-control" placeholder="Charity name" required autofocus>
				<input type="text" class="form-control" placeholder="Charity link" required>
				<span>Your charity will be accessible at http://www.eyeur.com/charity-link</span>
				<input type="email" class="form-control" placeholder="Contact email address" required>
				<input type="email" class="form-control" placeholder="Confirm contact email" required>
				<input type="text" class="form-control" placeholder="Contact phone number">
				<input type="text" class="form-control" placeholder="Address">
				<h2>Your details</h2>
				<input type="email" class="form-control" placeholder="Email address" required>
				<input type="email" class="form-control" placeholder="Confirm email" required>
				<input type="password" class="form-control" placeholder="Password" required>
				<input type="password" class="form-control" placeholder="Confirm password" required>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
			</form>
		</div>';

	output_footer();

?>