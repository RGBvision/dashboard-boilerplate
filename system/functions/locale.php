<?

/**
 * This file is part of the RGB.dashboard package.
 *
 * (c) Alexey Graham <contact@rgbvision.net>
 *
 * @package    RGB.dashboard
 * @author     Alexey Graham <contact@rgbvision.net>
 * @copyright  2017-2019 RGBvision
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    1.7
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

//--- ToDo: move to locale / i18n class

function set_locale()
{
    $language = empty($_SESSION['current_language']) ? 'en' : $_SESSION['current_language'];
    $locale = strtolower($language);

    switch ($locale) {
        case 'ru':
            @setlocale(LC_ALL, 'ru_RU.UTF-8', 'rus_RUS.UTF-8', 'russian');
            @setlocale(LC_NUMERIC, 'C');
            break;
        default:
            @setlocale(LC_ALL, 'en_US.UTF-8', 'en_US.UTF-8', 'english');
            @setlocale(LC_NUMERIC, 'C');
            break;
    }

}

function _strtolower($string)
{
    $language = empty($_SESSION['current_language']) ? 'en' : $_SESSION['current_language'];
    $language = strtolower($language);

    switch ($language) {
        case 'ru':
            $small = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й',
                'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф',
                'х', 'ч', 'ц', 'ш', 'щ', 'э', 'ю', 'я', 'ы', 'ъ', 'ь',
                'э', 'ю', 'я');
            $large = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й',
                'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф',
                'Х', 'Ч', 'Ц', 'Ш', 'Щ', 'Э', 'Ю', 'Я', 'Ы', 'Ъ', 'Ь',
                'Э', 'Ю', 'Я');
            break;
        default:
            return mb_strtolower($string);
            break;
    }

    return str_replace($large, $small, $string);

}

function prettyDate($string, $language = '')
{
    if (!mb_check_encoding($string, 'UTF-8')) {
        $string = iconv('Windows-1251', 'UTF-8', $string);
    }

    if ($language == '') {
        $language = $_SESSION['current_language'];
    }

    $language = strtolower($language);

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
    }

    return (isset($pretty)
        ? strtr($string, $pretty)
        : $string);
}