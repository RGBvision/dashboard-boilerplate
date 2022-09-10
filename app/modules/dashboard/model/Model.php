<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2022, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    3.2
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ModelDashboard extends Model
{

    /**
     * Get number of visits
     * GENERATED DATA FOR DEMO PURPOSES ONLY
     *
     * @return string
     */
    public function getVisits(): string
    {

        $data = [];

        for ($i = 0; $i < 28; $i++) {
            $data[date('Y-m-d', strtotime("today -$i days"))] = round(((int)date('z', strtotime("today -$i days")) % 22) * (int)date('N', strtotime("today -$i days"))) + 500;
        }

        ksort($data);

        return Json::encode($data);

    }

    public function getStorageSize(): int
    {
        return 100 * pow(1024, 3);
    }

    public function getStorageUsage(): array
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/dashboard/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $details = [
            $Template->_get('dashboard_storage_db') => DB::getSize(),
            $Template->_get('dashboard_storage_db_backups') => Dir::size(DASHBOARD_DIR . '/tmp/backup/'),
            $Template->_get('dashboard_storage_cache') => Dir::size(DASHBOARD_DIR . '/tmp/cache/'),
            $Template->_get('dashboard_storage_log') => Dir::size(DASHBOARD_DIR . '/tmp/logs/'),
            $Template->_get('dashboard_storage_user_files') => Dir::size(DASHBOARD_DIR . '/uploads/'),
        ];

        $size = array_sum($details);

        return [
            'size' => $size,
            'usage' => round($size / self::getStorageSize(), 2),
            'details' => $details,
        ];
    }

    /**
     * Copy module template to modules directory as a new module
     *
     * @param string $source
     * @param string $destination
     * @param string $module_name Generated module name
     */
    public function copy_template(string $source, string $destination, string $module_name): void
    {
        $dir = opendir($source);

        if (!Dir::create($destination)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $destination));
        }

        while (false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..')) {

                if (is_dir($source . '/' . $file)) {

                    self::copy_template($source . '/' . $file, $destination . '/' . $file, $module_name);

                } else {

                    $new_file = str_replace(
                        'example',
                        preg_replace('/\s+/', '', strtolower($module_name)),
                        $file
                    );

                    $_new_name = explode(' ', $module_name);

                    foreach ($_new_name as &$part) {
                        $part = ucfirst($part);
                    }

                    $content = File::getContents($source . '/' . $file);

                    $new_content = str_replace(
                        [
                            'example',
                            'Example',
                            'EXAMPLE',
                            ':date:',
                        ],
                        [
                            strtolower(implode('', $_new_name)),
                            implode('', $_new_name),
                            implode(' ', $_new_name),
                            date('d.m.Y'),
                        ],
                        $content
                    );

                    File::putContents($destination . '/' . $new_file, $new_content, true, true);

                }
            }
        }

        closedir($dir);
    }

}