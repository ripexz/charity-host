<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	output_admin_header("Users", $_SESSION["charity_name"], "admin");
	echo '<button class="btn btn-lg btn-primary btn-top-right" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#addUserModal">Add user</button>';
	echo '<div>';

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];


	if (isset($_GET['action'])) {

		$action = (string) $_GET['action'];
		if ($action == 'delete' && $_SESSION['is_owner']) {
			$id = (int) $_GET['id'];
			if ($id > 0) {
				$res = $conn->query("SELECT admins WHERE id = {$id} AND is_owner = false");
				if ($res->num_rows == 1) {
					$res2 = $conn->query("DELETE FROM admins WHERE id = {$id}");
					if ($res2) {
						$res3 = $conn->query("DELETE FROM charity_admins WHERE admin_id = {$id}");
					}
				}
			}
		}
		if ($action == 'add') {
			//todo
		}
	}


	$sql = "SELECT a.*
			FROM admins a
				JOIN charity_admins ca ON a.id = ca.admin_id
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

	// Add user modal:
	echo '<div id="addUserModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form action="'.$_SERVER['PHP_SELF'].'?action=add" role="form" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add a user</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="email">Email address</label>
							<input id="email" name="email" type="email" class="form-control" placeholder="Enter the user\'s email address" required>
						</div>
						<div class="form-group">
							<label for="email_2">Confirm email</label>
							<input id="email_2" name="email_2" type="email" class="form-control" placeholder="Repeat the email address provided" required>
						</div>
					</div>
					<div class="modal-footer">
						<button name="submit" type="submit" class="btn btn-primary">Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->';

	echo '</div>';
	output_admin_footer();

?>