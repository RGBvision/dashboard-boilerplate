var Groups = {

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
    this.Validate();
    this.SaveButton();
    this.ConfirmDelete();
  },

  events: function () {
  },

  SaveButton: function () {
    $('.SaveGroupBtn').on('click', function (event) {
      event.preventDefault();
      $('#GroupForm').submit();
      return false;
    });
  },

  ConfirmDelete: function () {
    $(document).on('click', '.ConfirmDeleteGroup', function (event) {
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
    });
  },

  Validate: function () {
    $('#GroupForm').validate({
      onkeyup: false,
      onclick: false,
      onfocusout: false,
      rules: {
        user_group_name: {
          required: true,
          minlength: 3
        }
      },
      errorPlacement: function () {
        return true;
      },
      errorClass: "b-danger",
      validClass: "b-success",
      submitHandler: function (form) {
        form.submit();
      }
    });
  },

};

$(document).ready(function () {
  Groups.initialize();
});