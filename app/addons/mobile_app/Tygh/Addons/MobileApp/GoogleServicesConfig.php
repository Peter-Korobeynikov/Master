<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Tygh\Addons\MobileApp;

use Tygh\Storage;

/**
 * Provides methods for handling google-services config file.
 *
 * @package Tygh\Addons\MobileApp\GoogleServicesConfig
 */
class GoogleServicesConfig
{
    protected static $file_path = 'mobile_app/google-services.json';

    public static function upload($uploaded_data)
    {
        if (empty($uploaded_data['google_services_config_file']['path'])) {
            return false;
        }

        list($size) = Storage::instance('downloads')->put(self::$file_path, [
            'file'      => $uploaded_data['google_services_config_file']['path'],
            'overwrite' => true,
        ]);

        return $size > 0;
    }

    public static function isExist()
    {
        return Storage::instance('downloads')->isExist(self::$file_path);
    }

    public static function getFilePath()
    {
        return Storage::instance('downloads')->getAbsolutePath(self::$file_path);
    }

    public static function getFile()
    {
        return Storage::instance('downloads')->get(self::$file_path);
    }

    public static function deleteFile()
    {
        return Storage::instance('downloads')->delete(self::$file_path);
    }
}
