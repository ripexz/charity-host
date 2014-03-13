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

	output_admin_header("Sponsor an Animal", $_SESSION["charity_name"], "admin");

	echo '<div id="sa-settings">
			<button id="sa-modal-toggle" class="btn btn-top-right btn-primary btn-lg" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#saModal">Add animal</button>
			<form id="sa-settings-form" action="#">
			<div class="row">
  				<div class="col-md-4">
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
  				<div class="col-md-8 submit">
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
						<span data-bind="text: \'E: \' + email"></span>
						<!-- ko if: phone --><span style="margin-left:10px;" data-bind="text: \'T: \' + phone"></span><!-- /ko -->
						<span class="type" data-bind="text: isFound == 1 ? \'Found\' : \'Lost\', css: {\'found\': isFound == 1}"></span>
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
						<input type="hidden" name="charity_id" value="'.$charity_id.'" id="sa_charity_id"/>
						<div class="form-group">
							<label for="title">Title</label>
							<input name="title" type="text" class="form-control" id="title" placeholder="Short title (e.g. type of animal, location)" required>
						</div>
						<div class="form-group">
							<label for="description">Description</label>
							<input name="description" type="text" class="form-control" id="description" placeholder="Animal description and other details" required>
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