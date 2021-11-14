<!DOCTYPE html>
<html lang="{$current_language}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>

    <title>{$data.page_title} &middot; {$APP_NAME}</title>

    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0095ff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    {$styles_tpl}

</head>
<body data-page-id="{$data.page}" data-timezone="{$smarty.const.TIMEZONE}" data-theme="{$smarty.cookies.theme|default:'light'}">

<!-- CONTENT -->
{$content}
<!-- ./CONTENT -->

{foreach from=$injections item=injection}
    {$injection.html}
{/foreach}

</body>

{$scripts_tpl}

</html>