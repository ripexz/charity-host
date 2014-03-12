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

	output_admin_header("Lost and found", $_SESSION["charity_name"], "admin");
	echo '<div id="lost-and-found">';

	echo '</div>';
	echo '<script type="text/javascript">window.charity_id = '.$charity_id.'</script>';
	echo '<script type="text/javascript" src="/core/js/lnf.js"></script>';
	output_admin_footer();

?>