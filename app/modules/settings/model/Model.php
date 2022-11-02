<?php

class SettingsModel extends Model
{

    private static function generate_timezone_list()
    {

        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        $timezone_offsets = [];
        foreach ($timezones as $timezone) {
            $tz = new DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
        }

        // sort timezone by offset
        asort($timezone_offsets);

        $timezone_list = [];
        foreach ($timezone_offsets as $timezone => $offset) {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate('H:i', abs($offset));
            $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
            $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
        }

        return $timezone_list;
    }

    public static function getSettings()
    {
        $new = [];

        $new['TIMEZONE'] = [
            'TYPE' => 'dropdown',
            'DEFAULT' => 'Europe/Moscow',
            'VARIANT' => self::generate_timezone_list(),
            'LANG' => 'settings_title_timezone',
        ];

        $settings = defined('DASHBOARD_CONFIG_DEFAULTS') ? DASHBOARD_CONFIG_DEFAULTS : null;

        foreach ($settings as $key => $params) {
            $params['DEFAULT'] = (defined($key)
                ? constant($key)
                : $params['DEFAULT']);

            if ($params['TYPE'] === 'folder') {
                $params['DEFAULT'] = trim($params['DEFAULT'], '/');
            } else if ($params['TYPE'] === 'bool') {
                $params['DEFAULT'] = (bool)$params['DEFAULT'];
            } else if ($params['TYPE'] === 'integer') {
                $params['DEFAULT'] = (int)$params['DEFAULT'];
            } else if ($params['TYPE'] === 'string') {
                $params['DEFAULT'] = (string)$params['DEFAULT'];
            } else if ($params['TYPE'] === 'dropdown') {
                $params['VARIANT'] = (is_array($params['VARIANT'])
                    ? $params['VARIANT']
                    : explode(',', $params['VARIANT']));
            }

            $params['LANG'] = 'settings_title_' . strtolower($key);

            $new[$key] = $params;
        }

        unset($settings);

        return $new;
    }


    public static function saveSettings()
    {
        Router::demo();

        $type = 'danger';

        $Smarty = Tpl::getInstance();

        $permission = Permissions::has('admin_settings_edit');

        $default = self::getSettings();

        if ($permission) {
            $set = '<?php' . "\r\n";

            $settings = Request::post('const');

            if (!empty($settings))
                foreach ($default as $key => $constant) {
                    $input = $settings[$key] ?? $constant['DEFAULT'];

                    $set .= "\r\n\t";
                    $set .= '//--- ' . $Smarty->_get($constant['LANG']);
                    $set .= "\r\n\t";

                    if ($default[$key]['TYPE'] == 'string')
                        $set .= "define('" . $key . "', '" . (string)$input . "');";
                    else if ($default[$key]['TYPE'] == 'integer')
                        $set .= "define('" . $key . "', " . (int)$input . ");";
                    else if ($default[$key]['TYPE'] == 'folder')
                        $set .= "define('" . $key . "', '/" . trim($input, '/') . "');";
                    else if ($default[$key]['TYPE'] == 'bool')
                        $set .= "define('" . $key . "', " . ((bool)$input ? 'true' : 'false') . ");";
                    else if ($default[$key]['TYPE'] == 'dropdown')
                        $set .= "define('" . $key . "', '" . (string)$input . "');";
                    else if ($default[$key]['TYPE'] == 'tags')
                        $set .= "define('" . $key . "', '" . (string)$input . "');";

                    $set .= "\r\n";
                }

            $result = file_put_contents(DASHBOARD_DIR . '/configs/environment.php', $set);

            if ($result > 0) {
                $message = $Smarty->_get('admin_settings_message_edit_success');
                $type = 'success';
            } else {
                $message = $Smarty->_get('admin_settings_message_edit_danger');
            }

        } else {
            $message = $Smarty->_get('admin_settings_message_perm_danger');
        }

        Router::response($type, $message, '/admin/settings');
    }
}
