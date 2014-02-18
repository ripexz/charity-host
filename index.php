<?php

	require_once('core/lib/db.php');
	require_once('core/lib/output.php');

	$page = "home";
	$title = "Charity Host";
	if (isset($_GET['page'])) {
		$page = htmlentities($_GET['page']);
		if ($page == "faq") {
			$title = "FAQ";
		}
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