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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
use Tygh\Settings;
function fn_ab__cb_install()
{
fn_ab__cb_install_db_fields();
fn_ab__cb_install_cron_key();
}
function fn_ab__cb_install_db_fields()
{
$objects = array(
array(
'table' => '?:ab__category_banners',
'field' => 'position',
'sql' => 'ALTER TABLE ?:ab__category_banners ADD position VARCHAR(255) NOT NULL DEFAULT \'\'',
),
array(
'table' => '?:ab__category_banners',
'field' => 'include_subcategories',
'sql' => 'ALTER TABLE ?:ab__category_banners ADD include_subcategories CHAR(1) NOT NULL DEFAULT \'N\'',
),
);
if (!empty($objects) && is_array($objects)) {
foreach ($objects as $object) {
$fields = db_get_fields('DESCRIBE ' . $object['table']);
if (!empty($fields) && is_array($fields)) {
$is_present_field = false;
foreach ($fields as $f) {
if ($f == $object['field']) {
$is_present_field = true;
break;
}
}
if (!$is_present_field) {
db_query($object['sql']);
if (!empty($object['add_sql'])) {
foreach ($object['add_sql'] as $sql) {
db_query($sql);
}
}
}
}
}
}
}
function fn_ab__cb_install_cron_key()
{
$new_cron_key = fn_generate_password(15);
Settings::instance()->updateValue('cron_key', $new_cron_key, 'ab__category_banners');
}
