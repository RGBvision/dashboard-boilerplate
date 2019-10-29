<div id="paymentModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="paymentForm" method="post" action="https://api.avstechs.com/order">
        <div class="modal-header">
          <h4 class="modal-title" id="paymentModalLabel">Пополнить баланс</h4>
        </div>
        <div class="modal-body">
          <input id="customerID" name="customerID" value="{$smarty.session.organization_id}" type="hidden">
          <input id="sum" name="sum" value="100.00" type="hidden">
          <div class="form-group">
            <label for="paymentInput">Сумма пополнения</label>
            <input type="number" class="form-control" id="paymentInput" aria-describedby="paymentHelp" placeholder="" value="100">
            <small id="paymentHelp" class="form-text text-muted">Сумма пополнения счета должна быть не менее 100 рублей.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{#button_cancel#}</button>
          <button type="submit" class="btn btn-primary">К оплате</button>
        </div>
      </form>
    </div>
  </div>
</div>