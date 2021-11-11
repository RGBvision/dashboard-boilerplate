<input type="hidden" name="user_id" value="{$user.user_id}">
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="d-flex align-items-end rounded-top" style="min-height: 150px; background: bottom / cover transparent url({$ABS_PATH}assets/images/profile_bg.jpg) no-repeat scroll">
                <div class="d-flex w-100 justify-content-between align-items-center px-2 px-md-4 mt-5 mb-2">
                    <div>
                        <a id="changePhoto" href="#." title="{#button_change#}" class="d-inline-block position-relative">
                            <img class="wd-100 rounded-circle" src="{$user.user_avatar|default:"/uploads/avatars/default.jpg"}" alt="profile">
                            <i class="mdi mdi-camera-iris mdi-24px position-absolute" style="bottom: -5px; right: 0"></i>
                        </a>
                        <input type="file" name="img[]" accept="image/*" class="d-none" id="cropperImageUpload">
                        <input id="newAvatar" type="hidden" name="new_avatar" class="d-none">
                        <span class="h4 ms-3 text-dark">{$user.firstname|default:""} {$user.lastname|default:""}</span>
                    </div>
                    <div class="d-none d-md-block">
                        <a href="{$ABS_PATH}users/view/{$user.user_id}" class="btn btn-primary btn-icon-text">
                            <i class="mdi mdi-eye btn-icon-prepend"></i> {#users_action_view#}
                        </a>
                    </div>
                </div>
            </div>
            <div class="m-0 pt-3">
            </div>
        </div>
    </div>
</div>
{*
<div class="profile-page tx-13">
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="profile-header">
                <div class="cover">
                    <div class="gray-shade"></div>
                    <figure>
                        <img src="{$ABS_PATH}assets/images/profile_bg.jpg" class="img-fluid" alt="profile cover">
                    </figure>
                    <div class="cover-body d-flex justify-content-between align-items-center">
                        <div class="position-relative">
                            <a id="changePhoto" href="#." title="{#button_change#}" class="d-inline-block position-relative">
                                <img class="profile-pic" src="{$user.user_avatar|default:"/uploads/avatars/default.jpg"}" alt="profile">
                                <i class="mdi mdi-camera-iris mdi-24px position-absolute" style="bottom: -5px; right: 0"></i>
                            </a>
                            <input type="file" name="img[]" accept="image/*" class="d-none" id="cropperImageUpload">
                            <input id="newAvatar" type="hidden" name="new_avatar" class="d-none">
                            <span class="profile-name">
                                {$user.firstname|default:""} {$user.lastname|default:""}
                                {if $user.deleted}<i class="mdi mdi-cancel text-danger"></i>{elseif !$user.active}<i class="mdi mdi-alert text-warning"></i>{/if}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="header-links">
                    <ul class="links d-flex align-items-center mt-3 mt-md-0">
                        <li class="header-link-item d-flex align-items-center">
                            {if $user.active}
                                <a href="{$ABS_PATH}users/block?user_id={$user.user_id}">
                                    <i class="mr-1 mdi mdi-cancel"></i>
                                    <span class="pt-1px d-none d-md-inline">{#button_block#}</span>
                                </a>
                            {else}
                                {if !$user.deleted}
                                    <a href="{$ABS_PATH}users/unblock?user_id={$user.user_id}">
                                        <i class="mr-1 mdi mdi-lock-open-variant-outline"></i>
                                        <span class="pt-1px d-none d-md-inline">{#button_unblock#}</span>
                                    </a>
                                {else}
                                    <span class="text-muted cursor-pointer">
                                        <i class="mr-1 mdi mdi-lock-open-variant-outline"></i>
                                        <span class="pt-1px d-none d-md-inline">{#button_unblock#}</span>
                                    </span>
                                {/if}
                            {/if}
                        </li>
                        <li class="header-link-item ml-3 pl-3 border-left d-flex align-items-center">
                            {if !$user.deleted}
                                <a href="{$ABS_PATH}users/delete?user_id={$user.user_id}">
                                    <i class="mr-1 mdi mdi-trash-can-outline"></i>
                                    <span class="pt-1px d-none d-md-inline">{#button_delete#}</span>
                                </a>
                            {else}
                                <a href="{$ABS_PATH}users/restore?user_id={$user.user_id}">
                                    <i class="mr-1 mdi mdi-restore"></i>
                                    <span class="pt-1px d-none d-md-inline">{#button_restore#}</span>
                                </a>
                            {/if}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row profile-body">
        <div class="col-12 col-md-6 col-gt-4 col-xg-3">
            <div class="card rounded">
                <form id="saveUserForm" method="post" action="{$ABS_PATH}users/save">
                    <input type="hidden" name="user_id" value="{$user.user_id}">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label" for="firstname">{#users_form_firstname#}</label>
                            <input id="firstname" name="firstname" type="text" class="form-control" placeholder="{#users_form_firstname#}" value="{$user.firstname}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="lastname">{#users_form_lastname#}</label>
                            <input id="lastname" name="lastname" type="text" class="form-control" placeholder="{#users_form_lastname#}" value="{$user.lastname}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">{#users_form_phone#}</label>
                            <div class="row">
                                <div class="col-4 col-sm-3">
                                    <div class="input-group">
                                        <select id="code" name="code" class="country-select2 w-100" data-width="100%" data-dropdown-auto-width="true">
                                            {if !$user.country_code}
                                                <option value="" data-country-name="" disabled selected>{#users_form_country_code#}</option>
                                            {/if}
                                            {foreach from=$countries item=country}
                                                <option value="{$country[1]|strtoupper}" data-country-name="{$country[0]}"
                                                        {if $country[1]|strtoupper == $user.country_code}selected{/if}>+{$country[2]}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <input id="phone" name="phone" type="tel" class="form-control" placeholder="{#users_form_phone#}" value="{$user.phone}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">{#users_form_email#}</label>
                            <input id="email" name="email" type="email" class="form-control" placeholder="{#users_form_email#}" value="{$user.email}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">{#users_form_pass_change#}</label>
                            <div class="input-group">
                                <input id="password" name="password" type="text" class="form-control" placeholder="Пароль">
                                <span class="input-group-append"><button class="btn btn-primary genPass" type="button">{#button_generate#}</button></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="group">{#users_form_group#}</label>
                            <select id="group" name="group" class="select2" data-width="100%">
                                {foreach from=$groups item=group}
                                    <option value="{$group.user_group_id}" {if $user.user_group_id === $group.user_group_id}selected{/if}>{$group.name}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input name="send_email" type="checkbox" class="form-check-input" value="1">
                                    {#users_form_send_auth_email#}
                                    <i class="input-frame"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{$smarty.server.HTTP_REFERER}" class="btn btn-secondary btn-icon-text mr-3"><i class="mdi mdi-arrow-left btn-icon-prepend"></i> {#button_return#}</a>
                        <button type="submit" class="saveUserBtn btn btn-primary btn-icon-text"><i class="mdi mdi-check"></i> {#button_save#}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
*}
{$cropper_tpl}
