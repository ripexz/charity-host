<?php

	require_once('core/lib/db.php');
	require_once('core/lib/output.php');

	$req = "home";
	if (isset($_GET["req"])) {
		//prepare string:
		$req = trim((string) $_GET["req"]);

		//remove slashes from start or end:
		$req = trim($req, '/');
	}

	$reqArr = explode('/', $req);
	if (isset($reqArr[1])) {
		//charity stuff
		header("Location: test.php", true);
	}
	else {
		switch ($reqArr[0]) {
			case 'faq':
				$page = "faq";
				$title = "FAQ";
				break;
			
			default:
				$page = "home";
				$title = "Home";
				break;
		}

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

	/*
	$db = new db(null);
	$conn = $db->connect();

	if ($conn->connect_errno) {
		echo $conn->connect_errno;
	}
	else {
		$result = $conn->query("SELECT * FROM test");
		echo $result->num_rows;
	}
	*/

?>