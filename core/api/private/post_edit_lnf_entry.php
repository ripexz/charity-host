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
			"MESSAGE": "Entry ID is required."
		}';
		exit();
	}
	if (isset($_POST['action'])) {
		$action = trim((string) $_POST['action']);
		if ($action != "delete" && $action != "approve") {
			http_response_code(400);
			echo '{
				"STATUS": "ERROR",
				"MESSAGE": "Invalid action."
			}';
			exit();
		}
	}
	else {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Action is required."
		}';
		exit();
	}

	// Validate entry ownership
	$sql = "SELECT lnf.id
			FROM lost_and_found lnf
				JOIN charity_lost_found clf ON lnf.id = clf.lost_found_id
			WHERE clf.charity_id = {$charity_id} 
				AND lnf.id = {$id}";
	$res = $conn->query($sql);
	if ($res->num_rows != 1) {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Entry does not exist or does not belong to this charity."
		}';
		exit();
	}

	// Execute query
	if ($action == "delete") {
		$result = $conn->query("UPDATE lost_and_found SET approved = 1 WHERE id = {$id}");
	}
	else {
		$result = $conn->query("DELETE FROM lost_and_found WHERE id = {$id}");
	}

	if (!$result) {
		http_response_code(500);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Entry could not be '.$action.'d."
		}';
		exit();
	}

	// Output result
	http_response_code(200);
	echo '{ "STATUS": "OK"}';
?>