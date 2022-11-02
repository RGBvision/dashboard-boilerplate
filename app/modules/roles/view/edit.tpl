{assign var=disable value=''}
{if $disabled}{assign var=disable value='disabled'}{/if}
<div class="row">
    <div class="col-12">
        {if ! $editable}
            <div class="text-danger">
                <h4 class="h4">{#no_permissions_title#}</h4>
                <p>{#no_permissions_descr_edit#}</p>
            </div>
        {elseif ! $exists}
            <div class="text-danger">
                <h4 class="h4">{#roles_help_danger_header#}</h4>
                <p>{#roles_help_danger_descr#}</p>
            </div>
        {/if}
        {if $editable AND $exists}
            <div class="card">
                {if ! $disabled}
                <form id="RoleForm" action="{$ABS_PATH}roles/save" method="post">
                    <input type="hidden" name="user_role_id" value="{$user_role_id}">
                    {/if}
                    <div class="card-body pb-0">
                        <h6 class="card-title">{#roles_input_name#}</h6>
                        <div class="mb-4">
                            <input class="form-control" type="text" name="user_role_name" value="{$user_role_name|default:""}" id="role_name" autocomplete="off" required>
                        </div>
                        {foreach from=$permissions key=key item="inner"}
                            {assign var=header_title value=$inner.name}
                            <div class="mb-4">
                                <h6 class="mb-1"><i class="{$inner.icon|default:"icon-list"} me-3 icon-md"></i> {$smarty.config.$header_title}</h6>
                                <div class="mb-3">
                                    {foreach from=$inner.perm key=permission item="value"}
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
                            </div>
                        {/foreach}
                    </div>
                    <div class="card-footer text-end">
                        <a href="{$smarty.server.HTTP_REFERER}" class="btn btn-secondary btn-icon-text"><i class="mdi mdi-arrow-left btn-icon-prepend"></i> {#button_return#}</a>
                        {if $editable && !$disabled && $exists}
                            <button type="submit" class="SaveRoleBtn btn btn-primary btn-icon-text"><i class="mdi mdi-check btn-icon-prepend"></i> {#button_save#}</button>
                        {else}
                            <button type="button" disabled class="SaveGroupBtn btn btn-primary btn-icon-text disabled"><i class="mdi mdi-check btn-icon-prepend"></i> {#button_save#}</button>
                        {/if}
                    </div>
                    {if ! $disabled}
                </form>
                {/if}
            </div>
        {/if}
    </div>
</div>