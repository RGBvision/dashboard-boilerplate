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
                </div>
            </div>
            <div class="m-0 pt-3">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xxxl-3 d-flex flex-column grid-margin">
        <div class="row h-100">
            <div class="col-md-6 col-xxxl-12 stretch-card">
                <div class="card mb-4 mb-md-0 mb-xxxl-4">
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
                            <label class="form-label" for="password">{#profile_form_pass_change#}</label>
                            <div class="input-group">
                                <input id="password" name="password" type="text" class="form-control" placeholder="{#profile_form_pass#}">
                                <button class="btn btn-primary genPass" type="button">{#button_generate#}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xxxl-12 stretch-card flex-grow-1">
                <div class="card">
                    <div class="card-header"><h6>{#profile_form_social_header#}</h6></div>
                    <div class="card-body pb-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text btn-instagram"><i class="fab fa-fw fa-instagram"></i></span>
                            <input id="socials-instagram" name="data[socials][instagram]" value="{$user.data.socials.instagram}" type="url" class="form-control" placeholder="{#profile_form_socials_instagram#}">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text btn-youtube"><i class="fab fa-fw fa-youtube"></i></span>
                            <input id="socials-youtube" name="data[socials][youtube]" value="{$user.data.socials.youtube}" type="url" class="form-control" placeholder="{#profile_form_socials_youtube#}">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text btn-twitter"><i class="fab fa-fw fa-twitter"></i></span>
                            <input id="socials-twitter" name="data[socials][twitter]" value="{$user.data.socials.twitter}" type="url" class="form-control" placeholder="{#profile_form_socials_twitter#}">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text btn-tiktok"><i class="fab fa-fw fa-tiktok"></i></span>
                            <input id="socials-tiktok" name="data[socials][tiktok]" value="{$user.data.socials.tiktok}" type="url" class="form-control" placeholder="{#profile_form_socials_tiktok#}">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text btn-facebook"><i class="fab fa-fw fa-facebook-f"></i></span>
                            <input id="socials-facebook" name="data[socials][facebook]" value="{$user.data.socials.facebook}" type="url" class="form-control" placeholder="{#profile_form_socials_facebook#}">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text btn-vk"><i class="fab fa-fw fa-vk"></i></span>
                            <input id="socials-vk" name="data[socials][vk]" value="{$user.data.socials.vk}" type="url" class="form-control" placeholder="{#profile_form_socials_vk#}">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text btn-github"><i class="fas fa-fw fa-globe"></i></span>
                            <input id="socials-web" name="data[socials][web]" value="{$user.data.socials.web}" type="url" class="form-control" placeholder="{#profile_form_socials_web#}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header"><h6>{#profile_form_personal_header#}</h6></div>
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-sm-6 col-lg-12 col-xl-6">
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
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="birthdate">{#profile_form_birthdate#}</label>
                            <input id="birthdate" name="data[birthdate]" value="{$user.data.birthdate}" type="date" class="form-control" placeholder="{#profile_form_birthdate#}">
                        </div>
                    </div>
                    <div class="col-12">
                        <h5 class="mb-2">{#profile_form_contract_header#}</h5>
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="zip">{#profile_form_zip#}</label>
                            <input id="zip" name="data[address][zip]" value="{$user.data.address.zip}" type="number" class="form-control" placeholder="{#profile_form_zip#}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="country">{#profile_form_country#}</label>
                            <input id="country" name="data[address][country]" value="{$user.data.address.country}" type="text" class="form-control" placeholder="{#profile_form_country#}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="region">{#profile_form_region#}</label>
                            <input id="region" name="data[address][region]" value="{$user.data.address.region}" type="text" class="form-control" placeholder="{#profile_form_region#}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="city">{#profile_form_city#}</label>
                            <input id="city" name="data[address][city]" value="{$user.data.address.city}" type="text" class="form-control" placeholder="{#profile_form_city#}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" for="address">{#profile_form_address#}</label>
                            <textarea id="address" name="data[address][address]" class="form-control" style="min-height: 5rem" placeholder="{#profile_form_address#}">{$user.data.address.address}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="mb-2">{#profile_form_passport_header#}</h6>
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="passport_id">{#profile_form_passport_id#}</label>
                            <input id="passport_id" name="data[passport][id]" value="{$user.data.passport.id}" type="number" class="form-control" placeholder="{#profile_form_passport_id#}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="passport_date">{#profile_form_passport_date#}</label>
                            <input id="passport_date" name="data[passport][date]" value="{$user.data.passport.date}" type="date" class="form-control" placeholder="{#profile_form_passport_date#}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" for="passport_issuer">{#profile_form_passport_issuer#}</label>
                            <input id="passport_issuer" name="data[passport][issuer]" value="{$user.data.passport.issuer}" type="text" class="form-control" placeholder="{#profile_form_passport_issuer#}">
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="mb-2">{#profile_form_bank_account_header#}</h6>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-6 col-xl-3">
                        <div class="mb-3">
                            <label class="form-label" for="inn">{#profile_form_inn#}</label>
                            <input id="inn" name="data[inn]" value="{$user.data.inn}" type="number" class="form-control" placeholder="{#profile_form_inn#}">
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-6 col-xl-3">
                        <div class="mb-3">
                            <label class="form-label" for="bank_account_bik">{#profile_form_bank_account_bik#}</label>
                            <input id="bank_account_bik" name="data[bank][bik]" value="{$user.data.bank.bik}" type="number" class="form-control" placeholder="{#profile_form_bank_account_bik#}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="bank_account_id">{#profile_form_bank_account_id#}</label>
                            <input id="bank_account_id" name="data[bank][account_id]" value="{$user.data.bank.account_id}" type="number" class="form-control" placeholder="{#profile_form_bank_account_id#}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="bank_name">{#profile_form_bank_name#}</label>
                            <input id="bank_name" name="data[bank][name]" value="{$user.data.bank.name}" type="text" class="form-control" placeholder="{#profile_form_bank_name#}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-12 col-xl-6">
                        <div class="mb-3">
                            <label class="form-label" for="bank_corr_id">{#profile_form_bank_corr_id#}</label>
                            <input id="bank_corr_id" name="data[bank][corr_id]" value="{$user.data.bank.corr_id}" type="number" class="form-control" placeholder="{#profile_form_bank_corr_id#}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xxxl-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-header"><h6>{#profile_form_about_header#}</h6></div>
            <div class="card-body">
                <textarea id="about" name="about" class="form-control tinymce-editor h-100" style="min-height: 20rem">{$user.data.about}</textarea>
            </div>
        </div>
    </div>
</div>
{$cropper_tpl}