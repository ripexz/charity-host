var siteName = "Charity Host";

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
	
	$('#alertMsg').remove(); //remove any previous one
	$('body').append(el);
	$('#alertMsg').slideDown("fast", function() {
		setTimeout(function(){
			$('#alertMsg').fadeOut("slow", function() {
				$('#alertMsg').remove();
			});
		}, 2000);
	});
}