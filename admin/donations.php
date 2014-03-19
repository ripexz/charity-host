<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	output_admin_header("Donations", $_SESSION["charity_name"], "admin");
	echo '<div>';

	echo "<div><p><strong>Note:</strong> Our donation records are not guaranteed to be 100% correct as we cannot fully track all transactions. For accurate records please check your <a href=\"https://www.paypal.com/\" target=\"_blank\">PayPal</a> history.</p></div>";

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];

	$sql = "SELECT d.*
			FROM donations d
				JOIN charity_donations cd ON d.id = cd.donation_id
			WHERE cd.charity_id = {$charity_id}
			ORDER BY d.status ASC, d.timestamp DESC";
	$result = $conn->query($sql);
	if (!$result) {
		echo "<div class=\"alert alert-danger\"><strong>Error: </strong>Could not retrieve donations data.</div>";
	}
	else {
		if ($result->num_rows > 0) {
			echo '<table class="table table-striped">
					<thead>
						<tr>
							<th>Purpose</th>
							<th>Amount</th>
							<th>Status</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>';
			
			while ($row = $result->fetch_assoc()) {
				$amount = $row['amount'] / 100;
				echo '<tr>';
				echo ($row['animal_id'] == null) ? "<td>General donation</td>" : "<td><a target=\"_blank\" href=\"sponsoredanimals.php?search={$row['animal_id']}\">Sponsored animal</a></td>";
				echo "<td>&euro;{$amount}</td>";
				echo "<td>{$row['status']}</td>";
				echo "<td>{$row['timestamp']}</td>";
				echo '</tr>';
			}

			echo '</tbody>
				</table>';
		}
		else {
			echo "<div class=\"alert alert-info\">No records found.</div>";
		}
	}

	echo '</div>';
	output_admin_footer();

?>