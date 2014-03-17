<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	output_admin_header("Dashboard", $_SESSION["charity_name"], "admin");

	echo '<div>
			<p>Welcome to your control panel.</p>
			<p>This is where you can create and edit your charity website. There are several options to the side.</p>
			<ul>
				<li><strong>Pages:</strong> Update and create new pages for your site.</li>
				<li><strong>Lost & Found:</strong> Manage your lost and found adverts.</li>
				<li><strong>Sponsored Animals:</strong> Manage your sponsored animals.</li>
				<li><strong>Donations:</strong> View donation history.</li>
				<li><strong>Gallery:</strong> Upload or delete your images here.</li>
				<li><strong>Settings:</strong> Change your account information.</li>
				<li><strong>Users:</strong> Add and remove admins for your site</li>
			</ul>
			<p>Watch our video tutorial or refer to the FAQ section of our site if you need any help.</p>
		</div>';

	output_admin_footer();

?>