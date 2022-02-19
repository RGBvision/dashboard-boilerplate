<div class="row">
    <div class="col-12">
        <div class="card rounded">
            <div class="card-header">
                <div class="row justify-content-between align-items-center">
                    <div class="col">
                        <h6 class="card-title mb-0">{#routes_page_title#}</h6>
                    </div>
                    {if isset($smarty.session.permissions.routes_edit) or isset($smarty.session.permissions.all_permissions)}
                        <div class="col-auto order-sm-last">
                            <div class="text-end d-none d-md-block">
                                <button type="button" class="btn btn-primary btn-icon-text" data-bs-toggle="modal" data-bs-target="#addRoutesModal">
                                    <i class="mdi mdi-plus btn-icon-prepend"></i> {#button_add#}
                                </button>
                            </div>
                            <div class="dropdown d-md-none">
                                <button class="btn p-0" type="button" id="routesListMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="routesListMenu">
                                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addRoutesModal">
                                        <i class="mdi mdi-plus mr-1"></i>
                                        <span>{#button_add#}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    {/if}
                    <div class="col-12 col-sm-6 col-xl-4 mt-2 mt-sm-0">
                        <div class="input-group">
                            <span class="input-group-text px-2"><i class="mdi mdi-filter-outline"></i></span>
                            <input id="tableFilter" type="text" class="form-control">
                            <button id="tableFilterClear" type="button" class="btn border px-2"><i class="mdi mdi-close text-danger"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="routesControlTable" class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th class="text-start">{#routes_name#}</th>
                        <th class="text-center" width="1%" data-orderable="false"><i class="mdi mdi-dots-horizontal m-0"></i></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div id="menuTpl" class="d-none">
                    <div class="dropdown">
                        <button class="btn btn-secondary p-1" type="button" id="routesListMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="window">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="routesListMenu">
                            <a class="dropdown-item my-1 py-2 d-flex align-items-center" href="{$ABS_PATH}routes/edit?id=:id">
                                <i class="mdi mdi-pen me-1"></i>
                                <span>{#button_edit#}</span>
                            </a>
                            <a class="dropdown-item my-1 py-2 d-flex align-items-center confirmDeleteRoutes" href="{$ABS_PATH}routes/delete?id=:id">
                                <i class="mdi mdi-trash-can-outline me-1"></i>
                                <span>{#button_delete#}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>