let routesTable, routesFilter = '', routesFilterTimerId;

var Routes = {

    initialize: function () {
        this.build();
        this.events();
    },

    build: function () {
        this.setDataTable();
    },

    events: function () {
        this.onRoutesFilter();
        this.onRoutesFilterClear();
    },

    setDataTable: function () {

        const menuTpl = $('#menuTpl').html();
        const activeTpl = $('#activeTpl').html();

        routesTable = $('#routesControlTable').DataTable({
            processing: true,
            language: dataTable_i18n,
            dom: '<"table-responsive mb-3"t>rp',
            autoWidth: false,
            pageLength: 25,
            serverSide: true,
            searchDelay: 500,
            ajax: {
                url: "/routes/get",
                type: "POST",
                data: function (d) {
                    d.search.value = routesFilter;
                }
            },
            columns: [
                {
                    data: 'name'
                },
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return menuTpl.replace(/:id/g, data);
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('py-1');
                    }
                }
            ]
        });

        routesTable.on('page.dt', function () {
            $(':focus').blur();
            $('html, body').animate({
                scrollTop: 0
            }, 250);
        });
    },

    onRoutesFilter() {
        $('#tableFilter').on('input', () => {
            clearTimeout(routesFilterTimerId);
            routesFilterTimerId = setTimeout(
                () => {
                    routesFilter = $('#tableFilter').val();
                    routesTable.ajax.reload();
                },
                500
            );
        });
    },

    onRoutesFilterClear() {
        $('#tableFilterClear').on('click', () => {
            clearTimeout(routesFilterTimerId);
            $('#tableFilter').val('')
            routesFilter = '';
            routesTable.ajax.reload();
        });
    },

};

$(document).ready(function () {
    Routes.initialize();
});