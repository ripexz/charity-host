<?php

	function output_base_header($title, $bodyClasses = "") {
		echo "<!DOCTYPE html>
			<html lang=\"en\">
				<head>
					<meta charset=\"utf-8\" />
					<title>{$title} | Charity Host</title>
					<script src=\"/core/js/knockout-3.0.0.js\"></script>
					<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
					<script src=\"//tinymce.cachefly.net/4.0/tinymce.min.js\"></script>
					<script src=\"/core/js/bootstrap.min.js\"></script>
					<script src=\"/core/js/main.js\"></script>
					<script src=\"/core/js/admin.js\"></script>
					<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family=Open+Sans:400,700,600\">
					<link rel=\"stylesheet\" href=\"/core/css/bootstrap.min.css\" />
					<link rel=\"stylesheet\" href=\"/core/css/bootstrap-theme.min.css\" />
					<link rel=\"stylesheet\" href=\"/core/css/admin.css\" />
				</head>
			 <body class=\"{$bodyClasses}\">
				<div id=\"wrapper\"> <!-- start of wrapper -->";
	}

	function output_base_footer() {
		echo "</div> <!-- end of wrapper -->
			</body>
			</html>";
	}

	function output_admin_header($title, $charity_name, $bodyClasses = "") {
		output_base_header($title, $bodyClasses);
		echo '<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a target="_blank" class="navbar-brand" href="http://www.charityhost.eu/'.$_SESSION['charity_link'].'">'.$charity_name.'</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#">Dashboard</a></li>
						<li><a href="settings.php">Settings</a></li>
						<li><a href="logout.php">Log out</a></li>
					</ul>
				</div>
			</div>
		</div>';

		echo '<div class="container-fluid">
				<div class="row">
					<div class="col-sm-3 col-md-2 sidebar">
						<ul id="admin_snav" class="nav nav-sidebar">
							<li><a href="dashboard.php">Overview</a></li>
							<li><a href="pages.php">Pages</a></li>
							<li><a href="lostfound.php">Lost & Found</a></li>
							<li><a href="#">Sponsored Animals</a></li>
							<li><a href="gallery.php">Gallery</a></li>
						</ul>
						<ul class="nav nav-sidebar">
							<li><a href="settings.php">Settings</a></li>
							<li><a href="#">Users</a></li>
						</ul>
						<ul class="nav nav-sidebar">
							<li><a href="#">Your profile</a></li>
							<li><a href="logout.php">Log out</a></li>
						</ul>
					</div>
					<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">';
		echo "<h1 class=\"page-header\">{$title}</h1>";
	}

	function output_admin_footer() {
		echo '</div> </div> </div>';
		output_base_footer();
	}

?>