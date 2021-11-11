<?php

// Check if request via AJAX
// ToDO: move to request class
function isAjax()
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'));
}

// Get user IP
function getIp()
{
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $ip_address = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip_address = 'UNKNOWN';
    }

    return $ip_address;
}

// get random token
// ToDO: move to secure class
function token($length = 32)
{
    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $max = strlen($string) - 1;

    $token = '';

    for ($i = 0; $i < $length; $i++) {
        try {
            $token .= $string[random_int(0, $max)];
        } catch (Exception $e) {

        }
    }

    return $token;
}

// get random string
// ToDO: move to secure class
function randomString($length = 16, $chars = '')
{
    if ($chars === '') {
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $chars .= 'ABCDEFGHIJKLMNOPRQSTUVWXYZ';
        $chars .= '~!@#$%^&*()-_=+{[;:/?.,]}';
        $chars .= '0123456789';
    }

    $s_len = strlen($chars) - 1;

    $string = '';

    while (strlen($string) < $length) {
        try {
            $string .= $chars[random_int(0, $s_len)];
        } catch (Exception $e) {

        }
    }

    return $string;
}

// ToDO: move to locale / i18n class
function pretty_date($string, $language = '')
{
    // trying solve encoding problems on Windows
    if (!mb_check_encoding($string, 'UTF-8')) {
        $string = iconv('Windows-1251', 'UTF-8', $string);
    }

    if ($language === '' && isset($_SESSION['current_language'])) {
        $language = strtolower($_SESSION['current_language']);
    } else {
        $language = 'en';
    }

    switch ($language) {
        default:
        case 'ru':
            $pretty = array(
                'Январь' => 'января', 'Февраль' => 'февраля', 'Март' => 'марта',
                'Апрель' => 'апреля', 'Май' => 'мая', 'Июнь' => 'июня',
                'Июль' => 'июля', 'Август' => 'августа', 'Сентябрь' => 'сентября',
                'Октябрь' => 'октября', 'Ноябрь' => 'ноября', 'Декабрь' => 'декабря',

                'воскресенье' => 'Воскресенье', 'понедельник' => 'Понедельник', 'вторник' => 'Вторник',
                'среда' => 'Среда', 'четверг' => 'Четверг', 'пятница' => 'Пятница',
                'суббота' => 'Суббота'
            );
            break;

        case 'ua':
        case 'uk':
            $pretty = array(
                'Січень' => 'січня', 'Лютий' => 'лютого', 'Березень' => 'березня',
                'Квітень' => 'квітня', 'Травень' => 'травня', 'Червень' => 'червня',
                'Липень' => 'липня', 'Серпень' => 'серпня', 'Вересень' => 'вересня',
                'Жовтень' => 'жовтня', 'Листопад' => 'листопада', 'Грудень' => 'грудня',

                'неділя' => 'Неділя', 'понеділок' => 'Понеділок', 'вівторок' => 'Вівторок',
                'середа' => 'Середа', 'четвер' => 'Четвер', 'п’ятниця' => 'П’ятниця',
                'субота' => 'Субота'
            );
            break;
    }

    return (isset($pretty)
        ? strtr($string, $pretty)
        : $string);
}

// ToDO: move to locale / i18n class
function translate_date($data)
{
    if (isset($_SESSION['current_language']) && ($_SESSION['current_language'] !== 'en')) {
        $language = strtolower($_SESSION['admin_language']);
    } else {
        return $data;
    }

    switch ($language) {
        default:
        case 'ru':
            $data = strtr($data, array(
                'January' => 'Января',
                'February' => 'Февраля',
                'March' => 'Марта',
                'April' => 'Апреля',
                'May' => 'Мая',
                'June' => 'Июня',
                'July' => 'Июля',
                'August' => 'Августа',
                'September' => 'Сентября',
                'October' => 'Октября',
                'November' => 'Ноября',
                'December' => 'Декабря',

                'Jan' => 'Янв',
                'Feb' => 'Фев',
                'Mar' => 'Мрт',
                'Apr' => 'Апр',
                'May' => 'Май',
                'Jun' => 'Июн',
                'Jul' => 'Июл',
                'Aug' => 'Авг',
                'Sep' => 'Сен',
                'Oct' => 'Окт',
                'Nov' => 'Нбр',
                'Dec' => 'Дек',

                'Monday' => 'Понедельник',
                'Tuesday' => 'Вторник',
                'Wednesday' => 'Среда',
                'Thursday' => 'Четверг',
                'Friday' => 'Пятница',
                'Saturday' => 'Суббота',
                'Sunday' => 'Воскресенье',

                'Mon' => 'Пн',
                'Tue' => 'Вт',
                'Wed' => 'Ср',
                'Thu' => 'Чт',
                'Fri' => 'Пт',
                'Sat' => 'Сб',
                'Sun' => 'Вс'
            ));
            break;
    }

    return $data;
}

// normalize phone number
function normalizePhone($phone, $gaps = false)
{
    return '+' . trim(preg_replace("/\D+/", '', $phone));
}

// normalize email address
function normalizeEmail($email)
{

    $_email = mb_strtolower($email);

    $regex_email = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

    if (!preg_match($regex_email, $_email)) {
        return '';
    }

    return $_email;
}

function formatSize(int $size): string
{
    if ($size >= 1073741824) {
        $result = round($size / 1073741824 * 100) / 100 . ' Gb';
    } elseif ($size >= 1048576) {
        $result = round($size / 1048576 * 100) / 100 . ' Mb';
    } elseif ($size >= 1024) {
        $result = round($size / 1024 * 100) / 100 . ' Kb';
    } else {
        $result = $size . ' b';
    }

    return $result;
}