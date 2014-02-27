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

	echo "<div class=\"form-group\">
			<label>Sidebar position</label>";
	echo "<div id=\"pf-sidebar-select\">
			<label class=\"radio-inline\">
				<input";
	echo $sidebar == "left" ? " checked" : "";
	echo " name=\"sidebar\" type=\"radio\" id=\"pf-sidebar-left\" value=\"left\"> Left
			</label>
			<label class=\"radio-inline\">
				<input";
	echo $sidebar == "right" ? " checked" : "";
	echo " name=\"sidebar\" type=\"radio\" id=\"pf-sidebar-right\" value=\"right\"> Right
			</label>
			<label class=\"radio-inline\">
				<input";
	echo $sidebar == "none" ? " checked" : "";
	echo " name=\"sidebar\" type=\"radio\" id=\"pf-sidebar-none\" value=\"none\"> None
			</label>
		</div>
		</div>";

	echo "<div id=\"pf-editors-wrap\">";
	echo "<div id=\"pf-content-wrap\">
			<textarea id=\"pf-content\">{$content}</textarea>
		</div>";
	echo "<div id=\"pf-sidebar-wrap\">
			<textarea id=\"pf-sidebar-content\">{$sidebar_content}</textarea>
		</div>";
	echo "</div>";

	echo "<button name=\"submit\" type=\"submit\" class=\"btn btn-default\">Submit</button>";
	echo "</form>";

	// Load additional JS:
	echo "<script type=\"text/javascript\" src=\"/core/js/editor.js\"></script>";

	echo '</div>';
	output_admin_footer();

?>