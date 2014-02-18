<?php

	require_once('core/lib/db.php');
	require_once('core/lib/output.php');

	$page = "home";
	$title = "Home";
	if (isset($_GET['page']) && isset($_GET['title'])) {
		$page = htmlentities($_GET['page']);
		$title = htmlentities($_GET['title']);
	}

	output_header($title);

	echo "<div class=\"container\">";

	echo '<h1 data-bind="text: title"></h1>';

	echo "<div id=\"content\">";

	include "core/pages/" . $page . ".php";

	echo "</div>";

	echo "</div>";

	output_footer();

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