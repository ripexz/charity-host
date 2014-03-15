<?php

	header('Content-Type: application/json');

	// Connect to database
	require_once('../../lib/db.php');
	require_once('../../lib/util.php');
	$db = new db(null);
	$conn = $db->connect();

	// Set up defaults
	$page = 1;
	$pagesize = 50;
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
		http_response_code(400);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Invalid charity_id"
		}';
		exit();
	}
	if (isset($_GET['page'])) {
		$page = $_GET['page'] > 0 ? (int) $_GET['page'] : $page;
	}
	if (isset($_GET['pagesize'])) {
		$pagesize = $_GET['pagesize'] > 0 ? (int) $_GET['pagesize'] : $pagesize;
	}

	// Calculate limits
	$lower_limit = $page * $pagesize - $pagesize;
	$upper_limit = $page * $pagesize;
	$next_page = $upper_limit + $page;

	// Check if there will be more entries
	$sql = "SELECT a.*
			FROM animals a
				JOIN charity_animals ca ON a.id = ca.animal_id
			WHERE ca.charity_id = {$charity_id}
			ORDER BY a.id DESC
			LIMIT {$upper_limit}, {$next_page}";
	$res = $conn->query($sql);
	$loadmore = ($res->num_rows > 0) ? "true" : "false";

	// Generate query
	$sql = "SELECT a.*
			FROM animals a
				JOIN charity_animals ca ON a.id = ca.animal_id
			WHERE ca.charity_id = {$charity_id}
			ORDER BY a.id DESC
			LIMIT {$lower_limit}, {$upper_limit}";

	// Execute query
	$result = $conn->query($sql);

	if (!$result) {
		http_response_code(500);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Animal entries could not be loaded."
		}';
		exit();
	}

	// Generate JSON
	$item = 0;
	$json = '{ "STATUS": "OK"';
	$json .= ', "loadmore": ' . $loadmore;
	$json .= ', "animals": [';
	while ($row = $result->fetch_assoc()) {
		$item++;
		$ready = htmlentities_array($row);
		$json .= "{
			\"id\": \"{$row['id']}\",
			\"title\": \"{$ready['title']}\",
			\"description\": \"{$ready['description']}\",
			\"image\": \"{$row['image']}\"
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
