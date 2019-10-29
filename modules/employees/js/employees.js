var Employees = {

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
    $('#employeesControlTable').DataTable({
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
    this.Validate();
    this.ConfirmDelete();
  },

  events: function () {
    this.AddSalary();
    this.DelSalary();
    this.SortSalary();
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
      var newImg = document.getElementById('employeeAvatar'); //avatarEdit
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

  ReindexSalary: function () {
    $('.salary-row:not(.topology)').each(function (i, e) {
      $(e).find('.form-control').each(function (ii, ie) {
        var new_name = $(ie).data('name').replace('[index]', '[' + i + ']');
        $(ie).attr('name', new_name);
      });
    });
  },

  AddSalary: function () {
    $(document).on('click', '#salaryAdd', function (event) {
      $('#salaryForm .topology').clone().removeClass('d-none topology').appendTo("table#salaryForm tbody");
      Employees.ReindexSalary();
    });
  },

  DelSalary: function () {
    $(document).on('click', '.salaryDel', function (event) {
      var this_parent = $(this).parents('.salary-row').eq(0);
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
        if (result) this_parent.remove();
      }).catch(swal.noop);
    });
  },

  SortSalary: function () {
    $("#salaryFormBody").sortable({
      handle: ".salaryMove",
      opacity: 0.8,
      containment: '#salaryForm',
      update: function( event, ui ) {
        Employees.ReindexSalary();
      }
    }).disableSelection();
  },

  ConfirmDelete: function () {
    $(document).on('click', '.ConfirmDeleteEmployee', function (event) {
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
    $('#EmployeeForm').validate({
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
          mobileRU: true
        }
      },
      invalidHandler: function (form, validator) {
        var errors = validator.numberOfInvalids();
        if (errors) {
          validator.errorList[0].element.focus();
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
  Employees.initialize();
});