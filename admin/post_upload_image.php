<?php

	session_start();
	header('Content-Type: application/json');
	
	// Check if logged in
	if ( !isset($_SESSION['authorised']) ) {
		http_response_code(401);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "User not logged in."
		}';
		exit();
	}

	http_response_code(400); // error by default

	// Set up defaults
	$charity_id = (int) $_SESSION['charity_id'];

	// Check passed values
	if ($_FILES[0]["error"] !== UPLOAD_ERR_OK) {
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Image upload failed"
		}';
		exit();
	}
	if ($_FILES[0]["size"] > 2097152) {
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Image file is too big, please select an image that is under 2 MB."
		}';
	}

	$info = getimagesize($_FILES[0]["tmp_name"]);
	if ($info === FALSE) {
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Unable to determine image type of uploaded file."
		}';
		exit();
	}

	// All good so far, try move file now
	$extension = pathinfo($_FILES[0]["name"], PATHINFO_EXTENSION);
	$filename = $charity_id . "_" . mt_rand(10, 99) . "_" . time() . '.' . $extension;
	$upload_to = "../../uploads/" . $filename;

	if (move_uploaded_file($_FILES[0]["tmp_name"], $upload_to)) {
		http_response_code(200);
		echo '{
			"STATUS": "OK",
			"imgUrl": "/core/uploads/'.$filename.'"
		}';
	}
	else {
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Image could not be uploaded. Please try again."
		}';
		exit();
	}

?>