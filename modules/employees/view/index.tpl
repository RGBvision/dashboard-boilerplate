<div id="main-holder" class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        {#employees_page_title#}
      </div>
      <div class="card-block">
        <table id="employeesControlTable" class="table table-striped nowrap" data-order="[[0,&quot;asc&quot;]]">
          <thead>
          <tr>
            <th class="text-center">{#employees_table_name#}</th>
            <th class="text-center">{#employees_table_phone#}</th>
            <th class="text-center">{#employees_table_department#}</th>
            <th class="text-center" data-orderable="false"><i class="sli sli-content-edition-flash-2"></i></th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$employees item=employee key=key}
            <tr>
              {if $employee.editable}
                <td>
                  <a href="./index.php?route=employees/edit&employee_id={$employee.employee_id}">
                    <img alt="profile" class="rounded-circle mr-3 d-none d-md-inline" src="{$employee.avatar}" width="48">
                    {if $employee.linked_employee}<span title="Аккаунт связан с пользователем" data-toggle="tooltip" data-placement="top"><i class="sli sli-content-edition-link-2 text-danger mr-2"></i></span>{/if}
                    {$employee.lastname} {$employee.firstname}
                  </a>
                </td>
              {else}
                <td>
                  <img alt="profile" class="rounded-circle mr-3 d-none d-md-inline" src="{$employee.avatar}" width="48"> {$employee.lastname} {$employee.firstname}
                </td>
              {/if}
              <td class="text-center">
                {if $employee.phone}
                  <i class="sli sli-phone-phone-call-2 mr-2"></i>
                  <a href="tel:{$employee.phone|default:"-"}">{$employee.phone|normalizePhone:true}</a>
                {else}
                  -
                {/if}
              </td>
              <td class="text-center">
                {$employee.department_name|default:"-"}
              </td>
              <td class="text-center">
                {if $employee.editable}
                  <a class="btn btn-xs btn-primary" title="{#button_edit#}" data-toggle="tooltip" data-placement="top" href="./index.php?route=employees/edit&employee_id={$employee.employee_id}">
                    <i class="sli sli-content-edition-pen-4"></i>
                  </a>
                {else}
                  <a class="btn btn-xs btn-default disabled" title="{#button_edit#}" href="#." disabled>
                    <i class="sli sli-content-edition-pen-4"></i>
                  </a>
                {/if}
                {if $employee.deletable}
                  <a class="btn btn-xs btn-danger ConfirmDeleteEmployee" title="{#button_delete#}" data-toggle="tooltip" data-placement="top" href="./index.php?route=employees/delete&employee_id={$employee.employee_id}">
                    <i class="sli sli-content-edition-bin-2"></i>
                  </a>
                {else}
                  <a class="btn btn-xs btn-default disabled" title="{#button_delete#}" href="#." disabled>
                    <i class="sli sli-content-edition-bin-2"></i>
                  </a>
                {/if}
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
      {if isset($smarty.session.permissions.employees_edit) or isset($smarty.session.permissions.all_permissions)}
        <div class="card-footer text-right">
          {if $smarty.const.ORGACTIVE and $can_add_employee}
            <a href="/index.php?route=employees/add" class="btn btn-primary btn-icon">
              <span><i class="sli sli-remove-add-add-1"></i></span> Добавить
            </a>
          {else}
            <a href="#." class="btn btn-primary btn-icon disabled" disabled="">
              <span><i class="sli sli-remove-add-add-1"></i></span> Добавить
            </a>
          {/if}
        </div>
      {/if}
    </div>
  </div>
</div>