<!DOCTYPE html>
<html lang="{$current_language}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>

    <title>{$data.page_title} &middot; {$APP_NAME}</title>

    <meta name="description" content="{$data.page_description|default:''}">
    <meta name="keywords" content="{$data.page_keywords|default:''}"">
    <meta name="author" content="{$data.page_author|default:''}"">

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
<div class="main-wrapper">

    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="{$ABS_PATH}dashboard" class="sidebar-brand">
                <img src="{$ABS_PATH}assets/images/logo.svg" class="d-light"><img src="{$ABS_PATH}assets/images/logo_l.svg" class="d-dark"> <span>{$smarty.const.APP_NAME}</span>
            </a>
            <div class="sidebar-toggler not-active">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        {$sidebar_tpl}

    </nav>

    <div class="page-wrapper">

        <nav class="navbar">
            <a href="#" class="sidebar-toggler">
                <i class="mdi mdi-menu"></i>
            </a>
            <div class="navbar-content">
                <form class="search-form" autocomplete="off">
                    <div class="input-group">
                        <div class="input-group-text">
                            <i class="mdi mdi-magnify"></i>
                        </div>
                        <input type="search" autocomplete="off" class="form-control" id="navbarForm" placeholder="{#search_form#}">
                    </div>
                </form>
                <ul class="navbar-nav">

                    <li class="nav-item dropdown ms-3 me-0">
                        <button class="nav-link dropdown-toggle" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="flag-icon flag-icon-{$current_language} mt-n1" title="{$current_language}"></i> <span class="font-weight-medium mx-1 d-none d-md-inline-block">{$current_language|mb_strtoupper}</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="languageDropdown">
                            {foreach from=$accept_lang key=lang item=item}
                                <a href="{$ABS_PATH}profile/set/user_lang/{$lang}" class="dropdown-item py-2"><i class="flag-icon flag-icon-{$lang}" title="{$item}" id="{$lang}"></i><span class="ms-2">{$item}</span></a>
                            {/foreach}
                        </div>
                    </li>

                    {$user_tpl}

                </ul>
            </div>
        </nav>

        <div class="page-content">

            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    {$header_tpl}
                    {$breadcrumbs_tpl}
                </div>
                <div>
                    {$header_addons_tpl}
                </div>
            </div>

            <!-- CONTENT -->
            {$content}
            <!-- ./CONTENT -->

        </div>
    </div>

</div>

{foreach from=$injections item=injection}
    {$injection.html}
{/foreach}

</body>

{$scripts_tpl}

</html>