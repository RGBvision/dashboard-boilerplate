<div id="main-holder" class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="pull-right actions">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        Документы
        <p class="text-muted card-subheading">Данные за {$documents.period}</p>
      </div>
      <div class="card-block">
        <table id="documentsControlTable" class="table table-striped nowrap" data-order="[[1,&quot;desc&quot;]]">
          <thead>
          <tr>
            <th data-orderable="false">№</th>
            <th>дата/время</th>
            {if {$access}}
              <th data-orderable="false">клиент</th>
            {/if}
            <th>приемщик</th>
            <th>тип</th>
            {if {$access}}
              <th class="text-center" data-orderable="false"><i class="sli sli-content-edition-flash-2"></i></th>
            {/if}
          </tr>
          </thead>
          <tbody>
          {foreach from=$documents.documents_data item=item}
            <tr>
              <td>{$item.numerate}</td>
              <td data-order="{$item.regtime}">{$item.datetime}</td>
              <td>{$item.car_numplate}</td>
              <td>{$item.ulastname} {$item.ufirstname}</td>
              <td>{$item.name}</td>
              {if {$access}}
                <td class="text-center">
                  <button type="button" class="btn btn-xs btn-info docShow"
                          data-url="/route/documents/show&only=1&id={$item.doc_id}">
                    <i class="sli sli-basic-file-view-2"></i>
                  </button>
                  {*<a class="btn btn-xs btn-primary" href="/route/documents/edit&id={$item.doc_id}">
                    <i class="sli sli-content-edition-pen-4"></i>
                  </a>*}
                </td>
              {/if}
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>