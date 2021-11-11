const gridLineColor = 'rgba(128,128,128,0.25)';

const colors = {
    primary: "#0095ff",
    secondary: "#545454",
    success: "#1bb322",
    info: "#0dcaf0",
    warning: "#ffc107",
    danger: "#de3131",
    light: "#f0f0f0",
    dark: "#15202B",
    muted: "#888888"
};

const Dashboard = {

    initialize() {
        this.build();
        this.events();
    },

    build() {
        this.storageChart();
    },

    events() {
        this.onBackupDB();
        this.onGetBackup();
        this.onClearCache();
    },

    storageChart() {
        if ($('#storageChart').length) {
            const bar = new ProgressBar.Circle(storageChart, {
                color: colors.primary,
                trailColor: gridLineColor,
                // This has to be the same size as the maximum width to
                // prevent clipping
                strokeWidth: 4,
                trailWidth: 1,
                easing: 'easeInOut',
                duration: 1000,
                text: {
                    autoStyleContainer: false
                },
                from: {color: colors.primary, width: 1},
                to: {color: colors.danger, width: 4},
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