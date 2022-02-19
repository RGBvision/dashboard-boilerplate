var Mailer = {

	initialized: false,

	initialize: function () {

		if (this.initialized) return;
		this.initialized = true;

		this.build();
		this.events();

	},


	build: function () {
		this.SaveSettingsBtn();
	},


	events: function () {
		this.SettingsCodemirror();
		this.MailSwitch();
		this.MouseTrapSave();
		this.onSubmitSettings();
	},


	SettingsCodemirror: function () {
		codemirrorInit('m_t_', 'maintance_template', 'SettingsForm', null, false, 'application/x-httpd-php', '', '100%', '250px');
		codemirrorInit('h_t_', 'permission_hide', 'SettingsForm', null, false, 'text/html', '', '100%', '100px');
		codemirrorInit('p_t_', 'permission_denied', 'SettingsForm', null, false, 'text/html', '', '100%', '100px');
		codemirrorInit('m_u_', 'mail_new_user', 'SettingsForm', null, false, 'text/html', '', '100%', '200px');
		codemirrorInit('m_s_', 'mail_signature', 'SettingsForm', null, false, 'text/html', '', '100%', '100px');
	},


	SaveSettings: function () {
		$('#SettingsForm').ajaxSubmit({
			url: './index.php?route=settings/save',
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


	SaveSettingsBtn: function () {
		$('.SaveSettingsBtn').on('click', function(event) {
			event.preventDefault();

			$('#SettingsForm').submit();

			return false;
		});
	},


	MailSwitch: function () {
		if ($("#mail_type option:selected").val() != 'smtp') {
			$(".smtp_group").hide();
		}

		if ($("#mail_type option:selected").val() != 'sendmail') {
			$(".sendmail_group").hide();
		}

		$("#mail_type").on('change', function () {
			if ($("#mail_type option:selected").val() == "mail") {
				$(".smtp_group").hide();
				$(".sendmail_group").hide();
			}
			else if ($("#mail_type option:selected").val() == "smtp") {
				$(".smtp_group").show();
				$(".sendmail_group").hide();
			}
			else if ($("#mail_type option:selected").val() == "sendmail") {
				$(".smtp_group").hide();
				$(".sendmail_group").show();
			}
		}).trigger('change');
	},


	onSubmitSettings: function () {
		$('#SettingsForm').on('submit', function (event) {

			event.preventDefault();

			Mailer.SaveSettings();
		});
	},


	MouseTrapSave: function () {
		Mousetrap.bind(['ctrl+s', 'command+s'], function (event) {

			event.preventDefault();

			$('#SettingsForm').submit();

			return false;
		});
	}
};

$(document).ready(function() {
	Mailer.initialize();
});