{if $data.breadcrumbs|count > 1}
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            {foreach from=$data.breadcrumbs item=item}
                {if $item.href}
                    <li class="breadcrumb-item"><a href="{$item.href}">{$item.text}</a></li>
                {else}
                    <li class="breadcrumb-item active" aria-current="page">{$item.text}</li>
                {/if}
            {/foreach}
        </ol>
    </nav>
{/if}