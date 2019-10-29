var Dashboard = {

  initialize: function () {
    this.build();
  },

  build: function () {
    this.Charts();
    this.Calendar();
  },

  Charts: function () {

    var numberWithCommas = function (x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "'");
    };

    $('.chart-js').each(function (i, e) {
      var $this = $(e),
        ctx = e.getContext("2d"),
        chartSuffix = $this.data('suffix'),
        chartOptions = {
          'line': {
            tooltips: {
              mode: 'index',
              position: 'nearest',
              intersect: false,
              callbacks: {
                label: function (tooltipItem, data) {
                  return numberWithCommas(tooltipItem.yLabel) + chartSuffix;
                }
              }
            },
            legend: {
              display: false
            },
            layout: {
              padding: {
                left: 20,
                right: 20,
                top: 0,
                bottom: 0
              }
            },
            scales: {
              ticks: {
                beginAtZero: true
              },
              xAxes: [{
                display: false
              }],
              yAxes: [{
                stacked: false,
                ticks: {
                  callback: function (value) {
                    return numberWithCommas(value) + chartSuffix;
                  }
                }
              }]
            },
            responsive: true,
            maintainAspectRatio: false
          }
        },
        chartData = {
          "labels": "",
          "datasets": [
            {
              "label": "",
              "backgroundColor": "rgba(41, 98, 255, 0.2)",
              "borderColor": "rgba(48, 120, 191, 1)",
              "borderWidth": 1,
              "lineTension": 0,
              "pointRadius": 2,
              "pointBorderColor": "rgba(255, 255, 255, 1)",
              "pointBackgroundColor": "rgba(48, 120, 191, 1)",
              "pointHitRadius": 5,
              "data": []
            },
            {
              "label": "",
              "backgroundColor": "rgba(128,128,128,0.1)",
              "borderColor": "rgba(128,128,128,0.5)",
              "borderWidth": 1,
              "lineTension": 0,
              "pointRadius": 2,
              "pointBorderColor": "rgba(255,255,255,0.5)",
              "pointBackgroundColor": "rgba(128,128,128,0.5)",
              "pointHitRadius": 5,
              "data": []
            }
          ]
        },
        thisData = $this.data('chart-data');
      $.extend(true, chartData, thisData);
      var newChart = new Chart(ctx, {
        type: $this.data('chart-type'),
        data: chartData,
        options: chartOptions[$this.data('chart-type')]
      });
    });
  },

  Calendar: function () {
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      navLinks: true, // can click day/week names to navigate views
      editable: false,
      eventLimit: false, // disallow "more" link when too many events
      locale: 'ru',
      events: '/dashboard/getevents',
      eventClick: function(info) {
        App.AddModal('orderInfoModal'+info.id, info.title,
          '<div class="page-loader__spinner mx-auto"><svg viewBox="25 25 50 50"><circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle></svg></div>',
          'modal-lg', '');
        $.post(info.url, function (data) {
          $('#orderInfoModal'+info.id).find('.modal-body').eq(0).html(data);
        });
        return false;
      }
    });
  }

};

$(document).ready(function () {
  Dashboard.initialize();
});