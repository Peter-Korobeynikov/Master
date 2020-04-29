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

use Tygh\Storage;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * @var string $mode
 * @var string $action
 * @var array $auth
 */

if ($mode === 'auth') {
    fn_hidpi_notify_if_images_not_restored();
}

function fn_hidpi_notify_if_images_not_restored()
{
    if (!fn_check_permissions('hidpi', 'restore', 'admin', 'POST')) {
        return;
    }

    /** @var \Tygh\Database\Connection $db_connection */
    $db_connection = Tygh::$app['db'];

    $sql = <<<SQL
SELECT 
    link.object_type, link.object_id, 
    icon.image_id AS icon_image_id, icon.image_path AS icon_image_path,
    detailed.image_id AS detailed_image_id, detailed.image_path AS detailed_image_path 

  FROM ?:images_links AS link
	  LEFT JOIN ?:images AS icon ON icon.image_id = link.image_id 
	  LEFT JOIN ?:images AS detailed ON detailed.image_id = link.detailed_id
	  
  WHERE (link.image_id  > 0 AND icon.is_high_res = 'N') OR (link.detailed_id > 0 AND detailed.is_high_res = 'N') 

  ORDER BY link.pair_id DESC LIMIT ?i, ?i;
SQL;

    $offset = 0;
    $limit = 500;
    $image_storage = Storage::instance('images');
    $exists = false;

    $get_next_images = function ($sql, $offset, $limit) use ($db_connection) {
        $db_connection->raw = true;
        return $db_connection->getArray($sql, $offset, $limit);
    };

    $check_original_image = function ($object_type, $image_id, $image_name)  use ($image_storage) {
        $path = $object_type . '/' . fn_get_image_subdir($image_id);
        $original_image_path = $path . '/' . substr_replace($image_name, '@2x.', strrpos($image_name, '.'), 1);

        if ($image_storage->isExist($original_image_path)) {
            $md5 = md5_file($image_storage->getAbsolutePath($original_image_path));

            //skip demo images
            return !in_array($md5, ['52cb52a940cb6ef34f77243341ee5ebe'], true);
        }

        return false;
    };

    while ($rows = $get_next_images($sql, $offset, $limit)) {
        foreach ($rows as $row) {
            $exists = ($row['icon_image_id'] && $check_original_image($row['object_type'], $row['icon_image_id'], $row['icon_image_path']))
                   || ($row['detailed_image_id'] && $check_original_image('detailed', $row['detailed_image_id'], $row['detailed_image_path']));

            if ($exists) {
                break 2;
            }
        }

        $offset += $limit;
    }

    if ($exists) {
        fn_set_notification(
            'W',
            __('warning'),
            __('hidpi.notice.images_were_not_restored', [
                '[convert_url]' => fn_url('hidpi.restore?switch_company_id=0'),
            ]),
            'S',
            'images_were_not_restored'
        );
    }
}

return [CONTROLLER_STATUS_OK];
