$(document).ready(function() {
	highlightAdminNav();
	bindColourPicker();
	bindImageUploader();

	$('#lnf-settings-form').on('submit', updateLostFoundSettings);
});

function updatePreview(el) {
	var linkEl = document.getElementById("urlPreview");
	linkEl.innerText = el.value.toLowerCase();
}

function highlightAdminNav() {
	var location = window.location.href.split('/'),
		page = location[location.length - 1];

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