<div class="row no-gutters">
  <div class="col-12 col-lg-6 ml-lg-auto">
    <div class="input-group">
      <span class="input-group-prepend"><i class="input-group-text sli sli-time-calendar-view-1"></i></span>
      <input id="reportrange" type="text" placeholder="" class="form-control pull-right">
      <form id="reportrangeform" class="d-none" action="" method="post">
        <input id="reportrange-start-date" type="hidden" name="reportrange-start-date" value="" data-start-date="{date('Y-m-d',$smarty.session.user_settings.report_range.start_date)}">
        <input id="reportrange-end-date" type="hidden" name="reportrange-end-date" value="" data-end-date="{date('Y-m-d',$smarty.session.user_settings.report_range.end_date)}">
      </form>
    </div>
  </div>
</div>