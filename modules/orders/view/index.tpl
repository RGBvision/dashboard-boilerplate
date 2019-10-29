<div id="main-holder" class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="pull-right actions">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Детализация заказов
        <p class="text-muted card-subheading">Данные за {$orders.period}</p>
      </div>
      <div class="card-block">
        <table id="ordersControlTable" class="table table-striped nowrap" data-order="[[0,&quot;desc&quot;]]">
          <thead>
          <tr>
            <th>дата/время</th>
            <th data-orderable="false">клиент</th>
            <th>приемщик</th>
            <th>исполнитель</th>
            <th>статус</th>
            <th>сумма</th>
            <th class="text-center" data-orderable="false"><i class="sli sli-content-edition-flash-2"></i></th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$orders.orders_data item=item}
            <tr class="{$item.status_class}">
              <td data-order="{$item.opened}">{$item.datetime}</td>
              <td>{$item.car_numplate}</td>
              <td>{$item.ulastname} {$item.ufirstname}</td>
              <td>{$item.elastname} {$item.efirstname}</td>
              <td data-order="{$item.status}">{$item.status_label}</td>
              <td class="text-right">{$item.sum|number_format:2:".":"'"}</td>
              <td class="text-center">
                <a class="btn btn-xs btn-primary" href="./orders/show?order_id={$item.order_id}">
                  <i class="sli sli-content-edition-view-1"></i>
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