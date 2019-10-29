var Documents = {

  initialize: function () {
    this.build();
  },

  build: function () {
    this.dataTables();
    this.Editor();
    this.Validator();
    this.ShowDoc();
  },

  dataTables: function () {
    $('#documentsControlTable').DataTable({
      dom: 'f<"table-responsive"t>',
      stateSave: true,
      colReorder: true,
      language: dataTable_lang,
      paging: false
    });
    $('#templatesControlTable').DataTable({
      dom: 'f<"table-responsive"t>',
      stateSave: true,
      colReorder: true,
      language: dataTable_lang,
      paging: false
    });
  },

  Editor: function () {
    $('.summernote').summernote({
      height: '30vh',
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

  Validator: function () {
    $('#tplEditForm').validate({
      onkeyup: false,
      onclick: false,
      onfocusout: false,
      rules: {
        template_name: {
          required: true,
          minlength: 3
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

  ShowDoc: function () {
    $('.docShow').click(function () {
      var url = $(this).data('url');
      App.AddModal('orderInfoModal', null,
        '<div class="page-loader__spinner mx-auto"><svg viewBox="25 25 50 50"><circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle></svg></div>',
        'modal-lg', '', '<button type="button" class="btn btn-info btn-icon" onclick="$(\'#orderInfoModal .modal-body\').print(); return false;"><span><i class="sli sli-content-edition-print-text"></i></span>Печать</button>');
      $.post(url, function (data) {
        $('#orderInfoModal').find('.modal-body').eq(0).html(data);
      });
      return false;
    });
  }

};

$(document).ready(function () {
  Documents.initialize();
});