<div class="main-sidebar-nav default-navigation">
  <div class="nano">
    <div class="nano-content sidebar-nav">
        {$user_tpl}
      <ul class="metisMenu nav flex-column" id="menu">
          {foreach from=$left_headers key=key item=header}
              {if !empty($left_menu_items.$key)}
                <li class="nav-heading"><span>{#$header#}</span></li>
                  {foreach from=$left_menu_items.$key key=k item=left_item}
                      {if ! isset($left_item.submenu)}
                        <li class="nav-item">
                          <a class="nav-link" href="./{$left_item.link}"
                             id="nav-{$left_item.id}"><i class="{$left_item.icon}"></i> <span class="toggle-none">{$left_item.name}</span>
                          </a>
                        </li>
                      {else}
                        <li class="nav-item">
                          <a class="nav-link" href="#" aria-expanded="false">
                            <i class="{$left_item.icon}"></i> <span class="toggle-none">{$left_item.name} <span class="sli sli-arrows-arrow-right-12 arrow"></span></span>
                          </a>
                          <ul class="nav-second-level nav sub-menu" aria-expanded="false">
                              {foreach from=$left_item.submenu item=submenu}
                                <li class="nav-item">
                                  <a class="nav-link" href="./{$submenu.link}"
                                     id="nav-{$submenu.id}"><i class="{$submenu.icon}"></i> {$submenu.name}</a>
                                </li>
                              {/foreach}
                          </ul>
                        </li>
                      {/if}
                  {/foreach}
              {/if}
          {/foreach}
        <li class="nav-heading"><span>Пользователь</span></li>
        <li class="nav-item">
          <a class="nav-link" href="/login?action=logout"><i class="sli sli-login-logout-1"></i> <span class="toggle-none">{#logout_button#}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>