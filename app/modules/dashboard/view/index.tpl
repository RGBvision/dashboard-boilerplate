<div class="row">
    <div class="col-lg-5 col-xl-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">{#dashboard_title_storage#}</h6>
            </div>
            <div class="card-body">
                <div id="storageChart" class="mx-auto position-relative" style="width: 200px; height: 200px;" data-usage="{$storage_usage.percentage}"></div>
                <div class="row mt-4 mb-3">
                    <div class="col-6 d-flex justify-content-end">
                        <div>
                            <label class="d-flex align-items-center justify-content-end tx-10 text-uppercase fw-normal">{#dashboard_storage_size#} <span class="p-1 ms-1 rounded-circle bg-primary"></span></label>
                            <h5 class="fw-bold mb-0 text-end">{$storage_size|formatSize}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div>
                            <label class="d-flex align-items-center tx-10 text-uppercase fw-normal"><span class="p-1 me-1 rounded-circle bg-danger"></span> {#dashboard_storage_used#}</label>
                            <h5 class="fw-bold mb-0">{$storage_usage.size|formatSize}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7 col-xl-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">{#dashboard_title_storage_details#}</h6>
            </div>
            <div class="card-body">
                {foreach from=$storage_usage.details key=key item=item name=foo}
                    <div class="row pb-2 mb-2 {if !$smarty.foreach.foo.last} border-bottom {/if}">
                        <div class="col">{$key}</div>
                        <div class="col text-end">{$item|formatSize}</div>
                    </div>
                {/foreach}
            </div>
            <div class="card-footer text-center pb-1">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <a id="backupDB" href="{$ABS_PATH}dashboard/backup_db" class="btn w-100 btn-success mb-2">{#dashboard_make_db_backup#}</a>
                    </div>
                    <div class="col-12 col-md-6">
                        <a id="clearCache" href="{$ABS_PATH}dashboard/clear_cache" class="btn w-100 btn-danger mb-2">{#dashboard_clear_cache#}</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{if $smarty.const.UID == 1}
    <div class="row">
        <div class="col-lg-5 col-xl-4 grid-margin stretch-card">
            <div class="card">
                <form method="post" action="{$ABS_PATH}dashboard/generate">
                    <div class="card-header">
                        <h6 class="card-title mb-0">{#dashboard_generate_module#}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-0">
                            <label class="form-label">{#dashboard_module_name#}</label>
                            <input value="" name="module" type="text" class="form-control" placeholder="{#dashboard_module_name#}" required>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">{#button_generate#}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{/if}