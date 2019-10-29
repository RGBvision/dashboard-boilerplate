var Store = {

  initialized: false,

  initialize: function () {

    if (this.initialized) return;
    this.initialized = true;

    this.build();

  },

  build: function () {
    this.DataTable();
    this.ConfirmDelete();
    this.Summernote();
    this.Validate();
    this.Change();
  },

  DataTable: function () {
    $('#storeConsumablesControlTable').DataTable({
      dom: 'f<"table-responsive"t>',
      stateSave: true,
      colReorder: true,
      language: dataTable_lang,
      paging: false
    });
  },

  Validate: function () {
    $('#consumableForm').validate({
      onkeyup: false,
      onclick: false,
      onfocusout: false,
      rules: {
        name: {
          required: true,
          minlength: 3
        },
        count: {
          required: true,
          number: true
        },
        unit: {
          required: true
        },
        cost: {
          required: true,
          number: true,
          min: 0
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
  },

  Change: function () {
    if ($('input[name=good_id]').length) {
      $('select[name=department]').on('change', function () {
        var $this = $(this);
        if ($this.find('option:selected').val() != $this.data('value')) {
          $this.parents('.form-group').eq(0).find('.form-text').removeClass('d-none').text('Перемещение');
        } else {
          $this.parents('.form-group').eq(0).find('.form-text').addClass('d-none').text();
        }
      });
      $('input[name=cost]').on('change', function () {
        var $this = $(this);
        if (parseFloat($this.val()) != parseFloat($this.data('value'))) {
          $this.parents('.form-group').eq(0).find('.form-text').removeClass('d-none').text('Изменение стоимости');
        } else {
          $this.parents('.form-group').eq(0).find('.form-text').addClass('d-none').text();
        }
      });
      $('input[name=count]').on('change', function () {
        var $this = $(this);
        if (parseFloat($this.val()) > parseFloat($this.data('value'))) {
          $this.parents('.form-group').eq(0).find('.form-text').removeClass('d-none').text('Приход');
        } else if (parseFloat($this.val()) < parseFloat($this.data('value'))) {
          $this.parents('.form-group').eq(0).find('.form-text').removeClass('d-none').text('Списание');
        } else {
          $this.parents('.form-group').eq(0).find('.form-text').addClass('d-none').text();
        }
      });
    }
    $('input[name=count]').on('change', function () {
      var new_val = parseFloat($(this).val());
      $(this).val(new_val.toFixed(3));
    });
    $('input[name=cost]').on('change', function () {
      var new_val = parseFloat($(this).val());
      $(this).val(new_val.toFixed(2));
    });
  },

  Summernote: function () {
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
  },

  ConfirmDelete: function () {
    $(document).on('click', '.ConfirmDeleteGood', function (event) {
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

  }

};

$(document).ready(function () {
  Store.initialize();
});