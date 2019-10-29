<div id="main-holder" class="row">
  <div class="col-12">
    <form id="serviceForm" action="/route/services/save" method="post">
      <input type="hidden" name="action" value="add">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-heading card-primary">
              <i class="sli sli-content-edition-pen-4"></i>
              {#services_input_name#}
            </div>
            <div class="card-block">
              <div class="form-group m-b-n">
                <input class="form-control input-sm" type="text" name="service_name" value="{$service.name|default:""}" autocomplete="off" required>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card" id="serviceUTM">
            <div class="card-heading card-default">
              <div class="actions pull-right">
                <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
              </div>
              {#services_input_parameters#}
            </div>
            <div class="card-block">
              <h6>Стоимость</h6>
              <div class="row">
                <div class="col-12 col-md">
                  <div class="form-group">
                    <label>Класс 1 <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input name="cost_1" type="number" class="form-control" value="{$service.cost1|default:""}" required>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md">
                  <div class="form-group">
                    <label>Класс 2</label>
                    <div class="input-group">
                      <input name="cost_2" type="number" class="form-control" value="{$service.cost2|default:""}">
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md">
                  <div class="form-group">
                    <label>Класс 3</label>
                    <div class="input-group">
                      <input name="cost_3" type="number" class="form-control" value="{$service.cost3|default:""}">
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md">
                  <div class="form-group">
                    <label>Класс 4</label>
                    <div class="input-group">
                      <input name="cost_4" type="number" class="form-control" value="{$service.cost4|default:""}">
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md">
                  <div class="form-group">
                    <label>Класс 5</label>
                    <div class="input-group">
                      <input name="cost_5" type="number" class="form-control" value="{$service.cost5|default:""}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-lg-4">
                  <div class="form-group">
                    <label>Опции</label>
                    <div class="checkbox checkbox-primary">
                      <input name="active" id="active-checkbox" type="checkbox" value="1" {if isset($service.active)}checked=""{/if}>
                      <label for="active-checkbox">Активна</label>
                    </div>
                    <div class="checkbox checkbox-primary">
                      <input name="prime" id="prime-checkbox" type="checkbox" value="1" {if isset($service.prime)}checked=""{/if}>
                      <label for="prime-checkbox">Основная</label>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-lg-4">
                  <div class="form-group">
                    <label>Регламент (0:00 - без ограничений)</label>
                    <div class="input-group clockpicker">
                      <input name="time_limit" type="text" class="form-control" value="{$service.time_limit|default:"0:00"}" required>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-lg-4">
                  <div class="form-group">
                    <label>Отдел</label>
                    <select name="department" class="form-control">
                      <option value="0">Любой</option>
                      <option value="1">Мойка</option>
                      <option value="2">Сервис</option>
                      <option value="3">Касса</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label>Описание услуги</label>
                    <textarea class="summernote" name="description">{$service.description|default:""|htmlspecialchars_decode}</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-12 text-right">
          {if $access_edit}
            <button class="SaveServiceBtn btn btn-primary btn-icon">
              <i class="sli sli-status-check-1"></i> {#button_save#}
            </button>
          {/if}
          <a href="/route/services" class="btn btn-danger btn-icon">
            <i class="sli sli-navigation-navigation-before-1"></i> {#button_cancel#}
          </a>
        </div>
      </div>
    </form>

  </div>
</div>