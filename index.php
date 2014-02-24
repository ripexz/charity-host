<?php

	require_once('core/lib/util.php');

	$req = "home";
	if (isset($_GET["req"])) {
		$req = $_GET["req"];
	}
	
	handle_request($req);

	/*
	$db = new db(null);
	$conn = $db->connect();

	if ($conn->connect_errno) {
		echo $conn->connect_errno;
	}
	else {
		$result = $conn->query("SELECT * FROM test");
		echo $result->num_rows;
	}
	*/

?>