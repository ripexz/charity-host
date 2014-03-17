var sa_vm;

function sponsorAnAnimalEntry(opts) {
	var self = this;

	self.id = opts.id;
	self.title = opts.title;
	self.description = opts.description;
	self.url = opts.image;

	self.hashCode = ko.observable('');

	self.changeHashCode = function() {
		var hashstr = '&hash=' + Date.now();
		self.hashCode(hashstr);
	}
}

function sponsorAnAnimalViewModel() {
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
				return animal.id == filter || animal.title.toLowerCase().indexOf(filter) != -1;
			});
		}
	});

	/* START: Admin functions */
	self.deleteEntry = function(id) {
		var confirmed = confirm("Are you sure you want to delete this entry?");
		if (!confirmed) {
			return;
		}
		
		$.ajax({
			url: '/core/api/private/get_delete_sa.php?id=' + id + '&f=' + Date.now(),
			type: 'GET',
			cache: false,
			dataType: 'json',
		}).done(function(data) {
			if (data.STATUS == "OK") {
				var index = self.findIndexByKeyValue(self.animals(), "id", id);
				self.animals.remove(self.animals()[index]);
				showAlert("success", "Sponsor an Animal entry deleted successfully.");
			}
		}).fail(function(data){
			showAlert("danger", data.responseJSON.MESSAGE);
		});
	}
	/* END: Admin functions */

	self.getData = function(page, pagesize) {
		var page = page || 1,
			pagesize = pagesize || 20,
			extra = '&f=' + Date.now();

		$.ajax({
			type: "GET",
			url: '/core/api/public/get_animals.php?charity_id=' + window.charity_id + '&page=' + page + '&pagesize=' + pagesize + extra,
		}).done(function(data) {
			if (data.STATUS == "OK") {
				$.each(data.animals, function(i, item) {
					lfe = new sponsorAnAnimalEntry(item)
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

$(document).ready(function(){
	sa_vm = new sponsorAnAnimalViewModel();

	sa_vm.getData();

	ko.applyBindings(sa_vm, $("#sponsor-an-animal")[0]);
});