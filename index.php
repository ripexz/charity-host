<?php

	require_once('lib/db.php');
	require_once('lib/output.php');

	$page = "home";
	$title = "Home";
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		$title = $_GET['title'];
	}

	output_header($title);

	echo "<div class=\"container\">";

	echo '<h1 data-bind="text: title"></h1>';

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