{if ! $_is_ajax}
  {if isset($smarty.session.permissions.dashboard_summary) or isset($smarty.session.permissions.all_permissions)}
    <div class="row">
      <div class="col-12 col-xl-4">
        <div class="row">
          <div class="col-12 col-sm-4 col-xl-12">
            <div class="widget widget-chart white-bg padding-0">
              <div class="widget-title">
                <div class="font-500 pull-right">{$stats.total_income|number_format:0:".":"'"}<i class="fa fa-rub text-black-50"></i></div>
                <h2 class="margin-b-0">Выручка</h2>
              </div>
              <div class="widget-content px-0">
                <div class="card-limit-30" style="max-height: 100px">
                  <canvas class="chart-js" id="chartFinances"
                          data-chart-type="line"
                          data-suffix=""
                          data-chart-data='{ldelim}"labels": [{$stats.labels}],"datasets": [{ldelim}"data": [{$stats.revenue_data}]{rdelim}]{rdelim}'>
                  </canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-4 col-xl-12">
            <div class="widget widget-chart white-bg padding-0">
              <div class="widget-title">
                <div class="font-500 pull-right">{$stats.total_count|number_format:0:".":"'"}</div>
                <h2 class="margin-b-0">Заказы</h2>
              </div>
              <div class="widget-content px-0">
                <div class="card-limit-30" style="max-height: 100px">
                  <canvas class="chart-js" id="chartOrders"
                          data-chart-type="line"
                          data-suffix=""
                          data-chart-data='{ldelim}"labels": [{$stats.labels}],"datasets": [{ldelim}"data": [{$stats.count_data}]{rdelim}]{rdelim}'>
                  </canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-4 col-xl-12">
            <div class="widget widget-chart white-bg padding-0">
              <div class="widget-title">
                <div class="font-500 pull-right">{$stats.total_average_check|number_format:0:".":"'"}<i class="fa fa-rub text-black-50"></i></div>
                <h2 class="margin-b-0">Средний чек</h2>
              </div>
              <div class="widget-content px-0">
                <div class="card-limit-30" style="max-height: 100px">
                  <canvas class="chart-js" id="chartCheck"
                          data-chart-type="line"
                          data-suffix=""
                          data-chart-data='{ldelim}"labels": [{$stats.labels}],"datasets": [{ldelim}"data": [{$stats.average_check}]{rdelim}]{rdelim}'>
                  </canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-xl-8">
        <div class="card">
          <div class="card-heading card-default">
            События
          </div>
          <div class="card-block">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
    </div>
    {$payment_tpl}
  {/if}
{/if}