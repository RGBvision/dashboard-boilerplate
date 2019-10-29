{if isset($smarty.session.permissions.orders_add) or isset($smarty.session.permissions.orders_payment)}
  {if $active_orders}
    <div id="activeOrdersContent">
      <div class="row">
        {foreach from=$active_orders item=item}
          <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
            <div class="card">
              <div class="card-heading {$item.status_class} text-center">
                {$item.car_numplate}
              </div>
              <div class="card-block text-center">
                <img src="{$item.photo}" class="img-fluid mb-2">
                <p class="mb-2">{$item.status}</p>
                {if isset($smarty.session.permissions.orders_add)}
                  {if $item.canclose}
                    <a href="/route/orders/close?order_id={$item.order_id}" class="btn btn-primary btn-icon"> <span><i class="sli sli-status-check-circle-1"></i></span>Завершить</a>
                  {else}
                    <a href="#." class="btn btn-success btn-icon disabled"> <span><i class="sli sli-status-check-1"></i></span>Завершен</a>
                  {/if}
                {/if}
                {if isset($smarty.session.permissions.orders_payment)}
                  {if $item.canpay}
                    <a href="/route/orders/beforepay?order_id={$item.order_id}" class="btn btn-primary btn-icon"> <span><i class="sli sli-money-coin-receive"></i></span>Оплата</a>
                  {else}
                    <a href="#." class="btn btn-success btn-icon disabled"> <span><i class="sli sli-status-check-1"></i></span>Оплачен</a>
                  {/if}
                {/if}
              </div>
            </div>
          </div>
        {/foreach}
      </div>
    </div>
  {/if}
{/if}