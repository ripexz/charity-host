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

	$get_settings = $conn->query("SELECT lnf_enabled, lnf_auto_approve FROM charities WHERE id = {$charity_id}");
	$data = $get_settings->fetch_assoc();

	output_admin_header("Lost and found", $_SESSION["charity_name"], "admin");

	echo '<div id="lnf-settings">
			<form id="lnf-settings-form" action="#">
			<div class="row">
  				<div class="col-md-4">
  					<div class="form-group">
						<label>Lost and found</label>
						<div class="logo-settings">
							<label class="radio-inline"><input type="radio" name="lnf_enabled" value="1"';
	echo $data["lnf_enabled"] ? ' checked ' : '';
	echo '>Enabled</label>
							<label class="radio-inline"><input type="radio" name="lnf_enabled" value="0"';
	echo $data["lnf_enabled"] ? '' : ' checked ';
	echo '>Disabled</label>
						</div>
  					</div>
  				</div>
  				<div class="col-md-4">
  					<div class="form-group">
						<label>Automatic approval</label>
						<div class="logo-settings">
							<label class="radio-inline"><input type="radio" name="lnf_auto_approve" value="1"';
	echo $data["lnf_auto_approve"] ? ' checked ' : '';
	echo '>Enabled</label>
							<label class="radio-inline"><input type="radio" name="lnf_auto_approve" value="0"';
	echo $data["lnf_auto_approve"] ? '' : ' checked ';
	echo '>Disabled</label>
						</div>
						<p class="help-block">If enabled, all new entries will be public automatically.</p>
  					</div>
  				</div>
  				<div class="col-md-4 submit">
  					<button class="btn btn-lg btn-primary">Save settings</button>
  				</div>
  			</div>
  			</form>
  		</div>';

	echo '<div id="lost-and-found">';

	echo '<input data-bind="value: searchText, valueUpdate: \'afterkeydown\'" type="text" class="form-control" placeholder="Search entries by title" />
		<!-- ko foreach: visibleAnimals -->
			<div class="lnf admin" data-bind="css: {approved: isApproved()}">
				<div class="lnf-overlay">
					<div class="lnf-tools">
						<button data-bind="click: function(){$root.deleteEntry(id)}" class="lnf-delete btn btn-lg btn-danger">Delete</button>
						<button data-bind="visible: !isApproved(), click: function(){$root.approveEntry(id)}" class="lnf-approve btn btn-lg btn-success">Approve</button>
					</div>
				</div>
				<div class="lnf-title">
					<p data-bind="text: title"></p>
				</div>
				<div class="lnf-image">
					<img data-bind="event: {error: changeHashCode}, attr: {src: \'/core/phpthumb/phpThumb.php?src=\' + url + \'&w=211&f=png&sia=\' + title + hashCode(), alt: title}"/>
				</div>
				<div class="lnf-desc">
					<div class="wrap">
						<p class="content" data-bind="text: description"></p>
					</div>
				</div>
				<div class="lnf-footer">
					<p>
						<span data-bind="text: \'E: \' + email"></span>
						<!-- ko if: phone --><span style="margin-left:10px;" data-bind="text: \'T: \' + phone"></span><!-- /ko -->
						<span class="type" data-bind="text: isFound == 1 ? \'Found\' : \'Lost\', css: {\'found\': isFound == 1}"></span>
					</p>
				</div>
			</div>
		<!-- /ko -->';

	echo '</div>';
	echo '<script type="text/javascript">window.charity_id = '.$charity_id.'</script>';
	echo '<script type="text/javascript" src="/core/js/lnf.js"></script>';
	output_admin_footer();

?>