var Services = {

  initialized: false,

  initialize: function () {

    if (this.initialized) return;
    this.initialized = true;

    this.build();

  },

  build: function () {
    this.Sorting();
    this.ConfirmDelete();
    this.Summernote();
    this.Tabs();
    this.AddParam();
    this.AddConsumable();
    this.Validate();
    this.setSelect2();
  },

  Sorting: function () {
    var servicesTable = $('#servicesControlTable'),
      servicesDataTable = servicesTable.DataTable({
        dom: 'f<"table-responsive"t>',
        stateSave: false,
        ordering: true,
        colReorder: false,
        rowReorder: {
          selector: '.serviceMove',
          snapX: true
        },
        language: dataTable_lang,
        paging: false
      });

    servicesDataTable.on('row-reordered', function (e, diff, edit) {

      var ordering = [];

      servicesDataTable.draw().rows().nodes().to$().each(function (i, e) {
        ordering.push($(e).find('input.service-id').eq(0).val());
      });

      $.post("/route/services/sort", {data: ordering.toString()}, function (data) {
        if (data.success) {
          $.toast({
            text: 'Сортировка успешно сохранена',
            position: 'bottom-right',
            loaderBg: '#fff',
            icon: 'success',
            hideAfter: 1500,
            stack: 1
          });
        }
      });

    });

    $("#parametricFormBody").sortable({
      handle: ".paramMove",
      opacity: 0.8,
      containment: '#parametricForm',
      update: function (event, ui) {
        Services.ReindexParams();
      }
    }).disableSelection();

  },

  Validate: function () {
    $('#serviceForm').validate({
      onkeyup: false,
      onclick: false,
      onfocusout: false,
      rules: {
        service_name: {
          required: true,
          minlength: 3
        },
        max_count: {
          required: true,
          number: true,
          min: 1
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

  ReindexParams: function () {
    $('#parametricFormBody tr.params-row:not(.topology)').each(function (i, e) {
      $(e).find('.form-control[name^="calculation"]').each(function (ii, ie) {
        var e_name = $(ie).attr('name').split('['),
          e_tpl = $(ie).data('name').split('['),
          e_id = e_tpl.indexOf('index]');
        if (e_id > 0) e_name[e_id] = i + ']';
        $(ie).attr('name', e_name.join('['));
      });
      $(e).find('.paramModalBtn').each(function (ii, ie) {
        var new_target = $(ie).data('reindex').replace('index', i);
        $(ie).attr('data-target', new_target);
      });
      $(e).find('.routingModal').each(function (ii, ie) {
        var new_id = $(ie).data('reindex').replace('index', i);
        $(ie).attr('id', new_id);
      });
    });
  },

  AddParam: function () {
    $(document).on('click', '#paramAdd', function (event) {
      var newParam = $('#parametricForm .topology').clone().removeClass('d-none topology').appendTo("table#parametricForm > tbody");
      newParam.find('.form-control[data-name^="calculation"]:not(.tpl)').each(function (ii, ie) {
        $(ie).attr('name', $(ie).data('name'));
      });
      Services.ReindexParams();
    });
  },

  AddConsumable: function () {
    $(document).on('click', '.consumableAdd', function (event) {
      var consumableTbl = $(this).parents('.routingModal').eq(0).find('.routingTable'),
        consumableTblBody = consumableTbl.find('tbody');
      var consumableTblRow = consumableTbl.find('.routing-tpl').clone().removeClass('d-none routing-tpl').appendTo(consumableTblBody);
      consumableTblRow.find('.select2-tpl').removeClass('select2-tpl');
      var consumableTblRowInput = consumableTblRow.find('.form-control[data-name^="calculation"]');
      consumableTblRowInput.attr('name', consumableTblRowInput.data('name'));
      Services.setSelect2();
      Services.ReindexParams();
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
    $(document).on('click', '.ConfirmDeleteService', function (event) {
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

    $(document).on('click', '.paramDel, .consumableDelete', function (event) {
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
        if (result) {
          $this.parents('tr').eq(0).remove();
          Services.ReindexParams();
        }
      }).catch(swal.noop);
      return false;
    });

  },

  Tabs: function () {
    $('input[data-toggle="ctab"]').on('change', function () {
      var target = $(this).data('target');
      $(target).parent().find('.collapse').collapse('hide');
      $(target).collapse('show');
    });
  },

  setSelect2: function () {
    if (consumablesData !== undefined) {
      $(".consumableSelect").each(function () {
        if (!$(this).hasClass("select2-hidden-accessible") && !$(this).hasClass("select2-tpl")) {
          $(this).select2({
            theme: 'bootstrap4',
            width: 'style',
            placeholder: 'Расходный материал...',
            data: consumablesData
          }).val(null).trigger('change')
            .on('select2:select', function (e) {
              var data = e.params.data;
              $(data.element).parents('tr').eq(0).find('.route-unit').text(data.units);
              $(data.element).parents('tr').eq(0).find('.form-control[name^="calculation"]').each(function (ii, ie) {
                var e_name = $(ie).attr('name').split('['),
                  e_tpl = $(ie).data('name').split('['),
                  e_id = e_tpl.indexOf('consumable]');
                if (e_id > 0) e_name[e_id] = data.id + ']';
                $(ie).attr('name', e_name.join('['));
                Services.ReindexParams();
              });
            });
        }
      });
    }
  }

};

$(document).ready(function () {
  Services.initialize();
});