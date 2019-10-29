<div id="main-holder" class="row">
  <div class="col-12">
      {if $permission}
    <form id="ConstantsForm" action="./index.php?route=constants/save" method="post">{/if}
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-heading card-default">
              {#constants_page_header#}
            </div>
            <div class="card-block">
              <div class="table-responsive">
                <table class="table table-striped nowrap">
                  <tbody>
                  {foreach from=$configs item=config key=key}
                    <tr>
                      <td>{#$config.LANG#}</td>
                      <td>
                          {if $config.TYPE == 'string'}
                            <input class="form-control" type="text" name="const[{$key}]"
                                   value="{$config.DEFAULT}">
                          {elseif $config.TYPE == 'bool'}
                            <div class="form-inline">
                              <div class="switch switch-sm">
                                <input type="checkbox"
                                       class="switch"
                                       id="checkbox-{$key}"
                                       name="const[{$key}]"
                                       value="1" {if $config.DEFAULT}checked{/if}><label for="checkbox-{$key}"></label>
                              </div>
                            </div>
                          {elseif $config.TYPE == 'dropdown'}
                            <select name="const[{$key}]" class="form-control">
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
                      </td>
                    </tr>
                      {foreachelse}

                  {/foreach}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
        {if $permission}</form>{/if}
  </div>
</div>