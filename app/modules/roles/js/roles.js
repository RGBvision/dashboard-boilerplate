const Roles = {

    redirect: false,
    redirect_add: false,

    initialize: function () {
        this.build();
        this.events();
    },

    build: function () {
        //this.Validate();
        //this.SaveButton();
        this.ConfirmDelete();
    },

    events: function () {
    },

    SaveButton: function () {
        $('.SaveRoleBtn').on('click', function (event) {
            event.preventDefault();
            $('#RoleForm').submit();
            return false;
        });
    },

    ConfirmDelete: function () {
        $(document).on('click', 'a[data-confirm="delete"]', function (event) {
            event.preventDefault();
            const $this = $(this);

            Swal.fire({
                title: confirmDeleteTitle,
                text: confirmDeleteText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: buttonDelete,
                cancelButtonText: buttonCancel,
            }).then(function (result) {
                if (result.isConfirmed) window.location = $this.attr('href');
            }).catch(Swal.noop);

        });
    },

    Validate: function () {
        $('#RoleForm').validate({
            onkeyup: false,
            onclick: false,
            onfocusout: false,
            rules: {
                user_role_name: {
                    required: true,
                    minlength: 3
                }
            },
            errorPlacement: function () {
                return true;
            },
            errorClass: "b-danger",
            validClass: "b-success",
            submitHandler: function (form) {
                form.submit();
            }
        });
    },

};

$(document).ready(function () {
    Roles.initialize();
});