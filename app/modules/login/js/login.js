const Login = {

    initialize() {
        this.build();
        this.events();
    },

    build() {
        this.loginFormValidate();
        this.resetFormValidate();
        this.newPassFormValidate();
    },

    events() {
        this.onResetModalClose();
    },

    loginFormValidate() {
        $('#loginForm').validate({
            rules: {
                email: {
                    required: true,
                    regex: /^(([^<>()\[\]\\.,;:\s@']+(\.[^<>()\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                },
                password: {
                    required: true,
                }
            },
            messages: {
                email: {
                    required: validator_i18n.required,
                    regex: validator_i18n.email,
                },
                password: {
                    required: validator_i18n.required,
                },
            },
            showErrors: DashboardCommon.validateCustomErrorMessage,
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            submitHandler: (form) => {
                form.submit();
            }
        });
    },

    resetFormValidate() {
        $('#resetForm').validate({
            rules: {
                email: {
                    required: true,
                    regex: /^(([^<>()\[\]\\.,;:\s@']+(\.[^<>()\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                },
            },
            messages: {
                email: {
                    required: validator_i18n.required,
                    regex: validator_i18n.email,
                },
            },
            showErrors: DashboardCommon.validateCustomErrorMessage,
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            submitHandler: (form) => {
                $.post(
                    $(form).attr('action'),
                    $(form).serialize(),
                ).done((data) => {
                    $('#modalReset').modal('hide');
                    Swal.fire({
                        title: '',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: buttonOk,
                    });
                }).fail((data) => {
                    Swal.fire({
                        title: '',
                        text: data.responseJSON?.message || ajaxErrorStatusMessage,
                        icon: 'error',
                        confirmButtonText: buttonOk,
                    });
                });
            }
        });
    },

    newPassFormValidate() {
        $('#newPassForm').validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                },
                password_confirm: {
                    equalTo: '#user_password'
                },
            },
            messages: {
                password: {
                    required: validator_i18n.required,
                    minlength: $.validator.format(validator_i18n.minLength)
                },
                password_confirm: {
                    equalTo: validator_i18n.equalTo,
                },
            },
            showErrors: DashboardCommon.validateCustomErrorMessage,
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            submitHandler: (form) => {
                $.post(
                    $(form).attr('action'),
                    $(form).serialize(),
                ).done((data) => {
                    Swal.fire({
                        title: '',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: buttonOk,
                    }).then(() => location.href = `${ABS_PATH}login`);
                }).fail((data) => {
                    Swal.fire({
                        title: '',
                        text: data.responseJSON?.message || ajaxErrorStatusMessage,
                        icon: 'error',
                        confirmButtonText: buttonOk,
                    }).then(() => location.href = `${ABS_PATH}login`);
                });
            }
        });
    },

    onResetModalClose() {
        $('#modalReset').on('hidden.bs.modal', () => {
            $('#resetForm')[0].reset();
        });
    }

};

$(document).ready(() => {
    Login.initialize();
});