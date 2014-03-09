<?

	require_once("db.php");
	require_once("util.php");

	/*
	* Checks if page is valid, if yes - generates and outputs
	* requested page
	*/
	function output_charity_page($request, $name, $charity_id) {

		if ($request[1]) {
			switch ($request[1]) {
				case 'lostfound':
					$page_data = get_lnf_data($charity_id);
					break;

				case 'sponsor':
					$page_data = get_sa_data($charity_id);
					break;

				default:
					$page_data = get_page_data($request, $charity_id);
					break;
			}
		}
		else {
			$page_data = get_page_data($request, $charity_id);
		}

		if (!$page_data) {
			show_not_found();
			exit();
		}

		output_charity_header($request, $name, $charity_id, $page_data["title"]);
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

	/*
	* Generates and outputs charity site header
	*/
	function output_charity_header(&$request, $charity, $charity_id, $title = "Home") {
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
		
		get_charity_nav($request, $charity_id);

		echo 			'</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
			</header>';

		echo '<div class="container page-title">
				<h2>'.$title.'</h2>
			</div>';
	}

	/*
	* Generates and outputs charity page footer
	*/
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

	/*
	* Retrieves charity page data from the database
	*/
	function get_page_data(&$request, $charity_id) {
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

	/*
	* Checks if Lost and Found is enabled, if yes returns
	* relevant page data
	*/
	function get_sa_data($charity_id) {
		$features = get_feature_status($charity_id);

		if ($features["sa_enabled"]) {
			$data = array();

			$data["sidebar"] = "none";
			$data["title"] = "Lost and found";
			$data["link"] = "lostfound";

			$data["content"] = '<div id="lost-and-found" data-bind="foreach: visibleAnimals">
								<div class="lnf">
									<div class="lnf-image">
										<img data-bind="event: {error: changeHashCode}, attr: {src: \'/core/phpthumb/phpThumb.php?src=/core/uploads/\' + url + \'&w=211&f=png&sia=\' + title + hashCode(), alt: title}"/>
									</div>
									<div class="lnf-title">
										<p data-bind="text: title"></p>
									</div>
									<div data-bind="html: description" class="lnf-desc"></div>
								</div>
							</div>
							<script src="/core/js/lnf.js"></script>';
			return $data;
		}

		return false;
	}

	/*
	* Checks if Sponsored Animals is enabled, if yes returns
	* relevant page data
	*/
	function get_sa_data($charity_id) {
		$features = get_feature_status($charity_id);

		if ($features["sa_enabled"]) {
			$data = array();

			$data["sidebar"] = "none";
			$data["title"] = "Sponsor an animal";
			$data["link"] = "lostfound";

			$data["content"] = '<div id="sponsored-animals" data-bind="foreach: visibleAnimals">
								<div class="sa">
									<div class="sa-image">
										<img data-bind="event: {error: changeHashCode}, attr: {src: \'/core/phpthumb/phpThumb.php?src=/core/uploads/\' + url + \'&w=211&f=png&sia=\' + title + hashCode(), alt: title}"/>
									</div>
									<div class="sa-title">
										<p data-bind="text: title"></p>
									</div>
									<div data-bind="html: description" class="sa-desc"></div>
								</div>
							</div>
							<script src="/core/js/sa.js"></script>';
			return $data;
		}

		return false;
	}

	/*
	* Generates and outputs charity nav <li>s
	*/
	function get_charity_nav(&$request, $charity_id) {
		$charity_link = $request[0];
		$curr_link = 'home';
		if ($request[1]) {
			$curr_link = $request[1];
		}

		$db = new db(null);
		$conn = $db->connect();

		$features = get_feature_status($charity_id);
		$lnf = $features["lnf_enabled"];
		$sa = $features["sa_enabled"];

		echo '<li' . ($curr_link == 'home' ? ' class="active"' : '') . '><a href="/'.$charity_link.'/">Home</a></li>';
		echo $lnf ? '<li' . ($curr_link == 'lostfound' ? ' class="active"' : '') . '><a href="/'.$charity_link.'/lostfound">Lost and Found</a></li>' : '';
		echo $sa ? '<li' . ($curr_link == 'sponsor' ? ' class="active"' : '') . '><a href="/'.$charity_link.'/sponsor">Sponsor an Animal</a></li>' : '';
		
		$links = $conn->query("SELECT p.link, p.title FROM pages p JOIN charity_pages ca ON p.id = ca.page_id WHERE p.link <> 'home' AND ca.charity_id = {$charity_id} ORDER BY p.id ASC");
		if ($links->num_rows > 0) {
			while ($row = $links->fetch_assoc()) {
				echo "<li" . ($curr_link == $row['link'] ? ' class="active"' : '') . "><a href=\"/{$charity_link}/{$row['link']}\">{$row['title']}</a></li>";
			}
		}
	}

	/*
	* Checks which charity features are enabled
	*/
	function get_feature_status($charity_id) {
		$data = array();
		$data["lnf_enabled"] = false;
		$data["sa_enabled"] = false;

		$db = new db(null);
		$conn = $db->connect();
		$features = $conn->query("SELECT lnf_enabled, sa_enabled FROM charities WHERE id = {$charity_id}");
		if ($features->num_rows == 1) {
			$arr = $features->fetch_assoc();
			$data["lnf_enabled"] = (bool) $arr["lnf_enabled"];
			$data["sa_enabled"] = (bool) $arr["sa_enabled"];
		}
		return $data;
	}
?>