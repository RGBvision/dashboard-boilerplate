<div id="main-holder" class="row">
  <div class="col-12">
    {if ! $access}
      <div class="row text-left m-b-md">
        <div class="col-12">
          <div class="bs-callout bs-callout-danger">
            <h4 class="h4">{#no_permissions_title#}</h4>
            <p>
              {#no_permissions_descr_add#}
            </p>
          </div>
        </div>
      </div>
    {/if}

    {if $access}
      <form id="GroupForm" action="/route/groups/save" method="post">
        <input type="hidden" name="action" value="add">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-heading card-primary">
                <i class="sli sli-content-edition-pen-4"></i>
                {#groups_input_name#}
              </div>
              <div class="card-block">
                <div class="form-group m-b-n">
                  <input class="form-control input-sm" type="text" name="user_group_name" value="{$user_group_name|default:""}" id="group_name" autocomplete="off" required>
                </div>
              </div>
            </div>
          </div>
        </div>
        {foreach from=$permissions key=key item="inner"}
          {assign var=header_title value=$inner.name}
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-heading card-default">
                  <i class="{$inner.icon|default:"icon-list"} mr-3"></i> {$smarty.config.$header_title}
                </div>
                <div class="card-block">
                  {foreach from=$inner.perm key=permission item="value"}
                    {assign var=permission_title value="perm_$permission"}
                    <div class="form-inline mb-2">
                      <div class="switch switch-sm">
                        <input type="checkbox"
                               class="switch"
                               id="checkbox-{$permission}"
                               name="permissions[]"
                               value="{$permission}"><label for="checkbox-{$permission}"></label>
                        <span class="ml-2">{$smarty.config.$permission_title}</span>
                      </div>
                    </div>
                  {/foreach}
                </div>
              </div>
            </div>
          </div>
        {/foreach}
        <div class="row mb-4">
          <div class="col-12 text-right">
            {if $access}
              <button class="SaveGroupBtn btn btn-primary btn-icon">
                <i class="sli sli-status-check-1"></i> {#button_save#}
              </button>
            {/if}
            <a href="/route/groups" class="btn btn-danger btn-icon" data-pjax-nav data-push="true" data-container="#content" data-fragment="#content">
              <i class="sli sli-navigation-navigation-before-1"></i> {#button_cancel#}
            </a>
          </div>
        </div>
      </form>
    {/if}

  </div>
</div>