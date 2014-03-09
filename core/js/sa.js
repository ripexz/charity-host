var sa_vm;

function sponsoredAnimalsViewModel() {
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
}

$(document).ready(function(){
	sa_vm = new sponsoredAnimalsViewModel();

	sa_vm.getData();

	ko.applyBindings(sa_vm, $("#sponsored-animals")[0]);
});