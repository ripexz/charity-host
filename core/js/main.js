var siteName = "Charity Host";

$(document).ready(function() {
	highlightAdminNav();
	bindColourPicker();
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
	if ($("#colour-range").length > 0) {
		// Bind white option
		$(".colour-picker .white").click(function(e){
			updateColourDemo();
		});
		// Bind hue option
		$(".colour-picker .hue").click(function(e){
			updateColourDemo($("#colour-range").val());
		});
		// Bind range changer
		$("#colour-range").on("change", function(e) {
			updateColourDemo(e.target.value);
		});
	}
}