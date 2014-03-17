<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	output_admin_header("Users", $_SESSION["charity_name"], "admin");
	echo '<div>';

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];

	$sql = "SELECT a.*
			FROM admins a
				JOIN charity_admins a ON a.id = ca.admin_id
			WHERE ca.charity_id = {$charity_id}
			ORDER BY a.id ASC";
	$result = $conn->query($sql);
	if (!$result) {
		echo "<div class=\"alert alert-danger\"><strong>Error: </strong>Could not retrieve user data.</div>";
	}
	else {
		if ($result->num_rows > 0) {
			echo '<table class="table table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Email</th>
							<th colspan="2">Is owner</th>
						</tr>
					</thead>
					<tbody>';
			
			while ($row = $result->fetch_assoc()) {
				$amount = $row['amount'] / 100;
				echo '<tr>';
				echo "<td>{$row['id']}</td>";
				echo "<td>{$row['email']}</td>";
				echo $row['is_owner'] ? "<td>Yes</td>" : "<td>No</td>";
				echo $_SESSION['is_owner'] && !$row['is_owner'] ? "<td><a href=\"{$_SERVER['PHP_SELF']}?id={$row['id']}&action=delete\">Delete</a></td>" : '';
				echo '</tr>';
			}

			echo '</tbody>
				</table>';
		}
		else {
			echo "<div class=\"alert alert-info\">No users found.</div>";
		}
	}

	echo '</div>';
	output_admin_footer();

?>