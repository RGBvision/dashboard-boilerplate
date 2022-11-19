let eventLogTable, eventLogFilter = '', eventLogFilterTimerId;

const EventLog = {

    initialize: function () {
        this.build();
        this.events();
    },

    build: function () {
        this.setDataTable();
    },

    events: function () {
        this.onEventLogFilter();
        this.onEventLogFilterClear();
    },

    setDataTable: function () {

        eventLogTable = $('#eventLogControlTable').DataTable({
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
                    d.search.value = eventLogFilter;
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
                        return (data_string.length > 50) ? `<span title="${data_string.replace(/"/g, '&quot;')}">${data_string.slice(0, 49)}&hellip;</span>` : data_string;
                    },
                }
            ],
            order: [[0, "desc"]]
        });

        eventLogTable.on('page.dt', function () {
            $(':focus').blur();
            $('html, body').animate({
                scrollTop: 0
            }, 250);
        });
    },

    onEventLogFilter() {
        $('#tableFilter').on('input', () => {
            clearTimeout(eventLogFilterTimerId);
            eventLogFilterTimerId = setTimeout(
                () => {
                    eventLogFilter = $('#tableFilter').val();
                    eventLogTable.ajax.reload();
                },
                500
            );
        });
    },

    onEventLogFilterClear() {
        $('#tableFilterClear').on('click', () => {
            clearTimeout(eventLogFilterTimerId);
            $('#tableFilter').val('')
            eventLogFilter = '';
            eventLogTable.ajax.reload();
        });
    },

};

$(document).ready(function () {
    EventLog.initialize();
});