<?php
/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2019   *
* / /_\ | | _____  _| |_/ /_ __ __ _ _ __   __| |_ _ __   __ _   | |_ ___  __ _ _ __ ___   *
* |  _  | |/ _ \ \/ / ___ \ '__/ _` | '_ \ / _` | | '_ \ / _` |  | __/ _ \/ _` | '_ ` _ \  *
* | | | | |  __/>  <| |_/ / | | (_| | | | | (_| | | | | | (_| |  | ||  __/ (_| | | | | | | *
* \_| |_/_|\___/_/\_\____/|_|  \__,_|_| |_|\__,_|_|_| |_|\__, |  \___\___|\__,_|_| |_| |_| *
*                                                         __/ |                            *
*                                                        |___/                             *
* ---------------------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license and  accept   *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
use Tygh\Registry;
function fn_ab__fast_navigation_get_static_data($params, &$fields)
{
$fields[] = '?:static_data_descriptions.ab__fn_label_show';
$fields[] = '?:static_data_descriptions.ab__fn_label_text';
$fields[] = 'sd.ab__fn_label_color';
$fields[] = 'sd.ab__fn_menu_status';
$fields[] = 'sd.ab__fn_label_background';
$fields[] = 'sd.ab__fn_use_origin_image';
}
function fn_ab__fast_navigation_update_static_data($data, $param_id, $condition, $section, $lang_code)
{
if (Registry::get('runtime.mode') == 'update') {
fn_attach_image_pairs('ab__fn_menu_icon', 'ab__fn_menu_icon', $param_id, $lang_code);
}
}
function fn_ab__fast_navigation_get_subcategories_params($category_id, $lang_code, &$params)
{
if (AREA == 'C') {
$params['ab__fn_get_additional'] = true;
}
}
function fn_ab__fast_navigation_get_categories($params, $join, $condition, &$fields, $group_by, $sortings, $lang_code)
{
if (isset($params['ab__fn_get_additional']) && $params['ab__fn_get_additional'] === true) {
$fields[] = '?:categories.ab__fn_category_status';
$fields[] = '?:categories.ab__fn_label_color';
$fields[] = '?:categories.ab__fn_label_background';
$fields[] = '?:categories.ab__fn_use_origin_image';
$fields[] = '?:category_descriptions.ab__fn_label_text';
$fields[] = '?:category_descriptions.ab__fn_label_show';
}
}
