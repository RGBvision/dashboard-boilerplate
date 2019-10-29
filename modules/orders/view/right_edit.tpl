{if isset($smarty.session.permissions.orders_payment)}
  <button type="button" class="btn btn-info btn-icon" data-toggle="modal" data-target="#customerModal"><span><i class="sli sli-users-actions-person-information-2"></i></span>Клиент</button>
  <div id="customerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form id="customerForm" method="post" action="/route/customers/update">
          <input type="hidden" name="customer_id" value="{$order_data.customer.customer_id}">
          <div class="modal-header">
            <h4 class="modal-title" id="customerModalLabel">Информация о клиенте</h4>
          </div>
          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="customer_lastname">Фамилия</label>
                <input type="text" class="form-control" name="customer_lastname" id="customer_lastname" value="{$order_data.customer.lastname}" required>
              </div>
              <div class="form-group col-md-4">
                <label for="customer_firstname">Имя</label>
                <input type="text" class="form-control" name="customer_firstname" id="customer_firstname" value="{$order_data.customer.firstname}" required>
              </div>
              <div class="form-group col-md-4">
                <label for="customer_secondname">Отчество</label>
                <input type="text" class="form-control" name="customer_secondname" id="customer_secondname" value="{$order_data.customer.secondname}">
              </div>
              <div class="form-group col-md-6">
                <label for="customer_phone">Телефон</label>
                <input type="text" class="form-control" name="customer_phone" id="customer_phone" value="{$order_data.customer.phone}">
              </div>
              <div class="form-group col-md-6">
                <label for="customer_email">Email</label>
                <input type="text" class="form-control" name="customer_email" id="customer_email" value="{$order_data.customer.email}">
              </div>
              <div class="form-group col-md-6">
                <label for="customer_car_model">Марка а/м</label>
                <input type="text" class="form-control" name="customer_car_model" id="customer_car_model" value="{$order_data.customer.car_model}">
              </div>
              <div class="form-group col-md-6">
                <label for="customer_car_numplate">Гос.номер а/м</label>
                <input type="text" class="form-control" name="customer_car_numplate" id="customer_car_numplate" value="{$order_data.customer.car_numplate}">
              </div>
              <div class="form-group col-12">
                <label for="customer_description">Комментарий</label>
                <textarea class="summernote" name="description" id="customer_description">{$order_data.customer.description|default:""|htmlspecialchars_decode}</textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer d-block">
            <div class="row no-gutters">
              <div class="col-6 text-left">
                <button type="button" class="btn btn-default" data-dismiss="modal">{#button_cancel#}</button>
              </div>
              <div class="col-6 text-right">
                <button type="submit" class="btn btn-primary">{#button_save#}</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  {* if $smarty.const.ORGACTIVE}
    <button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#addOrderModal"><i class="sli sli-remove-add-add-1"></i> Дополнить заказ</button>
  {else}
    <button class="btn btn-primary btn-icon disabled" disabled><i class="sli sli-remove-add-add-1"></i> Дополнить заказ</button>
  {/if *}
{/if}
