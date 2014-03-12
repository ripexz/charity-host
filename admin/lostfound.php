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

	output_admin_header("Lost and found", $_SESSION["charity_name"], "admin");
	echo '<div id="lost-and-found">';

	echo '<input data-bind="value: searchText, valueUpdate: \'afterkeydown\'" type="text" class="form-control" placeholder="Search entries by title" />
		<!-- ko foreach: visibleAnimals -->
			<div class="lnf">
				<div class="lnf-overlay">
					<div class="lnf-tools">
						<button data-bind="click: function(){$root.deleteEntry(id)}" class="lnf-delete btn btn-lg btn-danger">Delete</button>
						<button data-bind="click: function(){$root.approveEntry(id)}" class="lnf-approve btn btn-lg btn-success">Approve</button>
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