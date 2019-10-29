{assign var=disable value=''}
{if !$user.editable}{assign var=disable value='disabled'}{/if}
{assign var=linked value=''}
{if isset($user.linked) and $user.linked > 0}{assign var=linked value='readonly'}{/if}
<div id="main-holder" class="row">
  <div class="col-12">
    {if $user.editable}
    <form id="UserForm" action="/index.php?route=users/save" method="post">
      {/if}
      {if $action == 'save'}
      <input type="hidden" name="user_id" value="{$user.user_id}">
      {/if}
      <input type="hidden" id="action" name="action" value="{$action}">
      <input type="hidden" id="linked" name="linked" value="{$user.linked|default:''}">
      <div class="row">
        <div class="col-12 col-md-4 col-lg-3">
          <div class="widget padding-0 white-bg">
            <div class="bg-primary" style="height: 55px"></div>
            <div class="thumb-over">
              <img id="userAvatar" src="{$user.user_avatar|default:"/uploads/avatars/default.jpg"}" alt="" width="180" class="rounded-circle">
              <input type="hidden" id="new_avatar" name="new_avatar" value="">
            </div>
            <div class="p-3 text-center">
              <button type="button" id="changeAvatar" class="btn btn-primary btn-icon {$disable}" {$disable}><i class="sli sli-photos-images-camera-sync-1"></i> Изменить</button>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-8 col-lg-9">
          <div class="card">
            <div class="card-heading card-primary">
              Профиль
            </div>
            <div class="card-block">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="firstname" class="form-label">Имя</label>
                    <input type="text" class="form-control" {$disable} {$linked} id="firstname" name="firstname" value="{$user.firstname|default:""}" required>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="lastname" class="form-label">Фамилия</label>
                    <input type="text" class="form-control" {$disable} {$linked} id="lastname" name="lastname" value="{$user.lastname|default:""}" required>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="tel" class="form-control" {$disable} {$linked} id="phone" name="phone" value="{$user.phone|default:""}" data-phone="{$user.phone|default:""}" required>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="group" class="form-label">Группа</label>
                    <select class="form-control" {$disable} id="group" name="group" class="form-control">
                      {html_options values=$groups.ids output=$groups.names selected=$user.user_group_id|default:0}
                    </select>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="password" class="form-label">Пароль{if $action == 'save'} (изменить){/if}</label>
                    <input type="password" class="form-control" {$disable} id="password" name="password" {if $action == 'save'}value="******"{/if}>
                    <small id="strength" class="form-text"></small>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="password_again" class="form-label">Пароль (еще раз)</label>
                    <input type="password" class="form-control" {$disable} id="password_again" name="password_again" {if $action == 'save'}value="******"{/if}>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-heading">
              Комментарий
            </div>
            <div class="card-block">
              <textarea class="summernote" name="description">{$user.description|default:""|htmlspecialchars_decode}</textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-12 text-right">
          {if $user.editable}
            <button type="submit" class="SaveUserBtn btn btn-primary btn-icon">
              <i class="sli sli-status-check-1"></i> {#button_save#}
            </button>
          {/if}
          <a href="/index.php?route=users" class="btn btn-danger btn-icon" data-pjax-nav data-push="true" data-container="#content" data-fragment="#content">
            <i class="sli sli-navigation-navigation-before-1"></i> {#button_cancel#}
          </a>
        </div>
      </div>
      {if $user.editable}
    </form>
  <input id="uploadAvatar" type="file" accept="image/*" capture="camera" class="d-none">
    {/if}
  </div>
</div>