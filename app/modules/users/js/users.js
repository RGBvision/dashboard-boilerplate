var Password = {

    _pattern: /[a-zA-Z0-9_\-\+\.]/,


    _getRandomByte: function () {
        let result = null;
        if (window.crypto && window.crypto.getRandomValues) {
            result = new Uint8Array(1);
            window.crypto.getRandomValues(result);
            return result[0];
        } else if (window.msCrypto && window.msCrypto.getRandomValues) {
            result = new Uint8Array(1);
            window.msCrypto.getRandomValues(result);
            return result[0];
        } else {
            return Math.floor(Math.random() * 256);
        }
    },

    generate: function (length) {
        return Array.apply(null, {'length': length})
            .map(function () {
                let result;
                while (true) {
                    result = String.fromCharCode(this._getRandomByte());
                    if (this._pattern.test(result)) {
                        return result;
                    }
                }
            }, this)
            .join('');
    }

};

let controlTable, controlFilter = '', controlFilterTimerId;

var Users = {

    redirect: false,
    redirect_add: false,
    initialized: false,

    initialize: function () {
        if (this.initialized) return;
        this.initialized = true;
        this.build();
        this.events();
    },

    build: function () {
        this.setDataTable();
        this.customSelect();
        this.photoUpload();
        this.addValidateMethods();
        this.formValidate();
        this.ConfirmDelete();
    },

    events: function () {
        this.onFilter();
        this.onFilterClear();
        this.onGeneratePass();
        this.onAddUserModalClose();
    },

    setDataTable() {

        const menuTpl = $('#menuTpl').html();

        controlTable = $('#controlTable').DataTable({
            language: dataTable_i18n,
            dom: '<"table-responsive mb-3"t>p',
            autoWidth: false,
            pageLength: 20,
            serverSide: true,
            searchDelay: 500,
            ajax: {
                url: "/users/get",
                type: "POST",
                data: function (d) {
                    d.search.value = controlFilter;
                }
            },
            "order": [
                [1, "asc"]
            ],
            columns: [
                {
                    data: 'avatar',
                    orderable: false,
                    render: function (data, type, row) {
                        return `<img alt="profile" class="rounded-circle" src="${data}" width="48">`
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('py-1 d-none d-md-table-cell');
                    }
                },
                {
                    data: 'lastname',
                    render: function (data, type, row) {
                        return `<a href="${ABS_PATH}users/view/${row['user_id']}">${row['lastname']} ${row['firstname']}</a>`
                    }
                },
                {
                    data: 'phone',
                    render: function (data, type, row) {
                        return `<a href="tel:${data.replace(/[^+\d]+/g, '')}">${data}</a>`
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('d-none d-md-table-cell');
                    }
                },
                {
                    data: 'email',
                    render: function (data, type, row) {
                        return `<a href="mailto:${data}">${data}</a>`
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('d-none d-md-table-cell');
                    }
                },
                {
                    data: 'group_name'
                },
                {
                    data: 'last_activity',
                    render: function (data, type, row) {
                        return data ? new Date(data).toLocaleString($locale) : '-';
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('d-none d-md-table-cell');
                    }
                },
                {
                    data: 'user_id',
                    orderable: false,
                    render: function (data, type, row) {
                        return menuTpl.replace(/:id/g, data).replace(/disabled/g, row['editable'] ? '' : 'disabled');
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('py-1');
                    }
                }
            ]
        });

        controlTable.on('page.dt', function () {
            $(':focus').blur();
            $('html, body').animate({
                scrollTop: 0
            }, 250);
        });
    },

    onFilter() {
        $('#tableFilter').on('input', () => {
            clearTimeout(controlFilterTimerId);
            controlFilterTimerId = setTimeout(
                () => {
                    controlFilter = $('#tableFilter').val();
                    controlTable.ajax.reload();
                },
                500
            );
        });
    },

    onFilterClear() {
        $('#tableFilterClear').on('click', () => {
            clearTimeout(controlFilterTimerId);
            $('#tableFilter').val('')
            controlFilter = '';
            controlTable.ajax.reload();
        });
    },

    customSelect: function () {

        function formatCountry(country) {
            if (!country.id) {
                return country.text;
            }
            return $(
                '<span>' + country.text + ' <span class="text-muted">' + $(country.element).data('country-name') + '</span>' + '</span>'
            );
        }

        function matchCustom(params, data) {
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // `params.term` should be the term that is used for searching
            // `data.text` is the text that is displayed for the data object
            if ((data.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1) || ($(data.element).data('country-name').toUpperCase().indexOf(params.term.toUpperCase()) > -1)) {
                return data;
            }

            // Return `null` if the term should not be displayed
            return null;
        }

        $('.select2').select2({
            dropdownParent: $("#addUserModal"),
            minimumResultsForSearch: 25
        });

        $('.country-select2').select2({
            dropdownParent: $("#addUserModal"),
            minimumResultsForSearch: 25,
            templateResult: formatCountry,
            matcher: matchCustom
        });
    },

    onGeneratePass: function () {
        $(document).on('click', '.genPass', (e) => {
            $(e.target).parents('.input-group').eq(0).find('.form-control').eq(0).val(Password.generate(16)).focus().blur();
            $(e.target).valid();
        })
    },

    photoUpload: function () {

        const changePhotoBtn = $('#changePhoto');

        if (changePhotoBtn.length) {

            const croppingImage = document.querySelector('#croppingImage')
            const avatarUploadInput = $('#cropperImageUpload');
            const avatarEditModal = $('#avatarEditModal');
            let cropper;

            changePhotoBtn.click(function (e) {
                e.preventDefault();
                avatarUploadInput.trigger('click');
                return false;
            });

            avatarUploadInput[0].addEventListener('change', function (e) {
                if (e.target.files.length) {

                    croppingImage.src = ABS_PATH + 'assets/images/placeholder.jpg';

                    if (cropper) {
                        cropper.destroy();
                    }

                    // start file reader
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        if (e.target.result) {
                            avatarEditModal.modal('show');
                            setTimeout(() => {
                                croppingImage.src = e.target.result;
                                cropper = new Cropper(croppingImage, {
                                    aspectRatio: 1,
                                    viewMode: 1,
                                    autoCropArea: 1
                                });
                            }, 300);
                        }
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            avatarEditModal.on('hidden.bs.modal', () => {
                avatarUploadInput[0].value = null;
            });

            $('#applyNewAvatar').click(function (e) {
                e.preventDefault();
                let imgSrc = cropper.getCroppedCanvas({
                    width: 1000
                }).toDataURL('image/jpeg', 100);
                $('.profile-pic').eq(0).attr('src', imgSrc);
                $('#newAvatar').val(imgSrc);
                avatarEditModal.modal('hide');
                $.post('/users/save_avatar', {"new_avatar": imgSrc, user_id: () => $('input[name="user_id"]').val()}, () => location.reload());
                return false;
            });

        }

    },

    ConfirmDelete: function () {
        $(document).on('click', '.ConfirmDeleteUser', function (event) {
            event.preventDefault();
            var $this = $(this);
            Swal.fire({
                title: confirmDeleteTitle,
                text: confirmDeleteText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: buttonDelete,
                cancelButtonText: buttonCancel,
            }).then(function (result) {
                if (result.isConfirmed) window.location = $this.attr('href');
            }).catch(swal.noop);
            return false;
        });
    },

    addValidateMethods() {

        $.validator.addMethod(
            "phoneWithCode",
            (value, element, arg) => {

                const code = $('select[name="code"]').val();

                if ((code == null) || (code === '')) {
                    return false;
                }

                const filtered = value.replace(/\D/g, '');

                $(element).val(filtered);

                const phoneNumber = libphonenumber.parsePhoneNumber(value, code);

                if ((filtered !== null) && (filtered !== '') && (filtered.length > 4) && phoneNumber.isValid()) {

                    $(element).val(phoneNumber.nationalNumber);
                    return true;
                }

            },
            validator_i18n.phone
        );

        $.validator.addMethod(
            "codeWithPhone",
            (value, element, arg) => {

                const valid = ((value !== null) && (value !== ''));

                if (valid) {
                    $(element).removeClass('is-invalid'); //.addClass('is-valid');
                    $(element).siblings('.select2.select2-container').eq(0).find('.select2-selection').removeClass('is-invalid');
                } else {
                    $(element).addClass('is-invalid'); // .removeClass('is-valid')
                    $(element).siblings('.select2.select2-container').eq(0).find('.select2-selection').addClass('is-invalid');
                }

                return valid;
            },
            validator_i18n.select
        );

    },

    formValidate: function () {

        $("select").on("select2:close", function (e) {
            $(this).valid();
        });

        $('select[name="code"]').on('change', function (e) {
            console.log("change");
            $('input[name="phone"]').val('');
        });

        function isPasswordPresent() {
            return $('input[name="password"]').val().length > 0;
        }

        $('#addUserForm, #saveUserForm').validate({
            rules: {
                firstname: {
                    required: true,
                    minlength: 2
                },
                lastname: {
                    required: true,
                    minlength: 2
                },
                code: {
                    required: true,
                    codeWithPhone: true
                },
                phone: {
                    required: true,
                    phoneWithCode: true,
                    remote: {
                        url: "/users/check_phone",
                        type: "post",
                        dataType: 'json',
                        data: {
                            code: () => {
                                return $('select[name="code"]').val()
                            },
                            user_id: () => {
                                const uid = $('input[name="user_id"]').val();
                                return uid ? uid : null;
                            }
                        }
                    }
                },
                email: {
                    required: true,
                    regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                    remote: {
                        url: "/users/check_email",
                        type: "post",
                        dataType: 'json',
                        data: {
                            user_id: () => {
                                const uid = $('input[name="user_id"]').val();
                                return uid ? uid : null;
                            }
                        }
                    }
                },
                password: {
                    minlength: {
                        depends: isPasswordPresent,
                        param: 6
                    }
                }
            },
            messages: {
                firstname: {
                    required: validator_i18n.required,
                    minlength: $.validator.format(validator_i18n.minLength)
                },
                lastname: {
                    required: validator_i18n.required,
                    minlength: $.validator.format(validator_i18n.minLength)
                },
                code: {
                    required: validator_i18n.required
                },
                phone: {
                    required: validator_i18n.required,
                    remote: validator_i18n.phone_used
                },
                email: {
                    required: validator_i18n.required,
                    regex: validator_i18n.email,
                    remote: validator_i18n.email_used
                },
                password: {
                    required: validator_i18n.required,
                    minlength: $.validator.format(validator_i18n.minLength)
                },
            },
            showErrors: DashboardCommon.validateCustomErrorMessage,
            errorClass: "is-invalid",
            validClass: "is-valid",
            submitHandler: (form) => {
                form.submit();
            }
        });
    },

    onAddUserModalClose: function () {
        $('#addUserModal').on('hidden.bs.modal', () => {
            $('#addUserForm *').removeClass('is-valid is-invalid').tooltip("dispose");
            $('#addUserForm')[0].reset();
            $('.country-select2').val(null).trigger('change.select2');
        });
    }

};

$(document).ready(function () {
    Users.initialize();
});