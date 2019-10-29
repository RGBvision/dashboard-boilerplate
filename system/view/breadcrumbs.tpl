<ol class="breadcrumb d-none d-lg-flex">
  {foreach from=$data.breadcrumbs item=item}
    {if $item.href}
      <li class="breadcrumb-item">
        <a href="{$item.href}" data-pjax-nav data-push="{$item.push}" data-container="#content" data-fragment="#content" id="nav-{$item.page}">{$item.text}</a>
      </li>
    {else}
      <li class="breadcrumb-item active">{$item.text}</li>
    {/if}
  {/foreach}
</ol>