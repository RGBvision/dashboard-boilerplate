<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="SettingsForm" action="{$ABS_PATH}settings/save" method="post">
                <div class="card-header"><span>{#settings_help_descr#}</span></div>
                <div class="card-body">
                    {foreach from=$configs item=config key=key name=foo}
                        <div class="row align-items-center py-1 mx-0 bg-highlight-hover {if !$smarty.foreach.foo.last} border-bottom {/if}">
                            <div class="col-12 mb-1 col-lg-8 mb-lg-0">{#$config.LANG#}</div>
                            <div class="col-12 col-lg-4">
                                {if $config.TYPE == 'string'}
                                    <input class="form-control" type="text" name="const[{$key}]"
                                           value="{$config.DEFAULT}">
                                {elseif $config.TYPE == 'readonly'}
                                    <input class="form-control" type="text" name="const[{$key}]"
                                           value="{$config.DEFAULT}" readonly>
                                {elseif $config.TYPE == 'bool'}
                                    <div class="form-check form-switch m-0 py-1">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="checkbox-{$key}"
                                               name="const[{$key}]"
                                               value="1" {if $config.DEFAULT}checked{/if}><label for="checkbox-{$key}" class="custom-control-label"></label>
                                    </div>
                                {elseif $config.TYPE == 'dropdown'}
                                    <select name="const[{$key}]" class="form-select select2">
                                        {foreach from=$config.VARIANT key=key item=select}
                                            {if $key|is_numeric}
                                                <option value="{$select}" {if $select == $config.DEFAULT}selected{/if}>
                                                    {$select}
                                                </option>
                                            {else}
                                                <option value="{$key}" {if $key == $config.DEFAULT}selected{/if}>
                                                    {$select}
                                                </option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                {elseif $config.TYPE == 'folder'}
                                    <input class="form-control" type="text" name="const[{$key}]"
                                           value="{$config.DEFAULT}">
                                {elseif $config.TYPE == 'integer'}
                                    <input class="form-control" type="text" name="const[{$key}]"
                                           value="{$config.DEFAULT}">
                                {elseif $config.TYPE == 'tags'}
                                    <input class="form-control input-tags" type="text" name="const[{$key}]" value="{$config.DEFAULT}">
                                {/if}
                            </div>
                        </div>
                    {/foreach}
                </div>
                <div class="card-footer text-end">
                    <a href="{$smarty.server.HTTP_REFERER}" class="btn btn-secondary btn-icon-text"><i class="mdi mdi-arrow-left btn-icon-prepend"></i> {#button_return#}</a>
                    <button type="submit" class="SaveSettingsBtn btn btn-primary btn-icon-text"><i class="mdi mdi-check btn-icon-prepend"></i> {#button_save#}</button>
                </div>
            </form>
        </div>
    </div>
</div>
