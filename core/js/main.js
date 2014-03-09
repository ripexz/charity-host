var siteName = "Charity Host";

$(document).ready(function() {
	highlightAdminNav();
	updateColourPicker();
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

function updateColourPicker() {
	$("#colour-range").on("change", function(e) {
		var value = e.target.value,
			hsl = "hsl(" + value + ", 21%, 52%)";
		$(".colour-picker .demo").css("background", hsl);
	});
}