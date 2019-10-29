$(document).ready(function () {
  $('#customersControlTable').DataTable({
    dom: 'f<"table-responsive"t>',
    stateSave: true,
    colReorder: true,
    language: dataTable_lang,
    paging: false
  });
});