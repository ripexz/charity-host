<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');

	output_admin_header("Gallery", $_SESSION["charity_name"], "admin");
	echo '<button class="btn btn-lg btn-primary btn-top-right" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#uploadModal">Upload</button>';
	echo '<div>';

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];
	$result = $conn->query("SELECT i.* FROM images i JOIN charity_images ci ON ci.image_id = i.id WHERE ci.charity_id = {$charity_id} ORDER BY i.id DESC");

	echo '<div><input data-bind="value: searchText, valueUpdate: \'afterkeydown\'" type="text" class="form-control" placeholder="Search"></div>';
	echo '<div id="image-list">';
	//todo
	echo '<p data-bind="text: searchText"></p>';
	echo '</div>';

	// Upload modal:
	echo '<div id="uploadModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Upload an image</h4>
					</div>
					<div class="modal-body">
						<p>Upload form goes here</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary">Upload</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->';

	// Load additional JS:
	echo "<script type=\"text/javascript\" src=\"/core/js/gallery.js\"></script>";

	echo '</div>';
	output_admin_footer();

?>