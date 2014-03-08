var gallery_vm;

function galleryImage(opts) {
	this.id = opts.id;
	this.title = opts.title;
	this.url = opts.url;
	this.hashCode = ko.observable('');

	this.changeHashCode = function() {
		var hashstr = Date.now() + '';
		this.hashCode(hashstr);
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
			url: '/core/api/private/get_images.php?page=' + page + '&pagesize=' + pagesize,
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
				url: '/core/api/private/delete_image.php?id=' + id,
			}).done(function(data) {
				if (data.STATUS == "OK") {
					var index = self.findIndexByKeyValue(self.images(), 'id', id);
					self.images.remove(self.images()[index]);
				}
				else {
					alert(data.STATUS, data.MESSAGE);
				}
			});
		}
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