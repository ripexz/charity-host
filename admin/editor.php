<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	$charity_id = (int) $_SESSION["charity_id"];
	$new = true;
	$title = 'New page';
	$link = '';
	$content = '';
	$sidebar = 'right';
	$sidebar_content = '';

	if (isset($_GET['id']) && $_GET['id'] > 0) { //editing existing page
		$new = false;
		$db = new db(null);
		$conn = $db->connect();
		
		$id = (int) $_GET['id'];

		$result = $conn->query("SELECT p.* FROM pages p JOIN charity_pages ca ON p.id = ca.page_id WHERE ca.charity_id = {$charity_id} AND p.id = {$id}");
		if ($result->num_rows == 1) {
			$data = $result->fetch_assoc();
			$title = $data["title"];
			$link = $data["link"];
			$content = $data["content"];
			$sidebar = $data["sidebar"];
			$sidebar_content = $data["sidebar_content"];
		}
		else {
			output_admin_header("Edit page", $_SESSION["charity_name"], "admin");
			echo "<div class=\"alert alert-danger\"><strong>Error: </strong>This page does not exist.</div>";
			output_admin_footer();
			exit();
		}
	}
	
	$h1 = $new ? "Add page" : "Edit page";
	output_admin_header($h1, $_SESSION["charity_name"], "admin");
	echo '<div>';
/*
<form role="form">
	<div class="form-group">
		<label for="exampleInputEmail1">Email address</label>
		<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1">Password</label>
		<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
	</div>
	<div class="form-group">
		<label for="exampleInputFile">File input</label>
		<input type="file" id="exampleInputFile">
		<p class="help-block">Example block-level help text here.</p>
	</div>
	<div class="checkbox">
		<label>
		<input type="checkbox"> Check me out
		</label>
	</div>
	<button type="submit" class="btn btn-default">Submit</button>
</form>*/

	echo '</div>';
	output_admin_footer();

?>