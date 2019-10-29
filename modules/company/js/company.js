function fancyTimeFormat(time)
{

  if (parseInt(time) === 0) {
    return "нет"
  } else {
    var hrs = ~~(time / 3600);
    var mins = ~~((time % 3600) / 60);
    var secs = ~~time % 60;

    var ret = "";

    if (hrs > 0) {
      ret += "" + hrs + ":" + (mins < 10 ? "0" : "");
    }

    ret += "" + mins + ":" + (secs < 10 ? "0" : "");
    ret += "" + secs;
    return ret;
  }
}

var Company = {

  initialized: false,

  initialize: function () {

    if (this.initialized) return;
    this.initialized = true;

    this.build();

  },


  build: function () {

    $(".rangeslider").ionRangeSlider({
      skin: "round",
      grid: true,
      grid_snap: true,
      step: 1
    });

    $(".rangeslidertime").ionRangeSlider({
      skin: "round",
      grid: true,
      grid_snap: true,
      step: 15,
      grid_num: 1,
      prettify: fancyTimeFormat
    });

    this.Validate();

  },

  Validate: function () {
    $('#companyForm').validate({
      onkeyup: false,
      onclick: false,
      onfocusout: false,
      rules: {
        name: {
          required: true,
          minlength: 3
        },
        addr: {
          required: true,
          minlength: 3
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
  Company.initialize();
});