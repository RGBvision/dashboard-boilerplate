<div class="row">
    <div class="col-12">
        <div class="card rounded">
            <div class="card-header">
                <div class="row justify-content-between align-items-center">
                    <div class="col">
                        <h6 class="card-title mb-0">{#eventlog_page_title#}</h6>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mt-2 mt-sm-0">
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-filter-outline"></i></span>
                            <input id="tableFilter" type="text" class="form-control">
                            <button id="tableFilterClear" type="button" class="input-group-text"><i class="mdi mdi-close text-danger"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="eventlogControlTable" class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th class="text-start">{#eventlog_dt#}</th>
                        <th class="text-start">{#eventlog_type#}</th>
                        <th class="text-start">{#eventlog_module#}</th>
                        <th class="text-start">{#eventlog_user#}</th>
                        <th class="text-start">{#eventlog_ip#}</th>
                        <th class="text-start">{#eventlog_description#}</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>