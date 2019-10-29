<div class="card-block border-bottom text-center nav-profile px-0 py-3 d-none d-lg-block">
  {*<a class="mx-2 disabled" href="/route/users/edit&user_id={$smarty.session.user_id}"><i class="sli sli-settings-cog"></i></a>*}
  <img alt="profile" class="rounded-circle margin-b-10 circle-border" src="{if isset($smarty.session.user_avatar)}{$smarty.session.user_avatar}{else}{$ABS_PATH}uploads/avatars/default.jpg{/if}" width="80">
  {*<a class="mx-2" href="/logout"><i class="sli sli-login-logout-1"></i></a>*}
  <p class="lead margin-b-0 toggle-none">{$smarty.session.user_firstname} {$smarty.session.user_lastname}</p>
  <p class="text-muted mv-0 toggle-none">{$smarty.session.organization_name}</p>
</div>