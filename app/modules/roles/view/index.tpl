<div class="row">
    <div class="col-12">
        <div class="card rounded">
            <div class="card-header d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">{#roles_page_header#}</h6>
                {if Permissions::has('roles_edit')}
                    <div class="text-end d-none d-md-block">
                        <a href="{$ABS_PATH}roles/add" class="btn btn-primary btn-icon-text">
                            <i class="mdi mdi-plus btn-icon-prepend"></i> {#button_add#}
                        </a>
                    </div>
                    <div class="dropdown d-md-none">
                        <button class="btn p-0" type="button" id="userListMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="userListMenu">
                            <a class="dropdown-item d-flex align-items-center" href="{$ABS_PATH}roles/add">
                                <i class="mdi mdi-plus me-1"></i>
                                <span>{#button_add#}</span>
                            </a>
                        </div>
                    </div>
                {/if}
            </div>
            <div class="card-body p-1 px-md-4 py-md-3">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap align-middle">
                        <thead>
                        <tr>
                            <th class="text-start">{#roles_table_name#}</th>
                            <th class="text-start">{#roles_table_users#}</th>
                            <th class="text-center" width="1%" data-orderable="false"><i class="mdi mdi-dots-horizontal m-0"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        {if $roles}
                            {foreach from=$roles item=role key=key}
                                <tr>
                                    <td class="text-start">
                                        {if $role.editable}
                                            <a title="{#button_edit#}" href="{$ABS_PATH}roles/edit/{$role.user_role_id}">
                                                {$role.name}
                                            </a>
                                        {else}
                                            {$role.name}
                                        {/if}
                                    </td>
                                    <td class="text-start">
                                        {$role.users|default:"-"}
                                    </td>
                                    <td class="text-center py-1">
                                        {if $role.editable}
                                            <a class="btn btn-sm btn-icon btn-primary" title="{#button_edit#}" href="{$ABS_PATH}roles/edit/{$role.user_role_id}">
                                                <i class="mdi mdi-pen"></i>
                                            </a>
                                        {else}
                                            <a class="btn btn-sm btn-icon btn-primary disabled" title="{#button_edit#}" href="#" disabled>
                                                <i class="mdi mdi-pen"></i>
                                            </a>
                                        {/if}
                                        {if $role.deletable}
                                            <a class="btn btn-sm btn-icon btn-danger" data-confirm="delete" title="{#button_delete#}" href="{$ABS_PATH}roles/delete/{$role.user_role_id}">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </a>
                                        {else}
                                            <a class="btn btn-sm btn-icon btn-danger disabled" title="{#button_delete#}" href="#." disabled>
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </a>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        {else}
                            <tr>
                                <td colspan="3" class="text-center">{#message_no_data#}</td>
                            </tr>
                        {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>