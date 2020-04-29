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
use Tygh\Languages\Languages;
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
function fn_ab__category_banners_install_banners($show_notification = false)
{
$langs_codes_list = array_keys(Languages::getAll());
$path_part = 'ab__data/ab__category_banners/demodata/banners';
$path = Registry::get('config.dir.var') . $path_part;
$data = fn_get_contents("{$path}/data.json");
if (empty($data)) {
$string = __('ab__cb.autoinstall.no_demodata');
return $show_notification ? fn_set_notification('E', __('error'), $string) : $string;
}
$data = json_decode($data, true);
$category_id = db_get_field('SELECT c.category_id FROM ?:category_descriptions AS cd'
. ' INNER JOIN ?:categories AS c ON c.category_id = cd.category_id'
. ' WHERE c.level = 1 AND c.status = ?s AND c.is_trash = ?s AND cd.category = ?s ?p', 'A', 'N', 'Electronics', fn_get_company_condition('c.category_id'));
if (empty($category_id)) {
$category_id = db_get_field('SELECT category_id FROM ?:categories WHERE level=1 AND status = ?s AND is_trash = ?s ?p', 'A', 'N', fn_get_company_condition('c.category_id'));
}
if (empty($category_id)) {
$string = __('ab__cb.autoinstall.no_category');
return $show_notification ? fn_set_notification('E', __('error'), $string) : $string;
}
$img_path = fn_get_files_dir_path() . $path_part;
fn_rm($img_path);
fn_mkdir($img_path);
fn_copy($path, $img_path);
foreach ($data as $banner) {
$banner['status'] = 'A';
$banner['category_ids'] = $category_id;
$banner['category_banner_id'] = fn_ab__update_category_banner($banner, 0);
foreach ($banner['images'] as $var_name => $file_name) {
foreach ($langs_codes_list as $lang_code) {
$banner_image_id = db_get_field('SELECT category_banner_image_id FROM ?:ab__category_banner_images_and_descr WHERE category_banner_id = ?i AND lang_code = ?s', $banner['category_banner_id'], $lang_code);
$img = array(
"{$var_name}_data" => array(
array(
'type' => ($var_name == 'category_banners_main_image') ? 'M' : 'L',
'is_new' => true,
'object_id' => $banner_image_id,
),
),
"file_{$var_name}_icon" => array("{$img_path}/{$file_name}"),
"type_{$var_name}_icon" => array('server'),
);
$_REQUEST = array_merge($_REQUEST, $img);
fn_attach_image_pairs(($var_name == 'category_banners_main_image' ? 'category_banners_main' : 'category_banners_list_image'), 'category_banner', $banner_image_id);
unset($_REQUEST["{$var_name}_data"], $_REQUEST["file_{$var_name}_icon"], $_REQUEST["type_{$var_name}_icon"]);
}
}
if (in_array('ru', $langs_codes_list)) {
fn_ab__update_category_banner(array_merge($banner, $banner['ru']), $banner['category_banner_id'], 'ru');
}
$banner_name = CART_LANGUAGE == 'ru' ? $banner['ru']['category_banner'] : $banner['category_banner'];
$result[$banner['category_banner_id']] = '<a href="' . fn_url('ab__category_banners.update?category_banner_id=' . $banner['category_banner_id'], 'A') . '">' . $banner_name . '</a>';
}
if (empty($result)) {
$string = __('ab__cb.autoinstall.banners_not_created');
return $show_notification ? fn_set_notification('E', __('error'), $string) : $string;
}
$string = __('ab__cb.autoinstall.banners_created', array('[banners]' => implode(', ', $result)));
return $show_notification ? fn_set_notification('N', __('notice'), $string) : $string;
}
