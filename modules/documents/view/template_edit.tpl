<div id="main-holder" class="row">
  <div class="col-lg-12">
    <form id="tplEditForm" method="post" action="/route/documents/tplsave">
      <input type="hidden" name="action" value="{$action}">
      <input type="hidden" name="template_id" value="{$template.template_id|default:""}">
      <div class="card">
        <div class="card-heading">
          Параметры документа
        </div>
        <div class="card-block">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="template_name">Название</label>
              <input class="form-control input-sm" type="text" name="template_name" value="{$template.name|default:""}" id="template_name" autocomplete="off" required>
            </div>
            <div class="form-group col-md-6">
              <label for="template_numerate">Нумерация</label>
              <input class="form-control input-sm" type="text" name="template_numerate" value="{$template.numerate|default:""}" id="template_numerate" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
              <label>Формировать</label>
              <select name="template_department" class="form-control" required>
                {html_options options=$department_types selected=$template.department|default:0}
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Выводить для печати</label>
              <select name="template_show" class="form-control" required>
                {html_options options=$show_types selected=$template.show|default:0}
              </select>
            </div>
          </div>
          <div class="form-group mb-0">
            <label>Шаблон</label>
            <textarea class="summernote" name="content">{$template.template|default:""|htmlspecialchars_decode}</textarea>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-12 text-right">
          <button type="submit" class="btn btn-primary btn-icon">
            <i class="sli sli-status-check-1"></i> {#button_save#}
          </button>
        </div>
      </div>
    </form>
  </div>
</div>