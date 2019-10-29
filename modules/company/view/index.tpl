<div id="main-holder" class="row">
  <div class="col-12">
    {if $permission}
    <form method="post" id="companyForm" action="/index.php?route=company/save">
      <input type="hidden" name="company_id" value="{$company_id}">
      {/if}
      <div class="card">
        <div class="card-heading card-default">
          <div class="actions pull-right">
            <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
          </div>
          Предприятие
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label>Наименование организации</label>
                <input type="text" class="form-control" name="name" value="{$company.name|default:''}">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label>Адрес</label>
                <input type="text" class="form-control" name="addr" value="{$company.addr|default:''}">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label>Количество боксов мойки</label>
                <input type="text" class="form-control rangeslider" data-min="0" data-max="10" name="wboxes" value="{$company.settings.wboxes|default:'0'}">
              </div>
              <div class="form-group">
                <label>Количество боксов сервиса</label>
                <input type="number" class="form-control rangeslider" data-min="0" data-max="10" name="sboxes" value="{$company.settings.sboxes|default:'0'}">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label>Количество касс</label>
                <input type="number" class="form-control rangeslider" data-min="0" data-max="5" name="cboxes" value="{$company.settings.cboxes|default:'0'}">
              </div>
              <div class="form-group">
                <label>Количество классов а/м в прайс-листе</label>
                <input type="number" class="form-control rangeslider" data-min="1" data-max="7" name="classes" value="{$company.settings.classes|default:'1'}">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-heading card-default">
          <div class="actions pull-right">
            <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
          </div>
          Контроль
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-lg-6">
              <h5>Мойка</h5>
              <div class="form-group">
                <label>Максимальное время на приемку
                  <span title="Уведомлять о подозрительной операции, если время от регистрации а/м в боксе до приема заказа больше указанного" class="ml-2" data-toggle="tooltip" data-placement="top">
                    <i class="sli sli-interface-feedback-infomation-circle small text-info"></i>
                  </span>
                </label>
                <input type="number" class="form-control rangeslidertime" data-min="0" data-max="300" name="wreceivetime" value="{$company.settings.wreceivetime|default:'0'}">
              </div>
              <div class="form-group">
                <label>Максимальное время на сдачу
                  <span title="Уведомлять о подозрительной операции, если время от завершения заказа до регистрации выезда а/м из бокса больше указанного" class="ml-2" data-toggle="tooltip" data-placement="top">
                    <i class="sli sli-interface-feedback-infomation-circle small text-info"></i>
                  </span>
                </label>
                <input type="number" class="form-control rangeslidertime" data-min="0" data-max="600" name="wreturntime" value="{$company.settings.wreturntime|default:'0'}">
              </div>
              <div class="form-group">
                <label>Досрочное выполнение
                  <span title="Считать заказ выполненным досрочно, если время выполнения меньше, чем по регламенту, на указанное и менее" class="ml-2" data-toggle="tooltip" data-placement="top">
                    <i class="sli sli-interface-feedback-infomation-circle small text-info"></i>
                  </span>
                </label>
                <input type="number" class="form-control rangeslidertime" data-min="0" data-max="600" name="wearlytime" value="{$company.settings.wearlytime|default:'0'}">
              </div>
              <div class="form-group">
                <label>Просрочка регламента
                  <span title="Считать заказ выполненным с просрочкой, если время выполнения больше, чем по регламенту, на указанное и более" class="ml-2" data-toggle="tooltip" data-placement="top">
                    <i class="sli sli-interface-feedback-infomation-circle small text-info"></i>
                  </span>
                </label>
                <input type="number" class="form-control rangeslidertime" data-min="0" data-max="600" name="wdelaytime" value="{$company.settings.wdelaytime|default:'0'}">
              </div>
            </div>
            <div class="col-lg-6">
              <h5>Сервис</h5>
              <div class="form-group">
                <label>Максимальное время на приемку
                  <span title="Уведомлять о подозрительной операции, если время от регистрации а/м в боксе до приема заказа больше указанного" class="ml-2" data-toggle="tooltip" data-placement="top">
                    <i class="sli sli-interface-feedback-infomation-circle small text-info"></i>
                  </span>
                </label>
                <input type="number" class="form-control rangeslidertime" data-min="0" data-max="300" name="creceivetime" value="{$company.settings.creceivetime|default:'0'}">
              </div>
              <div class="form-group">
                <label>Максимальное время на сдачу
                  <span title="Уведомлять о подозрительной операции, если время от завершения заказа до регистрации выезда а/м из бокса больше указанного" class="ml-2" data-toggle="tooltip" data-placement="top">
                    <i class="sli sli-interface-feedback-infomation-circle small text-info"></i>
                  </span>
                </label>
                <input type="number" class="form-control rangeslidertime" data-min="0" data-max="600" name="creturntime" value="{$company.settings.creturntime|default:'0'}">
              </div>
              <div class="form-group">
                <label>Досрочное выполнение
                  <span title="Считать заказ выполненным досрочно, если время выполнения меньше, чем по регламенту, на указанное и менее" class="ml-2" data-toggle="tooltip" data-placement="top">
                    <i class="sli sli-interface-feedback-infomation-circle small text-info"></i>
                  </span>
                </label>
                <input type="number" class="form-control rangeslidertime" data-min="0" data-max="600" name="cearlytime" value="{$company.settings.cearlytime|default:'0'}">
              </div>
              <div class="form-group">
                <label>Просрочка регламента
                  <span title="Считать заказ выполненным с просрочкой, если время выполнения больше, чем по регламенту, на указанное и более" class="ml-2" data-toggle="tooltip" data-placement="top">
                    <i class="sli sli-interface-feedback-infomation-circle small text-info"></i>
                  </span>
                </label>
                <input type="number" class="form-control rangeslidertime" data-min="0" data-max="600" name="cdelaytime" value="{$company.settings.cdelaytime|default:'0'}">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-12 text-right">
          {if $permission}
            <button type="submit" class="btn btn-primary btn-icon">
              <i class="sli sli-status-check-1"></i> {#button_save#}
            </button>
            {else}
            <button type="button" class="btn btn-primary btn-icon disabled" disabled>
              <i class="sli sli-status-check-1"></i> {#button_save#}
            </button>
          {/if}
        </div>
      </div>
      {if $permission}
    </form>
    {/if}
  </div>
</div>