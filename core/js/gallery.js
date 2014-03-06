var gallery_vm;

function galleryImage(opts) {
	this.id = opts.id;
	this.title = opts.title;
	this.url = opts.url;
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
}

$(document).ready(function() {
	var el = $("#imagesView")[0];
	gallery_vm = new galleryViewModel();
	
	ko.cleanNode(el);
	ko.applyBindings(gallery_vm, el);
});