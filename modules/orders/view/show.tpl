{if ! $_is_ajax}
  <div id="main-holder" class="row">
    <div class="col-lg-12">
      <div class="card" id="orderInfoDetailsCard">
        <div class="card-heading card-default">
          <div class="actions pull-right">
            <a href="javascript: void(0);" class="minimize"><i class="sli sli-arrows-arrow-down-12"></i></a>
          </div>
          Информация о заказе
        </div>
        <div class="card-block">
          <div class="table-responsive">
            <table class="table table-striped nowrap">
              <tr>
                <td>Принят</td>
                <td>{$order_data.order.opened}</td>
              </tr>
              <tr>
                <td>Завершен</td>
                <td>{$order_data.order.closed}</td>
              </tr>
              <tr>
                <td>Оплачен</td>
                <td>{$order_data.order.payed}</td>
              </tr>
              <tr>
                <td>Регламент</td>
                <td>{$order_data.order.time_limit} {$order_data.order.delayed}</td>
              </tr>
              <tr>
                <td>Приемщик</td>
                <td>{$order_data.order.ulastname} {$order_data.order.ufirstname}</td>
              </tr>
              <tr>
                <td>Исполнитель</td>
                <td>{$order_data.order.elastname} {$order_data.order.efirstname}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="card" id="orderInfoServicesCard">
        <div class="card-heading card-default">
          <div class="actions pull-right">
            <a href="javascript: void(0);" class="minimize"><i class="sli sli-arrows-arrow-down-12"></i></a>
          </div>
          Перечень услуг
        </div>
        <div class="card-block">
          <div class="table-responsive">
            <table id="orderSevicesTable" class="table table-striped nowrap">
              {$order_details}
            </table>
          </div>
          <p class="h6 text-right">ИТОГО: {$order_data.order.sum}</p>
        </div>
      </div>
      <div class="card" id="orderInfoCustomerCard">
        <div class="card-heading card-default">
          <div class="actions pull-right">
            <a href="javascript: void(0);" class="minimize"><i class="sli sli-arrows-arrow-down-12"></i></a>
          </div>
          Информация о клиенте
        </div>
        <div class="card-block">
          <div class="table-responsive">
            <table class="table table-striped nowrap">
              <tr>
                <td>Фамилия</td>
                <td>{$order_data.customer.lastname}</td>
              </tr>
              <tr>
                <td>Имя</td>
                <td>{$order_data.customer.firstname}</td>
              </tr>
              <tr>
                <td>Отчество</td>
                <td>{$order_data.customer.secondname}</td>
              </tr>
              <tr>
                <td>Телефон</td>
                <td>{$order_data.customer.phone}</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>{$order_data.customer.email}</td>
              </tr>
              <tr>
                <td>Марка а/м</td>
                <td>{$order_data.customer.car_model}</td>
              </tr>
              <tr>
                <td>Гос.номер а/м</td>
                <td>{$order_data.customer.car_numplate}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
{else}
  <div class="accordion" id="accordionExample">
    <div class="card mb-0">
      <div class="card-header p-0" id="headingOne">
        <h2 class="mb-0">
          <button class="btn btn-link btn-block text-primary" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Информация о заказе
          </button>
        </h2>
      </div>
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped nowrap">
              <tr>
                <td>Принят</td>
                <td>{$order_data.order.opened}</td>
              </tr>
              <tr>
                <td>Завершен</td>
                <td>{$order_data.order.closed}</td>
              </tr>
              <tr class="{$order_data.order.payed_class}">
                <td>Оплачен</td>
                <td>{$order_data.order.payed}</td>
              </tr>
              <tr class="{$order_data.order.delayed_class}">
                <td>Регламент</td>
                <td>{$order_data.order.time_limit} {$order_data.order.delayed}</td>
              </tr>
              <tr>
                <td>Приемщик</td>
                <td>{$order_data.order.ulastname} {$order_data.order.ufirstname}</td>
              </tr>
              <tr>
                <td>Исполнитель</td>
                <td>{$order_data.order.elastname} {$order_data.order.efirstname}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-0">
      <div class="card-header p-0" id="headingTwo">
        <h2 class="mb-0">
          <button class="btn btn-link btn-block text-primary collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Перечень услуг
          </button>
        </h2>
      </div>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
        <div class="card-body">
          <div class="table-responsive">
            <table id="orderSevicesTable" class="table table-striped nowrap">
              {$order_details}
            </table>
          </div>
          <p class="h6 text-right">ИТОГО: {$order_data.order.sum}</p>
        </div>
      </div>
    </div>
    <div class="card mb-0">
      <div class="card-header p-0" id="headingThree">
        <h2 class="mb-0">
          <button class="btn btn-link btn-block text-primary collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Информация о клиенте
          </button>
        </h2>
      </div>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped nowrap">
              <tr>
                <td>Фамилия</td>
                <td>{$order_data.customer.lastname}</td>
              </tr>
              <tr>
                <td>Имя</td>
                <td>{$order_data.customer.firstname}</td>
              </tr>
              <tr>
                <td>Отчество</td>
                <td>{$order_data.customer.secondname}</td>
              </tr>
              <tr>
                <td>Телефон</td>
                <td>{$order_data.customer.phone}</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>{$order_data.customer.email}</td>
              </tr>
              <tr>
                <td>Марка а/м</td>
                <td>{$order_data.customer.car_model}</td>
              </tr>
              <tr>
                <td>Гос.номер а/м</td>
                <td>{$order_data.customer.car_numplate}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
{/if}