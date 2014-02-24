<?php

	require_once("core/lib/output.php");

	$title = "Home";
	$page = "home";

	if (isset($_GET["page"]) && isset($_GET["title"])) {
		$title = trim((string)$_GET['title']);
		$page = trim((string)$_GET['page']);
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

?>