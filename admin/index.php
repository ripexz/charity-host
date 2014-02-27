<?php

	session_start();
	if ( isset($_SESSION['authorised']) ) {
		header("Location: dashboard.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/util.php');
	require_once('../core/lib/db.php');

	if (isset($_POST["submit"])) {
		$db = new db(null);
		$conn = $db->connect();

		if (!$conn->connect_errno) {
			$email = (string) $_POST["email"];
			$password = (string) $_POST["password"];
			$password = encrypt($password);

			$db_email = $conn->real_escape_string($email);
			$db_password = $conn->real_escape_string($password);

			$result = $conn->query("SELECT * FROM admins WHERE email = '{$db_email}' AND password = '{$db_password}'");
			if ($result->num_rows == 1) {
				$data = $result->fetch_assoc();

				session_regenerate_id();
				$_SESSION['authorised'] = true;
				$_SESSION['admin_id'] = $data["id"];
				$_SESSION['admin_email'] = $data["email"];
				$_SESSION['is_owner'] = $data["is_owner"];
				header("Location: dashboard.php");
			}
			else {
				echo "<div class=\"alert alert-danger\"><strong>Error: </strong>Email and password do not match our records.</div>";
			}
		}
		else {
			echo "<div class=\"alert alert-danger\"><strong>Error: </strong>Cannot connect to database.</div>";
		}
	}

	output_admin_header("Login", "forms");

	echo '<div class="container">
			<form class="form-signin" role="form" method="post" action="'.$_SERVER['PHP_SELF'].'">
				<h2 class="form-signin-heading">Please sign in</h2>
				<input name="email" type="email" class="form-control" placeholder="Email address" required autofocus>
				<input name="password" type="password" class="form-control" placeholder="Password" required>
				<label class="checkbox">
					<input type="checkbox" value="remember-me"> Remember me
				</label>
				<button name="submit" value="submit" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
				<p class="end-link">Not a user? <a href="register.php">Click here to register</a>.</p>
			</form>
		</div>';

	output_admin_footer();

?>