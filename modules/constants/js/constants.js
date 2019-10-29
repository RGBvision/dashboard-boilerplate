var Constants = {

	initialized: false,

	initialize: function () {

		if (this.initialized) return;
		this.initialized = true;

		this.build();
		this.events();

	},


	build: function () {
		this.SaveButton();
	},


	events: function () {
		this.TagsInput();
		this.MouseTrapSave();
		this.onSubmitConstants();
	},


	SaveConstants: function () {
		$('#ConstantsForm').ajaxSubmit({
			url: './index.php?route=constants/save',
			dataType:  'json',
			beforeSubmit: function () {
				loader.show();
			},
			success: function (data) {
				loader.hide();
				$.jGrowl(data);
			}
		});
	},


	MouseTrapSave: function () {
		Mousetrap.bind(['ctrl+s', 'command+s'], function (event) {

			event.preventDefault();

			$('#ConstantsForm').submit();

			return false;
		});
	},


	SaveButton: function () {
		$('.SaveConstantsBtn').on('click', function (event) {

			event.preventDefault();

			$('#ConstantsForm').submit();

			return false;
		});
	},


	onSubmitConstants: function () {
		$('#ConstantsForm').on('submit', function (event) {

			event.preventDefault();

			Constants.SaveConstants();
		});
	},


	TagsInput: function () {
		$('#ConstantsForm .input-tags').tagsinput({
			tagClass: 'label text-sm bg-info'
		});
	}
};

$(document).ready(function() {
	Constants.initialize();
});