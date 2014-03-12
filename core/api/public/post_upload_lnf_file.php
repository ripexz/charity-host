<?php

	header('Content-Type: application/json');
	http_response_code(400); // error by default

	// Set up defaults
	$charity_id = 0;

	// Check passed values
	$valid_id = false;
	if (isset($_GET['charity_id'])) {
		$charity_id = (int) $_GET['charity_id'];
		if ($charity_id > 0) {
			$valid_id = true;
		}
	}
	if (!$valid_id) {
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Invalid charity_id"
		}';
		exit();
	}

	if ($_FILES["imagefile"]["error"] !== UPLOAD_ERR_OK) {
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Image upload failed"
		}';
		exit();
	}
	if ($_FILES["imagefile"]["size"] > 1048576) {
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Image file is too big, please select an image that is under 1 MB."
		}';
	}

	$info = getimagesize($_FILES["imagefile"]["tmp_name"]);
	if ($info === FALSE) {
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Unable to determine image type of uploaded file."
		}';
		exit();
	}

	// All good so far, try move file now
	$extension = pathinfo($_FILES["imagefile"]["name"], PATHINFO_EXTENSION);
	$filename = $charity_id . "_lnf_" . time() . '.' . $extension;
	$upload_to = "../../uploads/" . $filename;

	if (move_uploaded_file($_FILES["imagefile"]["tmp_name"], $upload_to)) {
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