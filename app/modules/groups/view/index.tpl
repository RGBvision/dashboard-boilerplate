<div class="row">
    <div class="col-12">
        <div class="card rounded">
            <div class="card-header d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">{#groups_page_title#}</h6>
                {if isset($smarty.session.permissions.groups_edit) or isset($smarty.session.permissions.all_permissions)}
                    <div class="text-right d-none d-md-block">
                        <a href="{$ABS_PATH}groups/add" class="btn btn-primary btn-icon-text">
                            <i class="mdi mdi-plus btn-icon-prepend"></i> {#button_add#}
                        </a>
                    </div>
                    <div class="dropdown d-md-none">
                        <button class="btn p-0" type="button" id="userListMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="userListMenu">
                            <a class="dropdown-item d-flex align-items-center" href="{$ABS_PATH}groups/add">
                                <i class="mdi mdi-plus mr-1"></i>
                                <span>{#button_add#}</span>
                            </a>
                        </div>
                    </div>
                {/if}
            </div>
            <div class="card-body p-1 px-md-4 py-md-3">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th class="text-left">{#groups_table_name#}</th>
                            <th class="text-left">{#groups_table_users#}</th>
                            <th class="text-center" width="1%" data-orderable="false"><i class="mdi mdi-dots-horizontal m-0"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        {if $groups}
                            {foreach from=$groups item=group key=key}
                                <tr>
                                    <td class="text-left">
                                        {if $group.editable}
                                            <a title="{#button_edit#}" href="{$ABS_PATH}groups/edit?user_group_id={$group.user_group_id}">
                                                {$group.name}
                                            </a>
                                        {else}
                                            {$group.name}
                                        {/if}
                                    </td>
                                    <td class="text-left">
                                        {$group.users|default:"-"}
                                    </td>
                                    <td class="text-center py-1">
                                        {if $group.editable}
                                            <a class="btn btn-icon btn-primary" title="{#button_edit#}" href="{$ABS_PATH}groups/edit?user_group_id={$group.user_group_id}">
                                                <i class="mdi mdi-pen"></i>
                                            </a>
                                        {else}
                                            <a class="btn btn-icon btn-primary disabled" title="{#button_edit#}" href="#" disabled>
                                                <i class="mdi mdi-pen"></i>
                                            </a>
                                        {/if}
                                        {if $group.deletable}
                                            <a class="btn btn-icon btn-danger ConfirmDeleteUser" title="{#button_delete#}" href="{$ABS_PATH}groups/delete?user_group_id={$group.user_group_id}">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </a>
                                        {else}
                                            <a class="btn btn-icon btn-danger disabled" title="{#button_delete#}" href="#." disabled>
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