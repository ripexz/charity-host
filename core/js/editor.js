function rearrangeEditor(sidebar, callback) {
	switch (sidebar) {
		case "right":
			$("#pf-content-wrap").animate({
				left: "0%",
				right: "30%"
			});
			$("#pf-sidebar-wrap").fadeIn(function(){
				$("#pf-sidebar-wrap").animate({
					left: "70%",
					right: "0%"
				});
			});
			break;
		case "none":
			$("#pf-sidebar-wrap").fadeOut();
			$("#pf-content-wrap").animate({
				left: "0%",
				right: "0%"
			});
			break;
		case "left":
			$("#pf-content-wrap").animate({
				left: "30%",
				right: "0%"
			});
			$("#pf-sidebar-wrap").fadeIn(function(){
				$("#pf-sidebar-wrap").animate({
					left: "0%",
					right: "70%"
				});
			});
			break;

		default:
			break;
	}
	if (callback) {
		callback();
	}
}

$(document).ready(function(){
	var sidebar = $("#pf-sidebar-select").find(":checked").val();
	rearrangeEditor(sidebar, function(){
		tinymce.init({
			selector: "textarea",
			statusbar: false
		});
	});

	//Bind changes:
	$("#pf-sidebar-select input").click(function(e){
		rearrangeEditor($(this).val());
	});
});