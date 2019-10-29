<div id="main-holder" class="row">
  <div class="col-12">
    <form id="consumableForm" action="/route/store/save" method="post">
      {if isset($good.good_id)}
        <input type="hidden" name="good_id" value="{$good.good_id}">
      {else}
        <input type="hidden" name="action" value="add">
      {/if}
      <input type="hidden" name="type" value="consumable">
      <input type="hidden" name="department_src" value="{$good.department|default:0}">
      <input type="hidden" name="count_src" value="{$good.count|default:0}">
      <input type="hidden" name="cost_src" value="{$good.cost|default:0}">
      <input type="hidden" name="net" value="0">
      <input type="hidden" name="net_src" value="0">
      <input type="hidden" name="markup" value="0">
      <input type="hidden" name="markup_src" value="0">
      <input type="hidden" name="type" value="0">
      <div class="row">
        <div class="col-12">
          <div class="card" id="goodParams">
            <div class="card-heading card-default">
              <div class="actions pull-right">
                <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
              </div>
              {#goods_input_parameters#}
            </div>
            <div class="card-block">
              <div class="row">
                <div class="col-12">
                  <h6>{#goods_input_name#}</h6>
                  <div class="form-group">
                    <input class="form-control input-sm" type="text" name="name" value="{$good.name|default:""}" autocomplete="off" required>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                  <label>Отдел</label>
                  <div class="form-group">
                    <select name="department" class="form-control" data-value="{$good.department|default:0}" required>
                      {html_options options=$departments selected=$good.department}
                    </select>
                    <small class="form-text text-danger d-none"></small>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                  <label>Количество</label>
                  <div class="form-group">
                    <input class="form-control input-sm" type="number" name="count" value="{$good.count|default:0}" data-value="{$good.count|default:0}" autocomplete="off" required>
                    <small class="form-text text-danger d-none"></small>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                  <label>Ед.изм.</label>
                  <div class="form-group">
                    {if isset($good.good_id)}
                    <input class="form-control input-sm" type="text" name="unit" value="{$good.unit|default:"-"}" autocomplete="off" required readonly>
                    {else}
                      <select name="unit" class="form-control" autocomplete="off" required>
                        <option disabled selected>Выбрать...</option>
                        <option>шт</option>
                        <option>гр</option>
                        <option>л</option>
                      </select>
                    {/if}
                  </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                  <label>Стоимость ед.</label>
                  <div class="form-group">
                    <input class="form-control input-sm" type="number" name="cost" value="{$good.cost|default:0}" data-value="{$good.cost|default:0}" autocomplete="off" required>
                    <small class="form-text text-danger d-none"></small>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label>Комментарий</label>
                    <textarea class="summernote" name="description">{$good.description|default:""|htmlspecialchars_decode}</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-12 text-right">
          {if $smarty.const.ORGACTIVE}
            <button class="SaveGoodBtn btn btn-primary btn-icon">
              <i class="sli sli-status-check-1"></i> {#button_save#}
            </button>
          {/if}
          <a href="/route/store/consumables" class="btn btn-danger btn-icon">
            <i class="sli sli-navigation-navigation-before-1"></i> {#button_cancel#}
          </a>
        </div>
      </div>
    </form>
  </div>
</div>