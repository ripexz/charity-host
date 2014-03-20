<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];
	
	output_admin_header("Pages", $_SESSION["charity_name"], "admin");
	echo '<a href="editor.php" class="btn btn-lg btn-primary btn-top-right">New Page</a>';
	echo '<div>';

	if (isset($_POST['submit'])) {
		$page_id = (int) $_POST['id'];
		if ($page_id > 0) {
			// validate page belongs to charity
			$sql = "SELECT p.*
					FROM pages p
						JOIN charity_pages cp ON p.id = cp.page_id
					WHERE p.id = {$page_id}
						AND cp.charity_id = {$charity_id}";
			$validate = $conn->query($sql);
			if ($validate->num_rows == 1) {
				$res1 = $conn->query("DELETE FROM pages WHERE id = {$page_id}");
				if ($res1) {
					$res2 = $conn->query("DELETE FROM charity_pages WHERE id = {$page_id}");
				}
			}
		}
	}

	$result = $conn->query("SELECT p.* FROM pages p JOIN charity_pages cp ON cp.page_id = p.id WHERE cp.charity_id = {$charity_id} ORDER BY p.id ASC");

	if ($result) {
		if ($result->num_rows > 0) {
			echo '<table class="table table-striped">
					<thead>
						<tr>
							<th>Page title</th>
							<th>Link</th>
							<th></th>
						</tr>
					</thead>
					<tbody>';
			
			while ($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo "<td>{$row['title']}</td>";
				echo "<td><a href=\"http://www.charityhost.eu/{$_SESSION['charity_link']}/{$row['link']}\" target=\"_blank\">{$row['link']}</a></td>";
				echo "<td><a href=\"editor.php?id={$row['id']}\">Edit</a></td>";
				echo '</tr>';
			}

			echo '</tbody>
				</table>';
		}
		else {
			echo '<div class="alert alert-info">There are no pages.</div>';
		}
	}
	else {
		echo "<div class=\"alert alert-danger\"><strong>Error: </strong>Pages could not be retrieved.</div>";
	}

	echo '</div>';
	output_admin_footer();

?>