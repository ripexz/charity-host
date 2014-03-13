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
	if (isset($_POST['sa_enabled'])) {
		$sa = (int) (bool) $_POST['sa_enabled'];
	}
	else {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "sa_enabled is required."
		}';
		exit();
	}

	// Execute query
	$result = $conn->query("UPDATE charities SET sa_enabled = {$sa} WHERE id = {$charity_id}");

	if (!$result) {
		http_response_code(500);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Settings could not be updated."
		}';
		exit();
	}

	// Output result
	http_response_code(200);
	echo '{ "STATUS": "OK" }';
?>