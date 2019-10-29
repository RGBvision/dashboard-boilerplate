<div id="main-holder" class="row">
  <div class="col-12">
    <form id="serviceForm" action="/route/services/save" method="post">
      {if isset($service.service_id)}
        <input type="hidden" name="service_id" value="{$service.service_id}">
      {else}
        <input type="hidden" name="action" value="add">
      {/if}
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
              <div class="row">
                <div class="col-12">
                  <h6>{#services_input_name#}</h6>
                  <div class="form-group">
                    <input class="form-control input-sm" type="text" name="service_name" value="{$service.name|default:""}" autocomplete="off" required>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                  <label>Отдел</label>
                  <div class="form-group">
                    <select name="department" class="form-control">
                      {html_options options=$departmentOptions selected=$service.department}
                    </select>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                  <label>Макс. количество в заказе</label>
                  <div class="form-group">
                    <div class="input-group">
                      <input name="max_count" type="number" class="form-control" value="{$service.max_count|default:"1"}" min="1" required>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-auto">
                  <label>Тип услуги</label>
                  <div class="form-group pt-2">
                    <div class="form-check form-check-inline">
                      <div class="radio radio-primary">
                        <input name="type" id="type_1" type="radio" data-toggle="ctab" data-target="#pills-type-1" value="1" {if ($service.type == '1')}checked{/if}>
                        <label for="type_1" title="Единая стоимость независимо от класса автомобиля" data-toggle="tooltip" data-placement="top">Универсальная</label>
                      </div>
                    </div>
                    <div class="form-check form-check-inline">
                      <div class="radio radio-primary">
                        <input name="type" id="type_2" type="radio" data-toggle="ctab" data-target="#pills-type-2" value="2" {if ($service.type == '2')}checked{/if}>
                        <label for="type_2" title="Стоимость зависит от класса автомобиля" data-toggle="tooltip" data-placement="top">По классам</label>
                      </div>
                    </div>
                    <div class="form-check form-check-inline">
                      <div class="radio radio-primary">
                        <input name="type" id="type_3" type="radio" data-toggle="ctab" data-target="#pills-type-3" value="3" {if ($service.type == '3')}checked{/if}>
                        <label for="type_3" title="Стоимость зависит от произвольных параметров" data-toggle="tooltip" data-placement="top">По параметрам</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md">
                  <label>Опции</label>
                  <div class="form-group pt-2">
                    <div class="form-check form-check-inline">
                      <div class="checkbox checkbox-primary">
                        <input name="prime" id="prime-checkbox" type="checkbox" value="1" {if isset($service.prime) and ($service.prime == '1')}checked{/if}>
                        <label for="prime-checkbox" title="Входит в категорию «Приоритетные услуги»" data-toggle="tooltip" data-placement="top">Приоритетная</label>
                      </div>
                    </div>
                    <div class="form-check form-check-inline">
                      <div class="checkbox checkbox-primary">
                        <input name="bonus" id="bonus-checkbox" type="checkbox" value="1" {if isset($service.bonus) and ($service.bonus == '1')}checked{/if}>
                        <label for="bonus-checkbox" title="Входит в категорию «Акции»" data-toggle="tooltip" data-placement="top">Акционная</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-content" id="calculations-tabContent">
                <div class="collapse {if ($service.type == '1')}show{/if}" id="pills-type-1">
                  <div class="table-responsive">
                    <table class="table text-nowrap">
                      <thead>
                      <tr>
                        <th scope="col">Стоимость</th>
                        <th scope="col">Регламент<br>
                          <small>минут</small>
                        </th>
                        <th scope="col" class="table-light">Ставка<br>
                          <small>приемщик</small>
                        </th>
                        <th scope="col" class="table-light">Ед.изм.</th>
                        <th scope="col">Ставка<br>
                          <small>исполнитель</small>
                        </th>
                        <th scope="col">Ед.изм.</th>
                        <th scope="col" class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td>
                          <input name="calculation[0][cost]" type="number" class="form-control" value="{$service.calculation[0].cost|default:"0"|number_format:2:".":""}" required>
                        </td>
                        <td>
                          <input name="calculation[0][time]" type="number" class="form-control" value="{$service.calculation[0].time|default:"0"}" required>
                        </td>
                        <td class="table-light">
                          <input name="calculation[0][reward]" type="number" class="form-control" value="{$service.calculation[0].reward|default:"0"|number_format:2:".":""}" required>
                        </td>
                        <td class="table-light">
                          <select class="form-control" name="calculation[0][measure]">
                            {html_options options=$unit_ids selected=$service.calculation[0].measure|default:0}
                          </select>
                        </td>
                        <td>
                          <input name="calculation[0][salary]" type="number" class="form-control" value="{$service.calculation[0].salary|default:"0"|number_format:2:".":""}" required>
                        </td>
                        <td>
                          <select class="form-control" name="calculation[0][unit]">
                            {html_options options=$unit_ids selected=$service.calculation[0].unit}
                          </select>
                        </td>
                        <td class="text-center">
                          <span title="Технологическая карта" data-toggle="tooltip" data-placement="top"
                                class="d-inline-block"><button type="button" class="btn btn-xs btn-primary"
                                                               data-toggle="modal" data-target="#routingModal-0"><i class="sli sli-content-filter-text"></i></button></span>
                          <div id="routingModal-0" class="modal fade routingModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Технологическая карта</h4>
                                </div>
                                <div class="modal-body text-left">
                                  <div class="table-responsive">
                                    <table class="table text-nowrap m-0 routingTable">
                                      <thead>
                                      <tr>
                                        <th scope="col">Наименование</th>
                                        <th scope="col">Количество</th>
                                        <th scope="col">Ед.изм.</th>
                                        <th scope="col" class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <tr class="routing-tpl d-none">
                                        <td>
                                          <select class="form-control consumableSelect select2-tpl" style="width: 100%"></select>
                                        </td>
                                        <td>
                                          <input data-name="calculation[0][routing][consumable]" type="number" class="form-control" value="0" required>
                                        </td>
                                        <td class="route-unit">-</td>
                                        <td class="text-center">
                                          <button type="button" class="consumableDelete btn btn-xs btn-danger" title="{#button_delete#}" data-toggle="tooltip" data-placement="top">
                                            <i class="sli sli-content-edition-bin-2"></i>
                                          </button>
                                        </td>
                                      </tr>
                                      {if isset($service.calculation[0].routing)}
                                        {foreach from=$service.calculation[0].routing item=item key=key}
                                          <tr>
                                            <td><input class="form-control" value="{$consumables[$key].name|default:"-"}" readonly></td>
                                            <td><input name="calculation[0][routing][{$key}]" type="number" class="form-control" value="{$item|default:"0"|number_format:3:".":""}" required></td>
                                            <td class="route-unit">{$consumables[$key].unit|default:"-"}</td>
                                            <td class="text-center">
                                              <button type="button" class="consumableDelete btn btn-xs btn-danger" title="{#button_delete#}" data-toggle="tooltip" data-placement="top">
                                                <i class="sli sli-content-edition-bin-2"></i>
                                              </button>
                                            </td>
                                          </tr>
                                        {/foreach}
                                      {/if}
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-success btn-icon consumableAdd"><span><i class="sli sli-remove-add-add-1"></i></span> Добавить</button>
                                  <button type="button" class="btn btn-primary" data-dismiss="modal">{#button_close#}</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="collapse {if ($service.type == '2')}show{/if}" id="pills-type-2">
                  <div class="table-responsive">
                    <table id="salaryForm" class="table text-nowrap">
                      <thead>
                      <tr>
                        <th scope="col" class="text-center">Класс</th>
                        <th scope="col">Стоимость</th>
                        <th scope="col">Регламент<br>
                          <small>минут</small>
                        </th>
                        <th scope="col" class="table-light">Ставка<br>
                          <small>приемщик</small>
                        </th>
                        <th scope="col" class="table-light">Ед.изм.</th>
                        <th scope="col">Ставка<br>
                          <small>исполнитель</small>
                        </th>
                        <th scope="col">Ед.изм.</th>
                        <th scope="col" class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
                      </tr>
                      </thead>
                      <tbody>
                      {for $foo=1 to $smarty.session.organization_settings.classes}
                        <tr>
                          <td class="text-center">{$foo}</td>
                          <td>
                            <input name="calculation[{$foo}][cost]" type="number" class="form-control" value="{$service.calculation.$foo.cost|default:"0"|number_format:2:".":""}" required>
                          </td>
                          <td>
                            <input name="calculation[{$foo}][time]" type="number" class="form-control" value="{$service.calculation.$foo.time|default:"0"}" required>
                          </td>
                          <td class="table-light">
                            <input name="calculation[{$foo}][reward]" type="number" class="form-control" value="{$service.calculation.$foo.reward|default:"0"|number_format:2:".":""}" required>
                          </td>
                          <td class="table-light">
                            <select class="form-control" name="calculation[{$foo}][measure]">
                              {html_options options=$unit_ids selected=$service.calculation.$foo.measure|default:0}
                            </select>
                          </td>
                          <td>
                            <input name="calculation[{$foo}][salary]" type="number" class="form-control" value="{$service.calculation.$foo.salary|default:"0"|number_format:2:".":""}" required>
                          </td>
                          <td>
                            <select class="form-control" name="calculation[{$foo}][unit]">
                              {html_options options=$unit_ids selected=$service.calculation.$foo.unit}
                            </select>
                          </td>
                          <td class="text-center">
                            <span title="Технологическая карта" data-toggle="tooltip" data-placement="top"
                                  class="d-inline-block"><button type="button" class="btn btn-xs btn-primary"
                                                                 data-toggle="modal" data-target="#routingModal-{$foo}"><i class="sli sli-content-filter-text"></i></button></span>
                            <div id="routingModal-{$foo}" class="modal fade routingModal" tabindex="-1" role="dialog">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title">Технологическая карта</h4>
                                  </div>
                                  <div class="modal-body text-left">
                                    <div class="table-responsive">
                                      <table class="table text-nowrap m-0 routingTable">
                                        <thead>
                                        <tr>
                                          <th scope="col">Наименование</th>
                                          <th scope="col">Количество</th>
                                          <th scope="col">Ед.изм.</th>
                                          <th scope="col" class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="routing-tpl d-none">
                                          <td>
                                            <select class="form-control consumableSelect select2-tpl" style="width: 100%"></select>
                                          </td>
                                          <td>
                                            <input data-name="calculation[{$foo}][routing][consumable]" type="number" class="form-control" value="0" required>
                                          </td>
                                          <td class="route-unit">-</td>
                                          <td class="text-center">
                                            <button type="button" class="consumableDelete btn btn-xs btn-danger" title="{#button_delete#}" data-toggle="tooltip" data-placement="top">
                                              <i class="sli sli-content-edition-bin-2"></i>
                                            </button>
                                          </td>
                                        </tr>
                                        {if isset($service.calculation[$foo].routing)}
                                          {foreach from=$service.calculation[$foo].routing item=item key=key}
                                            <tr>
                                              <td><input class="form-control" value="{$consumables[$key].name|default:"-"}" readonly></td>
                                              <td><input name="calculation[{$foo}][routing][{$key}]" type="number" class="form-control" value="{$item|default:"0"|number_format:3:".":""}" required></td>
                                              <td class="route-unit">{$consumables[$key].unit|default:"-"}</td>
                                              <td class="text-center">
                                                <button type="button" class="consumableDelete btn btn-xs btn-danger" title="{#button_delete#}" data-toggle="tooltip" data-placement="top">
                                                  <i class="sli sli-content-edition-bin-2"></i>
                                                </button>
                                              </td>
                                            </tr>
                                          {/foreach}
                                        {/if}
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-success btn-icon consumableAdd"><span><i class="sli sli-remove-add-add-1"></i></span> Добавить</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">{#button_close#}</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                      {/for}
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="collapse {if ($service.type == '3')}show{/if}" id="pills-type-3">
                  <div class="table-responsive">
                    <table id="parametricForm" class="table text-nowrap">
                      <thead>
                      <tr>
                        <th scope="col">Параметр</th>
                        <th scope="col">Стоимость</th>
                        <th scope="col">Регламент<br>
                          <small>минут</small>
                        </th>
                        <th scope="col" class="table-light">Ставка<br>
                          <small>приемщик</small>
                        </th>
                        <th scope="col" class="table-light">Ед.изм.</th>
                        <th scope="col">Ставка<br>
                          <small>исполнитель</small>
                        </th>
                        <th scope="col">Ед.изм.</th>
                        <th scope="col" class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
                      </tr>
                      </thead>
                      <tbody id="parametricFormBody">
                      <tr class="d-none params-row topology">
                        <td>
                          <input data-name="calculation[parametric][index][name]" type="text" class="form-control" value="-" required>
                        </td>
                        <td>
                          <input data-name="calculation[parametric][index][cost]" type="number" class="form-control" value="0" required>
                        </td>
                        <td>
                          <input data-name="calculation[parametric][index][time]" type="number" class="form-control" value="0" required>
                        </td>
                        <td class="table-light">
                          <input data-name="calculation[parametric][index][reward]" type="number" class="form-control" value="0" required>
                        </td>
                        <td class="table-light">
                          <select data-name="calculation[parametric][index][measure]" class="form-control" required>
                            {html_options options=$unit_ids}
                          </select>
                        </td>
                        <td>
                          <input data-name="calculation[parametric][index][salary]" type="number" class="form-control" value="0" required>
                        </td>
                        <td>
                          <select data-name="calculation[parametric][index][unit]" class="form-control" required>
                            {html_options options=$unit_ids}
                          </select>
                        </td>
                        <td class="text-center">
                          <span class="paramMove btn btn-xs btn-default" title="Сортировка" data-toggle="tooltip" data-placement="top"><i class="sli sli-selection-cursors-cursor-move-up-down-1"></i></span>
                          <span title="Технологическая карта" data-toggle="tooltip" data-placement="top"
                                class="d-inline-block"><button type="button" class="btn btn-xs btn-primary paramModalBtn"
                                                               data-toggle="modal"
                                                               data-reindex="#routingModal-par-index"><i class="sli sli-content-filter-text"></i></button></span>
                          <button type="button" class="paramDel btn btn-xs btn-danger" title="{#button_edit#}" data-toggle="tooltip" data-placement="top"><i class="sli sli-content-edition-bin-2"></i></button>
                          <div data-reindex="routingModal-par-index"
                               class="modal fade routingModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Технологическая карта</h4>
                                </div>
                                <div class="modal-body text-left">
                                  <div class="table-responsive">
                                    <table class="table text-nowrap m-0 routingTable">
                                      <thead>
                                      <tr>
                                        <th scope="col">Наименование</th>
                                        <th scope="col">Количество</th>
                                        <th scope="col">Ед.изм.</th>
                                        <th scope="col" class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <tr class="routing-tpl d-none">
                                        <td>
                                          <select class="form-control consumableSelect select2-tpl" style="width: 100%"></select>
                                        </td>
                                        <td>
                                          <input data-name="calculation[parametric][index][routing][consumable]" type="number" class="form-control tpl" value="0" required>
                                        </td>
                                        <td class="route-unit">-</td>
                                        <td class="text-center">
                                          <button type="button" class="consumableDelete btn btn-xs btn-danger" title="{#button_delete#}" data-toggle="tooltip" data-placement="top">
                                            <i class="sli sli-content-edition-bin-2"></i>
                                          </button>
                                        </td>
                                      </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-success btn-icon consumableAdd"><span><i class="sli sli-remove-add-add-1"></i></span> Добавить</button>
                                  <button type="button" class="btn btn-primary" data-dismiss="modal">{#button_close#}</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      {foreach from=$service.calculation.parametric item=item name=foo}
                        <tr class="params-row">
                          <td>
                            <input name="calculation[parametric][{$smarty.foreach.foo.index}][name]" data-name="calculation[parametric][index][name]"
                                   type="text" class="form-control"
                                   value="{$item.name|default:"-"}" required>
                          </td>
                          <td>
                            <input name="calculation[parametric][{$smarty.foreach.foo.index}][cost]" data-name="calculation[parametric][index][cost]"
                                   type="number" class="form-control"
                                   value="{$item.cost|default:"0"|number_format:2:".":""}" required>
                          </td>
                          <td>
                            <input name="calculation[parametric][{$smarty.foreach.foo.index}][time]" data-name="calculation[parametric][index][time]"
                                   type="number" class="form-control"
                                   value="{$item.time|default:"0"}" required>
                          </td>
                          <td class="table-light">
                            <input name="calculation[parametric][{$smarty.foreach.foo.index}][reward]" data-name="calculation[parametric][index][reward]"
                                   type="number" class="form-control"
                                   value="{$item.reward|default:"0"|number_format:2:".":""}" required>
                          </td>
                          <td class="table-light">
                            <select class="form-control" name="calculation[parametric][{$smarty.foreach.foo.index}][measure]" data-name="calculation[parametric][index][measure]" required>
                              {html_options options=$unit_ids selected=$item.measure|default:0}
                            </select>
                          </td>
                          <td>
                            <input name="calculation[parametric][{$smarty.foreach.foo.index}][salary]" data-name="calculation[parametric][index][salary]"
                                   type="number" class="form-control"
                                   value="{$item.salary|default:"0"|number_format:2:".":""}" required>
                          </td>
                          <td>
                            <select class="form-control" name="calculation[parametric][{$smarty.foreach.foo.index}][unit]" data-name="calculation[parametric][index][unit]" required>
                              {html_options options=$unit_ids selected=$item.unit|default:0}
                            </select>
                          </td>
                          <td class="text-center">
                            <span class="paramMove btn btn-xs btn-default" title="Сортировка" data-toggle="tooltip" data-placement="top"><i class="sli sli-selection-cursors-cursor-move-up-down-1"></i></span>
                            <span title="Технологическая карта" data-toggle="tooltip" data-placement="top"
                                  class="d-inline-block"><button type="button" class="btn btn-xs btn-primary paramModalBtn"
                                                                 data-toggle="modal"
                                                                 data-target="#routingModal-par-{$smarty.foreach.foo.index}"
                                                                 data-reindex="#routingModal-par-index"><i class="sli sli-content-filter-text"></i></button></span>
                            <button type="button" class="paramDel btn btn-xs btn-danger" title="{#button_delete#}" data-toggle="tooltip" data-placement="top"><i class="sli sli-content-edition-bin-2"></i></button>
                            <div id="routingModal-par-{$smarty.foreach.foo.index}"
                                 data-reindex="routingModal-par-index"
                                 class="modal fade routingModal" tabindex="-1" role="dialog">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title">Технологическая карта</h4>
                                  </div>
                                  <div class="modal-body text-left">
                                    <div class="table-responsive">
                                      <table class="table text-nowrap m-0 routingTable">
                                        <thead>
                                        <tr>
                                          <th scope="col">Наименование</th>
                                          <th scope="col">Количество</th>
                                          <th scope="col">Ед.изм.</th>
                                          <th scope="col" class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="routing-tpl d-none">
                                          <td>
                                            <select class="form-control consumableSelect select2-tpl" style="width: 100%"></select>
                                          </td>
                                          <td>
                                            <input data-name="calculation[parametric][index][routing][consumable]" type="number" class="form-control tpl" value="0" required>
                                          </td>
                                          <td class="route-unit">-</td>
                                          <td class="text-center">
                                            <button type="button" class="consumableDelete btn btn-xs btn-danger" title="{#button_delete#}" data-toggle="tooltip" data-placement="top">
                                              <i class="sli sli-content-edition-bin-2"></i>
                                            </button>
                                          </td>
                                        </tr>
                                        {if isset($item.routing)}
                                          {foreach from=$item.routing item=consumable key=key}
                                            <tr>
                                              <td><input class="form-control" value="{$consumables[$key].name|default:"-"}" readonly></td>
                                              <td><input name="calculation[parametric][{$smarty.foreach.foo.index}][routing][{$key}]"
                                                         data-name="calculation[parametric][index][routing][consumable]"
                                                         type="number" class="form-control" value="{$consumable|default:"0"|number_format:3:".":""}" required></td>
                                              <td class="route-unit">{$consumables[$key].unit|default:"-"}</td>
                                              <td class="text-center">
                                                <button type="button" class="consumableDelete btn btn-xs btn-danger" title="{#button_delete#}" data-toggle="tooltip" data-placement="top">
                                                  <i class="sli sli-content-edition-bin-2"></i>
                                                </button>
                                              </td>
                                            </tr>
                                          {/foreach}
                                        {/if}
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-success btn-icon consumableAdd"><span><i class="sli sli-remove-add-add-1"></i></span> Добавить</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">{#button_close#}</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                      {/foreach}
                      </tbody>
                    </table>
                  </div>
                  <div class="text-right">
                    <button id="paramAdd" type="button" class="btn btn-success btn-icon"><span><i class="sli sli-remove-add-add-1"></i></span> Добавить</button>
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
<script>
  var consumablesData = [
    {foreach from=$consumables item=item name=foo}
    {
      id: {$item.good_id},
      text: '{$item.name}',
      units: '{$item.unit}'
    },
    {/foreach}
  ];
</script>
  </div>
</div>