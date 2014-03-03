<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	output_admin_header("Gallery", $_SESSION["charity_name"], "admin");
	echo '<a href="editor.php" class="btn btn-lg btn-primary btn-top-right">Upload</a>';
	echo '<div>';

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];
	$result = $conn->query("SELECT i.* FROM images i JOIN charity_images ci ON ci.image_id = i.id WHERE ci.charity_id = {$charity_id} ORDER BY i.id DESC");

	echo '<div><input data-bind="value: searchText, valueUpdate: \'afterkeydown\'" type="text" class="form-control" placeholder="Search"></div>';
	echo '<div id="image-list">';
	//todo
	echo '<p data-bind="text: searchText"></p>';
	echo '</div>';

	// Load additional JS:
	echo "<script type=\"text/javascript\" src=\"/core/js/gallery.js\"></script>";

	echo '</div>';
	output_admin_footer();

?>