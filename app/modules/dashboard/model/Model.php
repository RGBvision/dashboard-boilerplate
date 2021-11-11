<?php


class ModelDashboard extends Model
{

    public static function getStorageSize(): int
    {
        return 100 * pow(1024, 3);

    }

    public static function getStorageUsage(): array
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

        $size = 0;

        foreach ($details as $usage) {
            $size += $usage;
        }

        return [
            'size' => $size,
            'percentage' => round($size / (self::getStorageSize() / 100) / 100, 2),
            'details' => $details,
        ];
    }

}