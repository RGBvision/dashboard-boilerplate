<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

{foreach from=$dependencies item=file}
    {if $file.file|pathinfo:$smarty.const.PATHINFO_EXTENSION == 'css'}
        <link href="{$file.file}?v={$APP_BUILD}" rel="stylesheet" {$file.params}>
    {/if}
{/foreach}

<link rel="stylesheet" href="{$ABS_PATH}assets/vendors/flag-icon-css/css/flag-icon.min.css?v={$APP_BUILD}">
<link rel="stylesheet" href="{$ABS_PATH}assets/vendors/mdi/css/materialdesignicons.min.css?v={$APP_BUILD}">

<link rel="stylesheet" id="themeCSS" href="{$ABS_PATH}assets/css/dashboard/{$smarty.cookies.theme|default:'light'}.min.css?v={$APP_BUILD}">
