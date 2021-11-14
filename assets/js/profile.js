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

var Profile = {

    initialize: function () {
        this.build();
        this.events();
    },

    build: function () {
        this.customSelect();
        this.photoUpload();
        this.tinymceEditor();
    },

    events: function () {
        this.onGeneratePass();
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
            minimumResultsForSearch: 25
        });

        $('.country-select2').select2({
            minimumResultsForSearch: 25,
            templateResult: formatCountry,
            matcher: matchCustom
        });
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
                $('#newAvatar').val(imgSrc);
                avatarEditModal.modal('hide');
                $.post('/profile/save_avatar', {"new_avatar": imgSrc}, () => $('.user-profile-pic').attr('src', imgSrc));
                return false;
            });

        }

    },

    onGeneratePass: function () {
        $(document).on('click', '.genPass', (e) => {
            $(e.target).parents('.input-group').eq(0).find('.form-control').eq(0).val(Password.generate(16)).focus().blur();
            $(e.target).valid();
        })
    },

    tinymceEditor() {
        if ($(".tinymce-editor").length) {

            const useDarkMode = $('body').data('theme') === 'dark';

            tinymce.init({
                selector: '.tinymce-editor',
                invalid_styles: 'color font-size background',
                theme: 'silver',
                branding: false,
                content_style: useDarkMode ? 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; color: #fff }' : 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; color: #000 }',
                plugins: ['wordcount'],
                menubar: [],
                toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist',

                setup: function (editor) {
                    editor.on("init", function () {
                        editor.addShortcut('meta+b', '', '');
                        editor.addShortcut('meta+i', '', '');
                        editor.addShortcut('meta+u', '', '');
                    });
                    editor.on('change', function () {
                        editor.save();
                    });
                }
            });
        }
    },

};

$(document).ready(function () {
    Profile.initialize();
});