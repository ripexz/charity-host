<?

	function output_charity_page($request, $name, $id) {

		$page_data = get_page_data($request, $id);

		output_charity_header($request[0], $name);
		echo "<div class=\"container\">";
		echo '<div class="row">';

		//demo colour script:
		echo "<script>
				var hue = 0;
				$(document).ready(function() {
					setInterval(function(){
						hue = (hue == 360) ? 1 : hue + 1;
						document.body.style.background = 'hsl(' + hue + ', 21%, 52%)';
					}, 10);
				});
			</script>";
		
		echo '<div class="col-md-9 content">
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
				<p>Body content</p>
			</div>
			<div class="col-md-3 sidebar">
				<p>Sidebar content</p>
				<p>Sidebar content</p>
				<p>Sidebar content</p>
				<p>Sidebar content</p>
				<p>Sidebar content</p>
				<p>Sidebar content</p>
				<p>Sidebar content</p>
				<p>Sidebar content</p>
				<p>Sidebar content</p>
			</div>';

		echo '</div>';
		echo "</div>";
		output_charity_footer($name);

	}

	function output_charity_header($link, $charity, $title = "Home") {
		echo "<!DOCTYPE html>
			<html lang=\"en\">
				<head>
					<meta charset=\"utf-8\" />
					<title>{$title} | {$charity} | Charity Host</title>
					<script src=\"/core/js/knockout-3.0.0.js\"></script>
					<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
					<script src=\"/core/js/bootstrap.min.js\"></script>
					<script src=\"/core/js/main.js\"></script>
					<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family=Open+Sans:400,700,600\">
					<link rel=\"stylesheet\" href=\"/core/css/bootstrap.min.css\" />
					<link rel=\"stylesheet\" href=\"/core/css/bootstrap-theme.min.css\" />
					<link rel=\"stylesheet\" href=\"/core/css/charity.css\" />
				</head>
			 <body>
				<div id=\"wrapper\"> <!-- start of wrapper -->";

		echo "<header>";

		echo '<div class="container titlebar">
				<img class="logo" src="" alt="Logo">
				<h1 class="charity-name">'.$charity.'</h1>
			</div>';

		echo '<div class="navbar navbar-inverse" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<div class="collapse navbar-collapse">
						<ul class="nav navbar-nav">
							<li class="active"><a href="/'.$link.'/">Home</a></li>
							<li><a href="/'.$link.'/lostfound">Lost and Found</a></li>
							<li><a href="/'.$link.'/sponsor">Sponsor an Animal</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
			</header>';

		echo '<div class="container page-title">
				<h2>'.$title.'</h2>
			</div>';
	}

	function output_charity_footer($charity, $org = "Charity Host") {
		echo "<footer>
				<div class=\"container\">
					<p class=\"pull-right\"><a href=\"#\">Back to top</a></p>
					<p>&copy;" . date('Y') . " " . $charity . ". Powered by " . $org . "</p>
				</div>
			</footer>
			</div> <!-- end of wrapper -->
			</body>
			</html>";
	}

	function get_page_data($request, $charity_id) {
		//todo
		return false;
	}
?>