<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');
	require_once('../core/lib/validation.php');
	$errors = array();

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];
	$id = 0;

	// Handle data if submitted:
	if (isset($_POST["submit"])) {
		$id = (int) $_GET['id'];

		$valid = array();
		$valid["title"] = get_required_string($_POST, "title", "Page title", 255, $errors);
		$valid["sidebar"] = trim((string) $_POST["sidebar"]);
		$valid["content"] = trim((string) $_POST["content"]);
		$valid["sidebar_content"] = trim((string) $_POST["sidebar_content"]);

		if ($valid["sidebar"] != "none" && $valid["sidebar"] != "left" && $valid["sidebar"] != "right") {
			$errors[] = "Invalid sidebar position choice.";
		}

		if (isset($_POST["link"]) || $id == 0) {
			$valid["link"] = get_required_string($_POST, "link", "Page link", 60, $errors);
		}
		else {
			$valid["link"] = "home";
		}

		$safe = $db->escape_array($conn, $valid);

		$linkCheck = $conn->query("SELECT p.id FROM pages p JOIN charity_pages ca ON p.id = ca.page_id WHERE ca.charity_id = {$charity_id} AND p.link = '{$safe[link]}'");
		if ($linkCheck->num_rows > 0) {
			$tempArr = $linkCheck->fetch_assoc();
			$temp_id = $tempArr["id"];
			if ($id <= 0 || $temp_id != $id) {
				$errors[] = "This page link is already in use.";
			}
		}

		if (count($errors) == 0) { //all is good
			if ($id > 0) { //submitted edit
				$query = "UPDATE pages p
						JOIN charity_pages ca ON p.id = ca.page_id
						SET p.title = '{$safe[title]}',
							p.link = '{$safe[link]}',
							p.content = '{$safe[content]}',
							p.sidebar = '{$safe[sidebar]}',
							p.sidebar_content = '{$safe[sidebar_content]}'
						WHERE ca.charity_id = {$charity_id}";
				$result = $conn->query($query);
			}
			else { //submitted new
				$query = "INSERT INTO pages (title, link, content, sidebar, sidebar_content) VALUES ('{$safe[title]}', '{$safe[link]}', '{$safe[content]}', '{$safe[sidebar]}', '{$safe[sidebar_content]}')";
				$result = $conn->query($query);
				if ($result) {
					$id = $conn->insert_id;
					$conn->query("INSERT INTO charity_pages (page_id, charity_id) VALUES ({$id}, {$charity_id})");
				}
			}
		}
	}

	// Retrieve and display:
	$new = true;
	$title = '';
	$link = '';
	$content = '';
	$sidebar = 'right';
	$sidebar_content = '';

	if (isset($_GET['id']) && $_GET['id'] > 0) { //editing existing page
		$new = false;
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

	//If there are errors, show em here:
	foreach ($errors as $error) {
		echo "<div class=\"alert alert-danger\"><strong>Error: </strong>{$error}</div>";
	}

	echo "<form role=\"form\" method=\"post\" action=\"{$_SERVER['PHP_SELF']}?id={$id}\">";

	echo "<button id=\"pf-submit\" name=\"submit\" type=\"submit\" class=\"btn btn-lg btn-primary btn-top-right\">Submit</button>";
	
	echo "<div class=\"form-group\">
			<label for=\"pf-page-title\">Page title</label>
			<input name=\"title\" type=\"test\" class=\"form-control\" id=\"pf-page-title\" placeholder=\"Please enter the page title\" value=\"{$title}\" required autofocus />
		</div>";

	echo "<div class=\"form-group\">
			<label for=\"pf-page-link\">Page link</label>
			<input name=\"link\" type=\"text\" class=\"form-control\" id=\"pf-page-link\" placeholder=\"Please enter the page link\" value=\"{$link}\" onkeyup=\"updatePreview(this)\" required";
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
			<textarea name=\"content\" id=\"pf-content\">{$content}</textarea>
		</div>";
	echo "<div id=\"pf-sidebar-wrap\">
			<textarea name=\"sidebar_content\" id=\"pf-sidebar-content\">{$sidebar_content}</textarea>
		</div>";
	echo "</div>";

	echo "</form>";

	// Load additional JS:
	echo "<script type=\"text/javascript\" src=\"/core/js/editor.js\"></script>";

	echo '</div>';
	output_admin_footer();

?>