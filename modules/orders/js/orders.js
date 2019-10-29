var Orders = {

  initialized: false,

  initialize: function () {

    if (this.initialized) return;
    this.initialized = true;

    this.build();

  },

  build: function () {
    this.OrdersTable();
    this.Summernote();
    this.Validate();
  },

  OrdersTable: function () {
    $('#ordersControlTable').DataTable({
      dom: 'f<"table-responsive"t>',
      stateSave: true,
      colReorder: true,
      language: dataTable_lang,
      paging: false
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

  Validate: function () {
    $('#customerForm').validate({
      onkeyup: false,
      onclick: false,
      onfocusout: false,
      rules: {
        customer_lastname: {
          required: true,
          minlength: 3
        },
        customer_firstname: {
          required: true,
          minlength: 3
        },
        customer_phone: {
          required: false,
          mobileRU: true
        },
        car_numplate: {required: true, minlength: 6}
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
        $.post("/customers/update", $('#customerForm').serialize(), function (data) {
          if (data.success) {
            $.toast({
              text: 'Данные клиента сохранены',
              position: 'bottom-right',
              loaderBg: '#fff',
              icon: 'success',
              hideAfter: 1500,
              stack: 1
            });
            $('#customerForm').parents('.modal').eq(0).modal('hide');
          } else {
            $.toast({
              text: 'Ошибка при сохранении данных',
              position: 'bottom-right',
              loaderBg: '#fff',
              icon: 'warning',
              hideAfter: 1500,
              stack: 1
            });
          }
        });
        return false;
      }
    });
  }

};

$(document).ready(function () {
  Orders.initialize();
});