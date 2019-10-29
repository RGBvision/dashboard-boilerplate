<div id="main-holder" class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        {$data.page_title}
      </div>
      <div class="card-block">
        <table id="storeControlTable" class="table table-striped dt-responsive nowrap" data-order="[[1,&quot;desc&quot;]]">
          <thead>
          <tr>
            <th class="text-center">{#store_table_name#}</th>
            <th class="text-center">{#store_table_quantity#}</th>
            <th class="text-center">{#store_table_unit#}</th>
            <th class="text-center">{#store_table_price#}</th>
            <th class="text-center" data-orderable="false"><i class="sli sli-content-edition-flash-2"></i></th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$store item=good key=key}
            <tr>
              <td>
                <a href="./index.php?route=store/edit&good_id={$good.good_id}">{$good.name}</a>
              </td>
              <td class="text-right">
                {$good.count|number_format:3:".":"'"}
              </td>
              <td class="text-center">
                {$good.unit}
              </td>
              <td class="text-right">
                {$good.cost|number_format:2:".":"'"}
              </td>
              <td class="text-center">
                <a class="btn btn-xs btn-primary" title="{#button_edit#}" data-toggle="tooltip" data-placement="top" href="./index.php?route=store/edit&good_id={$good.good_id}">
                  <i class="sli sli-content-edition-pen-4"></i>
                </a>
                <a class="btn btn-xs btn-danger ConfirmDeleteGood" title="{#button_delete#}" data-toggle="tooltip" data-placement="top" href="./index.php?route=store/delete&good_id={$good.good_id}">
                  <i class="sli sli-content-edition-bin-2"></i>
                </a>
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
      {if isset($smarty.session.permissions.store_edit) or isset($smarty.session.permissions.all_permissions)}
        <div class="card-footer text-right">
          {if $smarty.const.ORGACTIVE}
            <a href="/index.php?route=store/add" class="btn btn-primary btn-icon">
              <span><i class="sli sli-remove-add-add-1"></i></span> Добавить
            </a>
          {else}
            <a href="#." class="btn btn-primary btn-icon disabled" disabled="">
              <span><i class="sli sli-remove-add-add-1"></i></span> Добавить
            </a>
          {/if}
        </div>
      {/if}
    </div>
  </div>
</div>