{assign var=disable value=''}
{if !$employee.editable}{assign var=disable value='disabled'}{/if}
<div id="main-holder" class="row">
  <div class="col-12">
    {if $employee.editable}
    <form id="EmployeeForm" action="/index.php?route=employees/save" method="post">
      {/if}
      {if $action == 'save'}
        <input type="hidden" name="employee_id" value="{$employee.employee_id}">
      {/if}
      <input type="hidden" id="action" name="action" value="{$action}">
      <div class="row">
        <div class="col-12 col-lg-4 col-xl-3">
          <div class="widget padding-0 white-bg">
            <div class="bg-primary" style="height: 55px"></div>
            <div class="thumb-over">
              <img id="employeeAvatar" src="{$employee.employee_avatar|default:"/uploads/avatars/default.jpg"}" alt="" width="180" class="rounded-circle">
              <input type="hidden" id="new_avatar" name="new_avatar" value="">
            </div>
            <div class="p-3 text-center">
              <button type="button" id="changeAvatar" class="btn btn-primary btn-icon {$disable}" {$disable}><i class="sli sli-photos-images-camera-sync-1"></i> Изменить</button>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-8 col-xl-9">
          <div class="card">
            <div class="card-heading card-primary">
              Профиль
            </div>
            <div class="card-block">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="firstname" class="form-label">Имя</label>
                    <input type="text" class="form-control {$disable}" {$disable} id="firstname" name="firstname" value="{$employee.firstname|default:""}" required>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="lastname" class="form-label">Фамилия</label>
                    <input type="text" class="form-control {$disable}" {$disable} id="lastname" name="lastname" value="{$employee.lastname|default:""}" required>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="lastname" class="form-label">Телефон</label>
                    <input type="tel" class="form-control {$disable}" {$disable} id="phone" name="phone" value="{$employee.phone|default:""}" required>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="department" class="form-label">Отдел</label>
                    <select class="form-control {$disable}" {$disable} id="department" name="department" class="form-control">
                      {html_options values=$departments.ids output=$departments.names selected=$employee.department_id}
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-heading">
              Формула расчета зарплаты
            </div>
            <div class="card-block">
              <div class="table-responsive">
                <table id="salaryForm" class="table text-nowrap">
                  <thead>
                  <tr>
                    <th scope="col">Событие</th>
                    <th scope="col">Ставка</th>
                    <th scope="col">Ед.изм.</th>
                    <th scope="col" class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
                  </tr>
                  </thead>
                  <tbody id="salaryFormBody">
                  <tr class="d-none salary-row topology">
                    <td>
                      <select class="form-control {$disable}" {$disable} name="salary[index][operand]" data-name="salary[index][operand]">
                        <option value="0" selected disabled>Выбрать</option>
                        {html_options options=$operand_ids}
                      </select>
                    </td>
                    <td><input type="number" class="form-control {$disable}" {$disable} name="salary[index][cost]" data-name="salary[index][cost]" value="0" required></td>
                    <td>
                      <select class="form-control {$disable}" {$disable} name="salary[index][unit]" data-name="salary[index][unit]" required>
                        <option value="0" selected disabled>Выбрать</option>
                        {html_options options=$unit_ids}
                      </select>
                    </td>
                    <td class="text-center">
                      <span class="salaryMove btn btn-xs btn-default"><i class="sli sli-selection-cursors-cursor-move-up-down-1"></i></span>
                      <button type="button" class="salaryDel btn btn-xs btn-danger"><i class="sli sli-content-edition-bin-2"></i></button>
                    </td>
                  </tr>
                  {if $employee.salary}
                    {foreach from=$employee.salary item=item key=key name=foo}
                      <tr class="salary-row">
                        <td>
                          <select class="form-control {$disable}" {$disable} name="salary[{$smarty.foreach.foo.index}][operand]" data-name="salary[index][operand]" required>
                            {html_options options=$operand_ids selected=$item.operand}
                          </select>
                        </td>
                        <td><input type="number" class="form-control {$disable}" {$disable} name="salary[{$smarty.foreach.foo.index}][cost]" data-name="salary[index][cost]" value="{$item.cost}" required></td>
                        <td>
                          <select class="form-control {$disable}" {$disable} name="salary[{$smarty.foreach.foo.index}][unit]" data-name="salary[index][unit]">
                            {html_options options=$unit_ids selected=$item.unit}
                          </select>
                        </td>
                        <td class="text-center">
                          <span class="salaryMove btn btn-xs btn-default" title="Сортировка" data-toggle="tooltip" data-placement="top"><i class="sli sli-selection-cursors-cursor-move-up-down-1"></i></span>
                          <button type="button" class="salaryDel btn btn-xs btn-danger" title="{#button_delete#}" data-toggle="tooltip" data-placement="top"><i class="sli sli-content-edition-bin-2"></i></button>
                        </td>
                      </tr>
                    {/foreach}
                  {/if}
                  </tbody>
                </table>
              </div>
              <div class="text-right">
                <button id="salaryAdd" type="button" class="btn btn-success btn-icon"><span><i class="sli sli-remove-add-add-1"></i></span> Добавить</button>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-heading">
              Комментарий
            </div>
            <div class="card-block">
              <textarea class="summernote" name="description">{$employee.description|default:""|htmlspecialchars_decode}</textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-12 text-right">
          {if $employee.editable}
            <button type="submit" class="SaveEmployeeBtn btn btn-primary btn-icon">
              <i class="sli sli-status-check-1"></i> {#button_save#}
            </button>
          {/if}
          <a href="/index.php?route=employees" class="btn btn-danger btn-icon" data-pjax-nav data-push="true" data-container="#content" data-fragment="#content">
            <i class="sli sli-navigation-navigation-before-1"></i> {#button_cancel#}
          </a>
        </div>
      </div>
      {if $employee.editable}
    </form>
  <input id="uploadAvatar" type="file" accept="image/*" capture="camera" class="d-none">
    {/if}
  </div>
</div>