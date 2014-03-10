<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');
	require_once('../core/lib/validation.php');
	$errors = array();

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];

	output_admin_header("Settings", $_SESSION["charity_name"], "admin");
	echo '<div id="settings">';

	// Handle submitted data
	if (isset($_POST["submit"])) {
		$valid = array();
		$valid["charity_name"] = get_required_string($_POST, "charity_name", "Charity name", 255, $errors);
		$valid["charity_email"] = get_optional_string($_POST, "charity_email", "Charity email", 255, $errors);
		$valid["charity_paypal"] = get_optional_string($_POST, "charity_paypal", "PayPal address", 255, $errors);
		$valid["charity_phone"] = get_optional_string($_POST, "charity_phone", "Contact phone number", 30, $errors);
		$valid["charity_address"] = get_optional_string($_POST, "charity_address", "Charity address", 255, $errors);

		if (isset($_POST["color"])) {
			$temp_colour = trim((string)$_POST["colour"]);
			if ($temp_colour == "white") {
				$colour = -1;
			}
			else {
				$colour = get_required_int($_POST, "hue", "Background colour", 3, $errors, 0, 359);
			}
		}
		else {
			$errors[] = "Invalid background colour selection.";
		}

		$logo = "keep";
		if (isset($_POST["logo"])) {
			$temp_logo = trim((string)$_POST["logo"]);
			if ($temp_logo == "new") {
				$logo = "new";
			}
		}
		if ($logo == "new") {
			if ($_FILES["imagefile"]["error"] !== UPLOAD_ERR_OK) {
				$errors[] = "Image upload failed.";
			}
			if ($_FILES["imagefile"]["size"] > 1048576) {
				$errors[] = "Image file is too big, please select an image that is under 1 MB.";
			}
			$info = getimagesize($_FILES["imagefile"]["tmp_name"]);
			if ($info === FALSE) {
				$errors[] = "Unable to determine image type of uploaded file.";
			}
			else {
				$valid_image = true;
				$extension = pathinfo($_FILES["imagefile"]["name"], PATHINFO_EXTENSION);
				$filename = $charity_id . "_logo_" . time() . '.' . $extension;
				$upload_to = "../core/uploads/" . $filename;

				if (move_uploaded_file($_FILES["imagefile"]["tmp_name"], $upload_to)) {
					$safe_url = $conn->real_escape_string('/core/uploads/' . $filename);
				}
				else {
					$errors[] = "Image upload failed.";
				}
			}
		}

		$safe = $db->escape_array($conn, $valid);

		if (count($errors) == 0) {
			//Build query:
			$sql = "UPDATE charities
					SET name = '{$safe[charity_name]}', email = '{$safe[charity_email]}', phone = '{$safe[charity_phone]}', address = '{$safe[charity_address]}', paypal = '{$safe[charity_paypal]}', bg_color = {$colour}";
			$sql .= ($logo == "new" && $safe_url) ? ", logo_url = '{$safe_url}'" : ""; //new logo url set here
			$sql .=	" WHERE id = {$charity_id}";

			$result = $conn->query($sql);
			if (!$result) {
				$errors[] = "Settings could not be updated.";
			}
			else {
				echo "<div class=\"alert alert-success\"><strong>Success! </strong>Settings have been updated.</div>";
			}
		}

		foreach ($errors as $error) {
			echo "<div class=\"alert alert-danger\"><strong>Error: </strong>{$error}</div>";
		}
	}

	// Get data
	$get_settings = $conn->query("SELECT * FROM charities WHERE id = {$charity_id}");
	$data = $get_settings->fetch_assoc();

	$bg = $data["bg_color"];

	// Output form
	echo '<form action="'.$_SERVER['PHP_SELF'].'" role="form" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="charity_name">Charity name</label>
				<input value="'.$data["name"].'" name="charity_name" type="text" class="form-control" id="charity_name" placeholder="Charity name" required autofocus>
			</div>
			<div class="form-group">
				<label for="charity_email">Charity email</label>
				<input value="'.$data["email"].'" name="charity_email" type="text" class="form-control" id="charity_email" placeholder="Contact email address">
			</div>
			<div class="form-group">
				<label for="charity_paypal">PayPal address</label>
				<input value="'.$data["paypal"].'" name="charity_paypal" type="text" class="form-control" id="charity_paypal" placeholder="PayPal email address">
				<p class="help-block">PayPal address is required to receive donations.</p>
			</div>
			<div class="form-group">
				<label for="charity_phone">Charity phone</label>
				<input value="'.$data["phone"].'" name="charity_phone" type="text" class="form-control" id="charity_phone" placeholder="Contact phone number">
			</div>
			<div class="form-group">
				<label for="charity_address">Charity address</label>
				<input value="'.$data["address"].'" name="charity_address" type="text" class="form-control" id="charity_address" placeholder="Charity address">
			</div>
			<div class="logo-preview">';

	if ($data["logo_url"] == '') {
		echo '<img src="/core/images/logo.png" alt="Charity Host" title="Logo"/>';
	}
	else {
		echo "<img src=\"/core/uploads/{$data[logo_url]}\" alt=\"Charity Host\" title=\"Logo\"/>";
	}

	echo'</div>
			<div class="form-group">
				<label>Charity logo</label>
				<div class="logo-settings">
					<label class="radio-inline"><input type="radio" name="logo" class="keep" value="keep" checked/>Keep current</label>
					<label class="radio-inline"><input type="radio" name="logo" class="new" value="new"/>Upload new</label>
				</div>
			</div>
			<div class="form-group logo-uploader">
				<label for="imagefile">Choose an image</label>
				<input name="imagefile" type="file" id="imagefile">
				<p class="help-block">No larger than 1MB in size.</p>
			</div>
			<div class="form-group">
				<label>Background colour</label>
				<div class="colour-settings">';
	
	echo '<label class="radio-inline"><input'.($bg == -1 ? ' checked' : '').' type="radio" name="color" value="white" class="white"/>White</label>';
	echo '<label class="radio-inline"><input'.($bg > -1 ? ' checked' : '').' type="radio" name="color" value="hue" class="hue"/>Choose...</label>';
	
	echo		'</div>
				<div class="colour-picker">
					<div style="background-color:'.($bg > -1 ? "hsl({$bg},21%,52%)" : '#FFF').';" class="demo"></div>
					<input'.($bg == -1 ? ' style="visibility:hidden;' : ' value="'.$bg.'"').' min="0" max="359" type="range" name="hue" id="colour-range" />
				</div>
			</div>
			<div class="form-group">
				<!-- todo -->
			</div>
			<button name="submit" type="submit" class="btn btn-primary">Save settings</button>
		</form>';

	echo '</div>';
	output_admin_footer();

?>