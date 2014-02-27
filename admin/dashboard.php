<?php

	session_start();
	
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	output_admin_header("Dashboard", "admin");

	echo '<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Charity name</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#">Dashboard</a></li>
						<li><a href="#">Settings</a></li>
						<li><a href="logout.php">Log out</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<ul class="nav nav-sidebar">
						<li class="active"><a href="#">Overview</a></li>
						<li><a href="#">Pages</a></li>
						<li><a href="#">Lost & Found</a></li>
						<li><a href="#">Sponsored Animals</a></li>
						<li><a href="#">Gallery</a></li>
					</ul>
					<ul class="nav nav-sidebar">
						<li><a href="#">Settings</a></li>
						<li><a href="#">Users</a></li>
					</ul>
					<ul class="nav nav-sidebar">
						<li><a href="#">Your profile</a></li>
						<li><a href="logout.php">Log out</a></li>
					</ul>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h1 class="page-header">Dashboard</h1>
					<p>Content goes here</p>
				</div>
			</div>
		</div>';

	output_admin_footer();

?>