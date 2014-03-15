<?php

	require_once('core/lib/db.php');

	//check params
	if (isset($_GET['charity_id'])) {
		$charity_id = (int) $_GET['charity_id'];

		if ($charity_id > 0) {

			$db = new db(null);
			$conn = $db->connect();

			if (isset($_GET['animal_id'])) {
				// Sponsoring an animal
				$animal_id = (int) $_GET['animal_id'];
				$query = "";
			}
			else {
				// General donations
				$query = "";
			}
			$result = $conn->query($query);
		}
	}

	// Return to source on error:
	header('Location: ' . $_SERVER['HTTP_REFERER']);

?>