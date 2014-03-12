function rearrangeEditor(sidebar, callback) {
	switch (sidebar) {
		case "right":
			$("#pf-content-wrap").animate({
				left: "0%",
				right: "30%"
			});
			$("#pf-sidebar-wrap").fadeIn(function(){
				$("#pf-sidebar-wrap").animate({
					left: "70.1%",
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
					right: "70.1%"
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
			selector: "#pf-content",
			menubar: false,
			statusbar: false,
			plugins: ["link image"],
			toolbar: "styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
		});
		tinymce.init({
			selector: "#pf-sidebar-content",
			menubar: false,
			statusbar: false,
			plugins: ["link image"],
			toolbar: "styleselect | bold italic underline | bullist numlist | link image"
		});
	});

	//Bind changes:
	$("#pf-sidebar-select input").click(function(e){
		rearrangeEditor($(this).val());
	});
});