<div class="sidebar-body">
    <ul class="nav">

        {foreach from=$sidebar_headers key=key item=header}

            {if !empty($sidebar_menu_items.$key)}

                <li class="nav-item nav-category"><span>{#$header#}</span></li>

                {foreach from=$sidebar_menu_items.$key key=k item=sidebar_menu_item}

                    {if !isset($sidebar_menu_item.submenu)}

                        <li class="nav-item {if $data.page == $sidebar_menu_item.id}active{/if}">
                            <a id="{$sidebar_menu_item.id}" href="{$sidebar_menu_item.link}" class="nav-link">
                                <i class="link-icon {$sidebar_menu_item.icon}"></i>
                                <span class="link-title">{$sidebar_menu_item.name}</span>
                            </a>
                        </li>

                    {else}

                        <li class="nav-item">
                            <a id="{$sidebar_menu_item.id}" href="#{$sidebar_menu_item.id}_submenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="{$sidebar_menu_item.id}_submenu">
                                <i class="link-icon {$sidebar_menu_item.icon}"></i>
                                <span class="link-title">{$sidebar_menu_item.name}</span>
                                <i class="link-arrow mdi mdi-chevron-down"></i>
                            </a>
                            <div class="collapse" id="{$sidebar_menu_item.id}_submenu">
                                <ul class="nav sub-menu">

                                    {foreach from=$sidebar_menu_item.submenu item=submenu_item}

                                        <li class="nav-item {if $data.page == $submenu_item.id}active{/if}">
                                            <a id="{$submenu.id}" href="{$submenu_item.link}" class="nav-link">{$submenu_item.name}</a>
                                        </li>

                                    {/foreach}

                                </ul>
                            </div>
                        </li>

                    {/if}

                {/foreach}

            {/if}

        {/foreach}

    </ul>
</div>