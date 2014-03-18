var gfileUrl = null,
	gfiles;

$(document).ready(function() {
	highlightAdminNav();
	bindColourPicker();
	bindImageUploader();

	$('#imageUploadForm').on('submit', uploadGalleryFiles);
	$('#imageUploadForm #imagefile').on('change', function(e){
		gfiles = e.target.files;
	});

	$('#lnf-settings-form').on('submit', updateLostFoundSettings);
	$('#sa-settings-form').on('submit', updateSponsorAnAnimalSettings);
});

function updatePreview(el) {
	var linkEl = document.getElementById("urlPreview");
	linkEl.innerText = el.value.toLowerCase();
}

function highlightAdminNav() {
	var location = window.location.href.split('/'),
		page = location[location.length - 1],
		page = page.split('?')[0];

	$("#admin_snav li").removeClass("active");
	$("#admin_snav li a[href='"+page+"']").parent().addClass("active");
}

function updateColourDemo(hue) {
	var colour = hue ? "hsl(" + parseInt(hue, 10) + ", 21%, 52%)" : "#FFF";
	$(".colour-picker .demo").css("background", colour);
}
function bindColourPicker() {
	if ($(".colour-picker").length == 1) {
		// Bind white option
		$(".colour-settings .white").click(function(e){
			$("#colour-range").css("visibility", "hidden");
			updateColourDemo();
		});
		// Bind hue option
		$(".colour-settings .hue").click(function(e){
			$("#colour-range").css("visibility", "visible");
			updateColourDemo($("#colour-range").val());
		});
		// Bind range changer
		$("#colour-range").on("change", function(e) {
			updateColourDemo(e.target.value);
		});
	}
}

function bindImageUploader() {
	if ($(".logo-settings").length == 1) {
		var uploader = $(".logo-uploader #imagefile");
		// Bind keep option
		$(".logo-settings .keep").click(function(e){
			$(".logo-uploader").css("visibility", "hidden");
			uploader.replaceWith(uploader = uploader.clone(true));
		});
		// Bind new option
		$(".logo-settings .new").click(function(e){
			$(".logo-uploader").css("visibility", "visible");
		});
	}
}

function updateLostFoundSettings(e) {
	e.stopPropagation();
	e.preventDefault();

	var form = $(e.target),
		formData = form.serialize();

	$.ajax({
		url: '/core/api/private/post_lnf_settings.php?f=' + Date.now(),
		type: 'POST',
		data: formData,
		cache: false,
		dataType: 'json',
	}).done(function(data) {
		if (data.STATUS == "OK") {
			showAlert("success", "Lost and Found settings updated successfully.");
		}
	}).fail(function(data){
		showAlert("danger", data.responseJSON.MESSAGE);
	});
}

function updateSponsorAnAnimalSettings(e) {
	e.stopPropagation();
	e.preventDefault();

	var form = $(e.target),
		formData = form.serialize();

	$.ajax({
		url: '/core/api/private/post_sa_settings.php?f=' + Date.now(),
		type: 'POST',
		data: formData,
		cache: false,
		dataType: 'json',
	}).done(function(data) {
		if (data.STATUS == "OK") {
			showAlert("success", "Sponsor an Animal settings updated successfully.");
		}
	}).fail(function(data){
		showAlert("danger", data.responseJSON.MESSAGE);
	});
}

function uploadGalleryFiles(e) {
	e.stopPropagation();
	e.preventDefault();

	// Alert if no file selected
	if (typeof gfiles == "undefined" || gfiles.length == 0) {
		showAlert('danger', 'No file selected.');
		return;
	}

	// Dont reupload file
	if (gfileUrl !== null) {
		submitGalleryForm(e, gfileUrl);
		return;
	}

	$(".loading").show();

	var data = new FormData();

	$.each(gfiles, function(key, value) {
		data.append(key, value);
	});

	$.ajax({
		url: '/core/api/private/post_upload_image.php?f=' + Date.now(),
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false, // Don't process the files
		contentType: false, // Set content type to false as jQuery will tell the server its a query string request
	}).done(function(data) {
		if (data.STATUS == "OK") {
			gfileUrl = data.imgUrl;
			submitGalleryForm(e, data.imgUrl);
		}
	}).fail(function(data){
		$(".loading").hide();
		showAlert("danger", data.responseJSON.MESSAGE);
	});
}

function submitGalleryForm(e, imgUrl) {
	var form = $(e.target);

	var formData = form.serialize();
	if (imgUrl !== null) {
		formData = formData + '&filename=' + imgUrl;
	}

	$.ajax({
		url: '/core/api/private/post_add_image.php?f=' + Date.now(),
		type: 'POST',
		data: formData,
		cache: false,
		dataType: 'json',
	}).done(function(data) {
		if (data.STATUS == "OK") {
			gfileUrl = null;
			$(".loading").hide();
			$("#uploadModal").modal('hide');
			$("#imageUploadForm")[0].reset();
			showAlert("success", "Image uploaded successfully.");
			gallery_vm.images.removeAll();
			gallery_vm.getData();
		}
	}).fail(function(data){
		$(".loading").hide();
		showAlert("danger", data.responseJSON.MESSAGE);
	});
}