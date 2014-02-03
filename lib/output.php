<?php

	function output_header($title = "Home") {
		echo "<!DOCTYPE html>
			<html lang=\"en\">
				<head>
					<meta charset=\"utf-8\" />
					<title>{$title} | Charity Host</title>
					<script src=\"js/knockout-3.0.0.js\"></script>
					<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
					<script src=\"js/bootstrap.min.js\"></script>
					<script src=\"js/main.js\"></script>
					<link rel=\"stylesheet\" href=\"css/bootstrap.min.css\" />
					<link rel=\"stylesheet\" href=\"css/bootstrap-theme.min.css\" />
					<link rel=\"stylesheet\" href=\"css/main.css\" />
				</head>
			 <body>
				<div id=\"wrapper\"> <!-- start of wrapper -->";

		echo "<header>";
		echo '<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<span class="navbar-brand">Charity Host</span>
					</div>
					<div class="navbar-collapse collapse" style="height: 1px;">
						<ul class="nav navbar-nav">
							<li class="active"><a class="ajax" href="home">Home</a></li>
							<li><a class="ajax" href="faq">FAQ</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>';
		echo "</header>";
	}

	function output_footer($org = "Charity Host") {
		echo "<footer>
				<div class=\"container\">
					<small>&copy;" . date('Y') . " " . $org . "</small>
				</div>
			</footer>
			</div> <!-- end of wrapper -->
			</body>
			</html>";
	}

?>