var siteName = "Charity Host";

$(document).ready(function() {
	highlightAdminNav();
	bindColourPicker();
	bindImageUploader();
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

function showAlert(type, text) {
	var el = $('<div id="alertMsg" class="alert alert-'+ type +'"></div>')
		.text(text)
		.css({
			"display": "none",
			"position": "fixed",
			"z-index": "99999",
			"top": "0",
			"left": "0",
			"right": "0"
		});
	$('body').append(el);
	$('#alertMsg').slideDown("fast", function() {
		/*setTimeout(function(){
			$('#alertMsg').fadeOut("slow", function() {
				$('#alertMsg').remove();
			});
		}, 2000);*/
	});
}