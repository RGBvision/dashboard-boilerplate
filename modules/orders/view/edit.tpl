<div id="main-holder" class="row">
  <div class="col-lg-12">
    <form method="post" action="/route/orders/pay">
      <div class="card" id="orderCard">
        <div class="card-heading card-default">
          Перечень услуг
        </div>
        <div class="card-block">
          <div class="table-responsive">
            <table id="orderSevicesTable" class="table table-striped nowrap">
              {$order_details}
            </table>
          </div>
          <p class="h6 text-right">ИТОГО: {$order_data.order.sum}</p>
          <hr>
          <label>Способ оплаты</label>
          <div class="radio radio-primary">
            <input type="radio" name="paytype" id="paytype1" value="1" checked="">
            <label for="paytype1"> Наличные</label>
          </div>
          <div class="radio radio-primary">
            <input type="radio" name="paytype" id="paytype2" value="2">
            <label for="paytype2"> Карта</label>
          </div>
          {* <div class="radio radio-primary">
            <input type="radio" name="paytype" id="paytype3" value="3">
            <label for="paytype3"> Контрагент</label>
          </div> *}
        </div>
        <div class="card-footer">
          <input type="hidden" name="order_id" value="{$order_data.order.order_id}">
          <div class="row">
            <div class="col-6">
              {if $order_data.documents}
                {assign var=documents_count value=$order_data.documents|@count}
                {if $documents_count == 1}
                  {foreach from=$order_data.documents item=item}
                    <button type="button" class="btn btn-info btn-icon" onclick="$('#orderPrintContent{$item.doc_id}').print(); return false;"><span><i class="sli sli-content-edition-print-text"></i></span>{$item.name}</button>
                  {/foreach}
                {else}
                  <div class="btn-group">
                    <button type="button" class="btn btn-info btn-icon dropdown-toggle" id="dropdownPrintButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span><i class="sli sli-content-edition-print-text"></i></span>Печать <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownPrintButton">
                      {foreach from=$order_data.documents item=item}
                        <li class="dropdown-item px-0"><a href="#." class="d-block" onclick="$('#orderPrintContent{$item.doc_id}').print(); return false;">{$item.name}</a></li>
                      {/foreach}
                    </ul>
                  </div>
                {/if}
              {/if}
            </div>
            <div class="col-6 text-right">
              <button type="submit" class="btn btn-primary btn-icon"><span><i class="sli sli-status-check-circle-1"></i></span>Оплачен</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="d-none">
    {foreach from=$order_data.documents item=item}
      <div id="orderPrintContent{$item.doc_id}">
        {$item.content|htmlspecialchars_decode}
      </div>
    {/foreach}
  </div>
</div>