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

			$result = $conn->query("SELECT a.id AS admin_id, a.email as admin_email, a.is_owner, c.id as charity_id, c.name, c.link FROM admins a JOIN charity_admins ca ON a.id = ca.admin_id JOIN charities c ON c.id = ca.charity_id WHERE a.email = '{$db_email}' AND a.password = '{$db_password}'");
			if ($result->num_rows == 1) {
				$data = $result->fetch_assoc();

				session_regenerate_id();
				$_SESSION['authorised'] = true;
				$_SESSION['admin_id'] = $data["admin_id"];
				$_SESSION['admin_email'] = $data["admin_email"];
				$_SESSION['is_owner'] = $data["is_owner"];
				$_SESSION['charity_id'] = $data["charity_id"];
				$_SESSION['charity_name'] = $data["name"];
				$_SESSION['charity_link'] = $data["link"];
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

	output_base_header("Login", "forms");

	echo '<div class="container">
			<form class="form-signin" role="form" method="post" action="'.$_SERVER['PHP_SELF'].'">
				<h2 class="form-signin-heading">Please sign in</h2>
				<input name="email" type="email" class="form-control" placeholder="Email address" required autofocus>
				<input name="password" type="password" class="form-control" placeholder="Password" required>
				<p class="text-center"><small><a href="reset.php">Forgot your password?</a></small></p>
				<button name="submit" value="submit" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
				<p class="end-link">Not a user? <a href="register.php">Click here to register</a>.</p>
			</form>
		</div>';

	output_base_footer();

?>