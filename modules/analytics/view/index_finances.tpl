<div id="main-holder" class="row edit-layout">
  <div class="col-12">
    <div id="financesPeriod" class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Период
        <p class="text-muted card-subheading">Данные за <span class="text-primary">{$interval_income.year}</span> ({$interval_income.compare_year})</p>
      </div>
      <div class="card-block">
        <div class="card-limit-30">
          <canvas class="chart-js" id="chartPeriod"
                  {if isset($smarty.session.user_settings.analytics.finances.ordersInterval.datatype) and  {$access} and $smarty.session.user_settings.analytics.finances.ordersInterval.datatype == 1}
                    data-suffix=""
                  {else}
                    data-suffix="%"
                  {/if}
                  {if isset($smarty.session.user_settings.analytics.finances.ordersInterval.viewtype) and $smarty.session.user_settings.analytics.finances.ordersInterval.viewtype == 1}
                    data-chart-type="radar"
                  {else}
                    data-chart-type="line"
                  {/if}
                  data-chart-data='{ldelim}"labels": [{$interval_income.labels}],"datasets": [{ldelim}"data": [{$interval_income.revenue_data}]{rdelim},{ldelim}"data": [{$interval_income.compare_data}]{rdelim}]{rdelim}'>
          </canvas>
        </div>
      </div>
      {if {$access}}
        <div class="card-footer">
          <small>Всего <strong class="text-primary">{$interval_income.total|number_format:0:".":"'"}</strong> ({$interval_income.compare_total|number_format:0:".":"'"}) | Динамика {if $interval_income.compare_diff_pct > 0}<strong class="text-success">+{$interval_income.compare_diff_pct|number_format:2:".":"'"}%</strong>{else}<strong class="text-danger">{$interval_income.compare_diff_pct|number_format:2:".":"'"}%</strong>{/if}</small>
        </div>
      {else}
        <div class="card-footer">
          <small>Динамика {if $interval_income.compare_diff_pct > 0}<strong class="text-success">+{$interval_income.compare_diff_pct}%</strong>{else}<strong class="text-danger">{$interval_income.compare_diff_pct}%</strong>{/if}</small>
        </div>
      {/if}
    </div>
  </div>
  <div class="col-12 mb-2">
    <h4>Распределение поступлений</h4>
  </div>
  <div class="col-xl-4" data-portlet data-width="1" data-min-width="1" data-max-width="3">
    <div id="financesYear" class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Год
        <p class="text-muted card-subheading">Данные за {$annual_income.year}</p>
      </div>
      <div class="card-block">
        <div class="card-limit-30">
          <canvas class="chart-js" id="chartYear"
                  {if isset($smarty.session.user_settings.analytics.finances.ordersYear.datatype) and  {$access} and $smarty.session.user_settings.analytics.finances.ordersYear.datatype == 1}
                    data-suffix=""
                  {else}
                    data-suffix="%"
                  {/if}
                  {if isset($smarty.session.user_settings.analytics.finances.ordersYear.viewtype) and $smarty.session.user_settings.analytics.finances.ordersYear.viewtype == 1}
                    data-chart-type="radar"
                  {else}
                    data-chart-type="line"
                  {/if}
                  data-chart-data='{ldelim}"labels": [{$annual_income.labels}],"datasets": [{ldelim}"data": [{$annual_income.revenue_data}]{rdelim}]{rdelim}'>
          </canvas>
        </div>
      </div>
      {if {$access}}
        <div class="card-footer">
          <small>Всего <strong>{$annual_income.total|number_format:0:".":"'"}</strong></small>
        </div>
      {/if}
    </div>
  </div>
  <div class="col-xl-4" data-portlet data-width="1" data-min-width="1" data-max-width="3">
    <div id="averageDaily" class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Неделя
        <p class="text-muted card-subheading">Данные за {$daily_average.period}</p>
      </div>
      <div class="card-block">
        <div class="card-limit-30">
          <canvas class="chart-js" id="chartDaily"
                  {if isset($smarty.session.user_settings.analytics.finances.averageDaily.datatype) and  {$access} and $smarty.session.user_settings.analytics.finances.averageDaily.datatype == 1}
                    data-suffix=""
                  {else}
                    data-suffix="%"
                  {/if}
                  {if isset($smarty.session.user_settings.analytics.finances.averageDaily.viewtype) and $smarty.session.user_settings.analytics.finances.averageDaily.viewtype == 1}
                    data-chart-type="radar"
                  {else}
                    data-chart-type="line"
                  {/if}
                  data-chart-data='{ldelim}"labels": [{$daily_average.labels}], "datasets": [{ldelim}"data": [{$daily_average.income_data}]{rdelim}]{rdelim}'>

          </canvas>
        </div>
      </div>
      {if {$access}}
        <div class="card-footer">
          <small>Всего <strong>{$daily_average.total|number_format:0:".":"'"}</strong></small>
        </div>
      {/if}
    </div>
  </div>
  <div class="col-xl-4" data-portlet data-width="1" data-min-width="1" data-max-width="3">
    <div id="averageHourly" class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Сутки
        <p class="text-muted card-subheading">Данные за {$hourly_average.period}</p>
      </div>
      <div class="card-block">
        <div class="card-limit-30">
          <canvas class="chart-js" id="chartHourly"
                  {if isset($smarty.session.user_settings.analytics.finances.averageHourly.datatype) and  {$access} and $smarty.session.user_settings.analytics.finances.averageHourly.datatype == 1}
                    data-suffix=""
                  {else}
                    data-suffix="%"
                  {/if}
                  {if isset($smarty.session.user_settings.analytics.finances.averageHourly.viewtype) and $smarty.session.user_settings.analytics.finances.averageHourly.viewtype == 1}
                    data-chart-type="radar"
                  {else}
                    data-chart-type="line"
                  {/if}
                  data-chart-data='{ldelim}"labels": [{$hourly_average.labels}], "datasets": [{ldelim}"data": [{$hourly_average.income_data}]{rdelim}]{rdelim}'>

          </canvas>
        </div>
      </div>
      {if {$access}}
        <div class="card-footer">
          <small>Всего <strong>{$hourly_average.total|number_format:0:".":"'"}</strong></small>
        </div>
      {/if}
    </div>
  </div>
</div>
{$finances_settings_tpl}