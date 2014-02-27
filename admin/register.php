<?php

	session_start();
	if ( isset($_SESSION['authorised']) ) {
		header("Location: dashboard.php");
	}

	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');
	require_once('../core/lib/util.php');
	require_once('../core/lib/validation.php');
	$errors = array();

	function output_registration_form() {
		echo '<div class="container">
			<form class="form-register" role="form" method="post" action="'.$_SERVER['PHP_SELF'].'">
				<h2 class="form-register-heading">Registration</h2>

				<h3>Charity details</h3>
				<input name="charity_name" type="text" class="form-control" placeholder="Charity name" required autofocus>
				<input name="charity_link" type="text" class="form-control" placeholder="Charity link" required onkeyup="updatePreview(this)">
				<p>Your charity will be accessible at http://www.eyeur.com/<span id="urlPreview"></span></p>
				<input name="charity_email" type="email" class="form-control" placeholder="Contact email address (optional)">
				<input name="charity_paypal" type="email" class="form-control" placeholder="PayPal address (optional)">
				<input name="charity_phone" type="text" class="form-control" placeholder="Contact phone number (optional)">
				<input name="charity_address" type="text" class="form-control" placeholder="Address (optional)">

				<h3>Your details</h3>
				<input name="admin_email" type="email" class="form-control" placeholder="Email address" required>
				<input name="admin_email_2" type="email" class="form-control" placeholder="Confirm email" required>
				<input name="password" type="password" class="form-control" placeholder="Password" required>
				<input name="password_2" type="password" class="form-control" placeholder="Confirm password" required>
				<button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Register</button>
			</form>
		</div>';

		echo '<script type="text/javascript">
				function updatePreview(el) {
					var linkEl = document.getElementById("urlPreview");
					linkEl.innerText = el.value.toLowerCase();
				}
			</script>';
	}

	output_base_header("Registration", "forms");

	if (isset($_POST["submit"])) {
		$db = new db(null);
		$conn = $db->connect();

		if (!$conn->connect_errno) {
			$valid["charity_name"] = get_required_string($_POST, "charity_name", "Charity name", 255, $errors);
			$valid["charity_link"] = get_required_string($_POST, "charity_link", "Charity link", 60, $errors);

			$valid["charity_email"] = get_optional_string($_POST, "charity_email", "Charity email", 255, $errors);
			$valid["charity_paypal"] = get_optional_string($_POST, "charity_paypal", "PayPal address", 255, $errors);
			$valid["charity_phone"] = get_optional_string($_POST, "charity_phone", "Contact phone number", 30, $errors);
			$valid["charity_address"] = get_optional_string($_POST, "charity_address", "Charity address", 255, $errors);

			$valid["admin_email"] = get_required_string($_POST, "admin_email", "Your email", 255, $errors);
			$valid["admin_email_2"] = get_required_string($_POST, "admin_email_2", "Your email (repeat)", 255, $errors);

			$valid["password"] = get_required_string($_POST, "password", "Your password", 255, $errors);
			$valid["password_2"] = get_required_string($_POST, "password_2", "Your password (repeat)", 255, $errors);

			$valid["password"] = encrypt($valid["password"]);
			$valid["password_2"] = encrypt($valid["password_2"]);

			$safe = $db->escape_array($conn, $valid);

			if ($valid["admin_email"] != $valid["admin_email_2"]) {
				$errors[] = "Passwords do not match.";
			}

			if ($valid["password"] != $valid["password_2"]) {
				$errors[] = "Passwords do not match.";
			}

			$valid["charity_link"] = strtolower($valid["charity_link"]);
			if (!preg_match("/^[\w-]+$/", $valid["charity_link"])) {
				$errors[] = "Charity link can only contain letters, numbers, underscores and dashes";
			}
			
			if ($valid["charity_link"] == "admin" || $valid["charity_link"] == "core" || $valid["charity_link"] == "home" || $valid["charity_link"] == "faq") {
				$errors[] = "Charity link entered cannot be used.";
			}

			$check_link = $conn->query("SELECT id FROM charities WHERE link = '{$safe[charity_link]}';");
			if ($check_link->num_rows != 0) {
				$errors[] = "Charity link entered is already taken.";
			}

			$check_email = $conn->query("SELECT id FROM admins WHERE email = '{$safe[admin_email]}';");
			if ($check_email->num_rows != 0) {
				$errors[] = "User email address already in use.";
			}

			if (count($errors) == 0) {
				// Create charity
				$sql = "INSERT INTO charities (name, link, email, paypal, phone, address)
						VALUES ('{$safe[charity_name]}', '{$safe[charity_link]}', '{$safe[charity_email]}', '{$safe[charity_paypal]}', '{$safe[charity_phone]}', '{$safe[charity_address]}')";
				$result = $conn->query($sql);
				$charity_id = $conn->insert_id;

				$result2 = $conn->query("INSERT INTO admins (email, password, is_owner) VALUES ('{$safe[admin_email]}', '{$safe[password]}', 1)");				
				$admin_id = $conn->insert_id;

				$result3 = $conn->query("INSERT INTO charity_admins (admin_id, charity_id) VALUES ({$admin_id}, {$charity_id})");

				echo "<div class=\"alert alert-success\"><strong>Success! </strong>You can now log in.</div>";
			}
			else {
				foreach ($errors as $error) {
					echo "<div class=\"alert alert-danger\"><strong>Error: </strong>{$error}</div>";
				}
				output_registration_form();
			}
		}
		else {
			echo "<div class=\"alert alert-danger\"><strong>Error: </strong>Cannot connect to database. Please try again later.</div>";
		}
	}
	else {
		output_registration_form();
	}

	output_base_footer();

?>