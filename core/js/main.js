var siteName = "Charity Host";
var alertTimeout;

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
	
	//remove any previous alert
	clearTimeout(alertTimeout);
	$('#alertMsg').remove(); 

	//show new one
	$('body').append(el);
	$('#alertMsg').slideDown("fast", function() {
		alertTimeout = setTimeout(function(){
			$('#alertMsg').fadeOut("slow", function() {
				$('#alertMsg').remove();
			});
		}, 2000);
	});
}