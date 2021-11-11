<div class="row">
    <div class="col-12">
        {if ! $access}
            <div class="text-danger">
                <h4 class="h4">{#no_permissions_title#}</h4>
                <p>{#no_permissions_descr_add#}</p>
            </div>
        {/if}

        {if $access}
            <div class="card rounded">
                <form id="GroupForm" action="{$ABS_PATH}groups/save" method="post">
                    <input type="hidden" name="action" value="add">
                    <div class="card-body pb-0">
                        <h6 class="card-title">{#groups_input_name#}</h6>
                        <div class="form-group mb-4">
                            <input class="form-control input-sm" type="text" name="user_group_name" value="{$user_group_name|default:""}" id="group_name" autocomplete="off" required>
                        </div>
                        <h6 class="card-title">{#groups_permissions#}</h6>
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
                    <div class="card-footer text-right">
                        <a href="{$ABS_PATH}groups" class="btn btn-secondary btn-icon-text"><i class="mdi mdi-undo btn-icon-prepend"></i> {#button_cancel#}</a>
                        <button type="submit" class="SaveGroupBtn btn btn-primary btn-icon-text"> <i class="mdi mdi-check btn-icon-prepend"></i> {#button_save#}</button>
                    </div>
                </form>
            </div>
        {/if}
    </div>
</div>