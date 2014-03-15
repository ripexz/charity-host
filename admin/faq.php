<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	output_admin_header("FAQ", $_SESSION["charity_name"], "admin");

	require_once('../core/pages/faq.php');
	//$('.question:not(:contains(search))')

	output_admin_footer();

?>