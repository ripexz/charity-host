<?php

	session_start();
	if ( isset($_SESSION['authorised']) ) {
		header("Location: dashboard.php");
	}

	require_once('lib/output.php');
	require_once('../core/lib/db.php');

	output_header("Registration", "forms");

	echo '<script type="text/javascript">
			function updatePreview(el) {
				var linkEl = document.getElementById("urlPreview");
				linkEl.innerText = el.value;
			}
		</script>';

	echo '<div class="container">
			<form class="form-register" role="form">
				<h2 class="form-register-heading">Registration</h2>

				<h3>Charity details</h3>
				<input type="text" class="form-control" placeholder="Charity name" required autofocus>
				<input type="text" class="form-control" placeholder="Charity link" required onkeyup="updatePreview(this)">
				<p>Your charity will be accessible at http://www.eyeur.com/<span id="urlPreview">charity-link</span></p>
				<input type="email" class="form-control" placeholder="Contact email address" required>
				<input type="email" class="form-control" placeholder="Confirm contact email" required>
				<input type="text" class="form-control" placeholder="Contact phone number (optional)">
				<input type="text" class="form-control" placeholder="Address (optional)">

				<h3>Your details</h3>
				<input type="email" class="form-control" placeholder="Email address" required>
				<input type="email" class="form-control" placeholder="Confirm email" required>
				<input type="password" class="form-control" placeholder="Password" required>
				<input type="password" class="form-control" placeholder="Confirm password" required>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
			</form>
		</div>';

	output_footer();

?>