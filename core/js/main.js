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

$(document).ready(function(){

	//Custom case-insensitive contains selector:
	$.expr[":"].containsci = $.expr.createPseudo(function(arg) {
		return function( elem ) {
			return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
		};
	});


});

function faqFilter(el) {
	var search = el.value;

	$('.question:not(:containsci('+search+'))').hide();
	$('#noResults').hide();
	
	var $visible = $('.question:containsci('+search+')');
	$visible.show();

	if (search.length > 0 && $visible.length == 0) {
		$('#noResults').show();
	}
}