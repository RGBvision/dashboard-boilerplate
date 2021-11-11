<div class="main-wrapper">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row justify-content-center auth-page">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-4 col-xxl-3">
                        <div class="card">
                            <div class="card-header">
                                <h5>{#login_reset#}</h5>
                            </div>
                            <form id="newPassForm" role="form" method="post" action="{$ABS_PATH}login/change_password">
                                <input type="hidden" name="email" value="{$email}">
                                <input type="hidden" name="hash" value="{$hash}">
                                <div class="card-body pb-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="user_password">{#login_new_password#}</label>
                                        <input id="user_password" type="password" name="password" class="form-control" placeholder="{#login_new_password#}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="user_password_confirm">{#login_new_password_confirm#}</label>
                                        <input id="user_password_confirm" type="password" name="password_confirm" class="form-control" placeholder="{#login_new_password_confirm#}">
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">{#login_change#}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>