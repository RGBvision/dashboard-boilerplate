<div id="main-holder" class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="pull-right actions">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Модули системы
        <p class="text-muted card-subheading">В разделе отображается список доступных модулей с указанием версии модуля, даты и активности модуля.</p>
      </div>
      <div class="card-block">
        <table id="modulesControlTable" class="table table-striped nowrap"
               data-order="[[3,&quot;asc&quot;]]"
               data-jquery="DataTable"
               data-jquery-destroy="$(e).DataTable().destroy()"
               data-options="{
                dom: 'r<&quot;table-responsive&quot;t>',
                stateSave: true,
                colReorder: true,
                language: dataTable_lang,
                'paging': false
                }">
          <thead>
          <tr>
            <th>Наименование</th>
            <th>Версия</th>
            <th>Дата</th>
            <th>Модуль</th>
            <th class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$classes item="module"}
            {assign var=module_title value=$module.title}
            <tr>
              <td>{$smarty.config.$module_title}</td>
              <td>{$module.ver}</td>
              <td>{$module.date}</td>
              <td>{$module.short}</td>
              <td class="text-center">
                <div class="form-inline">
                  <div class="switch switch-sm mx-auto">
                    <span class="mr-2">Off</span>
                    <input type="checkbox"
                           class="switch"
                           id="checkbox-{$module.short}"
                           name="modules[]"
                           value="{$module.short}" checked><label for="checkbox-{$module.short}"></label>
                    <span class="ml-2">On</span>
                  </div>
                </div>
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>