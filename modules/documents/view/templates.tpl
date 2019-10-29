<div id="main-holder" class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="pull-right actions">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Шаблоны документов
      </div>
      <div class="card-block">
        <table id="templatesControlTable" class="table table-striped nowrap" data-order="[[1,&quot;desc&quot;]]">
          <thead>
          <tr>
            <th>название</th>
            <th>создан</th>
            <th>изменен</th>
            <th class="text-center" data-orderable="false"><i class="sli sli-content-edition-flash-2"></i></th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$templates item=item}
            <tr>
              <td>{$item.name}</td>
              <td data-order="{$item.regtime}">{$item.created}</td>
              <td data-order="{$item.edittime}">{$item.edited}</td>
              <td class="text-center">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#tplModal-{$item.template_id}">
                  <i class="sli sli-basic-file-view-2"></i>
                </button>
                <a class="btn btn-xs btn-primary" href="./index.php?route=documents/tpledit&id={$item.template_id}">
                  <i class="sli sli-content-edition-pen-4"></i>
                </a>
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
      <div class="card-footer text-right">
        {if $smarty.const.ORGACTIVE}
          <a href="/route/documents/tpladd" class="btn btn-primary btn-icon">
            <span><i class="sli sli-remove-add-add-1"></i></span> Добавить
          </a>
        {else}
          <a href="#." class="btn btn-primary btn-icon disabled" disabled="">
            <span><i class="sli sli-remove-add-add-1"></i></span> Добавить
          </a>
        {/if}
      </div>
    </div>
  </div>
</div>
{foreach from=$templates item=item}
  <div id="tplModal-{$item.template_id}" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{$item.name}</h4>
        </div>
        <div class="modal-body">
          {$item.template|default:""|htmlspecialchars_decode}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{#button_cancel#}</button>
        </div>
      </div>
    </div>
  </div>
{/foreach}