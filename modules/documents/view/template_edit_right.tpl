<button type="button" data-toggle="modal" data-target="#templatesHelpModal" class="btn btn-info btn-icon">
  <span><i class="sli sli-interface-feedback-infomation-circle"></i></span> {#button_help#}
</button>
<div id="templatesHelpModal" class="settings modal fade" tabindex="-1" role="dialog" aria-labelledby="templatesHelpModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form class="settingsForm" method="post">
        <div class="modal-header">
          <h4 class="modal-title" id="templatesHelpModalLabel">{#button_help#}</h4>
        </div>
        <div class="modal-body text-left">
          <p>Теги <strong class="text-primary">нумерации</strong> для автозамены при формировании документа:</p>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <tr>
                <td>#N#</td>
                <td>порядковый номер документа в данной категории</td>
              </tr>
              <tr>
                <td>#NN#</td>
                <td>порядковый номер документа в общей базе документов</td>
              </tr>
              <tr>
                <td>#D# #M# #Y#</td>
                <td>день, месяц, год формирования документа</td>
              </tr>
            </table>
          </div>
          <p>Теги в <strong class="text-primary">шаблоне</strong> для автозамены при формировании документа:</p>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <tr>
                <td>#ДОК#</td>
                <td>выводит номер документа</td>
              </tr>
              <tr>
                <td>#ДАТА#</td>
                <td>выводит дату заказа</td>
              </tr>
              <tr>
                <td>#ВРЕМЯ#</td>
                <td>выводит время заказа</td>
              </tr>
              <tr>
                <td>#КЛИЕНТ_ИМЯ#</td>
                <td>выводит имя клиента</td>
              </tr>
              <tr>
                <td>#КЛИЕНТ_ОТЧЕСТВО#</td>
                <td>выводит отчество клиента</td>
              </tr>
              <tr>
                <td>#КЛИЕНТ_ФАМИЛИЯ#</td>
                <td>выводит фамилию клиента</td>
              </tr>
              <tr>
                <td>#АВТО_НОМЕР#</td>
                <td>выводит гос.номер автомобиля</td>
              </tr>
              <tr>
                <td>#АВТО_МАРКА#</td>
                <td>выводит марку автомобиля</td>
              </tr>
              <tr>
                <td>#ОТВЕТСТВЕННЫЙ#</td>
                <td>выводит ФИО исполнителя заказа</td>
              </tr>
              <tr>
                <td>#ЗАКАЗ_ТАБЛ#</td>
                <td>выводит таблицу с перечнем заказанных усуг</td>
              </tr>
              <tr>
                <td>#ЗАКАЗ_ИТОГ#</td>
                <td>выводит итоговую сумму заказа</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{#button_close#}</button>
        </div>
      </form>
    </div>
  </div>
</div>