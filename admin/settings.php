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


		if (count($errors) == 0) {
			//continue
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
				<label for="title">Charity name</label>
				<input value="'.$data["name"].'" name="charity_name" type="text" class="form-control" id="title" placeholder="Charity name" required autofocus>
			</div>
			<div class="form-group">
				<label for="title">Charity email</label>
				<input value="'.$data["email"].'" name="charity_email" type="text" class="form-control" id="title" placeholder="Contact email address">
			</div>
			<div class="form-group">
				<label for="title">PayPal address</label>
				<input value="'.$data["paypal"].'" name="paypal_email" type="text" class="form-control" id="title" placeholder="PayPal email address">
				<p class="help-block">PayPal address is required to receive donations.</p>
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
					<label class="radio-inline"><input type="radio" name="logo" value="keep" checked/>Keep current</label>
					<label class="radio-inline"><input type="radio" name="logo" value="new"/>Upload new</label>
				</div>
			</div>
			<div class="form-group logo-uploader">
				<label for="imagefile">Choose an image</label>
				<input name="imagefile" type="file" id="imagefile">
				<p class="help-block">No larger than 2MB in size.</p>
			</div>
			<div class="form-group">
				<label>Background colour</label>
				<div class="colour-settings">';
	
	echo '<label class="radio-inline"><input'.($bg == -1 ? ' checked' : '').' type="radio" name="color" value="white" class="white"/>White</label>';
	echo '<label class="radio-inline"><input'.($bg > -1 ? ' checked' : '').' type="radio" name="color" value="hue" class="hue"/>Choose...</label>';
	
	echo		'</div>
				<div class="colour-picker">
					<div style="background-color:'.($bg > -1 ? "hsl({$bg},21%,52%)" : '#FFF').';" class="demo"></div>
					<input'.($bg == -1 ? ' style="display:none;' : '').' min="0" max="359" type="range" name="hue" id="colour-range" />
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