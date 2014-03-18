<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');
	require_once('../core/lib/validation.php');

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];

	output_admin_header("Gallery", $_SESSION["charity_name"], "admin");
	echo '<button class="btn btn-lg btn-primary btn-top-right" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#uploadModal">Upload</button>';
	echo '<div id="imagesView">';

	echo '<div><input data-bind="value: searchText, valueUpdate: \'afterkeydown\'" type="text" class="form-control" placeholder="Search images by title"></div>';
	echo '<div id="gallery-images" data-bind="foreach: visibleImages">
			<div class="gi">
				<div class="gi-overlay">
					<div class="gi-title">
						<p data-bind="text: title"></p>
					</div>
					<button data-bind="click: function(){$root.deleteImage(id)}" class="gi-delete btn btn-sm btn-danger">Delete</button>
				</div>
				<img data-bind="event: {error: changeHashCode}, attr: {src: \'/core/phpthumb/phpThumb.php?src=/core/uploads/\' + url + \'&w=211&f=png&sia=\' + title + hashCode(), alt: title}"/>
			</div>
		</div>';

	// Upload modal:
	echo '<div id="uploadModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form id="imageUploadForm" action="'.$_SERVER['PHP_SELF'].'" role="form" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Upload an image</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="title">Image title</label>
							<input name="imagetitle" type="text" class="form-control" id="title" placeholder="Image title" required>
						</div>
						<div class="form-group">
							<label for="imagefile">Choose an image</label>
							<input name="imagefile" type="file" id="imagefile" required>
							<p class="help-block">No larger than 2MB in size.</p>
						</div>
					</div>
					<div class="modal-footer">
						<button name="submit" type="submit" class="btn btn-primary">Upload</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->';

	// Load additional JS:
	echo "<script type=\"text/javascript\" src=\"/core/js/gallery.js\"></script>";

	echo '</div>';
	output_admin_footer();

?>