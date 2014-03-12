var lnf_vm;
var files;
var fileUrl = null;
var admin;

function lostAndFoundEntry(opts) {
	var self = this;

	self.id = opts.id;
	self.title = opts.title;
	self.description = opts.description;
	self.url = opts.image;
	self.email = opts.email;
	self.phone = opts.phone;
	self.isFound = opts.isFound;
	self.isApproved = opts.isApproved == "0" ? ko.observable(false) : ko.observable(true);

	self.hashCode = ko.observable('');

	self.changeHashCode = function() {
		var hashstr = '&hash=' + Date.now();
		self.hashCode(hashstr);
	}
}

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

	/* START: Admin functions */
	self.approveEntry = function(id) {
		$.ajax({
			url: '/core/api/private/post_edit_lnf_entry.php',
			type: 'POST',
			data: {
				"id": id,
				"action": "approve"
			},
			cache: false,
			dataType: 'json',
		}).done(function(data) {
			if (data.STATUS == "OK") {
				var index = self.findIndexByKeyValue(self.animals(), "id", id);
				self.animals()[index].isApproved(true);
				showAlert("success", "Lost and Found entry approved successfully.");
			}
		}).fail(function(data){
			showAlert("danger", data.responseJSON.MESSAGE);
		});
	}

	self.deleteEntry = function(id) {
		$.ajax({
			url: '/core/api/private/post_edit_lnf_entry.php',
			type: 'POST',
			data: {
				"id": id,
				"action": "delete"
			},
			cache: false,
			dataType: 'json',
		}).done(function(data) {
			if (data.STATUS == "OK") {
				var index = self.findIndexByKeyValue(self.animals(), "id", id);
				self.animals.remove(self.animals()[index]);
				showAlert("success", "Lost and Found entry deleted successfully.");
			}
		}).fail(function(data){
			showAlert("danger", data.responseJSON.MESSAGE);
		});
	}
	/* END: Admin functions */

	self.getData = function(page, pagesize) {
		var page = page || 1,
			pagesize = pagesize || 20,
			extra = admin ? '&all=true' : '';

		$.ajax({
			type: "GET",
			url: '/core/api/public/get_lost_and_found.php?charity_id=' + window.charity_id + '&page=' + page + '&pagesize=' + pagesize + extra,
		}).done(function(data) {
			if (data.STATUS == "OK") {
				$.each(data.lost_and_found, function(i, item) {
					lfe = new lostAndFoundEntry(item)
					self.animals.push(lfe);
				});

				//load next page:
				if (data.loadmore) {
					self.getData(page+1, pagesize);
				}
			}
		}).fail(function(data){
			$(".loading").hide();
			showAlert("danger", data.responseJSON.MESSAGE);
		});
	}

	self.findIndexByKeyValue = function(array, key, value) {
		for ( i = 0; i < array.length; i++ ) {
			if ( array[i][key] == value ) {
				return i;
			}
		}
		return -1;
	}
}

$(document).ready(function() {
	var loc = window.location.href,
		lfm = 'charityhost.eu/admin/lostfound.php';
	admin = loc.indexOf('http://www.' + lfm) == 0 || loc.indexOf('http://' + lfm) == 0;
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

	// Dont reupload file
	if (fileUrl !== null) {
		submitForm(e, fileUrl);
		return;
	}

	$(".loading").show();

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
			fileUrl = data.imgUrl;
			submitForm(e, data.imgUrl);
		}
	}).fail(function(data){
		$(".loading").hide();
		showAlert("danger", data.responseJSON.MESSAGE);
	});
}

function submitForm(e, imgUrl) {
	var form = $(e.target);

	var formData = form.serialize();
	formData = formData + '&filename=' + imgUrl;

	$.ajax({
		url: '/core/api/public/post_add_lost_found.php',
		type: 'POST',
		data: formData,
		cache: false,
		dataType: 'json',
	}).done(function(data) {
		if (data.STATUS == "OK") {
			fileUrl = null;
			$(".loading").hide();
			$("#lnfModal").modal('hide');
			$("#lnfForm")[0].reset();
			if (data.autoApprove == "0") {
				showAlert("success", "Lost and Found entry submitted, but needs to be approved by an administrator.");
			}
			else {
				showAlert("success", "Lost and Found entry added successfully.");
				lnf_vm.animals.removeAll();
				lnf_vm.getData();
			}
		}
	}).fail(function(data){
		$(".loading").hide();
		showAlert("danger", data.responseJSON.MESSAGE);
	});
}