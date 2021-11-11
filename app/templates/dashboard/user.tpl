<li class="nav-item dropdown ms-3 me-0">
    <button class="nav-link dropdown-toggle" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="mdi mdi-bell-outline"></i>
    </button>
    <div class="dropdown-menu w-250" aria-labelledby="notificationDropdown">
        <div class="p-2 text-center">
            <p class="m-0 h6 fw-normal">Нет уведомлений</p>
        </div>
    </div>
</li>
<li class="nav-item dropdown ms-4 me-0">
    <button class="nav-link dropdown-toggle" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img class="wd-30 ht-30 rounded-circle" src="{if isset($smarty.session.user_avatar)}{$smarty.session.user_avatar}{else}{$ABS_PATH}uploads/avatars/default.jpg{/if}" alt="profile">
    </button>
    <div class="dropdown-menu" aria-labelledby="profileDropdown">
        <div class="d-flex flex-column align-items-center border-bottom px-5 py-2">
            <div class="mb-3">
                <img class="wd-80 ht-80 rounded-circle" src="{if isset($smarty.session.user_avatar)}{$smarty.session.user_avatar}{else}{$ABS_PATH}uploads/avatars/default.jpg{/if}" alt="">
            </div>
            <div class="text-center">
                <p class="tx-16 fw-bolder">{$smarty.session.user_firstname} {$smarty.session.user_lastname}</p>
                <p class="tx-12 text-muted">{$smarty.session.user_email}</p>
            </div>
        </div>
        <div class="border-bottom py-2">
            <a href="#." class="themeChange dropdown-item py-2 pe-2 w-100 d-flex justify-content-between">
                <div>
                    <i class="icon-md icon-fw lh-1 align-middle mdi mdi-brightness-6"></i>
                    <span class="ms-2">{#dark_theme#}</span>
                </div>
                <div class="form-check form-switch m-0">
                    <input type="checkbox" class="form-check-input" id="themeSwitch" {if $smarty.cookies.theme == 'dark'}checked{/if}>
                    <label class="custom-control-label" for="themeSwitch"></label>
                </div>
            </a>
        </div>
        <a href="{$ABS_PATH}profile" class="dropdown-item py-2">
            <i class="icon-md icon-fw lh-1 align-middle mdi mdi-account-circle-outline"></i>
            <span class="ms-2">{#profile#}</span>
        </a>
        <a href="{$ABS_PATH}logout" class="dropdown-item py-2">
            <i class="icon-md icon-fw lh-1 align-middle mdi mdi-logout"></i>
            <span class="ms-2">{#logout_button#}</span>
        </a>
    </div>
</li>