<?php

	if (isset($_GET['id'])) {
		require_once('core/lib/db.php');
		$db = new db(null);
		$conn = $db->connect();
		
		$id = (int) $_GET['id'];

		$result = $conn->query("UPDATE donations SET status = 'confirmed' WHERE id = {$id}");

		$result2 = $conn->query("SELECT c.link FROM charities c JOIN charity_donations cd ON c.id = cd.charity_id JOIN donations d ON cd.donation_id = d.id WHERE d.id = {$id}");
		if ($result2) {
			$data = $result2->fetch_assoc();
			$link = $data['link'];

			header('Location: http://www.charityhost.eu/'.$link.'/thankyou?id='.$id);
		}
	}
	header('Location: http://www.charityhost.eu');

?>