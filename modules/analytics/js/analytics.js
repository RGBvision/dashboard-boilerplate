var Analytics = {

  initialized: false,

  initialize: function () {

    if (this.initialized) return;
    this.initialized = true;

    this.build();
  },

  build: function () {
    this.Charts();
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
            scales: {
              ticks: {
                beginAtZero: true
              },
              xAxes: [{
                stacked: false,
                ticks: {
                  callback: function (value) {
                    return value;
                  }
                }
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
          },
          'radar': {
            tooltips: {
              mode: 'nearest',
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
            scale: {
              ticks: {
                beginAtZero: true, callback: function (value) {
                  return numberWithCommas(value) + chartSuffix;
                }
              }
            },
            responsive: true,
            maintainAspectRatio: false
          },
          'bar': {
            scaleBeginAtZero: true,
            scaleShowGridLines: true,
            scaleGridLineColor: "rgba(0,0,0,.00)",
            scaleGridLineWidth: 1,
            barShowStroke: false,
            barStrokeWidth: 1,
            barValueSpacing: 5,
            barDatasetSpacing: 1,
            responsive: true,
            maintainAspectRatio: false,
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
            scales: {
              ticks: {
                beginAtZero: true
              },
              xAxes: [{
                stacked: false,
                ticks: {
                  callback: function (value) {
                    return value;
                  }
                }
              }],
              yAxes: [{
                stacked: false,
                ticks: {
                  callback: function (value) {
                    return numberWithCommas(value) + chartSuffix;
                  }
                }
              }]
            }
          },
          'horizontalBar': {
            scaleBeginAtZero: true,
            scaleShowGridLines: true,
            scaleGridLineColor: "rgba(0,0,0,.00)",
            scaleGridLineWidth: 1,
            barShowStroke: false,
            barStrokeWidth: 1,
            barValueSpacing: 1,
            barDatasetSpacing: 1,
            responsive: true,
            maintainAspectRatio: true,
            tooltips: {
              mode: 'index',
              position: 'nearest',
              intersect: false,
              callbacks: {
                label: function (tooltipItem, data) {
                  return numberWithCommas(tooltipItem.xLabel) + chartSuffix;
                }
              }
            },
            legend: {
              display: false
            },
            scales: {
              ticks: {
                beginAtZero: true
              },
              xAxes: [{
                stacked: false,
                ticks: {
                  callback: function (value) {
                    return numberWithCommas(value) + chartSuffix;
                  }
                }
              }],
              yAxes: [{
                categoryPercentage: .8,
                barThickness: 20,
                maxBarThickness: 25,
                gridLines: {
                  offsetGridLines: true
                },
                stacked: false,
                ticks: {
                  callback: function (value) {
                    return value;
                  }
                }
              }]
            }
          }
        },
        chartData = {
          "labels": "",
          "datasets": [
            {
              "label": "",
              "backgroundColor": "rgba(123,31,162,0.25)",
              "borderColor": "rgba(123,31,162,1)",
              "borderWidth": 1,
              "lineTension": 0,
              "pointRadius": 2,
              "pointBorderColor": "rgba(255,255,255,1)",
              "pointBackgroundColor": "rgba(123,31,162,1)",
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
  }
};


$(document).ready(function () {
  Analytics.initialize();
});