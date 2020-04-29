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
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }


/**
 * @var string $mode
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($mode === 'restore') {
        $is_watermarks_enabled = Registry::get('addons.watermarks.status') === 'Y';
        $image_storage = Storage::instance('images');

        /** @var \Tygh\Database\Connection $db_connection */
        $db_connection = Tygh::$app['db'];

        $replace_by_original_image = function ($object_type, $image_id, $image_name)  use ($image_storage, $is_watermarks_enabled) {
            $path = $object_type . '/' . fn_get_image_subdir($image_id);
            $new_image_name = substr_replace($image_name, "_{$image_id}.", strrpos($image_name, '.'), 1);
            $original_image_path = $path . '/' . substr_replace($image_name, '@2x.', strrpos($image_name, '.'), 1);
            $image_path = $path . '/' . $image_name;
            $new_image_path = $path . '/' . $new_image_name;

            if (!$image_storage->isExist($original_image_path)) {
                return false;
            }

            $image_storage->copy($original_image_path, $new_image_path);

            if (!$image_storage->isExist($new_image_path)) {
                return false;
            }

            $image_storage->delete($original_image_path);
            $image_storage->delete($image_path);

            fn_delete_image_thumbnails($image_path);

            if ($is_watermarks_enabled) {
                $pair_id = 0;
                fn_watermarks_delete_image($image_id, $pair_id, $object_type, $image_path);
            }

            return $new_image_name;
        };

        $mark_image = function ($image_id, $image_name) use ($db_connection) {
            $db_connection->raw = true;
            $db_connection->query('UPDATE ?:images SET is_high_res = ?s, image_path = ?s WHERE image_id = ?i', 'Y', $image_name, $image_id);
        };

        $get_next_images = function ($sql, $offset, $limit) use ($db_connection) {
            $db_connection->raw = true;
            return $db_connection->getArray($sql, $offset, $limit);
        };

        $sql = <<<SQL
SELECT 
    link.object_type, link.object_id, 
    icon.image_id AS icon_image_id, icon.image_path AS icon_image_path,
    detailed.image_id AS detailed_image_id, detailed.image_path AS detailed_image_path 

  FROM ?:images_links AS link
	  LEFT JOIN ?:images AS icon ON icon.image_id = link.image_id 
	  LEFT JOIN ?:images AS detailed ON detailed.image_id = link.detailed_id

  LIMIT ?i, ?i;
SQL;

        $offset = 0;
        $limit = 500;

        $total = db_get_field('SELECT COUNT(*) FROM ?:images_links');

        fn_set_progress('step_scale', $total);

        while ($rows = $get_next_images($sql, $offset, $limit)) {
            foreach ($rows as $row) {
                fn_set_progress('echo', '.');

                if ($row['icon_image_id']
                    && ($image_name = $replace_by_original_image($row['object_type'], $row['icon_image_id'], $row['icon_image_path']))
                ) {
                    $mark_image($row['icon_image_id'], $image_name);
                }

                if ($row['detailed_image_id']
                    && ($image_name = $replace_by_original_image('detailed', $row['detailed_image_id'], $row['detailed_image_path']))) {
                    $mark_image($row['detailed_image_id'], $image_name);
                }
            }

            $offset += $limit;
        }

        fn_set_notification('N', __('notice'), __('successful'));

        if (defined('AJAX_REQUEST') && AJAX_REQUEST) {
            exit(0);
        }

        return [CONTROLLER_STATUS_REDIRECT, 'index.index'];
    }
}
