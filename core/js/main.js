var siteName = "Charity Host";
var mvvm;

function viewModel() {
	var self = this;

	self.title = ko.observable("Charity Host");

	self.getContent = function(page, title, dontPushState) {
		self.title(title);

		var psObject = {
				page: page,
				title: title
			};

		$.ajax({
			type: "GET",
			url: 'core/pages/' + page + '.php'
		}).done(function(data) {
			$("#content").html(data);
			if (title != siteName) {
				document.title = title + ' | ' + siteName;
			}
			else {
				document.title = "Home | " + siteName;
			}
			
			if (dontPushState !== true) {
				window.history.pushState(psObject, title, page);
			}
			$("header .navbar li.active").removeClass("active");
			$(".navbar a[href=" + page + "]").parent().addClass("active");
		});
	}
}

$(document).ready(function() {

	mvvm = new viewModel();
	ko.applyBindings(mvvm);

	$('header .navbar a.ajax').click(function(e){
		e.preventDefault();
		var page = $(this).attr('href'),
			title = $(this).text() == "Home" ? siteName : $(this).text();

		mvvm.getContent(page, title);
	});
});

window.onpopstate = function (e) {
	if (e.state == null) {
		return;
	}

	var page = e.state.page,
		title = e.state.title;

	console.log(e.state);

	mvvm.getContent(page, title, true);
};