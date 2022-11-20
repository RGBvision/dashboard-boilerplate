<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="d-flex position-relative align-items-end rounded-top" style="min-height: 150px; background: bottom / cover transparent url({$ABS_PATH}assets/images/profile_bg.jpg) no-repeat scroll">
                <div class="bg-gradient-card position-absolute align-self-end w-100 h-100"></div>
                <div class="d-flex position-absolute w-100 justify-content-between align-items-center px-2 px-md-4 mt-5 mb-2">
                    <div>
                        <div class="d-inline-block position-relative">
                            <img class="wd-100 rounded-circle" src="{$user.user_avatar|default:"/uploads/avatars/default.jpg"}" alt="profile">
                        </div>
                        <span class="h4 ms-3">{$user.firstname|default:""} {$user.lastname|default:""}</span>
                    </div>
                    {if $user.editable}
                        <div class="d-none d-md-block">
                            <a href="{$ABS_PATH}users/edit/{$user.user_id}" class="btn btn-primary btn-icon-text">
                                <i class="mdi mdi-pen btn-icon-prepend"></i> {#users_action_edit#}
                            </a>
                        </div>
                    {/if}
                </div>
            </div>
            <div class="d-flex justify-content-center px-3 rounded-bottom">
                <ul class="d-flex align-items-start border-0 nav nav-tabs nav-tabs-line">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center active"
                           id="profile-tab" data-bs-toggle="tab" href="#profile-panel" role="tab" aria-controls="profile-panel" aria-selected="true">
                            <i class="me-sm-2 icon-md mdi mdi-account-circle-outline"></i><span class="d-none d-sm-inline">{#users_tab_profile#}</span>
                        </a>
                    </li>
                    <li class="border-start nav-item">
                        <a class="nav-link d-flex align-items-center"
                           id="timeline-tab" data-bs-toggle="tab" href="#timeline-panel" role="tab" aria-controls="timeline-panel" aria-selected="false">
                            <i class="me-sm-2 icon-md mdi mdi-timeline-outline"></i><span class="d-none d-sm-inline">{#users_tab_timeline#}</span>
                        </a>
                    </li>
                    <li class="border-start nav-item">
                        <a class="nav-link d-flex align-items-center"
                           id="logs-tab" data-bs-toggle="tab" href="#logs-panel" role="tab" aria-controls="logs-panel" aria-selected="false">
                            <i class="me-sm-2 icon-md mdi mdi-script-text-outline"></i><span class="d-none d-sm-inline">{#users_tab_logs#}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="tab-content" id="userTabContent">
            <div class="tab-pane fade show active" id="profile-panel" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                    <div class="col-lg-4 stretch-card grid-margin">
                        <div class="card">
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
                                <div class="mb-3">
                                    <label class="form-label" for="phone">{#users_form_phone#}</label>
                                    <input id="phone" name="phone" value="{$formatted_phone}" type="tel" class="form-control" placeholder="{#users_form_phone#}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header"><h6>{#users_form_about_header#}</h6></div>
                            <div class="card-body">
                                <textarea id="about" name="data[about]" class="form-control h-100" style="min-height: 20rem" placeholder="{#users_form_about_header#}" readonly>{$user.data.about|default:''}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header"><h6>{#users_form_social_header#}</h6></div>
                            <div class="card-body pb-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text btn-instagram"><i class="icon-fw icon-md lh-1 mdi mdi-instagram"></i></span>
                                    <input id="socials-instagram" name="data[socials][instagram]" value="{$user.data.socials.instagram|default:''}" type="url" class="form-control" placeholder="{#users_form_socials_instagram#}" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text btn-youtube"><i class="icon-fw icon-md lh-1 mdi mdi-youtube"></i></span>
                                    <input id="socials-youtube" name="data[socials][youtube]" value="{$user.data.socials.youtube|default:''}" type="url" class="form-control" placeholder="{#users_form_socials_youtube#}" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text btn-twitter"><i class="icon-fw icon-md lh-1 mdi mdi-twitter"></i></span>
                                    <input id="socials-twitter" name="data[socials][twitter]" value="{$user.data.socials.twitter|default:''}" type="url" class="form-control" placeholder="{#users_form_socials_twitter#}" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text btn-facebook"><i class="icon-fw icon-md lh-1 mdi mdi-facebook"></i></span>
                                    <input id="socials-facebook" name="data[socials][facebook]" value="{$user.data.socials.facebook|default:''}" type="url" class="form-control" placeholder="{#users_form_socials_facebook#}" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text btn-vk"><i class="icon-fw icon-md lh-1 mdi mdi-vk"></i></span>
                                    <input id="socials-vk" name="data[socials][vk]" value="{$user.data.socials.vk|default:''}" type="url" class="form-control" placeholder="{#users_form_socials_vk#}" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text btn-github"><i class="icon-fw icon-md lh-1 mdi mdi-web"></i></span>
                                    <input id="socials-web" name="data[socials][web]" value="{$user.data.socials.web|default:''}" type="url" class="form-control" placeholder="{#users_form_socials_web#}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="timeline-panel" role="tabpanel" aria-labelledby="timeline-tab">
                <div class="card">
                    <div class="card-header"><h6>{#users_tab_timeline#}</h6></div>
                    <div class="card-body">...</div>
                </div>
            </div>
            <div class="tab-pane fade" id="logs-panel" role="tabpanel" aria-labelledby="logs-tab">
                <div class="card">
                    <div class="card-header"><h6>{#users_tab_logs#}</h6></div>
                    <div class="card-body">...</div>
                </div>
            </div>
        </div>
    </div>
</div>