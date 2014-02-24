<?php

	function output_page($page, $title) {

		output_header($title);
		echo "<div class=\"container\">";

		echo "<div id=\"content\">";
		echo "<script type=\"text/javascript\">
				$(document).ready(function(){
					mvvm.getContent('{$page}', '{$title}');
					$('.navbar li.active').removeClass('active');
					$('.navbar a[href={$page}]').parent().addClass('active');
				});
			</script>";
		echo "</div>";

		echo "</div>";
		output_footer();

	}

	function output_header($title = "Home") {
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
					<link rel=\"stylesheet\" href=\"/core/css/main.css\" />
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
					</div>
					<div class="navbar-collapse collapse" style="height: 1px;">
						<ul class="nav navbar-nav">
							<li class="active"><a class="ajax" href="home">Home</a></li>
							<li><a class="ajax" href="faq">FAQ</a></li>
							<li><a href="admin/register.php">Register</a></li>
							<li><a href="admin">Login</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>';

		echo '<div class="container">
				<h1 data-bind="text: title"></h1>
			</div>';
		echo "</header>";
	}

	function output_footer($org = "Charity Host") {
		echo "<footer>
				<div class=\"container\">
					<p class=\"pull-right\"><a href=\"#\">Back to top</a></p>
					<p>&copy;" . date('Y') . " " . $org . "</p>
				</div>
			</footer>
			</div> <!-- end of wrapper -->
			</body>
			</html>";
	}

?>