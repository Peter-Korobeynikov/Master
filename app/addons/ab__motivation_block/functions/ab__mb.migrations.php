<?php
/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2020   *
* / /_\ | | _____  _| |_/ /_ __ __ _ _ __   __| |_ _ __   __ _   | |_ ___  __ _ _ __ ___   *
* |  _  | |/ _ \ \/ / ___ \ '__/ _` | '_ \ / _` | | '_ \ / _` |  | __/ _ \/ _` | '_ ` _ \  *
* | | | | |  __/>  <| |_/ / | | (_| | | | | (_| | | | | | (_| |  | ||  __/ (_| | | | | | | *
* \_| |_/_|\___/_/\_\____/|_|  \__,_|_| |_|\__,_|_|_| |_|\__, |  \___\___|\__,_|_| |_| |_| *
*                                                         __/ |                            *
*                                                        |___/                             *
* ---------------------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license and accept    *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
use Tygh\Registry;
use Tygh\Enum\Addons\Ab_motivationBlock\ObjectTypes as Ab_objectTypes;
if (!defined('BOOTSTRAP')) {
die;
}
function fn_ab__mb_migration_from_v220_to_v230() {
$new_table_name = 'ab__mb_motivation_item_objects';
$new_table_empty = false;
$old_columns = [
Ab_objectTypes::CATEGORY => 'categories_ids',
Ab_objectTypes::DESTINATION => 'destinations_ids',];
$rows_count = db_get_field('SELECT COUNT(*) FROM ?:?f', $new_table_name);
if (empty($rows_count)) {
$new_table_empty = true;
}
if ($new_table_empty) {
$old_column_exists = true;
$main_table_describe = db_get_fields('DESCRIBE ?:ab__mb_motivation_items');
foreach ($old_columns as $column) {
if (empty($main_table_describe[$column])) {
$old_column_exists = false;
}
}
if ($old_column_exists) {
$items = db_get_hash_array('SELECT motivation_item_id, ?p FROM ?:ab__mb_motivation_items', 'motivation_item_id', implode(', ', $old_columns));
$insert_vals = [];
$count = 0;
foreach ($items as $item) {
foreach ($old_columns as $type => $old_column) {
if (!empty($item[$old_column])) {
foreach (explode(',', $item[$old_column]) as $_tmp_id) {
$insert_vals[] = "({$item['motivation_item_id']}, {$_tmp_id}, '{$type}')";
$count++;
}
} else {
$insert_vals[] = "({$item['motivation_item_id']}, 0, '{$type}')";
$count++;
}
}
}
$res = db_query('INSERT INTO ?:?f (motivation_item_id, object_id, object_type)
VALUES ?p', $new_table_name, implode(', ', $insert_vals));
if ($res == $count) {
foreach ($old_columns as $old_column) {
db_query('ALTER TABLE ?:?f DROP COLUMN ?f', 'ab__mb_motivation_items', $old_column);
}
}
}
}
}
function fn_ab__mb_migration_from_v230_to_v240() {
$has_column = db_get_array('SHOW COLUMNS FROM `?:ab__mb_motivation_items` LIKE "icon_class"');
if(!empty($has_column)) {
db_query('ALTER TABLE `?:ab__mb_motivation_items` CHANGE `icon_class` `icon_class` varchar(64) NOT NULL DEFAULT ""');
}
}