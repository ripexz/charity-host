<?php

	session_start();
	if ( !isset($_SESSION['authorised']) ) {
		header("Location: index.php");
	}
	require_once('../core/lib/admin.php');
	require_once('../core/lib/db.php');
	require_once('../core/lib/validation.php');
	$errors = array();

	$db = new db(null);
	$conn = $db->connect();
	$charity_id = (int) $_SESSION["charity_id"];

	$get_settings = $conn->query("SELECT sa_enabled FROM charities WHERE id = {$charity_id}");
	$data = $get_settings->fetch_assoc();

	output_admin_header("Sponsored Animals", $_SESSION["charity_name"], "admin");

	if (isset($_POST["submit"])) {
		$errors = array();
		$title = get_required_string($_POST, "title", "Title", 255, $errors);
		$description = get_required_string($_POST, "description", "Description", 1000, $errors);

		if ($_FILES["imagefile"]["error"] !== UPLOAD_ERR_OK) {
			$errors[] = "Image upload failed.";
		}

		if ($_FILES["imagefile"]["size"] > 1048576) {
			$errors[] = "Image file is too big, please select an image that is under 1 MB.";
		}
	
		$info = getimagesize($_FILES["imagefile"]["tmp_name"]);
		if ($info === FALSE) {
			$errors[] = "Unable to determine image type of uploaded file.";
		}

		if (count($errors) == 0) {
			$title = $conn->real_escape_string($title);
			$description = $conn->real_escape_string($description);

			$extension = pathinfo($_FILES["imagefile"]["name"], PATHINFO_EXTENSION);
			$filename = $charity_id . "_sa_" . time() . '.' . $extension;
			$upload_to = "../core/uploads/" . $filename;

			if (move_uploaded_file($_FILES["imagefile"]["tmp_name"], $upload_to)) {
				//Add db entry to sponsored animals:
				$safe_url = $conn->real_escape_string("/core/uploads/" . $filename);

				$result = $conn->query("INSERT INTO animals (title, description, image) VALUES ('{$title}', '{$description}', '{$safe_url}')");
				
				if ($result) {
					$animal_id = $conn->insert_id;

					//Add db entry to charity_images:
					$result2 = $conn->query("INSERT INTO charity_animals (animal_id, charity_id) VALUES ({$animal_id}, {$charity_id})");
				}
			}
			else {
				$errors[] = "Image upload failed.";
			}
		}

		foreach ($errors as $error) {
			echo "<div class=\"alert alert-danger\"><strong>Error: </strong>{$error}</div>";
		}
	}

	echo '<div id="sa-settings">
			<button id="sa-modal-toggle" class="btn btn-top-right btn-primary btn-lg" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#saModal">Add animal</button>
			<form id="sa-settings-form" action="#">
			<div class="row">
  				<div class="col-md-2">
  					<div class="form-group">
						<label>Lost and found</label>
						<div class="logo-settings">
							<label class="radio-inline"><input type="radio" name="sa_enabled" value="1"';
	echo $data["sa_enabled"] ? ' checked ' : '';
	echo '>Enabled</label>
							<label class="radio-inline"><input type="radio" name="sa_enabled" value="0"';
	echo $data["sa_enabled"] ? '' : ' checked ';
	echo '>Disabled</label>
						</div>
  					</div>
  				</div>
  				<div class="col-md-10 submit">
  					<button class="btn btn-primary">Save settings</button>
  				</div>
  			</div>
  			</form>
  		</div>';

	echo '<div id="sponsor-an-animal">';

	echo '<input data-bind="value: searchText, valueUpdate: \'afterkeydown\'" type="text" class="form-control" placeholder="Search entries by title" />
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
						<form action="/donate.php" method="post">
							<input class="sponsor-animal-amount" type="text" name="amount" value="0.00" />
							<input type="hidden" name="charity_id" value="'$charity_id'" />
							<input data-bind="value: id" type="hidden" name="animal_id" />
							<input type="image" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" alt="Donate" />
						</form>
					</p>
				</div>
			</div>
		<!-- /ko -->';

	echo '<div id="saModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="loading"><img src="/core/images/loading.gif" alt="Loading..." /></div>
					<form id="saForm" action="#" role="form" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add a Lost and Found entry</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="title">Title</label>
							<input name="title" type="text" class="form-control" id="title" placeholder="Short title" required>
						</div>
						<div class="form-group">
							<label for="description">Description</label>
							<textarea name="description" class="form-control" id="description" placeholder="Animal description and other details" required></textarea>
						</div>
						<div class="form-group">
							<label for="imagefile">Image</label>
							<input name="imagefile" type="file" id="imagefile" required>
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
	echo '</div>';
	echo '<script type="text/javascript">window.charity_id = '.$charity_id.'</script>';
	echo '<script type="text/javascript" src="/core/js/sa.js"></script>';
	output_admin_footer();

?>