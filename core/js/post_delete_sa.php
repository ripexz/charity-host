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
	require_once('../../lib/db.php');
	$db = new db(null);
	$conn = $db->connect();

	// Set up defaults
	$charity_id = $_SESSION["charity_id"];

	// Check passed values
	if (isset($_POST['id'])) {
		$id = (int) $_POST['id'];
	}
	else {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Animal ID is required."
		}';
		exit();
	}

	// Validate entry ownership
	$sql = "SELECT sa.id, sa.image
			FROM animals sa
				JOIN charity_animals ca ON sa.id = ca.animal_id
			WHERE ca.charity_id = {$charity_id} 
				AND sa.id = {$id}";
	$res = $conn->query($sql);
	if ($res->num_rows != 1) {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Animal entry does not exist or does not belong to this charity."
		}';
		exit();
	}
	$sadata = $res->fetch_assoc();
	$link = $sadata["image"];

	// Execute query
	$result = $conn->query("DELETE FROM animals WHERE id = {$id}");

	//remove file:
	$filename = substr($link, 14);
	$relpath = '../../uploads/' . $filename;
	unlink($relpath);

	if (!$result) {
		http_response_code(500);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Entry could not be deleted."
		}';
		exit();
	}

	// Output result
	http_response_code(200);
	echo '{ "STATUS": "OK" }';
?>