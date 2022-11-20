<div class="row">
    <div class="col-12">
        {if Permissions::has('roles_edit')}
            <div class="card rounded">
                <form id="RoleForm" action="{$ABS_PATH}roles/save" method="post">
                    <input type="hidden" name="action" value="add">
                    <div class="card-body pb-0">
                        <h6 class="card-title">{#roles_input_name#}</h6>
                        <div class="form-group mb-4">
                            <input class="form-control input-sm" type="text" name="user_role_name" value="{$user_role_name|default:""}" id="role_name" autocomplete="off" required>
                        </div>
                        <h6 class="card-title">{#roles_permissions#}</h6>
                        {foreach from=$permissions key=key item=inner name=modules}
                            {assign var=header_title value=$inner.name}
                            <div>
                                <h6 class="mb-2"><i class="{$inner.icon|default:"mdi mdi-adjust"} mr-3 icon-md"></i> {$smarty.config.$header_title}</h6>
                                <div>
                                    {foreach from=$inner.permission key=permission item=value}
                                        {assign var=permission_title value="perm_$permission"}
                                        <div class="form-check form-switch mb-2">
                                            <input type="checkbox" class="form-check-input"
                                                   id="checkbox-{$permission}"
                                                   name="permissions[]"
                                                    {$disable} value="{$permission}" {if $value || $disabled}checked{/if}>
                                            <label class="form-check-label" for="checkbox-{$permission}">{$smarty.config.$permission_title}</label>
                                        </div>
                                    {/foreach}
                                </div>
                                {if !$smarty.foreach.modules.last}
                                    <hr>
                                {/if}
                            </div>
                        {/foreach}
                    </div>
                    <div class="card-footer text-end">
                        <a href="{$smarty.server.HTTP_REFERER}" class="btn btn-secondary btn-icon-text"><i class="mdi mdi-undo btn-icon-prepend"></i> {#button_cancel#}</a>
                        <button type="submit" class="SaveRoleBtn btn btn-primary btn-icon-text"><i class="mdi mdi-check btn-icon-prepend"></i> {#button_add#}</button>
                    </div>
                </form>
            </div>
        {else}
            <div class="text-danger">
                <h4 class="h4">{#no_permissions_title#}</h4>
                <p>{#no_permissions_descr_add#}</p>
            </div>
        {/if}
    </div>
</div>