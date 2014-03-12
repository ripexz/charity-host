<?php

	function go_home() {
		header("Location: http://www.charityhost.eu/");
	}

	if (!isset($_GET["code"])) {
		go_home();
	}
	
	require_once('core/lib/db.php');
	$db = new db(null);
	$conn = $db->connect();

	$code = trim((string)$_GET["code"]);
	$code = $conn->real_escape_string($code);

	$check = $conn->query("SELECT id FROM lost_and_found WHERE delete_code = '{$code}'");
	if ($check->num_rows != 1) {
		go_home();
	}

	$data = $check->fetch_assoc();
	$entry_id = (int) $data["id"];

	// Get charity data:
	$sql = "SELECT c.link
			FROM charities c
				JOIN charity_lost_found clf ON clf.charity_id = c.id
				JOIN lost_and_found lf ON clf.lost_found_id = lf.id
			WHERE lf.id = {$entry_id}";
	$get_data = $conn->query($sql);
	if (!$get_data) {
		go_home();
	}

	$get_data_arr = $get_data->fetch_assoc();
	$link = $get_data_arr["link"];

	// Delete entry:
	$delete = $conn->query("DELETE FROM lost_and_found WHERE id = {$entry_id}");
	if (!$delete) {
		go_home();
	}
	
	$delete2 = $conn->query("DELETE FROM charity_lost_found WHERE lost_found_id = {$id}");
	if (!$delete2) {
		go_home();
	}

	echo '<h1 style="text-align:center;font-family:Arial,sans-serif;">Lost and found entry deleted successfully.</h1>';
	echo '<script type="text/javascript">
			setTimeout(function(){
				window.location.href = "http://www.charityhost.eu/"'.$link.'
			}, 2000);
		</script>';

?>