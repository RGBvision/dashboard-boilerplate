<div id="main-holder" class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        {#users_page_title#}
      </div>
      <div class="card-block">
        <table id="usersControlTable" class="table table-striped nowrap" data-order="[[0,&quot;asc&quot;]]">
          <thead>
          <tr>
            <th class="text-center">{#users_table_name#}</th>
            <th class="text-center">{#users_table_phone#}</th>
            <th class="text-center">{#users_table_email#}</th>
            <th class="text-center">{#users_table_group#}</th>
            <th class="text-center d-none d-md-table-cell">{#users_table_activity#}</th>
            <th class="text-center" data-orderable="false"><i class="sli sli-content-edition-flash-2"></i></th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$users item=user key=key}
            <tr>
              <td data-order="{$user.lastname}">
                {if $user.editable}
                  <a title="{#button_edit#}" href="./index.php?route=users/edit&user_id={$user.user_id}">
                    <img alt="profile" class="rounded-circle mr-3 d-none d-md-inline" src="{$user.avatar}" width="48">
                    {if $user.linked_employee}<span title="Аккаунт связан с сотрудником" data-toggle="tooltip" data-placement="top"><i class="sli sli-content-edition-link-2 text-danger mr-2"></i></span>{/if}
                    {$user.lastname} {$user.firstname}
                  </a>
                {else}
                  <img alt="profile" class="rounded-circle mr-3 d-none d-md-inline" src="{$user.avatar}" width="48">
                  {if $user.linked_employee}<span title="Аккаунт связан с сотрудником" data-toggle="tooltip" data-placement="top"><i class="sli sli-content-edition-link-2 text-danger mr-2"></i></span>{/if}
                  {$user.lastname} {$user.firstname}
                {/if}
              </td>
              <td class="text-center">
                <i class="sli sli-phone-phone-call-2 mr-2"></i>
                <a href="tel:{$user.phone|default:"-"}">{$user.phone|normalizePhone:true}</a>
              </td>
              <td class="text-center">
                {$user.email}
              </td>
              <td class="text-center">
                {$user.group_name|default:"-"}
              </td>
              <td class="text-center d-none d-md-table-cell">
                {$user.last_activity|date_format:TIME_FORMAT|prettyDate}
              </td>
              <td class="text-center">
                {if $user.editable}
                  <a class="btn btn-xs btn-primary" title="{#button_edit#}" data-toggle="tooltip" data-placement="top" href="./index.php?route=users/edit&user_id={$user.user_id}">
                    <i class="sli sli-content-edition-pen-4"></i>
                  </a>
                {else}
                  <a class="btn btn-xs btn-default disabled" title="{#button_edit#}" href="#." disabled>
                    <i class="sli sli-content-edition-pen-4"></i>
                  </a>
                {/if}
                {if $user.deletable}
                  <a class="btn btn-xs btn-danger ConfirmDeleteUser" title="{#button_delete#}" data-toggle="tooltip" data-placement="top" href="./index.php?route=users/delete&user_id={$user.user_id}">
                    <i class="sli sli-content-edition-bin-2"></i>
                  </a>
                {else}
                  <a class="btn btn-xs btn-default disabled" title="{#button_delete#}" href="#." disabled>
                    <i class="sli sli-content-edition-bin-2"></i>
                  </a>
                {/if}
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
      {if isset($smarty.session.permissions.users_edit) or isset($smarty.session.permissions.all_permissions)}
        <div class="card-footer text-right">
          {if $smarty.const.ORGACTIVE and $can_add_user}
            <a href="/index.php?route=users/add" class="btn btn-primary btn-icon">
              <span><i class="sli sli-remove-add-add-1"></i></span> Добавить
            </a>
          {else}
            <a href="#." class="btn btn-primary btn-icon disabled" disabled="">
              <span><i class="sli sli-remove-add-add-1"></i></span> Добавить
            </a>
          {/if}
        </div>
      {/if}
    </div>
  </div>
</div>