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
    $('#usersControlTable').DataTable({
      dom: '<"table-responsive"t>',
      stateSave: true,
      colReorder: true,
      language: dataTable_lang,
      paging: false
    });
    $('.summernote').summernote({
      height: '20vh',
      lang: 'ru-RU',
      styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
      toolbar: [
        ['para', ['style', 'ul', 'ol', 'paragraph']],
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['view', ['fullscreen', 'codeview']]
      ]
    });
    this.photoUpload();
    this.passwordChange();
    this.Validate();
    this.ConfirmDelete();
  },

  events: function () {
    this.linkEmployee();
  },

  linkEmployee: function () {
    $('#linkEmployee').on('hidden.bs.modal', function (e) {
      $('.linkEmployeeRadio').prop('checked', false);
      $('#linkEmployeeButton').addClass('disabled').prop('disabled', true);
    });
    $('.linkEmployeeRadio').change(function () {
      if ($('.linkEmployeeRadio:checked').length) {
        $('#linkEmployeeButton').removeClass('disabled').prop('disabled', false);
      }
    });
    $('#linkEmployeeButton').click(function () {
      if ($('.linkEmployeeRadio:checked').length) {
        var empData = $('.linkEmployeeRadio:checked').eq(0);
        $('#firstname').val(empData.data('firstname')).prop('readonly', true);
        $('#lastname').val(empData.data('lastname')).prop('readonly', true);
        $('#phone').val(empData.data('phone')).prop('readonly', true);
        $('#linked').val(empData.data('id'));
        $('#linkEmployee').modal('hide');
        $('#linkEmployeeBtn').removeClass('btn-danger').addClass('disabled btn-success').prop('disabled', true)
          .html('<i class="sli sli-content-edition-link-2"></i> Связан с '+empData.data('firstname')+' '+empData.data('lastname'));
      }
    });
  },

  photoUpload: function () {
    $('#changeAvatar').on('click', function (event) {
      event.preventDefault();
      $('#uploadAvatar').click();
    });

    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');
    var img;

    $('#uploadAvatar').change(function () {
      handleFiles(this.files);
    });

    function handleFiles(files) {
      if (files.length > 0) {
        var file = files[0];
        if (
          typeof FileReader !== 'undefined' &&
          file.type.indexOf('image') != -1
        ) {
          var reader = new FileReader();
          // Note: addEventListener doesn't work in Google Chrome for this event
          reader.onload = function (evt) {
            load(evt.target.result);
          };
          reader.readAsDataURL(file);
        }
      }
    }

    function load(src) {
      img = new Image();
      img.onload = function () {
        img.onload = null;
        var width = 720;
        var elem = document.createElement('canvas');
        var scaleFactor = width / img.width;
        elem.width = width;
        elem.height = img.height * scaleFactor;
        var ctxResized = elem.getContext('2d');
        ctxResized.drawImage(img, 0, 0, width, img.height * scaleFactor);
        var fullQuality = ctxResized.canvas.toDataURL('image/jpeg', 1.0);
        img.src = fullQuality;
        run();
      };
      img.src = src;
    }

    function run() {
      if (!img) return;
      var options = {
        width: 720,
        height: 720,
        minScale: 1,
        ruleOfThirds: true,
        debug: true
      };

      faceDetectionOpenCV(options, function () {
        analyze(options);
      });
    }

    function prescaleImage(image, maxDimension, callback) {
      // tracking.js is very slow on big images so make sure the image is reasonably small
      var width = image.naturalWidth || image.width;
      var height = image.naturalHeight || image.height;
      if (width < maxDimension && height < maxDimension)
        return callback(image, 1);
      var scale = Math.min(maxDimension / width, maxDimension / height);
      var canvas = document.createElement('canvas');
      canvas.width = ~~(width * scale);
      canvas.height = ~~(height * scale);
      canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);
      var result = document.createElement('img');
      result.onload = function () {
        callback(result, scale);
      };
      result.src = canvas.toDataURL();
    }

    function faceDetectionOpenCV(options, callback) {
      prescaleImage(img, 720, function (img, scale) {
        var src = cv.imread(img);
        var gray = new cv.Mat();
        cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY, 0);
        var faces = new cv.RectVector();
        var faceCascade = new cv.CascadeClassifier();
        // load pre-trained classifiers
        faceCascade.load('haarcascade_frontalface_default.xml');
        console.log(faceCascade);
        // detect faces
        var msize = new cv.Size(0, 0);
        // let c = document.createElement('canvas');
        // cv.imshow(c, gray);
        // document.body.appendChild(c)
        faceCascade.detectMultiScale(gray, faces, 1.1, 3, 0, msize, msize);
        options.boost = [];
        for (var i = 0; i < faces.size(); ++i) {
          var face = faces.get(i);
          options.boost.push({
            x: face.x / scale,
            y: face.y / scale,
            width: face.width / scale,
            height: face.height / scale,
            weight: 1.0
          });
        }
        src.delete();
        gray.delete();
        faceCascade.delete();
        faces.delete();
        callback();
      });
    }

    function analyze(options) {
      console.log(options);
      smartcrop.crop(img, options, draw);
    }

    function draw(result) {
      var selectedCrop = result.topCrop;
      drawCrop(selectedCrop);
    }

    function drawCrop(crop) {
      canvas.width = img.width;
      canvas.height = img.height;
      ctx.drawImage(img, 0, 0);
      var cropped = ctx.getImageData(crop.x, crop.y, crop.width, crop.height);
      canvas.width = crop.width;
      canvas.height = crop.height;
      ctx.putImageData(cropped, 0, 0);
      //ctx.drawImage(cropped, 0, 0, crop.width, crop.height);
      var fullQuality = ctx.canvas.toDataURL('image/jpeg', 1.0);
      var newImg = document.getElementById('userAvatar'); //avatarEdit
      newImg.src = fullQuality;
      $('#new_avatar').val(fullQuality);
    }

    window.openCvReady = function () {
      console.log('opencv code ready');
      loadCascade(
        'haarcascade_frontalface_default.xml',
        'https://unpkg.com/opencv.js@1.2.1/tests/haarcascade_frontalface_default.xml',
        function () {
          console.log('opencv ready');
        }
      );
    };

    function loadCascade(path, url, callback) {
      var request = new XMLHttpRequest();
      request.open('GET', url, true);
      request.responseType = 'arraybuffer';
      request.onload = function () {
        if (request.readyState === 4) {
          if (request.status === 200) {
            var data = new Uint8Array(request.response);
            cv.FS_createDataFile('/', path, data, true, false, false);
            callback();
          } else {
            self.printError(
              'Failed to load ' + url + ' status: ' + request.status
            );
          }
        }
      };
      request.send();
    }

  },

  passwordChange: function () {

    var pass_input = $('#password');

    function passwordChanged() {
      var strength = document.getElementById('strength');
      var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
      var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
      var enoughRegex = new RegExp("(?=.{6,}).*", "g");
      var pwd = document.getElementById('password');
      if (pwd.value.length === 0) {
        strength.innerHTML = '';
      } else if (false === enoughRegex.test(pwd.value)) {
        strength.innerHTML = '<span style="color:red">введите не менее 6 символов</span>';
      } else if (strongRegex.test(pwd.value)) {
        strength.innerHTML = '<span style="color:green">Хороший пароль</span>';
      } else if (mediumRegex.test(pwd.value)) {
        strength.innerHTML = '<span style="color:orange">Средний пароль</span>';
      } else {
        strength.innerHTML = '<span style="color:red">Слабый пароль</span>';
      }
    }

    pass_input.on('keyup', function (event) {
      passwordChanged();
    });

    pass_input.on('focus', function (event) {
      if (pass_input.val() === '******') {
        pass_input.val('');
        $('#password_again').val('');
      }
    });

    pass_input.on('focusout', function (event) {
      if ((pass_input.val() === '') && ($('#action').val() === 'save')) {
        pass_input.val('******');
        $('#password_again').val('******');
      }
    });

  },

  ConfirmDelete: function () {
    $(document).on('click', '.ConfirmDeleteUser', function (event) {
      event.preventDefault();
      var $this = $(this);
      swal({
        title: confirmDeleteTitle,
        text: confirmDeleteText,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: buttonDelete,
        cancelButtonText: buttonCancel,
        confirmButtonClass: 'btn btn-danger pull-right',
        cancelButtonClass: 'btn btn-o btn-default pull-left',
        buttonsStyling: false,
        reverseButtons: true,
        focusCancel: false,
        focusConfirm: true
      }).then(function (result) {
        if (result) window.location = $this.attr('href');
      }).catch(swal.noop);
      return false;
    });
  },

  Validate: function () {
    $('#UserForm').validate({
      onkeyup: false,
      onclick: false,
      onfocusout: false,
      rules: {
        firstname: {
          required: true,
          minlength: 3
        },
        lastname: {
          required: true,
          minlength: 3
        },
        phone: {
          required: true,
          mobileRU: true,
          remote: {
            url: "/route/users/checkphone",
            type: "post",
            data: {
              current_phone: function () {
                return $("#phone").data('phone');
              }
            }
          }
        },
        password: {
          required: true,
          minlength: 6
        },
        password_again: {
          equalTo: "#password"
        }
      },
      errorPlacement: function () {
        return true;
      },
      errorClass: "is-invalid",
      validClass: "is-valid",
      submitHandler: function (form) {
        form.submit();
      }
    });
  }

};

$(document).ready(function () {
  Users.initialize();
});