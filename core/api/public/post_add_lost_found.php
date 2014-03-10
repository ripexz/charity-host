<?php

	header('Content-Type: application/json');

	// Connect to database
	require_once('../../lib/db.php');
	$db = new db(null);
	$conn = $db->connect();

	// Set up defaults
	$charity_id = 0;

	// Check passed values
	$valid_id = false;
	if (isset($_POST['charity_id'])) {
		$charity_id = (int) $_POST['charity_id'];
		if ($charity_id > 0) {
			$valid_id = true;
		}
	}
	if (!$valid_id) {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Invalid charity_id"
		}';
		exit();
	}

	// Validate data here
	exit();

	// Check if Lost and Found is enabled for that charity
	$res = "SELECT lnf_enabled, lnf_auto_approve FROM charities WHERE charity_id = {$charity_id}";
	if ($res->num_rows != 1) {
		http_response_code(500);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Database error."
		}';
		exit();
	}

	$res_data = $res->fetch_assoc();
	$lnf_enabled = $res_data["lnf_enabled"];
	$lnf_auto_approve = $res_data["lnf_auto_approve"];

	if (!$lnf_enabled) {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Lost and found is not enabled for this charity."
		}';
		exit();
	}

	// Generate query
	$sql = "INSERT INTO lost_and_found ()
			VALUES ()";
	// Execute query
	$result = $conn->query($sql);
	if (!$result) {
		http_response_code(500);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Lost and found entry could not be added."
		}';
		exit();
	}

	// Add charity_animals entry
	$last_id = $conn->insert_id;	
	$result2 = $conn->query("INSERT INTO charity_lost_found (lost_found_id, charity_id) VALUES ({$last_id}, {$charity_id})");

	// Generate JSON
	$json = '{ "STATUS": "OK", "id": "'.$last_id.'"}';

	// Output result
	http_response_code(200);
	echo $json;
?>