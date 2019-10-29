<div id="main-holder" class="row edit-layout">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="pull-right actions">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Отчет по заказам
        <p class="text-muted card-subheading">Данные за {$orders.period}</p>
      </div>
      <div class="card-block">
        <table id="ordersReportTable" class="data-table table table-striped nowrap" data-order="[[0,&quot;desc&quot;]]">
          <thead>
          <tr>
            <th>дата/время</th>
            <th data-orderable="false">клиент</th>
            <th>приемщик</th>
            <th>исполнитель</th>
            <th>статус</th>
            <th>сумма</th>
          </tr>
          </thead>
          <tfoot>
          <tr class="table-secondary">
            <th class="text-right" colspan="4">Итого</th>
            <td class="font-weight-bold">{$orders.total_orders} заказов</td>
            <td class="text-right font-weight-bold">{$orders.total_income|number_format:2:".":""}</td>
          </tr>
          </tfoot>
          <tbody>
          {foreach from=$orders.orders_data item=item}
            <tr class="{$item.status_class}">
              <td data-order="{$item.opened}">{$item.datetime}</td>
              <td>{$item.car_numplate}</td>
              <td>{$item.ulastname} {$item.ufirstname}</td>
              <td>{$item.elastname} {$item.efirstname}</td>
              <td data-order="{$item.status}">{$item.status_label}</td>
              <td class="text-right">{$item.sum|number_format:2:".":""}</td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>