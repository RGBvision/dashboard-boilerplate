<div class="row">
    <div class="col-12">
        <div class="card rounded">
            <div class="card-header">
                <div class="row justify-content-between align-items-center">
                    <div class="col">
                        <h6 class="card-title mb-0">{#event_log_page_title#}</h6>
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
                <table id="eventLogControlTable" class="table table-hover text-nowrap align-middle">
                    <thead>
                    <tr>
                        <th class="text-start">{#event_log_dt#}</th>
                        <th class="text-start">{#event_log_type#}</th>
                        <th class="text-start">{#event_log_module#}</th>
                        <th class="text-start">{#event_log_user#}</th>
                        <th class="text-start">{#event_log_ip#}</th>
                        <th class="text-start">{#event_log_description#}</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>