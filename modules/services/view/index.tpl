<div id="main-holder" class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        {#services_page_title#}
      </div>
      <div class="card-block">
        <table id="servicesControlTable" class="table table-striped nowrap" data-order="[[0,&quot;asc&quot;]]">
          <thead>
          <tr>
            <th class="text-center" data-orderable="false"><i class="sli sli-text-list-number"></i></th>
            <th class="text-center" data-orderable="false">{#services_table_name#}</th>
            <th class="text-center" data-orderable="false">{#services_table_department#}</th>
            <th class="text-center" data-orderable="false">{#services_table_params#}</th>
            <th class="text-center" data-orderable="false"><i class="sli sli-content-edition-flash-2"></i></th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$services item=service key=key name=foo}
            <tr>
              <td class="text-center">
                {$smarty.foreach.foo.iteration}
              </td>
              <td>
                <input class="service-id" type="hidden" value="{$service.service_id}">
                <a href="./index.php?route=services/edit&service_id={$service.service_id}">{$service.name}</a>
              </td>
              <td class="text-center">
                {$service.department|default:"-"}
              </td>
              <td class="text-center">
                {if $service.type == 1}<span title="Универсальная" data-toggle="tooltip" data-placement="top"><i class="sli sli-navigation-filter-1 mr-2"></i></span>{/if}
                {if $service.type == 2}<span title="По классам" data-toggle="tooltip" data-placement="top"><i class="sli sli-organization-hierarchy-3 mr-2"></i></span>{/if}
                {if $service.type == 3}<span title="По параметрам" data-toggle="tooltip" data-placement="top"><i class="sli sli-content-view-list mr-2"></i></span>{/if}
                {if $service.prime}<span title="Приоритетная" data-toggle="tooltip" data-placement="top"><i class="sli sli-vote-rewards-flag-triangle-1 text-primary"></i></span>{/if}
                {if $service.bonus}<span title="Акционная" data-toggle="tooltip" data-placement="top"><i class="sli sli-vote-rewards-badge-star-2 text-success"></i></span>{/if}
              </td>
              <td class="text-center">
                <span class="serviceMove btn btn-xs btn-default" title="Сортировка" data-toggle="tooltip" data-placement="top"><i class="sli sli-selection-cursors-cursor-move-up-down-1"></i></span>
                <a class="btn btn-xs btn-primary" title="{#button_edit#}" data-toggle="tooltip" data-placement="top" href="./index.php?route=services/edit&service_id={$service.service_id}">
                  <i class="sli sli-content-edition-pen-4"></i>
                </a>
                <a class="btn btn-xs btn-danger ConfirmDeleteService" title="{#button_delete#}" data-toggle="tooltip" data-placement="top" href="./index.php?route=services/delete&service_id={$service.service_id}">
                  <i class="sli sli-content-edition-bin-2"></i>
                </a>
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
      {if isset($smarty.session.permissions.services_edit) or isset($smarty.session.permissions.all_permissions)}
        <div class="card-footer text-right">
          {if $smarty.const.ORGACTIVE}
            <a href="/index.php?route=services/add" class="btn btn-primary btn-icon">
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