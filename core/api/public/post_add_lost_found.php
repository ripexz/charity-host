<?php

	header('Content-Type: application/json');

	// Connect to database
	require_once('../../lib/db.php');
	require_once('../../lib/validation.php');
	$errors = array();
	$db = new db(null);
	$conn = $db->connect();

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

	var_dump($_GET);
	exit();

	if (!$valid_id) {
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Invalid charity_id"
		}';
		exit();
	}

	$valid = array();
	$valid["is_found"] = get_required_int($_POST, 'type_is_found', "Type", 1, $errors, 0, 1);
	$valid["title"] = get_required_string($_POST, 'title', 'Title', 255, $errors)
	$valid["description"] = get_required_string($_POST, 'description', 'Description', 500, $errors)
	$valid["email"] = get_required_string($_POST, 'email', 'Email address', 255, $errors)
	$valid["phone"] = get_optional_string($_POST, 'phone', 'Phone number', 30, $errors)
	$valid["image"] = (string) $_POST["filenames"][0];
	var_dump($valid);
	echo "<br>";
	var_dump($_POST);
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
