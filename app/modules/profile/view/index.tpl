<input type="hidden" name="user_id" value="{$user.user_id}">
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="d-flex position-relative align-items-end rounded-top" style="min-height: 150px; background: bottom / cover transparent url({$ABS_PATH}assets/images/profile_bg.jpg) no-repeat scroll">
                <div class="bg-gradient-card position-absolute align-self-end w-100 h-100"></div>
                <div class="d-flex position-absolute w-100 justify-content-between align-items-center px-2 px-md-4 mt-5 mb-2">
                    <div>
                        <a id="changePhoto" href="#." title="{#button_change#}" class="d-inline-block position-relative">
                            <img class="wd-100 rounded-circle user-profile-pic" src="{$user.user_avatar|default:"/uploads/avatars/default.jpg"}" alt="profile">
                            <i class="mdi mdi-camera-iris mdi-24px position-absolute" style="bottom: -5px; right: 0"></i>
                        </a>
                        <input type="file" name="img[]" accept="image/*" class="d-none" id="cropperImageUpload">
                        <input id="newAvatar" type="hidden" name="new_avatar" class="d-none">
                        <span class="h4 ms-3">{$user.firstname|default:""} {$user.lastname|default:""}</span>
                    </div>
                </div>
            </div>
            <div class="m-0 pt-3">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-header"><h6>{#profile_form_account_header#}</h6></div>
            <div class="card-body pb-2">
                <div class="mb-3">
                    <label class="form-label" for="firstname">{#profile_form_firstname#}</label>
                    <input id="firstname" name="firstname" value="{$user.firstname}" type="text" class="form-control" placeholder="{#profile_form_firstname#}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="lastname">{#profile_form_lastname#}</label>
                    <input id="lastname" name="lastname" value="{$user.lastname}" type="text" class="form-control" placeholder="{#profile_form_lastname#}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">{#profile_form_email#}</label>
                    <input id="email" name="email" value="{$user.email}" type="email" class="form-control" placeholder="{#profile_form_email#}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="phone">{#profile_form_phone#}</label>
                    <div class="row">
                        <div class="col-4 col-sm-3">
                            <div class="input-group">
                                <select id="code" name="code" class="country-select2 w-100" data-width="100%" data-dropdown-auto-width="true">
                                    {if !$user.country_code}
                                        <option value="" data-country-name="" disabled selected>{#profile_form_country_code#}</option>
                                    {/if}
                                    {foreach from=$countries item=country}
                                        <option value="{$country[1]|strtoupper}" data-country-name="{$country[0]}"
                                                {if $country[1]|strtoupper == $user.country_code}selected{/if}>+{$country[2]}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <input id="phone" name="phone" value="{$user.phone}" type="tel" class="form-control" placeholder="{#profile_form_phone#}" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">{#profile_form_pass_change#}</label>
                    <div class="input-group">
                        <input id="password" name="password" type="text" class="form-control" placeholder="{#profile_form_pass#}">
                        <button class="btn btn-primary genPass" type="button">{#button_generate#}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-header"><h6>{#profile_form_about_header#}</h6></div>
            <div class="card-body">
                <textarea id="about" name="data[about]" class="form-control tinymce-editor h-100" style="min-height: 20rem">{$user.data.about|default:''}</textarea>
            </div>
        </div>
    </div>
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-header"><h6>{#profile_form_social_header#}</h6></div>
            <div class="card-body pb-2">
                <div class="input-group mb-3">
                    <span class="input-group-text btn-instagram"><i class="icon-fw icon-md lh-1 mdi mdi-instagram"></i></span>
                    <input id="socials-instagram" name="data[socials][instagram]" value="{$user.data.socials.instagram|default:''}" type="url" class="form-control" placeholder="{#profile_form_socials_instagram#}">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text btn-youtube"><i class="icon-fw icon-md lh-1 mdi mdi-youtube"></i></span>
                    <input id="socials-youtube" name="data[socials][youtube]" value="{$user.data.socials.youtube|default:''}" type="url" class="form-control" placeholder="{#profile_form_socials_youtube#}">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text btn-twitter"><i class="icon-fw icon-md lh-1 mdi mdi-twitter"></i></span>
                    <input id="socials-twitter" name="data[socials][twitter]" value="{$user.data.socials.twitter|default:''}" type="url" class="form-control" placeholder="{#profile_form_socials_twitter#}">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text btn-facebook"><i class="icon-fw icon-md lh-1 mdi mdi-facebook"></i></span>
                    <input id="socials-facebook" name="data[socials][facebook]" value="{$user.data.socials.facebook|default:''}" type="url" class="form-control" placeholder="{#profile_form_socials_facebook#}">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text btn-vk"><i class="icon-fw icon-md lh-1 mdi mdi-vk"></i></span>
                    <input id="socials-vk" name="data[socials][vk]" value="{$user.data.socials.vk|default:''}" type="url" class="form-control" placeholder="{#profile_form_socials_vk#}">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text btn-github"><i class="icon-fw icon-md lh-1 mdi mdi-web"></i></span>
                    <input id="socials-web" name="data[socials][web]" value="{$user.data.socials.web|default:''}" type="url" class="form-control" placeholder="{#profile_form_socials_web#}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="grid-margin text-end">
    <a href="{$smarty.server.HTTP_REFERER}" class="btn btn-secondary btn-icon-text mr-3"><i class="mdi mdi-arrow-left btn-icon-prepend"></i> {#button_return#}</a>
    <button type="submit" class="saveProfileBtn btn btn-primary btn-icon-text"><i class="mdi mdi-check btn-icon-prepend"></i> {#button_save#}</button>
</div>
{$cropper_tpl}