<!DOCTYPE html>
<html lang="{Session::getvar('current_language')}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$data.page_title} &middot; {$smarty.const.APP_NAME} {$smarty.const.APP_VERSION}</title>
  <link rel="apple-touch-icon" sizes="180x180" href="{$ABS_PATH}assets/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="{$ABS_PATH}assets/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="194x194" href="{$ABS_PATH}assets/img/favicon-194x194.png">
  <link rel="icon" type="image/png" sizes="192x192" href="{$ABS_PATH}assets/img/android-chrome-192x192.png">
  <link rel="icon" type="image/png" sizes="16x16" href="{$ABS_PATH}assets/img/favicon-16x16.png">
  <link rel="manifest" href="{$ABS_PATH}site.webmanifest">
  <link rel="mask-icon" href="{$ABS_PATH}assets/img/safari-pinned-tab.svg" color="#222222">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
</head>
<body>

{$loading_tpl}

<!-- APPLICATION -->
<div id="app" class="app">

  <!-- TOP BAR -->
  <div class="top-bar light-top-bar">
    <div class="container-fluid">
      <div class="row">
        <div class="col-6 col-lg-8">
          <div class="admin-logo">
            <h1>
              <img alt="" src="{$ABS_PATH}assets/img/icon.png" class="logo-icon margin-r-10">
              <span>{$smarty.const.APP_NAME}</span>
            </h1>
          </div>
            {if $show_navigation}
              <div class="left-nav-toggle">
                <a id="left-collapse" href="#" class="nav-collapse"><i class="sli sli-navigation-navigation-drawer-1"></i><span class="sr-only">Menu</span></a>
              </div>
              <div class="left-nav-collapsed">
                <a id="left-folded" href="#" class="nav-collapsed"><i class="sli sli-navigation-navigation-drawer-1"></i><span class="sr-only">Menu</span></a>
              </div>
            {/if}
        </div>
        <div class="col">
          <ul class="list-inline top-right-nav">
            <li class="dropdown icon-dropdown d-none-m">
              <a class="dropdown-toggle " data-toggle="dropdown" href="#"><i class="sli sli-tasks-task-checklist"></i> <span class="badge badge-success">1</span></a>
              <ul class="dropdown-menu dropdown-menu-right top-dropdown lg-dropdown task-dropdown">
                <li>
                  <div class="dropdown-header">
                    <a class="pull-right text-muted" href="#">
                      <small>Смотреть все</small>
                    </a> Задачи
                  </div>
                  <div class="scrollDiv nano">
                    <div class="notification-list nano-content">
                      <a class="clearfix" href="javascript:%20void(0);">
													<span class="notification-icon">
														<i class="sli sli-status-check-circle-1 text-success"></i>
													</span>
                        <span class="notification-title">
													Тестовая задача
													<label class="label label-success pull-right">Выполнено</label>
													</span>
                        <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                        <span class="notification-time">15 минут назад</span>
                      </a>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
            <li class="dropdown icon-dropdown d-none-m">
              <a class="dropdown-toggle " data-toggle="dropdown" href="#"><i class="sli sli-email-email-2"></i> <span class="badge badge-warning">3</span></a>
              <ul class="dropdown-menu dropdown-menu-right top-dropdown lg-dropdown notification-dropdown">
                <li>
                  <div class="dropdown-header">
                    <a class="pull-right text-muted" href="#">
                      <small>Смотреть все</small>
                    </a> Сообщения
                  </div>
                  <div class="scrollDiv nano">
                    <div class="notification-list nano-content">
                      <a class="clearfix" href="javascript:%20void(0);">
													<span class="notification-icon">
														<img alt="" class="rounded-circle" src="{$ABS_PATH}uploads/avatars/default.jpg" width="50">
													</span>
                        <span class="notification-title">
													Demo Demo
													<label class="label label-warning pull-right">Поддержка</label>
													</span>
                        <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                        <span class="notification-time">15 минут назад</span>
                      </a>
                      <a class="clearfix" href="javascript:%20void(0);">
													<span class="notification-icon">
													<img alt="" class="rounded-circle" src="{$ABS_PATH}uploads/avatars/default.jpg" width="50">
													</span>
                        <span class="notification-title">
													Demo Demo
													<label class="label label-warning pull-right">Поддержка</label>
													</span>
                        <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                        <span class="notification-time">15 минут назад</span>
                      </a>
                      <a class="clearfix" href="javascript:%20void(0);">
													<span class="notification-icon">
													<img alt="" class="rounded-circle" src="{$ABS_PATH}uploads/avatars/default.jpg" width="50">
													</span>
                        <span class="notification-title">
													Demo Demo
													<label class="label label-warning pull-right">Поддержка</label>
													</span>
                        <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                        <span class="notification-time">15 минут назад</span>
                      </a>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
            <li class="dropdown icon-dropdown d-none-m">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="sli sli-time-alarm"></i> <span class="badge badge-danger">4</span></a>
              <ul class="dropdown-menu dropdown-menu-right top-dropdown lg-dropdown notification-dropdown">
                <li>
                  <div class="dropdown-header">
                    <a class="pull-right text-muted" href="#">
                      <small>Смотреть все</small>
                    </a> Уведомления
                  </div>
                  <div class="scrollDiv nano">
                    <div class="notification-list nano-content">
                      <a class="clearfix" href="javascript:%20void(0);">
													<span class="notification-icon">
														<i class="sli sli-cloud-cloud-upload text-primary"></i>
													</span>
                        <span class="notification-title">Загрузка завершена</span>
                        <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                        <span class="notification-time">15 минут назад</span>
                      </a>
                      <a class="clearfix" href="javascript:%20void(0);">
													<span class="notification-icon">
														<i class="sli sli-computers-computer-harddisk text-warning"></i>
													</span>
                        <span class="notification-title">Дисковое пространство заканчивается</span>
                        <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                        <span class="notification-time">15 минут назад</span>
                      </a>
                      <a class="clearfix" href="javascript:%20void(0);">
													<span class="notification-icon">
														<i class="sli sli-status-check-circle-1 text-success"></i>
													</span>
                        <span class="notification-title">Задача выполнена</span>
                        <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                        <span class="notification-time">15 минут назад</span>
                      </a>
                      <a class="clearfix" href="javascript:%20void(0);">
													<span class="notification-icon">
														<i class="sli sli-computers-computer-chip text-danger"></i>
													</span>
                        <span class="notification-title">Высокая нагрузка на CPU</span>
                        <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                        <span class="notification-time">15 минут назад</span>
                      </a>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
            <li class="dropdown user-dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <img alt="" class="rounded-circle" src="{if isset($smarty.session.user_avatar)}{$smarty.session.user_avatar}{else}{$ABS_PATH}uploads/avatars/default.jpg{/if}" width="30">
                <span class="sr-only">User</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right top-dropdown">
                <li class="d-md-none">
                  <a class="dropdown-item" href="javascript:%20void(0);"><i class="sli sli-tasks-task-checklist"></i> Задачи <label class="label label-success pull-right">1</label></a>
                </li>
                <li class="d-md-none">
                  <a class="dropdown-item" href="javascript:%20void(0);"><i class="sli sli-email-email-2"></i> Сообщения <label class="label label-warning pull-right">4</label></a>
                </li>
                <li class="d-md-none">
                  <a class="dropdown-item" href="javascript:%20void(0);"><i class="sli sli-time-alarm"></i> Уведомления <label class="label label-danger pull-right">6</label></a>
                </li>

                <li>
                  <a class="dropdown-item" href="/route/users/edit&user_id={$smarty.session.user_id}"><i class="sli sli-settings-cog"></i> Настройки</a>
                </li>
                <li class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="/login?action=logout"><i class="sli sli-login-logout-1"></i> {#logout_button#}</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- ./TOP BAR -->
    {if $show_navigation}
        {$left_menu_tpl}
    {/if}

  <main id="content" class="content main-content container {if !$show_navigation}no-navigation{/if}" role="main" data-pjax-container>
    <!-- CONTENT BODY -->
    <div class="content-body" data-page-id="{$data.page}">
        {if $show_navigation}
          <!-- HEADER -->
          <div class="page-header">
            <div class="row">
              <div class="col-lg-6 mb-2 mb-lg-0 text-center text-lg-left">
                  {$header_tpl}
                  {*$breadcrumb_tpl*}
              </div>
              <div class="col-lg-6 text-center text-lg-right">
                  {$right_header_tpl}
              </div>
            </div>
          </div>
          <!-- ./HEADER -->
        {/if}
      <!-- CONTENT -->
        {$content}
      <!-- ./CONTENT -->
        {if $_is_pjax}
          <script type="text/javascript">
              $(document).ready(function () {
                  setTimeout(function () {
                      {foreach from=$javascripts item=javascript}
                      $.getScript("{$javascript.file}");
                      {/foreach}
                  }, 500);
              });
          </script>
        {/if}

        {* $debugs *}
    </div>
    <!-- ./CONTENT BODY -->

      {$footer_tpl}

  </main>
    {if $show_navigation}
      <div id="left-collapse-overlay"></div>
      <div id="left-pan-toggler"></div>
    {/if}

</div>
<!-- ./APPLICATION -->

{$scripts_tpl}

{foreach from=$dependencies item=file}
{if $file.file|pathinfo:$smarty.const.PATHINFO_EXTENSION == 'js'}
  <script src="{$file.file}" {$file.params}></script>
{/if}
{if $file.file|pathinfo:$smarty.const.PATHINFO_EXTENSION == 'css'}
<link href="{$file.file}" rel="stylesheet" {$file.params}>
{/if}
{/foreach}

<!-- Custom Css-->
<link href="{$ABS_PATH}assets/css/style.css" rel="stylesheet">

</body>
</html>