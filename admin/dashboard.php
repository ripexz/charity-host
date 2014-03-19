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

	output_admin_header("Overview", $_SESSION["charity_name"], "admin");
	echo '<div>';
	
	echo '<p>Welcome to your control panel.</p>
			<p>This is where you can create and edit your charity website. There are several options to the side.</p>
			<ul>
				<li><strong>Pages:</strong> Update and create new pages for your site.</li>
				<li><strong>Lost & Found:</strong> Manage your lost and found adverts.</li>
				<li><strong>Sponsor/Adopt an Animal:</strong> Add and remove animals that need help or are up for adoption.</li>
				<li><strong>Donations:</strong> View donation history.</li>
				<li><strong>Gallery:</strong> Upload or delete your images here.</li>
				<li><strong>Settings:</strong> Change your account information.</li>
				<li><strong>Users:</strong> Add and remove admins for your site</li>
			</ul>
			<p>Watch our video tutorial or refer to the FAQ section of our site if you need any help.</p>';
	
	// Get some info: 
	echo '<h3>Stats</h3>';

	$lnf_qry = "SELECT SUM(CASE lnf.approved WHEN 1 THEN 1 ELSE 0 END) AS approved,
						SUM(CASE lnf.approved WHEN 0 THEN 1 ELSE 0 END) AS unapproved
				FROM lost_and_found lnf
					JOIN charity_lost_found clf ON lnf.id = clf.lost_found_id
				WHERE clf.charity_id = {$charity_id}";
	$lnf_res = $conn->query($lnf_qry);
	if ($lnf_res->num_rows == 1) {
		$lnf = $lnf_res->fetch_assoc();
		$total = $lnf['approved'] + $lnf['unapproved'];
		echo "<p><strong>{$total}</strong> Lost and Found entries of which {$lnf['approved']} are approved and {$lnf['unapproved']} are to be reviewed.</p>";
	}

	$dons_qry = "SELECT SUM(CASE d.status WHEN 'confirmed' THEN 1 ELSE 0 END) AS confirmed,
						SUM(CASE d.status WHEN 'unconfirmed' THEN 1 ELSE 0 END) AS unconfirmed
				FROM donations d
					JOIN charity_donations cd ON d.id = cd.donation_id
				WHERE cd.charity_id = {$charity_id}";
	$dons_res = $conn->query($dons_qry);
	if ($dons_res->num_rows == 1) {
		$dons = $dons_res->fetch_assoc();
		$total = $dons['confirmed'] + $dons['unconfirmed'];
		echo "<p><strong>{$total}</strong> donations of which {$dons['confirmed']} are confirmed and {$dons['unconfirmed']} are unconfirmed or in progress.</p>";
	}

	$sa_qry = "SELECT COUNT(a.id) AS total
				FROM animals a
					JOIN charity_animals ca ON a.id = ca.animal_id
				WHERE ca.charity_id = {$charity_id}";
	$sa_res = $conn->query($sa_qry);
	if ($sa_res->num_rows == 1) {
		$sa = $sa_res->fetch_assoc();
		echo "<p><strong>{$sa['total']}</strong> animals that need to be sponsored or adopted.</strong></p>";
	}

	$adm_qry = "SELECT COUNT(a.id) AS total
				FROM admins a
					JOIN charity_admins ca ON a.id = ca.admin_id
				WHERE ca.charity_id = {$charity_id}";
	$adm_res = $conn->query($adm_qry);
	if ($adm_res->num_rows == 1) {
		$adm = $adm_res->fetch_assoc();
		echo "<p><strong>{$adm['total']}</strong> charity administrators.</strong></p>";
	}

	echo '<p>If you have any questions, please contact us at <a href="mailto:charityhosteu@gmail.com">charityhosteu@gmail.com</a></p>';

	echo '</div>';
	output_admin_footer();

?>