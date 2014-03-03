var gallery_vm;

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
}

$(document).ready(function() {
	gallery_vm = new galleryViewModel();
	ko.applyBindings(gallery_vm);
});