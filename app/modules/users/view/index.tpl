<div class="row">
    <div class="col-12">
        <div class="card rounded">
            <div class="card-header">
                <div class="row justify-content-between align-items-center">
                    <div class="col">
                        <h6 class="card-title mb-0">{#users_page_title#}</h6>
                    </div>
                    {if Permissions::has('users_add')}
                        <div class="col-auto order-sm-last">
                            <div class="d-none d-md-block">
                                <button type="button" class="btn btn-primary btn-icon-text" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="mdi mdi-plus btn-icon-prepend"></i> {#button_add#}
                                </button>
                            </div>
                            <div class="dropdown d-md-none">
                                <button class="btn p-0 me-n2" type="button" id="userListMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="userListMenu">
                                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                        <i class="mdi mdi-plus me-2"></i>
                                        <span>{#button_add#}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    {/if}
                    <div class="col-12 col-sm-6 col-xl-4 mt-2 mt-sm-0">
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-filter-outline"></i></span>
                            <input id="tableFilter" type="text" class="form-control">
                            <button id="tableFilterClear" type="button" class="input-group-text"><i class="mdi mdi-close text-danger"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-1 px-md-4 py-md-3">
                <div class="table-responsive">
                    <table id="controlTable" class="table table-hover text-nowrap align-middle">
                        <thead>
                        <tr>
                            <th class="text-center d-none d-md-table-cell" width="1%" data-orderable="false"><i class="mdi mdi-camera-iris m-0"></i></th>
                            <th class="text-start">{#users_table_name#}</th>
                            <th class="text-start d-none d-md-table-cell">{#users_table_phone#}</th>
                            <th class="text-start d-none d-md-table-cell">{#users_table_email#}</th>
                            <th class="text-start">{#users_table_group#}</th>
                            <th class="text-start d-none d-md-table-cell">{#users_table_activity#}</th>
                            <th class="text-center" width="1%" data-orderable="false"><i class="mdi mdi-dots-horizontal m-0"></i></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div id="menuTpl" class="d-none">
                        <div class="d-none d-md-block">
                            <a class="btn btn-sm btn-icon btn-secondary" title="{#button_view#}" href="{$ABS_PATH}users/view/:id">
                                <i class="mdi mdi-eye"></i>
                            </a>
                            {if Permissions::has('users_edit')}
                                <a class="btn btn-sm btn-icon btn-primary disabled" title="{#button_edit#}" href="{$ABS_PATH}users/edit/:id" disabled>
                                    <i class="mdi mdi-pen"></i>
                                </a>
                            {/if}
                        </div>
                        <div class="dropdown d-md-none">
                            <button class="btn btn-sm btn-icon" type="button" id="userListMenu" data-bs-toggle="dropdown" data-bs-reference="parent" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="userListMenu">
                                <a class="dropdown-item my-1 d-flex align-items-center" href="{$ABS_PATH}users/view/:id">
                                    <i class="mdi mdi-eye me-2"></i>
                                    <span>{#button_view#}</span>
                                </a>
                                {if Permissions::has('users_edit')}
                                    <a class="dropdown-item my-1 d-flex align-items-center disabled" href="{$ABS_PATH}users/edit/:id" disabled>
                                        <i class="mdi mdi-pen me-2"></i>
                                        <span>{#button_edit#}</span>
                                    </a>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{$add_user_tpl}
