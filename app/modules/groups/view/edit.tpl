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
                <h4 class="h4">{#groups_help_danger_header#}</h4>
                <p>{#groups_help_danger_descr#}</p>
            </div>
        {/if}
        {if $editable AND $exists}
            <div class="card">
                {if ! $disabled}
                <form id="GroupForm" action="{$ABS_PATH}groups/save" method="post">
                    <input type="hidden" name="user_group_id" value="{$user_group_id}">
                    {/if}
                    <div class="card-body pb-0">
                        <h6 class="card-title">{#groups_input_name#}</h6>
                        <div class="form-group mb-4">
                            <input class="form-control input-sm" type="text" name="user_group_name" value="{$user_group_name|default:""}" id="group_name" autocomplete="off" required>
                        </div>
                        {foreach from=$permissions key=key item="inner"}
                            {assign var=header_title value=$inner.name}
                            <div class="mb-4">
                                <h6 class="mb-1"><i class="{$inner.icon|default:"icon-list"} mr-3 icon-md"></i> {$smarty.config.$header_title}</h6>
                                <div class="form-group">
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
                        <a href="{$smarty.server.HTTP_REFERER}" class="btn btn-secondary btn-icon-text mr-3"><i class="mdi mdi-arrow-left btn-icon-prepend"></i> {#button_return#}</a>
                        {if $editable && !$disabled && $exists}
                            <button type="submit" class="SaveGroupBtn btn btn-primary btn-icon-text"><i class="mdi mdi-check btn-icon-prepend"></i> {#button_save#}</button>
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