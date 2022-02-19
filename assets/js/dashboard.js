let colorScheme;

const fontFamily = "'Roboto', Helvetica, sans-serif";

const Dashboard = {

    initialize() {
        this.build();
        this.events();
    },

    build() {
        this.setColorScheme();
        this.visitsChart();
        this.storageChart();
    },

    events() {
        this.onBackupDB();
        this.onGetBackup();
        this.onClearCache();
    },

    setColorScheme() {

        colorScheme = {
            gridLineColor: $body.data('theme') === 'dark' ? "#1a2835" : "#f0f0f0",
            colors: {
                primary: "#0095ff",
                secondary: "#545454",
                success: "#1bb322",
                info: "#0dcaf0",
                warning: "#ffc107",
                danger: "#de3131",
                light: "#f0f0f0",
                dark: "#15202B",
                muted: $body.data('theme') === 'dark' ? "#cccccc" : "#888888",
                bodyColor: $body.data('theme') === 'dark' ? "#f0f0f0" : "#15202B",
                cardBg: $body.data('theme') === 'dark' ? "#15202B" : "#ffffff",
                gridBorder: $body.data('theme') === 'dark' ? "#1a2835" : "#f0f0f0",
            }
        }

        $window.on('themechange', () => {
            this.setColorScheme();
        });

    },

    visitsChart() {
        if ($('#dailyVisitsChart').length) {

            const _chartData = $('#dailyVisitsChart').data('chart');

            const chartData = (typeof _chartData == "object") ? _chartData : JSON.parse(_chartData);

            if (Object.keys(chartData).length) {

                let chartKeys = [];
                let chartValues = [];

                for (const [key, value] of Object.entries(chartData)) {
                    chartKeys.push(key);
                    chartValues.push(value);
                }

                console.dir(chartKeys, chartValues);

                const options = {
                    chart: {
                        locales: [apex_i18n],
                        defaultLocale: $locale,
                        type: 'bar',
                        height: '250',
                        parentHeightOffset: 0,
                        foreColor: colorScheme.colors.bodyColor,
                        background: colorScheme.colors.cardBg,
                        toolbar: {
                            show: false
                        },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 200,
                            animateGradually: {
                                enabled: true,
                                delay: 50
                            },
                            dynamicAnimation: {
                                enabled: true,
                                speed: 150
                            }
                        }
                    },
                    theme: {
                        mode: $body.data('theme')
                    },
                    tooltip: {
                        theme: $body.data('theme')
                    },
                    colors: [colorScheme.colors.primary],
                    fill: {
                        opacity: .9
                    },
                    grid: {
                        padding: {
                            bottom: -4
                        },
                        borderColor: colorScheme.colors.gridBorder,
                        xaxis: {
                            lines: {
                                show: false
                            }
                        }
                    },
                    series: [{
                        name: $('#dailyVisitsChart').data('series'),
                        data: chartValues,
                    }],
                    xaxis: {
                        type: 'datetime',
                        categories: chartKeys,
                        axisBorder: {
                            color: colorScheme.colors.gridBorder,
                        },
                        axisTicks: {
                            color: colorScheme.colors.gridBorder,
                        },
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontFamily: fontFamily,
                                fontWeight: 300,
                            }
                        },
                    },
                    legend: {
                        show: true,
                        position: "top",
                        horizontalAlign: 'center',
                        fontFamily: fontFamily,
                        fontWeight: 300,
                        itemMargin: {
                            horizontal: 8,
                            vertical: 0
                        },
                    },
                    stroke: {
                        width: 0
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: fontFamily,
                            fontWeight: 300,
                        },
                        offsetY: -27
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: "50%",
                            borderRadius: 4,
                            dataLabels: {
                                position: 'top',
                                orientation: 'vertical',
                            }
                        },
                    },
                };

                const apexBarChart = new ApexCharts(document.querySelector("#dailyVisitsChart"), options);
                apexBarChart.render();

                $window.on('themechange', () => {
                    setTimeout(() => {
                        apexBarChart.destroy();
                        this.visitsChart();
                    }, 10);
                });

            }

        }
    },

    storageChart() {
        if ($('#storageChart').length) {
            const bar = new ProgressBar.Circle(storageChart, {
                color: colorScheme.colors.primary,
                trailColor: colorScheme.gridLineColor,
                // This has to be the same size as the maximum width to
                // prevent clipping
                strokeWidth: 4,
                trailWidth: 1,
                easing: 'easeInOut',
                duration: 1000,
                text: {
                    autoStyleContainer: false
                },
                from: {color: colorScheme.colors.primary, width: 1},
                to: {color: colorScheme.colors.danger, width: 4},
                // Set default step function for all animate calls
                step: function (state, circle) {
                    circle.path.setAttribute('stroke', state.color);
                    circle.path.setAttribute('stroke-width', state.width);

                    const value = Math.round(circle.value() * 100);
                    circle.setText((value > 0) ? `${value}%` : '< 1%');

                }
            });
            bar.text.style.fontFamily = "'Roboto', sans-serif;";
            bar.text.style.fontSize = '3rem';

            bar.animate(parseFloat($('#storageChart').data('usage')));

            $window.on('themechange', () => {
                setTimeout(() => {
                    bar.destroy();
                    this.storageChart();
                }, 10);
            });

        }
    },

    onBackupDB() {
        $(document).on('click', '#backupDB', (event) => {
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: $('#backupDB').attr('href'),
                dataType: "JSON",
                timeout: 5000
            })
                .done((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '',
                            text: ajaxSuccessMessage,
                            allowOutsideClick: false,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: ajaxErrorStatusMessage,
                            text: ajaxErrorStatus500,
                            allowOutsideClick: false,
                        });
                    }
                })
                .fail(() => {
                    Swal.fire({
                        icon: 'error',
                        title: ajaxErrorStatusMessage,
                        text: ajaxErrorStatus500,
                        allowOutsideClick: false,
                    });
                });
            return false;
        });
    },

    onGetBackup() {
        $(document).on('click', '#getBackup', (event) => {
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: $('#getBackup').attr('href'),
                dataType: "JSON",
                timeout: 5000
            })
                .done((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '',
                            text: ajaxSuccessMessage,
                            allowOutsideClick: false,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: ajaxErrorStatusMessage,
                            text: ajaxErrorStatus500,
                            allowOutsideClick: false,
                        });
                    }
                })
                .fail(() => {
                    Swal.fire({
                        icon: 'error',
                        title: ajaxErrorStatusMessage,
                        text: ajaxErrorStatus500,
                        allowOutsideClick: false,
                    });
                });
            return false;
        });
    },

    onClearCache() {
        $(document).on('click', '#clearCache', (event) => {
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: $('#clearCache').attr('href'),
                dataType: "JSON",
                timeout: 5000
            })
                .done((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '',
                            text: ajaxSuccessMessage,
                            allowOutsideClick: false,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: ajaxErrorStatusMessage,
                            text: ajaxErrorStatus500,
                            allowOutsideClick: false,
                        });
                    }
                })
                .fail(() => {
                    Swal.fire({
                        icon: 'error',
                        title: ajaxErrorStatusMessage,
                        text: ajaxErrorStatus500,
                        allowOutsideClick: false,
                    });
                });
            return false;
        });
    }

};

$(document).ready(() => {
    Dashboard.initialize();
});