<?php

	header('Content-Type: application/json');

	// Connect to database
	require_once('../../lib/db.php');
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
	$sql = "SELECT lnf.*
			FROM lost_and_found lnf
				JOIN charity_lost_found clf ON lnf.id = clf.lost_found_id
			WHERE clf.charity_id = {$charity_id}
				AND lnf.approved = 1
			ORDER BY lnf.id DESC
			LIMIT {$upper_limit}, {$next_page}";
	$res = $conn->query($sql);
	$loadmore = ($res->num_rows > 0) ? "true" : "false";

	// Generate query
	$sql = "SELECT lnf.*
			FROM lost_and_found lnf
				JOIN charity_lost_found clf ON lnf.id = clf.lost_found_id
			WHERE clf.charity_id = {$charity_id}
				AND lnf.approved = 1
			ORDER BY lnf.id DESC
			LIMIT {$lower_limit}, {$upper_limit}";

	// Execute query
	$result = $conn->query($sql);

	if (!$result) {
		http_response_code(500);
		echo '{
			"STATUS": "ERROR",
			"MESSAGE": "Lost and found entries could not be loaded."
		}';
		exit();
	}

	// Generate JSON
	$item = 0;
	$json = '{ "STATUS": "OK"';
	$json .= ', "loadmore": ' . $loadmore;
	$json .= ', "lost_and_found": [';
	while ($row = $result->fetch_assoc()) {
		$item++;
		$json .= "{
			\"id\": \"{$row['id']}\",
			\"title\": \"{$row['title']}\",
			\"description\": \"{$row['description']}\",
			\"image\": \"{$row['image']}\",
			\"email\": \"{$row['email']}\",
			\"phone\": \"{$row['phone']}\",
			\"isFound\": \"{$row['type_is_found']}\",
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
