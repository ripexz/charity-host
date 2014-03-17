<?php

	session_start();
	if ( isset($_SESSION['authorised']) ) {
		header("Location: dashboard.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/util.php');
	require_once('../core/lib/db.php');
	require_once('../core/lib/validation.php');
	$errors = array();

	if (isset($_POST["submit"])) {
		$db = new db(null);
		$conn = $db->connect();

		if (!$conn->connect_errno) {
			$email = get_required_string($_POST, "email", "Email address", 255, $errors);
			
			if (count($errors) == 0) {
				$db_email = $conn->real_escape_string($email);
				$result = $conn->query("SELECT * FROM admins WHERE email = '{$db_email}'");

				if ($result->num_rows == 1) {
					$text_password = generate_password();
					$password = encrypt($text_password);
					$password = $conn->real_escape_string($password);

					$res = $conn->query("UPDATE admins SET password = '{$password}' WHERE email = '{$db_email}'");
					if ($res) {
						email_user_details($email, $text_password, '', false);
						echo "<div class=\"alert alert-success\">A new password has been sent to your email.</div>";
					}
					else {
						echo "<div class=\"alert alert-danger\"><strong>Error: </strong>User not found.</div>";	
					}
				}
				else {
					echo "<div class=\"alert alert-danger\"><strong>Error: </strong>User not found.</div>";
				}
			}
			else {
				foreach ($errors as $error) {
					echo "<div class=\"alert alert-danger\"><strong>Error: </strong>{$error}</div>";
				}
			}
		}
		else {
			echo "<div class=\"alert alert-danger\"><strong>Error: </strong>Cannot connect to database.</div>";
		}
	}

	output_base_header("Reset your password", "forms");

	echo '<div class="container">
			<form class="form-signin" role="form" method="post" action="'.$_SERVER['PHP_SELF'].'">
				<h2 class="form-signin-heading">Reset your password</h2>
				<input name="email" type="email" class="form-control" placeholder="Email address" required autofocus>
				<button name="submit" value="submit" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
				<p class="end-link">Not a user? <a href="register.php">Click here to register</a>.</p>
			</form>
		</div>';

	output_base_footer();

?>