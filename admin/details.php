<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');
	require_once('../core/lib/util.php');
	require_once('../core/lib/validation.php');
	$errors = array();

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];

	output_admin_header("Your details", $_SESSION["charity_name"], "admin");
	echo '<div>';

	if (isset($_POST['submit'])) {
		$email_changed = false;
		$pass_changed = false;

		$valid = array();
		$valid["email"] = get_required_string($_POST, "email", "Your email", 255, $errors);
		$valid["email_2"] = get_required_string($_POST, "email_2", "Your email (repeat)", 255, $errors);
		$valid["password"] = get_optional_string($_POST, "password", "Your password", 255, $errors);
		$valid["password_2"] = get_optional_string($_POST, "password_2", "Your password (repeat)", 255, $errors);

		if ($valid["email"] != $_SESSION["admin_email"]) {
			$email_changed = true;
			if ($valid["email"] != $valid["email_2"]) {
				$errors[] = "Passwords do not match.";
			}
		}

		if ($valid["password"] != NULL) {
			$pass_changed = true;
			if ($valid["password"] != $valid["password_2"]) {
				$errors[] = "Passwords do not match.";
			}
		}

		if ($email_changed || $pass_changed) {
			if (count($errors) == 0) {
				$valid["enc_pass"] = encrypt($valid["password"]);
				$safe = $db->escape_array($conn, $valid);

				$sql = "UPDATE admins SET ";
				$sql .= $email_changed ? "email = '{$safe[email]}'" : '';
				$sql .= $pass_changed ? "password = '{$safe[enc_pass]}'" : '';
				$sql .= " WHERE id = {$_SESSION[admin_id]}";

				$result = $conn->query($sql);
				if ($result) {
					$_SESSION['admin_email'] = $valid['email'];
					echo "<div class=\"alert alert-success\">Details updated successfully.</div>";
				}
			}
			else {
				foreach ($errors as $error) {
					echo "<div class=\"alert alert-danger\"><strong>Error: </strong>{$error}</div>";
				}
			}
		}
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