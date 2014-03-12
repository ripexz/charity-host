var lnf_vm;
var files;

function lostAndFoundViewModel() {
	var self = this;

	self.searchText = ko.observable("");
	self.animals = ko.observableArray([]);

	self.visibleAnimals = ko.computed(function(){
		var filter = self.searchText().toLowerCase();
		if (!filter) {
			return self.animals();
		}
		else {
			return ko.utils.arrayFilter(self.animals(), function(animal){
				return animal.title.toLowerCase().indexOf(filter) != -1;
			});
		}
	});

	self.getData = function(page, pagesize) {
		//todo
	}
}

$(document).ready(function(){
	$('#imagefile').on('change', function(e){
		files = e.target.files;
	});

	$('#lnfForm').on('submit', uploadFiles);

	lnf_vm = new lostAndFoundViewModel();
	lnf_vm.getData();
	ko.applyBindings(lnf_vm, $("#lost-and-found")[0]);
});

function uploadFiles(e) {
	e.stopPropagation();
	e.preventDefault();

	var data = new FormData();

	$.each(files, function(key, value) {
		data.append(key, value);
	});

	var charity_id = $("#lnf_charity_id").val();

	$.ajax({
		url: '/core/api/public/post_upload_lnf_file.php?charity_id=' + charity_id,
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false, // Don't process the files
		contentType: false, // Set content type to false as jQuery will tell the server its a query string request
	}).done(function(data) {
		if (data.STATUS == "OK") {
			submitForm(e, data.imgUrl);
		}
		else {
			alert(data.STATUS, data.MESSAGE);
		}
	});
}

function submitForm(e, imgUrl) {
	var form = $(e.target);

	var formData = form.serialize();

	$.each(data.files, function(key, value) {
		formData = formData + '&filenames[]=' + value;
	});

	$.ajax({
		url: '/core/api/public/post_add_lost_found.php',
		type: 'POST',
		data: formData,
		cache: false,
		dataType: 'json',
	}).done(function(data) {
		if (data.STATUS == "OK") {
			$("#lnfModal").modal('hide');
			$("#lnfForm")[0].reset();
		}
		else {
			alert(data.STATUS, data.MESSAGE);
		}
	});
}