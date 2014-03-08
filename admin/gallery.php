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

	if (isset($_POST["submit"])) {
		$errors = array();
		$title = get_required_string($_POST, "imagetitle", "Image title", 255, $errors);

		if ($_FILES["imagefile"]["error"] !== UPLOAD_ERR_OK) {
			$errors[] = "Image upload failed.";
		}

		if ($_FILES["imagefile"]["size"] > 2097152) {
			$errors[] = "Image file is too big, please select an image that is under 2 MB.";
		}

		if (count($errors) == 0) {
			$info = getimagesize($_FILES["imagefile"]["tmp_name"]);
			if ($info === FALSE) {
				$errors[] = "Unable to determine image type of uploaded file.";
			}
			else {
				$title = $conn->real_escape_string($title);
				$extension = pathinfo($_FILES["imagefile"]["name"], PATHINFO_EXTENSION);
				$filename = $charity_id . "_" . mt_rand(10, 99) . "_" . time() . '.' . $extension;
				$upload_to = "../core/uploads/" . $filename;

				if (move_uploaded_file($_FILES["imagefile"]["tmp_name"], $upload_to)) {
					//Add db entry to images:
					$safe_url = $conn->real_escape_string($filename);
					$result = $conn->query("INSERT INTO images (title, url) VALUES ('{$title}', '{$filename}')");
					if ($result) {
						$image_id = $conn->insert_id;

						//Add db entry to charity_images:
						$result2 = $conn->query("INSERT INTO charity_images (image_id, charity_id) VALUES ({$image_id}, {$charity_id})");
					}
				}
				else {
					$errors[] = "Image upload failed.";
				}
			}
		}

		foreach ($errors as $error) {
			echo "<div class=\"alert alert-danger\"><strong>Error: </strong>{$error}</div>";
		}
	}

	echo '<div><input data-bind="value: searchText, valueUpdate: \'afterkeydown\'" type="text" class="form-control" placeholder="Search images by title"></div>';
	echo '<div id="gallery-images" data-bind="foreach: visibleImages">
			<div class="gi">
				<div class="gi-overlay">
					<button data-bind="click: function(){$root.deleteImage(id)}" class="gi-delete btn btn-sm btn-danger">Delete</button>
				</div>
				<img data-bind="attr: {onerror: changeHashCode }, src: \'/core/phpthumb/phpThumb.php?src=/core/uploads/\' + url + \'&w=211&f=png&sia=\' + title + hashCode(), alt: title}"/>
			</div>
		</div>';

	// Upload modal:
	echo '<div id="uploadModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form action="'.$_SERVER['PHP_SELF'].'" role="form" method="post" enctype="multipart/form-data">
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