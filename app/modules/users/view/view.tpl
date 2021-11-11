<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="d-flex align-items-end rounded-top" style="min-height: 150px; background: bottom / cover transparent url({$ABS_PATH}assets/images/profile_bg.jpg) no-repeat scroll">
                <div class="d-flex w-100 justify-content-between align-items-center px-2 px-md-4 mt-5 mb-2">
                    <div>
                        <div class="d-inline-block position-relative">
                            <img class="wd-100 rounded-circle" src="{$user.user_avatar|default:"/uploads/avatars/default.jpg"}" alt="profile">
                        </div>
                        <span class="h4 ms-3 text-dark">{$user.firstname|default:""} {$user.lastname|default:""}</span>
                    </div>
                    {if $can_edit_user}
                        <div class="d-none d-md-block">
                            <a href="{$ABS_PATH}users/edit/{$user.user_id}" class="btn btn-primary btn-icon-text">
                                <i class="mdi mdi-pen btn-icon-prepend"></i> {#users_action_edit#}
                            </a>
                        </div>
                    {/if}
                </div>
            </div>
            <div class="d-flex justify-content-center px-3 rounded-bottom">
                <ul class="d-flex align-items-center m-0 p-0 border-0 nav nav-tabs nav-tabs-line">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center active"
                           id="profile-tab" data-bs-toggle="tab" href="#profile-panel" role="tab" aria-controls="profile-panel" aria-selected="true">
                            <i class="me-sm-2 icon-md mdi mdi-account-circle-outline"></i><span class="d-none d-sm-inline">{#users_tab_profile#}</span>
                        </a>
                    </li>
                    <li class="border-start nav-item">
                        <a class="nav-link d-flex align-items-center"
                           id="dealings-tab" data-bs-toggle="tab" href="#dealings-panel" role="tab" aria-controls="dealings-panel" aria-selected="false">
                            <i class="me-sm-2 icon-md mdi mdi-currency-usd-circle-outline"></i><span class="d-none d-sm-inline">{#users_tab_dealings#} <span class="badge bg-light text-dark">0</span></span>
                        </a>
                    </li>
                    <li class="border-start nav-item">
                        <a class="nav-link d-flex align-items-center"
                           id="offers-tab" data-bs-toggle="tab" href="#offers-panel" role="tab" aria-controls="offers-panel" aria-selected="false">
                            <i class="me-sm-2 icon-md mdi mdi-tag-multiple-outline"></i><span class="d-none d-sm-inline">{#users_tab_offers#} <span class="badge bg-light text-dark">0</span></span>
                        </a>
                    </li>
                    <li class="border-start nav-item">
                        <a class="nav-link d-flex align-items-center"
                           id="brands-tab" data-bs-toggle="tab" href="#brands-panel" role="tab" aria-controls="brands-panel" aria-selected="false">
                            <i class="me-sm-2 icon-md mdi mdi-image-multiple-outline"></i><span class="d-none d-sm-inline">{#users_tab_brands#} <span class="badge bg-light text-dark">0</span></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="tab-content" id="userTabContent">
            <div class="tab-pane fade show active" id="profile-panel" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                    <div class="col-xxxl-3 d-flex flex-column grid-margin">
                        <div class="row h-100">
                            <div class="col-md-6 col-xxxl-12 stretch-card">
                                <div class="card mb-4 mb-md-0 mb-xxxl-4">
                                    <div class="card-header"><h6>{#users_form_account_header#}</h6></div>
                                    <div class="card-body pb-2">
                                        <div class="mb-3">
                                            <label class="form-label" for="firstname">{#users_form_firstname#}</label>
                                            <input id="firstname" name="firstname" value="{$user.firstname}" type="text" class="form-control" placeholder="{#users_form_firstname#}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="lastname">{#users_form_lastname#}</label>
                                            <input id="lastname" name="lastname" value="{$user.lastname}" type="text" class="form-control" placeholder="{#users_form_lastname#}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="email">{#users_form_email#}</label>
                                            <input id="email" name="email" value="{$user.email}" type="email" class="form-control" placeholder="{#users_form_email#}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xxxl-12 stretch-card flex-grow-1">
                                <div class="card">
                                    <div class="card-header"><h6>{#users_form_social_header#}</h6></div>
                                    <div class="card-body pb-2">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text btn-instagram"><i class="fab fa-fw fa-instagram"></i></span>
                                            <input id="socials-instagram" name="data[socials][instagram]" value="{$user.data.socials.instagram}" type="url" class="form-control" placeholder="{#users_form_socials_instagram#}" readonly>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text btn-youtube"><i class="fab fa-fw fa-youtube"></i></span>
                                            <input id="socials-youtube" name="data[socials][youtube]" value="{$user.data.socials.youtube}" type="url" class="form-control" placeholder="{#users_form_socials_youtube#}" readonly>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text btn-twitter"><i class="fab fa-fw fa-twitter"></i></span>
                                            <input id="socials-twitter" name="data[socials][twitter]" value="{$user.data.socials.twitter}" type="url" class="form-control" placeholder="{#users_form_socials_twitter#}" readonly>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text btn-tiktok"><i class="fab fa-fw fa-tiktok"></i></span>
                                            <input id="socials-tiktok" name="data[socials][tiktok]" value="{$user.data.socials.tiktok}" type="url" class="form-control" placeholder="{#users_form_socials_tiktok#}" readonly>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text btn-facebook"><i class="fab fa-fw fa-facebook-f"></i></span>
                                            <input id="socials-facebook" name="data[socials][facebook]" value="{$user.data.socials.facebook}" type="url" class="form-control" placeholder="{#users_form_socials_facebook#}" readonly>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text btn-vk"><i class="fab fa-fw fa-vk"></i></span>
                                            <input id="socials-vk" name="data[socials][vk]" value="{$user.data.socials.vk}" type="url" class="form-control" placeholder="{#users_form_socials_vk#}" readonly>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text btn-github"><i class="fas fa-fw fa-globe"></i></span>
                                            <input id="socials-web" name="data[socials][web]" value="{$user.data.socials.web}" type="url" class="form-control" placeholder="{#users_form_socials_web#}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header"><h6>{#users_form_personal_header#}</h6></div>
                            <div class="card-body pb-2">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">{#users_form_phone#}</label>
                                            <input id="phone" name="phone" value="{$formatted_phone}" type="tel" class="form-control" placeholder="{#users_form_phone#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="birthdate">{#users_form_birthdate#}</label>
                                            <input id="birthdate" name="data[birthdate]" value="{$user.data.birthdate}" type="date" class="form-control" placeholder="{#users_form_birthdate#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h5 class="mb-2">{#users_form_contract_header#}</h5>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="zip">{#users_form_zip#}</label>
                                            <input id="zip" name="data[address][zip]" value="{$user.data.address.zip}" type="number" class="form-control" placeholder="{#users_form_zip#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="country">{#users_form_country#}</label>
                                            <input id="country" name="data[address][country]" value="{$user.data.address.country}" type="text" class="form-control" placeholder="{#users_form_country#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="region">{#users_form_region#}</label>
                                            <input id="region" name="data[address][region]" value="{$user.data.address.region}" type="text" class="form-control" placeholder="{#users_form_region#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="city">{#users_form_city#}</label>
                                            <input id="city" name="data[address][city]" value="{$user.data.address.city}" type="text" class="form-control" placeholder="{#users_form_city#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="address">{#users_form_address#}</label>
                                            <textarea id="address" name="data[address][address]" class="form-control" style="min-height: 5rem" placeholder="{#users_form_address#}" readonly>{$user.data.address.address}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h6 class="mb-2">{#users_form_passport_header#}</h6>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="passport_id">{#users_form_passport_id#}</label>
                                            <input id="passport_id" name="data[passport][id]" value="{$user.data.passport.id}" type="number" class="form-control" placeholder="{#users_form_passport_id#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="passport_date">{#users_form_passport_date#}</label>
                                            <input id="passport_date" name="data[passport][date]" value="{$user.data.passport.date}" type="date" class="form-control" placeholder="{#users_form_passport_date#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="passport_issuer">{#users_form_passport_issuer#}</label>
                                            <input id="passport_issuer" name="data[passport][issuer]" value="{$user.data.passport.issuer}" type="text" class="form-control" placeholder="{#users_form_passport_issuer#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h6 class="mb-2">{#users_form_bank_account_header#}</h6>
                                    </div>
                                    <div class="col-6 col-sm-3 col-lg-6 col-xl-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="inn">{#users_form_inn#}</label>
                                            <input id="inn" name="data[inn]" value="{$user.data.inn}" type="number" class="form-control" placeholder="{#users_form_inn#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 col-lg-6 col-xl-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="bank_account_bik">{#users_form_bank_account_bik#}</label>
                                            <input id="bank_account_bik" name="data[bank][bik]" value="{$user.data.bank.bik}" type="number" class="form-control" placeholder="{#users_form_bank_account_bik#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="bank_account_id">{#users_form_bank_account_id#}</label>
                                            <input id="bank_account_id" name="data[bank][account_id]" value="{$user.data.bank.account_id}" type="number" class="form-control" placeholder="{#users_form_bank_account_id#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="bank_name">{#users_form_bank_name#}</label>
                                            <input id="bank_name" name="data[bank][name]" value="{$user.data.bank.name}" type="text" class="form-control" placeholder="{#users_form_bank_name#}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="bank_corr_id">{#users_form_bank_corr_id#}</label>
                                            <input id="bank_corr_id" name="data[bank][corr_id]" value="{$user.data.bank.corr_id}" type="number" class="form-control" placeholder="{#users_form_bank_corr_id#}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxxl-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header"><h6>{#users_form_about_header#}</h6></div>
                            <div class="card-body">
                                <textarea id="about" name="about" value="{$user.data.about}" class="form-control h-100" style="min-height: 20rem" readonly></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="dealings-panel" role="tabpanel" aria-labelledby="dealings-tab">
                <div class="card">
                    <div class="card-header"><h6>{#users_tab_dealings#}</h6></div>
                    <div class="card-body">...</div>
                </div>
            </div>
            <div class="tab-pane fade" id="offers-panel" role="tabpanel" aria-labelledby="offers-tab">
                <div class="card">
                    <div class="card-header"><h6>{#users_tab_offers#}</h6></div>
                    <div class="card-body">...</div>
                </div>
            </div>
            <div class="tab-pane fade" id="brands-panel" role="tabpanel" aria-labelledby="brands-tab">
                <div class="card">
                    <div class="card-header"><h6>{#users_tab_brands#}</h6></div>
                    <div class="card-body">...</div>
                </div>
            </div>
        </div>
    </div>
</div>