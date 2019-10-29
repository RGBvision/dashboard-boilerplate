<div id="ordersSettingsModal" class="settings modal fade" tabindex="-1" role="dialog" aria-labelledby="ordersSettingsModalLabel">
  <div class="modal-dialog {if {$access}}modal-lg{/if}" role="document">
    <div class="modal-content">
      <form class="settingsForm" method="post">
        <div class="modal-header">
          <h4 class="modal-title" id="ordersSettingsModalLabel">{#button_customize#}</h4>
        </div>
        <div class="modal-body">
          {foreach from=$orders_settings_data item=item key=key name=order_settings}
            <div class="row">
              <div class="col-12 col-lg">{$key}</div>
              {if {$access}}
                <div class="col-6 col-lg">
                  <div class="form-inline d-lg-block text-lg-right">
                    <div class="switch switch-sm">
                      <input type="hidden"
                             class="switch"
                             value="0"
                             name="user_settings[analytics][orders][{$item}][datatype]">
                      <small class="mr-2">Проц</small>
                      <input type="checkbox"
                             class="switch"
                             id="switch-datatype-{$item}"
                             value="1"
                             name="user_settings[analytics][orders][{$item}][datatype]"
                             {if isset($smarty.session.user_settings.analytics.orders.$item.datatype) and $smarty.session.user_settings.analytics.orders.$item.datatype == 1}checked{/if}><label for="switch-datatype-{$item}"></label>
                      <small class="ml-2">Знач</small>
                    </div>
                  </div>
                </div>
              {/if}
              <div class="col-6 col-lg">
                <div class="form-inline pull-right">
                  <div class="switch switch-sm">
                    <input type="hidden"
                           class="switch"
                           value="0"
                           name="user_settings[analytics][orders][{$item}][viewtype]">
                    <small class="mr-2">Линия</small>
                    <input type="checkbox"
                           class="switch"
                           id="switch-viewtype-{$item}"
                           value="1"
                           name="user_settings[analytics][orders][{$item}][viewtype]"
                           {if isset($smarty.session.user_settings.analytics.orders.$item.viewtype) and $smarty.session.user_settings.analytics.orders.$item.viewtype == 1}checked{/if}><label for="switch-viewtype-{$item}"></label>
                    <small class="ml-2">Радар</small>
                  </div>
                </div>
              </div>
            </div>
            {if not $smarty.foreach.order_settings.last}
              <hr>
            {/if}
          {/foreach}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{#button_cancel#}</button>
          <button type="submit" class="btn btn-primary">{#button_apply#}</button>
        </div>
      </form>
    </div>
  </div>
</div>