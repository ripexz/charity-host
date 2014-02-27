<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	output_admin_header("Dashboard", $_SESSION["charity_name"], "admin");

	echo '<p>Content goes here</p>';

	output_admin_footer();

?>