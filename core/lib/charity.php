<?

	require_once("db.php");
	require_once("util.php");

	/*
	* Checks if page is valid, if yes - generates and outputs
	* requested page
	*/
	function output_charity_page($request, $name, $charity_id, $contacts, $bg_color = -1, $logo = "/core/images/logo.png") {

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

		output_charity_header($request, $name, $charity_id, $bg_color, $logo, $page_data["title"]);
		echo '<div class="container">
				<div class="row">';

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
		output_charity_footer($name, $contacts);

	}

	/*
	* Generates and outputs charity site header
	*/
	function output_charity_header(&$request, $charity, $charity_id, $color, $logo, $title = "Home") {
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
					<style>";
		if ($color == -1) {
			echo "body{background:#FFF;}div.sidebar,div.content{border:1px solid #999;}div.page-title h2{color:#222;}";
		}
		else {
			echo "body {background:hsl(" . $color . ", 21%, 52%);}";
		}
		echo	"	</style>
					<script type=\"text/javascript\">window.charity_id = {$charity_id}</script>
				</head>
				<body>
					<div id=\"wrapper\"> <!-- start of wrapper -->";

		echo "<header>";

		echo '<div class="container titlebar">
				<div class="logo"><img src="'.$logo.'" alt="Logo"></div>
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
	function output_charity_footer($charity, $contacts, $org = "Charity Host") {
		echo "<footer>
				<div class=\"container\">
					<p class=\"pull-right\"><a href=\"#\">Back to top</a></p>
					<div class=\"contact-details\">";

		echo "<p>{$charity}</p>";
		echo isset($contacts["phone"]) ? "<p>T: {$contacts['phone']}</p>" : '';
		echo isset($contacts["email"]) ? "<p>E: {$contacts['email']}</p>" : '';
		echo isset($contacts["address"]) ? "<p>{$contacts['address']}</p>" : '';
		
		echo		"</div>
					<p class=\"copyright\"><small>&copy;" . date('Y') . " " . $charity . ". Powered by " . $org . "</small></p>
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
	function get_lnf_data($charity_id) {
		$features = get_feature_status($charity_id);

		if ($features["lnf_enabled"]) {
			$data = array();

			$data["sidebar"] = "none";
			$data["title"] = "Lost and found";
			$data["link"] = "lostfound";

			$data["content"] = '<button id="lnf-modal-toggle" class="btn btn-default" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#lnfModal">Add entry</button>
								<div id="lost-and-found">
								<input data-bind="value: searchText, valueUpdate: \'afterkeydown\'" type="text" class="form-control" placeholder="Search entries by title" />
								<div data-bind="visible: visibleAnimals.length" class="blankSlate">
									<!-- ko if: searchText == "" -->
										<h3>Currently there are no lost and found entries.</h3>
									<!-- /ko -->
									<!-- ko ifnot: searchText == "" -->
										<h3>There are no lost and found entries that match your search term.</h3>
									<!-- /ko -->
								</div>
								<!-- ko foreach: visibleAnimals -->
									<div class="lnf">
										<div class="lnf-title">
											<p data-bind="text: title"></p>
										</div>
										<div class="lnf-image">
											<img data-bind="event: {error: changeHashCode}, attr: {src: \'/core/phpthumb/phpThumb.php?src=\' + url + \'&w=211&f=png&sia=\' + title + hashCode(), alt: title}"/>
										</div>
										<div class="lnf-desc">
											<div class="wrap">
												<p class="content" data-bind="text: description"></p>
											</div>
										</div>
										<div class="lnf-footer">
											<p>
												<span data-bind="text: \'Email: \' + email"></span>
												<!-- ko if: phone --><span style="margin-left:10px;" data-bind="text: \'Phone: \' + phone"></span><!-- /ko -->
												<span class="type" data-bind="text: isFound == 1 ? \'Found\' : \'Lost\', css: {\'found\': isFound == 1}"></span>
											</p>
										</div>
									</div>
								<!-- /ko -->
							</div>';

			$data["content"] .= '<div id="lnfModal" class="modal fade">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="loading"><img src="/core/images/loading.gif" alt="Loading..." /></div>
										<form id="lnfForm" action="#" role="form" method="post" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title">Add a Lost and Found entry</h4>
										</div>
										<div class="modal-body">
											<input type="hidden" name="charity_id" value="'.$charity_id.'" id="lnf_charity_id"/>
											<div class="form-group">
												<label>Entry type</label>
												<div class="lnf-type">
													<label class="radio-inline"><input type="radio" name="type_is_found" value="0" checked/>Lost</label>
													<label class="radio-inline"><input type="radio" name="type_is_found" value="1"/>Found</label>
												</div>
											</div>
											<div class="form-group">
												<label for="title">Title</label>
												<input name="title" type="text" class="form-control" id="title" placeholder="Short title (e.g. type of animal, location)" required>
											</div>
											<div class="form-group">
												<label for="description">Description</label>
												<input name="description" type="text" class="form-control" id="description" placeholder="Animal description and other details" required>
											</div>
											<div class="form-group">
												<label for="email">Email address</label>
												<input name="email" type="email" class="form-control" id="email" placeholder="Contact email address" required>
											</div>
											<div class="form-group">
												<label for="phone">Phone number</label>
												<input name="phone" type="text" class="form-control" id="phone" placeholder="Contact phone number (optional)">
											</div>
											<div class="form-group">
												<label for="imagefile">Image</label>
												<input name="imagefile" type="file" id="imagefile">
												<p class="help-block">No larger than 1MB in size.</p>
											</div>
										</div>
										<div class="modal-footer">
											<button name="submit" type="submit" class="btn btn-primary">Submit</button>
											<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										</div>
										</form>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->';

			$data["content"] .= '<script src="/core/js/lnf.js"></script>';
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
			$data["title"] = "Sponsor an Animal";
			$data["link"] = "lostfound";

			$data["content"] = '<div id="sponsor-an-animal">
								<input data-bind="value: searchText, valueUpdate: \'afterkeydown\'" type="text" class="form-control" placeholder="Search entries by title" />
								<div data-bind="visible: visibleAnimals.length" class="blankSlate">
									<!-- ko if: searchText == "" -->
										<h3>Currently there are no animals you can sponsor.</h3>
									<!-- /ko -->
									<!-- ko ifnot: searchText == "" -->
										<h3>There are no animals that match your search term.</h3>
									<!-- /ko -->
								</div>
								<!-- ko foreach: visibleAnimals -->
									<div class="sa">
										<div class="sa-title">
											<p data-bind="text: title"></p>
										</div>
										<div class="sa-image">
											<img data-bind="event: {error: changeHashCode}, attr: {src: \'/core/phpthumb/phpThumb.php?src=\' + url + \'&w=211&f=png&sia=\' + title + hashCode(), alt: title}"/>
										</div>
										<div class="sa-desc">
											<div class="wrap">
												<p class="content" data-bind="text: description"></p>
											</div>
										</div>
										<div class="sa-footer">
											<p>
												<span data-bind="text: \'Email: \' + email"></span>
												<!-- ko if: phone --><span style="margin-left:10px;" data-bind="text: \'Phone: \' + phone"></span><!-- /ko -->
												<span class="type" data-bind="text: isFound == 1 ? \'Found\' : \'Lost\', css: {\'found\': isFound == 1}"></span>
											</p>
										</div>
									</div>
								<!-- /ko -->
							</div>';
			$data["content"] .= '<script src="/core/js/sa.js"></script>';
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