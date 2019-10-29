<div id="main-holder" class="row edit-layout">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="pull-right actions">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Отчет по финансам
        <p class="text-muted card-subheading">Данные за {$finances.period}</p>
      </div>
      <div class="card-block">
        <table id="financesReportTable" class="data-table table table-striped nowrap" data-order="[[0,&quot;desc&quot;]]">
          <thead>
          <tr>
            <th>дата</th>
            <th>заказов</th>
            <th>средний чек</th>
            <th>зарплата</th>
            <th>выручка</th>
          </tr>
          </thead>
          <tfoot>
          <tr class="table-secondary">
            <th class="text-right">Итого</th>
            <td class="text-right font-weight-bold">{$finances.total_count}</td>
            <td class="text-right font-weight-bold">{$finances.total_average_check|number_format:2:".":""}</td>
            <td class="text-right font-weight-bold">{$finances.total_salary|number_format:2:".":""}</td>
            <td class="text-right font-weight-bold">{$finances.total_sum|number_format:2:".":""}</td>
          </tr>
          </tfoot>
          <tbody>
          {foreach from=$finances.finances_data item=item}
            <tr class="{$item.status_class}">
              <td data-order="{$item.report_rts}">{$item.report_ts}</td>
              <td class="text-right">{$item.report_count}</td>
              <td class="text-right">{$item.average_check|number_format:2:".":""}</td>
              <td class="text-right">{$item.report_salary|number_format:2:".":""}</td>
              <td class="text-right">{$item.report_sum|number_format:2:".":""}</td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>