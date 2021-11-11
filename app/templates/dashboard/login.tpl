<!DOCTYPE html>
<html lang="{Session::getvar('current_language')}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{#login_title#} &middot; {$smarty.const.APP_NAME} {$smarty.const.APP_VERSION}</title>
    <meta name="description" content="{$smarty.const.APP_NAME} {$smarty.const.APP_VERSION}">
    <!-- core:css -->
    <link rel="stylesheet" href="{$ABS_PATH}assets/vendors/core/core.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- end plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{$ABS_PATH}assets/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="{$ABS_PATH}assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <!-- endinject -->
    <!-- Layout styles -->
    <link id="themeCSS" rel="stylesheet" href="{$ABS_PATH}assets/css/{$smarty.cookies.theme|default:'light'}/style.css">
    <!-- End layout styles -->
</head>
<body>
<div class="main-wrapper">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row w-100 mx-0 auth-page">
                    <div class="col-12 col-xl-8 col-gt-6 col-xg-5 mx-auto">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-4 d-none d-md-block pr-md-0 align-self-center">
                                    <img src="{$ABS_PATH}assets/images/logo_blue.svg" alt="" class="img-fluid m-3">
                                </div>
                                <div class="col-md-8 pl-md-0 align-self-center">
                                    <div class="auth-form-wrapper p-3">
                                        <h5 class="text-muted font-weight-normal mb-3">{#login_title#}</h5>
                                        {if isset($error)}
                                            <div class="alert alert-fill-danger text-center" role="alert">{$error}</div>
                                        {/if}
                                        {if isset($message)}
                                            <div class="alert alert-fill-success text-center" role="alert">{$message}</div>
                                        {/if}
                                        {if $passreset && $email}
                                            <form id="form-login" role="form" method="post" action="{$ABS_PATH}login">
                                                <input type="hidden" name="action" value="reset">
                                                <input type="hidden" name="email" value="{$email}">
                                                <input type="hidden" name="hash" value="{$hash}">
                                                <div class="form-group">
                                                    <label for="user_password">{#login_new_password#}</label>
                                                    <input id="user_password" type="password" name="password" class="form-control" placeholder="{#login_new_password#}">
                                                </div>
                                                <div class="row justify-content-end">
                                                    <div class="col-auto">
                                                        <button type="submit" class="btn btn-primary">{#login_change#}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        {else}
                                            <form id="form-login" role="form" method="post" action="{$ABS_PATH}login">
                                                <input type="hidden" name="action" value="loginform">
                                                <div class="form-group">
                                                    <label for="user_login">{#login_username#}</label>
                                                    <input id="user_login" type="email" name="user_login" class="form-control" placeholder="{#login_username#}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="user_password">{#login_password#}</label>
                                                    <input id="user_password" type="password" name="user_password" class="form-control" autocomplete="current-password" placeholder="{#login_password#}">
                                                </div>
                                                <div class="row justify-content-between align-items-center">
                                                    <div class="col-auto">
                                                        <div class="form-check form-check-flat form-check-primary">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" name="keep_in" value="1">
                                                                {#login_remember#}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button" class="btn btn-link" data-toggle="modal" href="#modalremind">{#login_forgot#}</button>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0 text-white">{#login_button#}</button>
                                                </div>
                                            </form>
                                            <div class="modal fade" id="modalremind">
                                                <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                                    <div class="modal-content">
                                                        <form id="form-login" role="form" method="post" action="{$ABS_PATH}login">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">{#login_reset#}</h6>
                                                                <button aria-label="{#button_close#}" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>{#login_reset_message#}</p>
                                                                <input type="hidden" name="action" value="reminderform">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-lg fa-fw mt-1 fa-envelope text-primary"></i></span>
                                                                    <input type="email" name="email" class="form-control" placeholder="{#login_username#}" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" data-dismiss="modal" type="button">{#button_close#}</button>
                                                                <button class="btn btn-primary" type="submit">{#button_send#}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- core:js -->
<script src="{$ABS_PATH}assets/vendors/core/core.js"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<!-- end plugin js for this page -->
<!-- inject:js -->
<script src="{$ABS_PATH}assets/vendors/feather-icons/feather.min.js"></script>
<script src="{$ABS_PATH}assets/vendors/jscookie/js.cookie.min.js"></script>
<script src="{$ABS_PATH}assets/js/template.js"></script>
<!-- endinject -->
<!-- custom js for this page -->
<!-- end custom js for this page -->
</body>
</html>