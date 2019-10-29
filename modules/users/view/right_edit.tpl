{if (!isset($user.linked) or $user.linked == 0) and ($employees)}
  <button class="btn btn-danger btn-icon" id="linkEmployeeBtn" data-toggle="modal" data-target="#linkEmployee"><i class="sli sli-content-edition-link-2"></i> Связать с сотрудником</button>
  <div id="linkEmployee" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="linkEmployeeLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="linkEmployeeLabel">Связать с сотрудником</h4>
          </div>
          <div class="modal-body text-left">
            {foreach from=$employees item=employee key=key}
              <div class="radio radio-primary">
                <input class="linkEmployeeRadio" type="radio" id="linkEmployeeRadio_{$employee.employee_id}"
                       data-firstname="{$employee.firstname}"
                       data-lastname="{$employee.lastname}"
                       data-phone="{$employee.phone}"
                       data-id="{$employee.employee_id}">
                <label for="linkEmployeeRadio_{$employee.employee_id}">{$employee.lastname} {$employee.firstname}</label>
              </div>
            {/foreach}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{#button_cancel#}</button>
            <button type="button" id="linkEmployeeButton" class="btn btn-primary disabled" disabled>Применить</button>
          </div>
      </div>
    </div>
  </div>
{/if}
{if isset($user.linked) and $user.linked > 0}
  <a href="./index.php?route=employees/edit&employee_id={$user.emp_id}" class="btn btn-success btn-icon"><i class="sli sli-content-edition-link-2"></i> Связан с {$user.emp_lastname} {$user.emp_firstname}</a>
{/if}