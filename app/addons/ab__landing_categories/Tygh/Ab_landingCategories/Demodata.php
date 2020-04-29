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
namespace Tygh\Ab_landingCategories;
use Tygh\Registry;
class Demodata
{
private static $path_part = 'ab__landing_categories/demodata/';
public static $install_functions = [
'install_category',
];
public static function install_category($status = 'D')
{
$path = Registry::get('config.dir.var') . 'ab__data/ab__landing_categories/demodata/category';
$data = fn_get_contents("{$path}/data.json");
if (empty($data)) {
return false;
}
fn_copy($path, fn_get_files_dir_path() . self::$path_part);
$arr = [];
$data = json_decode($data, true);
foreach ($data as $category) {
$category['position'] = 10000;
$category['status'] = $status;
$arr[] = $par_id = self::create_category($category);
if ($par_id) {
foreach ($category['subcategories'] as $subcategory) {
$subcategory['parent_id'] = $par_id;
$subpar_id = self::create_category($subcategory);
if ($subpar_id) {
foreach ($subcategory['subcategories'] as $sub_subcategory) {
$sub_subcategory['parent_id'] = $subpar_id;
self::create_category($sub_subcategory);
}
}
}
} else {
return false;
}
}
fn_set_notification('N', __('notice'), __('ab__lc.demodata.success.category', ['[href]' => fn_url('categories.update&category_id=' . $arr[0])]));
return $arr;
}
private static function create_category($category)
{
$category['company_id'] = fn_get_runtime_company_id();
$category_id = fn_update_category($category);
if ($category_id) {
$server_icon = ['server'];
if (!empty($category['main_img'])) {
$img_text = 'category_main_image_';
$image = [
"file_{$img_text}icon" => ['category_main'],
"type_{$img_text}icon" => ['local'],
"file_{$img_text}detailed" => [self::$path_part . $category['main_img']],
"type_{$img_text}detailed" => $server_icon,
"{$img_text}data" => [
[
'type' => 'M',
'object_id' => $category_id,
],
],
];
$_REQUEST = array_merge($_REQUEST, $image);
fn_attach_image_pairs('category_main', 'category', $category_id, DESCR_SL);
}
if (isset($category['lc-img'])) {
$img_text = 'ab__lc_catalog_icon_image';
$image = [
"{$img_text}_data" => [
[
'type' => 'M',
'object_id' => $category_id,
],
],
"file_{$img_text}_icon" => [self::$path_part . $category['lc-img']],
"type_{$img_text}_icon" => $server_icon,
];
$_REQUEST = array_merge($_REQUEST, $image);
fn_attach_image_pairs('ab__lc_catalog_icon', 'ab__lc_catalog_icon', $category_id, DESCR_SL);
}
if (in_array('ru', array_keys(fn_get_languages()))) {
fn_update_category(array_merge($category, $category['ru']), $category_id, 'ru');
}
}
return $category_id;
}
public static function export_category($categories)
{
$tree = [];
$dir = Registry::get('config.dir.var') . 'ab__data/ab__landing_categories/demodata/category';
fn_rm($dir);
fn_mkdir($dir);
foreach ($categories as $cat) {
$category = fn_get_category_data($cat, 'en');
$category = self::make_image($category, $dir, 'main_pair', 'main_img');
$category = self::make_image($category, $dir, 'ab__lc_catalog_icon', 'lc-img');
$category['subcategories'] = fn_get_categories_tree($cat, true, 'en');
$category['ru'] = fn_get_category_data($cat, 'ru');
$category['age_warning_message'] = $category['ru']['age_warning_message'] = '1';
$category['ru'] = self::unset_category_data($category['ru']);
$category = self::unset_category_data($category);
unset($category['ru']['ab__lc_landing'], $category['ru']['ab__lc_catalog_image_control'], $category['lang_code']);
$temp = [];
foreach ($category['subcategories'] as &$subcategory) {
$subcategory = array_merge($subcategory, fn_get_category_data($subcategory['category_id'], 'en'));
$subcategory = self::make_image($subcategory, $dir, 'main_pair', 'main_img');
$subcategory['ru'] = fn_get_category_data($subcategory['category_id'], 'ru');
$subcategory['age_warning_message'] = $subcategory['ru']['age_warning_message'] = '1';
$subcategory['ru'] = self::unset_category_data($subcategory['ru']);
$subcategory = self::unset_category_data($subcategory);
unset($subcategory['ru']['ab__lc_landing'], $subcategory['ru']['ab__lc_catalog_image_control'], $subcategory['lang_code']);
foreach ($subcategory['subcategories'] as &$sub_subcategory) {
$sub_subcategory = array_merge($sub_subcategory, fn_get_category_data($sub_subcategory['category_id'], 'en'));
$sub_subcategory['ru'] = fn_get_category_data($sub_subcategory['category_id'], 'ru');
$sub_subcategory['age_warning_message'] = $sub_subcategory['ru']['age_warning_message'] = '1';
$sub_subcategory['ru'] = self::unset_category_data($sub_subcategory['ru']);
$sub_subcategory = self::unset_category_data($sub_subcategory);
unset($sub_subcategory['lang_code'], $sub_subcategory['ab__lc_catalog_image_control'], $sub_subcategory['ru']['ab__lc_landing'], $sub_subcategory['ru']['ab__lc_catalog_image_control']);
}
$temp[] = $subcategory;
}
$tree[] = $category;
}
fn_put_contents("{$dir}/data.json", json_encode($tree, JSON_PRETTY_PRINT));
fn_set_notification('N', __('notice'), 'Categories was exported successfully!');
}
private static function make_image($category, $dir, $image_existing, $image)
{
if ($category[$image_existing]) {
$img = isset($category[$image_existing]['detailed']) ? $category[$image_existing]['detailed'] : $category[$image_existing]['icon'];
$image_name = "ab__category-{$category['category_id']}." . strtolower(pathinfo($img['absolute_path'], PATHINFO_EXTENSION));
$category[$image] = $image_name;
fn_copy($img['absolute_path'], "{$dir}/{$image_name}");
}
return $category;
}
private static function unset_category_data($category)
{
$arr = [
'category_id',
'parent_id',
'company_id',
'seo_name',
'seo_path',
'product_count',
'usergroup_ids',
'parent_age_limit',
'parent_age_verification',
'age_verification',
'yml2_market_category',
'yml2_model_select',
'yml2_type_prefix',
'yml2_model',
'yml2_offer_type',
'yml2_type_prefix_select',
'meta_keywords',
'meta_description',
'page_title',
'age_warning_message',
'product_details_layout',
'selected_layouts',
'default_layout',
'is_op',
'age_limit',
'default_view',
'product_details_view',
'product_columns',
'is_trash',
'ab__lc_subsubcategories',
'ab__lc_catalog_icon',
'ab__lc_how_to_use_menu',
'ab__lc_inherit_control',
'ab__lc_menu_id',
'main_pair',
'position',
'status',
'selected_views',
'localization',
'abt__yt_banners_use',
'abt__yt_banner_max_position',
'timestamp',
'id_path',
];
foreach ($arr as $item) {
if (isset($category[$item])) {
unset($category[$item]);
}
}
return $category;
}
}
