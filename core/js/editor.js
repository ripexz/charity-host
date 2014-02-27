function rearrangeEditor(sidebar) {
	switch (sidebar) {
		case "right":
			$("#pf-sidebar-wrap").fadeIn(function(){
				$("#pf-sidebar-wrap").animate({
					left: "70%",
					right: "0%"
				});
			});
			$("#pf-content-wrap").animate({
				left: "0%",
				right: "30%"
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
			$("#pf-sidebar-wrap").fadeIn(function(){
				$("#pf-sidebar-wrap").animate({
					left: "0%",
					right: "70%"
				});
			});
			$("#pf-content-wrap").animate({
				left: "30%",
				right: "0%"
			});
			break;

		default:
			break;
	}
}

$(document).ready(function(){
	$("#pf-sidebar-select").find(":checked") {

	}
});