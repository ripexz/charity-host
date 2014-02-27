function rearrangeEditor(sidebar) {
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
}

$(document).ready(function(){
	tinymce.init({
		selector: "textarea"
	});

	var sidebar = $("#pf-sidebar-select").find(":checked").val();
	rearrangeEditor(sidebar);

	//Bind changes:
	$("#pf-sidebar-select input").click(function(e){
		rearrangeEditor($(this).val());
	});
});