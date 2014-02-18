<?php

	function output_header($title) {
		echo "<!DOCTYPE html>
			<html lang=\"en\">
				<head>
					<meta charset=\"utf-8\" />
					<title>{$title} | Charity Host</title>
					<script src=\"/core/js/knockout-3.0.0.js\"></script>
					<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
					<script src=\"/core/js/bootstrap.min.js\"></script>
					<script src=\"/core/js/main.js\"></script>
					<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family=Open+Sans:400,700,600\">
					<link rel=\"stylesheet\" href=\"/core/css/bootstrap.min.css\" />
					<link rel=\"stylesheet\" href=\"/core/css/bootstrap-theme.min.css\" />
					<link rel=\"stylesheet\" href=\"/core/css/admin.css\" />
				</head>
			 <body>
				<div id=\"wrapper\"> <!-- start of wrapper -->";
	}

	function output_footer() {
		echo "</div> <!-- end of wrapper -->
			</body>
			</html>";
	}

?>