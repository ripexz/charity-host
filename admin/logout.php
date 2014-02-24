<?php

	session_start();
	if ( isset($_SESSION['authorised']) ) {
		session_unset();
		session_destroy();
	}
	header('Location: index.php');
	
?>