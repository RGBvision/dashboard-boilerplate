<div id="main-holder" class="row edit-layout">
  <div class="col-12">
    <div id="employeesIncome" class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Заказов на сумму
        <p class="text-muted card-subheading">Данные за <span class="text-primary">{$interval_data.year}</span> ({$interval_data.compare_year})</p>
      </div>
      <div class="card-block">
        <div class="card-limit-30">
          <canvas class="chart-js" id="chartIncome"
                  data-suffix=""
                  data-chart-type="bar"
                  data-chart-data='{ldelim}"labels": [{$interval_data.labels}],"datasets": [{ldelim}"data": [{$interval_data.income}]{rdelim},{ldelim}"data": [{$interval_data.income_c}]{rdelim}]{rdelim}'>
          </canvas>
        </div>
      </div>
    </div>
    <div id="employeesSalary" class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Вознаграждение
        <p class="text-muted card-subheading">Данные за <span class="text-primary">{$interval_data.year}</span> ({$interval_data.compare_year})</p>
      </div>
      <div class="card-block">
        <div class="card-limit-30">
          <canvas class="chart-js" id="chartSalary"
                  data-suffix=""
                  data-chart-type="bar"
                  data-chart-data='{ldelim}"labels": [{$interval_data.labels}],"datasets": [{ldelim}"data": [{$interval_data.salary}]{rdelim},{ldelim}"data": [{$interval_data.salary_c}]{rdelim}]{rdelim}'>
          </canvas>
        </div>
      </div>
    </div>
  </div>
</div>