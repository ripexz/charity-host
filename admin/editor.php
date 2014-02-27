<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	$charity_id = (int) $_SESSION["charity_id"];
	$new = true;
	$title = '';
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

	echo "<form role=\"form\" method=\"post\" action=\"{$_SERVER['PHP_SELF']}?id={$id}\">";
	
	echo "<div class=\"form-group\">
			<label for=\"pf-page-title\">Page title</label>
			<input type=\"test\" class=\"form-control\" id=\"pf-page-title\" placeholder=\"Please enter the page title\" value=\"{$title}\" required autofocus />
		</div>";
	
	echo "<div class=\"form-group\">
			<label for=\"pf-page-link\">Page link</label>
			<input type=\"text\" class=\"form-control\" id=\"pf-page-link\" placeholder=\"Please enter the page link\" value=\"{$link}\" onkeyup=\"updatePreview(this)\" required";
	if ($link == "home") { 
		echo " disabled";
	}
	echo " />
			<p class=\"help-block\">http://www.eyeur.com/{$_SESSION['charity_link']}/<span id=\"urlPreview\">{$link}</span></p>
		</div>";

	echo "<button name=\"submit\" type=\"submit\" class=\"btn btn-default\">Submit</button>";
	echo "</form>";

	echo '</div>';
	output_admin_footer();

?>