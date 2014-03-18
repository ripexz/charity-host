var mainEditor, sidebarEditor;

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
			toolbar: "styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
			setup: function(editor) {
				mainEditor = editor;
			}
		});
		tinymce.init({
			selector: "#pf-sidebar-content",
			menubar: false,
			statusbar: false,
			plugins: ["link image"],
			toolbar: "styleselect | bold italic underline | bullist numlist | link image",
			setup: function(editor) {
				sidebarEditor = editor;
			}
		});
	});

	$('#galleryModal').modal({ keyboard: false, backdrop: 'static', show: false });

	//Bind changes:
	$("#pf-sidebar-select input").click(function(e){
		rearrangeEditor($(this).val());
	});

	//Bind image button:
	$('button').find('.mce-i-image').parent().click(function(e) {
		var el = $('<div class="mce-widget mce-gallery-button mce-btn mce-last mce-abs-layout-item"><button type="button">Open Gallery</button></div>');
		
		//Remove existing:
		$('.mce-gallery-button').remove();

		//Add the button:
		$('.mce-window[aria-label="Insert/edit image"] .mce-first.mce-formitem').prepend(el);
	});

	//Bind gallery button:
	$(document).delegate('.mce-gallery-button', 'click', function(e){
		$('#galleryModal').modal('show');
	});
});