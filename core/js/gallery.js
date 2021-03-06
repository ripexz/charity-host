var gallery_vm;

function galleryImage(opts) {
	var self = this;

	self.id = opts.id;
	self.title = opts.title;
	self.url = opts.url;
	self.hashCode = ko.observable('');

	self.changeHashCode = function() {
		var hashstr = '&hash=' + Date.now();
		self.hashCode(hashstr);
	}
}

function galleryViewModel() {
	var self = this;

	self.searchText = ko.observable("");
	self.images = ko.observableArray([]);

	self.visibleImages = ko.computed(function(){
		var filter = self.searchText().toLowerCase();
		if (!filter) {
			return self.images();
		}
		else {
			return ko.utils.arrayFilter(self.images(), function(image){
				return image.title.toLowerCase().indexOf(filter) != -1;
			});
		}
	});

	self.getData = function(page, pagesize) {
		var page = page || 1,
			pagesize = pagesize || 50;

		$.ajax({
			type: "GET",
			url: '/core/api/private/get_images.php?page=' + page + '&pagesize=' + pagesize + '&f=' + Date.now(),
		}).done(function(data) {
			if (data.STATUS == "OK") {
				$.each(data.images, function(i, item) {
					var img = new galleryImage(item);
					self.images.push(img);
				});

				//load next page:
				if (data.loadmore) {
					self.getData(page+1, pagesize);
				}
			}
			else {
				alert(data.STATUS, data.MESSAGE);
			}
		});
	}

	self.deleteImage = function(id) {
		var confirmed = confirm("Are you sure you want to delete this image?");
		var id = parseInt(id, 10);

		if (confirmed) {
			$.ajax({
				type: "DELETE",
				url: '/core/api/private/get_delete_image.php?id=' + id + '&f=' + Date.now(),
			}).done(function(data) {
				if (data.STATUS == "OK") {
					var index = self.findIndexByKeyValue(self.images(), 'id', id);
					self.images.remove(self.images()[index]);
					showAlert("success", "Image deleted successfully.");
				}
			}).fail(function(data){
				showAlert("danger", data.responseJSON.MESSAGE);
			});
		}
	},

	/* Only used in editor: */
	self.selectImage = function(url) {
		var fullurl = "/core/uploads/" + url;
		$('.mce-window[aria-label="Insert/edit image"] .mce-first.mce-formitem input').val(fullurl);
		$('#galleryModal').modal('hide');
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
	var el = $("#imagesView")[0];
	gallery_vm = new galleryViewModel();
	gallery_vm.getData();

	ko.cleanNode(el);
	ko.applyBindings(gallery_vm, el);
});