<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>

    <title>{#install#} &middot; RGB.admin</title>

    <meta name="description" content="{$data.page_description|default:''}">
    <meta name="keywords" content="{$data.page_keywords|default:''}"
    ">
    <meta name="author" content="{$data.page_author|default:''}"
    ">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0095ff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{$ABS_PATH}assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" id="themeCSS" href="{$ABS_PATH}assets/css/default/{$smarty.cookies.theme|default:'light'}.min.css">

</head>
<body>
<main class="main-wrapper">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card shadow">
                            <div class="card-body px-2 px-sm-4">
                                <h4 class="card-title">{#install#}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    var ABS_PATH = '{$ABS_PATH}';
</script>

<script src="{$ABS_PATH}assets/i18n/{i18n::$active_language}.js"></script>

<script src="{$ABS_PATH}assets/vendors/core/core.js"></script>
<script src="{$ABS_PATH}assets/js/common.js"></script>

<script>

    $(document).ready(() => {

        $('#dbEngine').change(function () {
            const port = $('#dbPort');
            switch ($(this).val()) {
                case "mysql":
                    port.val('3306');
                    break;
                case "postgresql":
                    port.val('5432');
                    break;
            }
        });

    });

</script>
</body>
</html>