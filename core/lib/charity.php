<?

	require_once("db.php");
	require_once("util.php");

	function output_charity_page($request, $name, $charity_id) {

		$page_data = get_page_data($request, $charity_id);
		if (!$page_data) {
			show_not_found();
			exit();
		}

		output_charity_header($request[0], $name, $charity_id, $page_data["title"]);
		echo '<div class="container">
				<div class="row">';

		// Demo colour script:
		echo "<script>
				var hue = 0;
				$(document).ready(function() {
					setInterval(function(){
						hue = (hue == 360) ? 1 : hue + 1;
						document.body.style.background = 'hsl(' + hue + ', 21%, 52%)';
					}, 10);
				});
			</script>";
		
		// Render page:
		switch ($page_data["sidebar"]) {
			case "left":
				echo '<div class="col-md-3 sidebar">' . $page_data["sidebar_content"] . '</div>';
				echo '<div class="col-md-9 content">' . $page_data["content"] . '</div>';
				break;

			case "right":
				echo '<div class="col-md-9 content">' . $page_data["content"] . '</div>';
				echo '<div class="col-md-3 sidebar">' . $page_data["sidebar_content"] . '</div>';
				break;
			
			default:
				echo '<div class="col-md-12 content">' . $page_data["content"] . '</div>';
				break;
		}

		echo '</div>
			</div>';
		output_charity_footer($name);

	}

	function output_charity_header($link, $charity, $charity_id, $title = "Home") {
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
						<ul class="nav navbar-nav">';
		
		get_charity_nav($link, $charity_id);

		echo 			'</ul>
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
		$page = 'home';
		if (isset($request[1])) {
			$page = $request[1];
		}
		
		$db = new db(null);
		$conn = $db->connect();

		$page = $conn->real_escape_string($page);
		$result = $conn->query("SELECT p.* FROM pages p JOIN charity_pages ca ON p.id = ca.page_id WHERE p.link = '{$page}' AND ca.charity_id = {$charity_id}");
		
		if ($result->num_rows == 1) {
			return $result->fetch_assoc();
		}

		return false;
	}

	function get_charity_nav($charity_link, $charity_id) {
		$db = new db(null);
		$conn = $db->connect();

		$lnf = false;
		$sa = false;

		$features = $conn->query("SELECT lnf_enabled, sa_enabled FROM charities WHERE id = {$charity_id}");
		if ($features->num_rows == 1) {
			$arr = $features->fetch_assoc();
			$lnf = (bool) $arr["lnf_enabled"];
			$sa = (bool) $arr["sa_enabled"];
		}
		echo '<li><a href="/'.$charity_link.'/">Home</a></li>';
		echo $lnf ? '<li><a href="/'.$charity_link.'/lostfound">Lost and Found</a></li>' : '';
		echo $sa ? '<li><a href="/'.$charity_link.'/sponsor">Sponsor an Animal</a></li>' : '';
		
		$links = $conn->query("SELECT p.link, p.title FROM pages p JOIN charity_pages ca ON p.id = ca.page_id WHERE p.link <> 'home' AND ca.charity_id = {$charity_id} ORDER BY p.id ASC");
		if ($links->num_rows > 0) {
			foreach ($row = $links->fetch_assoc()) {
				echo "<li><a href=\"/{$charity_link}/{$row['link']}\">{$row['title']}</a></li>";
			}
		}
	}
?>