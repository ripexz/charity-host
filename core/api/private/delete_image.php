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

	// Set up defaults
	$charity_id = $_SESSION["charity_id"];

	// Check passed values
	if (isset($_GET['id'])) {
		$id = (int) $_GET['id'];
		if ($id <= 0) {
			http_response_code(400);
			echo '{
				"STATUS": "ERROR",
				"MESSAGE": "Invalid image id."
			}';
			exit();
		}
	}
	else {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Image id is required."
		}';
		exit();
	}

	// Connect to database
	require_once('../../lib/db.php');
	$db = new db(null);
	$conn = $db->connect();

	// Validate image owenership
	$sql = "SELECT i.*
			FROM images i
				JOIN charity_images ci ON i.id = ci.image_id
			WHERE ci.charity_id = {$charity_id}
				AND i.id = {$id}";
	$res = $conn->query($sql);

	if ($res->num_rows !== 1) {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Image does not belong to your charity."
		}';
		exit();
	}

	$image_data = $res->fetch_assoc();

	// Delete from images table
	//$result = $conn->query("DELETE FROM images WHERE id = {$id}");
	//$result2 = $conn->query("DELETE FROM charity_images WHERE image_id = {$id}");

	// Delete image from server
	$basepath = realpath("/core/uploads/");
	$path = $basepath . $image_data['url'];

	var_dump($path);
	exit();

	$deleted = unlink($path);

	http_response_code(200);
	if ($deleted) {
		echo '{
			"STATUS": "OK",
			"MESSAGE": "Image has been removed from database."
		}';
	}
	else {
		echo '{
			"STATUS": "OK",
			"MESSAGE": "Image has been removed from database and deleted."
		}';
	}

?>