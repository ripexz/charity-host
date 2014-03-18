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

	// Connect to database
	require_once('../../lib/validation.php');
	require_once('../../lib/db.php');
	$db = new db(null);
	$conn = $db->connect();

	// Set up defaults
	$errors = array();
	$valid = array();
	$charity_id = (int) $_SESSION['charity_id'];

	$valid['title'] = get_required_string($_POST, "imagetitle", "Image title", 255, $errors);
	$valid['url'] = get_required_string($_POST, "filename", "Image", 255, $errors);

	if (count($errors) > 0) {
		//return first error only
		$err = '';
		foreach ($errors as $error) {
			$err = $error;
			break;
		}
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "'.$err.'"
		}';
		exit();
	}

	$safe = $db->escape_array($conn, $valid);

	// Execute query
	$result = $conn->query("INSERT INTO images (title, url) VALUES ('{$safe[title]}', '{$safe[url]}')");
	if (!$result) {
		http_response_code(500);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Image could not be added."
		}';
		exit();
	}

	// Add charity_images entry
	$image_id = $conn->insert_id;
	$result2 = $conn->query("INSERT INTO charity_images (image_id, charity_id) VALUES ({$image_id}, {$charity_id})");

	// Output result
	http_response_code(200);
	echo '{ "STATUS": "OK" }';
?>
