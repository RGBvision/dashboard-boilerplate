<input type="hidden" name="user_id" value="{$user.user_id}">
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="d-flex position-relative align-items-end rounded-top" style="min-height: 150px; background: bottom / cover transparent url({$ABS_PATH}assets/images/profile_bg.jpg) no-repeat scroll">
                <div class="bg-gradient-card position-absolute align-self-end w-100 h-100"></div>
                <div class="d-flex position-absolute w-100 justify-content-between align-items-center px-2 px-md-4 mt-5 mb-2">
                    <div>
                        <a id="changePhoto" href="#." title="{#button_change#}" class="d-inline-block position-relative">
                            <img class="wd-100 rounded-circle" src="{$user.user_avatar|default:"/uploads/avatars/default.jpg"}" alt="profile">
                            <i class="mdi mdi-camera-iris mdi-24px position-absolute" style="bottom: -5px; right: 0"></i>
                        </a>
                        <input type="file" name="img[]" accept="image/*" class="d-none" id="cropperImageUpload">
                        <input id="newAvatar" type="hidden" name="new_avatar" class="d-none">
                        <span class="h4 ms-3">{$user.firstname|default:""} {$user.lastname|default:""}</span>
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
{$cropper_tpl}
