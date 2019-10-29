<!DOCTYPE html>
<html lang="{Session::getvar('current_language')}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$smarty.const.APP_NAME} {$smarty.const.APP_VERSION}</title>
  <meta name="description" content="{$smarty.const.APP_NAME} {$smarty.const.APP_VERSION}">
  <link rel="apple-touch-icon" sizes="180x180" href="{$ABS_PATH}assets/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="{$ABS_PATH}assets/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="194x194" href="{$ABS_PATH}assets/img/favicon-194x194.png">
  <link rel="icon" type="image/png" sizes="192x192" href="{$ABS_PATH}assets/img/android-chrome-192x192.png">
  <link rel="icon" type="image/png" sizes="16x16" href="{$ABS_PATH}assets/img/favicon-16x16.png">
  <link rel="manifest" href="{$ABS_PATH}site.webmanifest">
  <link rel="mask-icon" href="{$ABS_PATH}assets/img/safari-pinned-tab.svg" color="#222222">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  <style type="text/css">
    html, body, .content, .container, .row {
      min-height: 100vh;
    }
  </style>
</head>
<body class="bg-light">
{$loading_tpl}
<main id="content" class="content" role="main" data-pjax-container>
  <div class="container">
    <div class="row align-items-center">
      <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto">
        <div class="misc-header text-center">
          <img src="{$ABS_PATH}assets/img/icon.png" alt="">&nbsp;<span class="h5 ml-2">{$smarty.const.APP_NAME}</span>
        </div>
        <div class="misc-box">
          {if isset($error)}
            <div class="alert alert-danger text-center" role="alert">{$error}</div>
          {/if}
          <form id="form-login" role="form" method="post" action="./login">
            <input type="hidden" name="action" value="loginform">
            <div class="form-group">
              <label for="user">{#login_username#}</label>
              <div class="group-icon">
                <input id="user" name="user_login" class="form-control" placeholder="{#login_username#}" required="">
                <span class="icon-user text-muted icon-input"></span>
              </div>
            </div>
            <div class="form-group">
              <label for="password">{#login_password#}</label>
              <div class="group-icon">
                <input id="password" type="password" name="user_password" class="form-control" placeholder="{#login_password#}" required="">
                <span class="icon-lock text-muted icon-input"></span>
              </div>
            </div>
            <div class="checkbox checkbox-primary margin-r-5">
              <input id="checkbox" type="checkbox" name="keep_in" value="1">
              <label for="checkbox"> {#login_remember#} </label>
            </div>
            <button type="submit" class="btn btn-block btn-primary">{#login_button#}</button>
          </form>
          <div class="text-center misc-footer">
            <p>{$smarty.const.APP_INFO}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Common Plugins -->
<link href="{$ABS_PATH}assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom Css-->
<link href="{$ABS_PATH}assets/css/style.css" rel="stylesheet">
<!-- jQuery -->
<script src="{$ABS_PATH}assets/jquery/jquery-3.4.1.min.js"></script>
<script src="{$ABS_PATH}assets/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">
  $(document).ready(function () {
    $('.page-loader').fadeOut(500);
  });
</script>
</body>
</html>