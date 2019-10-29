var Reports = {

  initialized: false,

  initialize: function () {

    if (this.initialized) return;
    this.initialized = true;

    this.build();
  },

  build: function () {
    this.dataTables();
  },

  dataTables: function () {
    $('.data-table').DataTable({
      dom: '<"row"<"col-12 col-md-6"B><"col-12 col-md-6"f>><"table-responsive"t>',
      stateSave: true,
      colReorder: false,
      language: dataTable_lang,
      buttons: [
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
      ],
      paging: false
    });
  }
};


$(document).ready(function () {
  Reports.initialize();
});