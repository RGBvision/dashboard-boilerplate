var Settings = {

	initialize: function () {
		this.build();
		this.events();
	},


	build: function () {
		this.customSelect();
	},


	events: function () {
	},

	customSelect: function () {
		$('.select2').select2({
			minimumResultsForSearch: 25
		});
	},

};

$(document).ready(function() {
	Settings.initialize();
});