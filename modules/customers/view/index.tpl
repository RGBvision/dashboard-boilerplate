<div id="main-holder" class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        {#customers_page_title#}
      </div>
      <div class="card-block">
        <table id="customersControlTable" class="table table-striped nowrap" data-order="[[1,&quot;desc&quot;]]">
          <thead>
          <tr>
            <th class="text-center">{#customers_table_name#}</th>
            <th class="text-center">{#customers_table_vehicle#}</th>
            <th class="text-center">{#customers_table_vehicle_num#}</th>
            <th class="text-center">{#customers_table_vehicle_class#}</th>
            <th class="text-center">{#customers_table_phone#}</th>
            <th class="text-center d-none d-md-table-cell">{#customers_table_activity#}</th>
            <th class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$customers item=customer key=key}
            <tr>
              <td>
                <a href="./index.php?route=customers/edit&customer_id={$customer.customer_id}">{$customer.lastname} {$customer.firstname}</a>
              </td>
              <td class="text-center">
                {$customer.car_model|default:"-"}
              </td>
              <td class="text-center">
                {$customer.car_numplate|default:"-"}
              </td>
              <td class="text-center">
                {$customer.car_class|default:"-"}
              </td>
              <td class="text-center">
                {if $customer.phone}
                  <i class="sli sli-phone-phone-call-2 mr-2"></i>
                  <a href="tel:{$customer.phone|default:"-"}">{$customer.phone|normalizePhone:true}</a>
                {else}
                  -
                {/if}
              </td>
              <td class="text-center d-none d-md-table-cell">
                {$customer.last_activity|date_format:TIME_FORMAT|prettyDate}
              </td>
              <td class="text-center">
                <a class="btn btn-xs btn-primary" title="{#button_edit#}" data-pjax-nav data-push="true" data-container="#content" data-fragment="#content" href="./index.php?route=customers/edit&customer_id={$customer.customer_id}">
                  <i class="sli sli-content-edition-pen-4"></i>
                </a>
                <a class="btn btn-xs btn-danger ConfirmDeleteGroup" title="{#button_delete#}" href="./index.php?route=customers/delete&customer_id={$customer.customer_id}">
                  <i class="sli sli-content-edition-bin-2"></i>
                </a>
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>