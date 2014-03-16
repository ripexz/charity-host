<?php

	require_once('core/lib/db.php');

	//check params
	if (isset($_POST['charity_id'])) {
		$charity_id = (int) $_POST['charity_id'];
		
		$amount = (float) $_POST['amount'];
		$amount = (int) ($amount * 100);

		if ($charity_id > 0 && $amount > 0) {

			$db = new db(null);
			$conn = $db->connect();

			//Get charity PayPal address
			$res = $conn->query("SELECT paypal FROM charities WHERE id = {$charity_id}");
			$paypal = "";
			if ($res->num_rows == 1) {
				$cdata = $res->fetch_assoc();
				$paypal = $cdata['paypal'];
			}

			if ($paypal != "") {
				if (isset($_POST['animal_id'])) {
					// Sponsoring an animal
					$animal_id = (int) $_POST['animal_id'];
					$query = "INSERT INTO donations (amount, animal_id, timestamp) VALUES ({$amount}, {$animal_id}, NOW())";
				}
				else {
					// General donations
					$query = "INSERT INTO donations (amount, timestamp) VALUES ({$amount}, NOW())";
				}
				$result = $conn->query($query);

				if ($result) {
					$donation_id = $conn->insert_id;
					$result2 = $conn->query("INSERT INTO charity_donations (donation_id, charity_id) VALUES ({$donation_id}, {$charity_id})");
				}

				if ($result2) {
					$amount_str = (string) ($amount / 100);
					echo '<h1 style="text-align:center">Processing...</h1>
							<form name="donateForm" action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_donations">
							<input type="hidden" name="business" value="'.$paypal.'">
							<input type="hidden" name="lc" value="IE">
							<input type="hidden" name="no_note" value="0">
							<input type="hidden" name="currency_code" value="EUR">
							<input type="hidden" name="return" value="http://www.charityhost.eu/donate_success.php?id='.$donation_id.'">
							<input type="hidden" name="cancel_return" value="http://www.charityhost.eu/donate_fail.php?id='.$donation_id.'">
							<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
							<input type="hidden" name="amount" value="'.$amount_str.'">
							<noscript>
								<p>Please click Confirm to continue</p>
								<input type="submit" value="Confirm" />
							</noscript>
						</form>
						<script type="text/javascript">
							document.donateForm.submit();
						</script>';
					exit();
				}
			}
		}
	}

	// Return to source on error:
	header('Location: ' . $_SERVER['HTTP_REFERER']);

?>