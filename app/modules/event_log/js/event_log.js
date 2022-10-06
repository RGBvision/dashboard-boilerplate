let eventlogTable, eventlogFilter = '', eventlogFilterTimerId;

var Eventlog = {

    initialize: function () {
        this.build();
        this.events();
    },

    build: function () {
        this.setDataTable();
    },

    events: function () {
        this.onEventlogFilter();
        this.onEventlogFilterClear();
    },

    setDataTable: function () {

        eventlogTable = $('#eventlogControlTable').DataTable({
            processing: true,
            language: dataTable_i18n,
            dom: '<"table-responsive mb-3"t>rp',
            autoWidth: false,
            pageLength: 25,
            serverSide: true,
            searchDelay: 500,
            ajax: {
                url: "/event_log/get",
                type: "POST",
                data: function (d) {
                    d.search.value = eventlogFilter;
                }
            },
            columns: [
                {
                    data: 'timestamp'
                },
                {
                    data: 'type'
                },
                {
                    data: 'module'
                },
                {
                    data: 'user'
                },
                {
                    data: 'ip'
                },
                {
                    data: 'message',
                    render: function (data, type, row) {
                        const data_string = String(data);
                        return (data_string.length > 50) ? `<span title="${data_string}">${data_string.slice(0, 49)}&hellip;</span>` : data_string;
                    },
                }
            ],
            order: [[0, "desc"]]
        });

        eventlogTable.on('page.dt', function () {
            $(':focus').blur();
            $('html, body').animate({
                scrollTop: 0
            }, 250);
        });
    },

    onEventlogFilter() {
        $('#tableFilter').on('input', () => {
            clearTimeout(eventlogFilterTimerId);
            eventlogFilterTimerId = setTimeout(
                () => {
                    eventlogFilter = $('#tableFilter').val();
                    eventlogTable.ajax.reload();
                },
                500
            );
        });
    },

    onEventlogFilterClear() {
        $('#tableFilterClear').on('click', () => {
            clearTimeout(eventlogFilterTimerId);
            $('#tableFilter').val('')
            eventlogFilter = '';
            eventlogTable.ajax.reload();
        });
    },

};

$(document).ready(function () {
    Eventlog.initialize();
});