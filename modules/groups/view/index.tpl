<div id="main-holder" class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-heading card-default">
        <div class="actions pull-right">
          <a href="javascript: void(0);" class="full-screen"><i class="sli sli-resize-move-expand-1"></i></a>
        </div>
        {#groups_page_title#}
      </div>
      <div class="card-block">
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
            <tr>
              <th class="text-center">{#groups_table_name#}</th>
              <th class="text-center">{#groups_table_users#}</th>
              <th class="text-center"><i class="sli sli-content-edition-flash-2"></i></th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$groups item=group key=key}
              <tr>
                <td class="text-center">
                  {$group.name}
                </td>
                <td class="text-center">
                  {$group.users|default:"-"}
                </td>
                <td class="text-center">
                  <div class="btn-group d-none d-md-block mx-auto text-center">
                    {if ! $group.editable}
                      <a class="btn btn-sm btn-primary" title="{#button_edit#}" data-pjax-nav data-push="true" data-container="#content" data-fragment="#content" href="/route/groups/edit&user_group_id={$group.user_group_id}">
                        <i class="sli sli-content-edition-pen-4"></i>
                      </a>
                    {else}
                      <a class="btn btn-sm btn-default disabled" title="{#button_edit#}" href="javascript:;" disabled>
                        <i class="sli sli-content-edition-pen-4"></i>
                      </a>
                    {/if}
                    {if $group.deleted}
                      <a class="btn btn-sm btn-danger ConfirmDeleteGroup" title="{#button_delete#}" href="/route/groups/delete&user_group_id={$group.user_group_id}">
                        <i class="sli sli-content-edition-bin-2"></i>
                      </a>
                    {else}
                      <a class="btn btn-sm btn-default disabled" title="{#button_delete#}" href="javascript:;" disabled>
                        <i class="sli sli-content-edition-bin-2"></i>
                      </a>
                    {/if}
                  </div>
                  <div class="btn-group d-md-none mx-auto text-center">
                    <button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                      <i class="sli sli-navigation-navigation-drawer-1"></i>
                    </button>
                    {if ! $group.editable OR $group.deleted}
                      <ul class="dropdown-menu actions-dropdown-menu dropdown-menu-right" role="menu">
                        {if ! $group.editable}
                          <li class="dropdown-item">
                            <a title="{#button_edit#}" data-pjax-nav data-push="true" data-container="#content" data-fragment="#content" href="/route/groups/edit&user_group_id={$group.user_group_id}">
                              <i class="sli sli-content-edition-pen-4"></i>{#button_edit#}
                            </a>
                          </li>
                        {/if}
                        {if $group.deleted}
                          <li class="dropdown-item">
                            <a class="ConfirmDeleteGroup" title="{#button_delete#}" href="/route/groups/delete&user_group_id={$group.user_group_id}">
                              <i class="sli sli-content-edition-bin-2"></i>{#button_delete#}
                            </a>
                          </li>
                        {/if}
                      </ul>
                    {/if}
                  </div>
                </td>
              </tr>
              {foreachelse}

            {/foreach}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>