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
	$page = 1;
	$pagesize = 50;
	$charity_id = $_SESSION["charity_id"];

	// Check passed values
	if (isset($_GET['page'])) {
		$page = $_GET['page'] > 0 ? (int) $_GET['page'] : $page;
	}
	if (isset($_GET['pagesize'])) {
		$pagesize = $_GET['pagesize'] > 0 ? (int) $_GET['pagesize'] : $pagesize;
	}

	// Calculate limits
	$lower_limit = $page * $pagesize - $page;
	$upper_limit = $page * $pagesize;

	// Generate query
	$sql = "SELECT i.*
			FROM images i
				JOIN charity_images ci ON i.id = ci.image_id
			WHERE ci.charity_id = {$charity_id}
			ORDER BY i.id DESC
			LIMIT {$lower_limit}, {$upper_limit}";

	// Execute query
	$result = $conn->query($sql);

	if (!$result) {
		http_response_code(500);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Images could not be loaded."
		}';
		exit();
	}

	// Generate JSON
	$item = 0;
	$json = '{ "STATUS": "OK", "images": [';
	while ($row = $result->fetch_assoc()) {
		$item++;
		$json .= "{
			\"id\": \"{$row['id']}\",
			\"title\": \"{$row['title']}\",
			\"url\": \"{$row['url']}\"
		}";
		if ($item < $result->num_rows) {
			$json .= ',';
		}
	}
	$json .= ']}';

	// Output result
	http_response_code(200);
	echo $json;
?>