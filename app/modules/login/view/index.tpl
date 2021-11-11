<div class="main-wrapper">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row justify-content-center auth-page">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-4 col-xxl-3">
                        <div class="card">
                            <div class="card-header">
                                <h5>{#login_title#}</h5>
                            </div>
                            <form id="loginForm" role="form" method="post" action="{$ABS_PATH}login/auth">
                                <div class="card-body pb-2">
                                    {if $message}
                                        <div class="alert alert-success" role="alert"><i class="icon-md lh-1 align-middle mdi mdi-check-circle-outline"></i> {$message}</div>
                                    {/if}
                                    {if $error}
                                        <div class="alert alert-danger" role="alert"><i class="icon-md lh-1 align-middle mdi mdi-alert-circle-outline"></i> {$error}</div>
                                    {/if}
                                    <div class="mb-3">
                                        <label class="form-label" for="login_email">{#login_email#}</label>
                                        <input id="login_email" type="email" name="email" class="form-control" placeholder="{#login_email#}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="login_password">{#login_password#}</label>
                                        <input id="login_password" type="password" name="password" class="form-control" autocomplete="current-password" placeholder="{#login_password#}" required>
                                    </div>
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto mb-3">
                                            <div class="form-check form-check-flat form-check-primary">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="keep_in" value="1" checked>
                                                    {#login_remember#}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-auto mb-3">
                                            <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" href="#modalReset">{#login_forgot#}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">{#login_button#}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalReset" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="resetForm" role="form" method="post" action="{$ABS_PATH}login/reset_request">
                <div class="modal-header">
                    <h6 class="modal-title">{#login_reset#}</h6>
                    <button aria-label="{#button_close#}" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">{#login_reset_message#}</p>
                    <input type="email" name="email" class="form-control" placeholder="{#login_email#}" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{#button_close#}</button>
                    <button class="btn btn-primary" type="submit">{#button_send#}</button>
                </div>
            </form>
        </div>
    </div>
</div>