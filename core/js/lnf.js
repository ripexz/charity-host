var lnf_vm;

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
	var files;
	$('#imagefile').on('change', prepareUpload);

	lnf_vm = new lostAndFoundViewModel();
	lnf_vm.getData();
	ko.applyBindings(lnf_vm, $("#lost-and-found")[0]);
});