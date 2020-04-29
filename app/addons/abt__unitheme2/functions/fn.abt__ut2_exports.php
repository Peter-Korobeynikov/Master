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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
if (AREA == 'A') {
define('ABT__UT2_DATA_EXP_PATH', Registry::get('config.dir.var') . 'ab__data/abt__unitheme2/');
function fn_abt__ut2_export_banners($ids)
{
$answer = [];
$notif = '';
$path = ABT__UT2_DATA_EXP_PATH . 'banners/';
fn_rm($path);
fn_mkdir($path);
$ids = explode(',', $ids);
foreach ($ids as $id) {
$temporal = fn_get_banner_data($id);
$image_types = [
'main_pair' => [
'type' => 'M',
'device' => 'all',
],
'abt__ut2_main_image' => [
'type' => 'M',
'device' => 'all',
],
'abt__ut2_background_image' => [
'type' => 'A',
'device' => 'all',
],
'abt__ut2_tablet_main_image' => [
'type' => 'M',
'device' => 'tablet',
],
'abt__ut2_tablet_background_image' => [
'type' => 'A',
'device' => 'tablet',
],
'abt__ut2_mobile_main_image' => [
'type' => 'M',
'device' => 'mobile',
],
'abt__ut2_mobile_background_image' => [
'type' => 'A',
'device' => 'mobile',
],
];
foreach ($image_types as $img_type => $type) {
if (!empty($temporal[$img_type])) {
$new_type = ($img_type == 'main_pair') ? 'banners_main' : $img_type;
$name = $temporal['banner_id'] . '-' . $new_type . '-banner.' . strtolower(pathinfo($temporal[$img_type]['icon']['absolute_path'], PATHINFO_EXTENSION));
fn_copy($temporal[$img_type]['icon']['absolute_path'], "{$path}{$name}");
$temporal['images'][$new_type] = ['img' => $name, 'type' => $type['type'], 'device' => $type['device']];
}
unset($temporal[$img_type]);
}
$ru = fn_get_banner_data($id, 'ru');
$temporal['ru'] = [
'banner' => $ru['banner'],
'abt__ut2_button_text' => $ru['abt__ut2_button_text'],
'abt__ut2_title' => $ru['abt__ut2_title'],
'abt__ut2_description' => $ru['abt__ut2_description'],
];
$notif .= "<a target='_blank' href='?dispatch=banners.update&banner_id=$id'>$id</a>,";
fn_abt__ut2_unset_banners_data($temporal);
$answer[] = $temporal;
}
fn_put_contents("{$path}/data.json", json_encode($answer, JSON_PRETTY_PRINT));
fn_set_notification('N', __('notice'), __('abt__ut2.export.success.banners', ['[ids]' => substr($notif, 0, -1)]));
}
function fn_abt__ut2_export_blog()
{
$path = ABT__UT2_DATA_EXP_PATH . 'blog/';
fn_rm($path);
fn_mkdir($path);
$blog_pages = array_shift(fn_get_pages([
'page_type' => 'B',
'get_tree' => 'multilevel',
], 0, 'en')[0])['subpages'];
$images = fn_get_image_pairs(array_keys($blog_pages), 'blog', 'M', true, false);
$arr = [];
$i = 0;
foreach ($blog_pages as $key => $page) {
if ($images[$key]) {
$img = array_shift($images[$key])['icon'];
$image_name = 'blog-image-' . $key . '.' . pathinfo($img['absolute_path'], PATHINFO_EXTENSION);
$page['blog_image'] = $image_name;
fn_copy($img['absolute_path'], "{$path}/{$image_name}");
}
$page['author'] = 'AlexBranding';
$page['position'] = ++$i * 100;
$ru = fn_get_page_data($key, 'ru');
$page['ru'] = [
'lang_code' => 'ru',
'page' => $ru['page'] ? $ru['page'] : '',
'description' => $ru['description'] ? $ru['description'] : '',
];
fn_abt__ut2_unset_blog_page_data($page);
$arr[] = $page;
}
fn_put_contents("{$path}/data.json", json_encode($arr, JSON_PRETTY_PRINT));
fn_set_notification('N', __('notice'), __('abt__ut2.export.success.blog'));
}
function fn_abt__ut2_export_menu($ids)
{
$path = ABT__UT2_DATA_EXP_PATH . 'menu/';
fn_rm($path);
fn_mkdir($path);
$data = [];
$notif = '';
foreach ((array) explode(',', $ids) as $menu_id) {
$menu_name = db_get_field('SELECT name FROM ?:menus_descriptions WHERE lang_code = ?s AND menu_id = ?i', CART_LANGUAGE, $menu_id);
$_REQUEST['menu_id'] = $menu_id;
$p = [
'section' => 'A',
'status' => 'A',
'generate_levels' => true,
'get_params' => true,
'multi_level' => true,
];
$temp = fn_top_menu_form(fn_get_static_data($p, 'en'));
foreach ($temp as &$item) {
fn_abt__ut2_add_more_to_static_data($item);
}
$notif .= '<a target="_blank" href="' . fn_url('static_data.manage&section=A&menu_id=' . $menu_id) . '">' . $menu_id . '</a>,';
$data[$menu_name] = $temp;
}
fn_put_contents("{$path}/data.json", json_encode($data, JSON_PRETTY_PRINT));
fn_set_notification('N', __('notice'), __('abt__ut2.export.success.menu', ['[ids]' => substr($notif, 0, -1)]));
}
function fn_abt__ut2_export_products($ids)
{
}
function fn_abt__ut2_unset_banners_data(&$banner)
{
$arr = [
'banner_id',
'timestamp',
'status',
];
foreach ($arr as $unset) {
unset($banner[$unset]);
}
}
function fn_abt__ut2_unset_blog_page_data(&$page)
{
$arr = [
'page_id',
'parent_id',
'id_path',
'company_id',
'lang_code',
'timestamp',
'main_pair',
'meta_keywords',
'meta_description',
'seo_name',
'seo_path',
'page_title',
'status',
];
foreach ($arr as $unset) {
if (isset($page[$unset])) {
unset($page[$unset]);
}
}
}
function fn_abt__ut2_add_more_to_static_data(&$item)
{
$path = ABT__UT2_DATA_EXP_PATH . 'menu/';
$item['ru'] = db_get_row('SELECT descr, abt__ut2_mwi__text, abt__ut2_mwi__desc, abt__ut2_mwi__label
FROM ?:static_data_descriptions
WHERE lang_code=?s AND param_id=?n', 'ru', $item['param_id']);
$icon = fn_get_image_pairs($item['param_id'], 'abt__ut2/menu-with-icon', 'M', true, false);
if (!empty($icon)) {
$ico_name = $item['param_id'] . '-abt__ut2_mwi__icon.' . pathinfo($icon['icon']['absolute_path'], PATHINFO_EXTENSION);
$item['image'] = $ico_name;
fn_copy($icon['icon']['absolute_path'], "{$path}/{$ico_name}");
}
fn_abt__ut2_unset_static_data($item);
if (!empty($item['param_3']) && strpos($item['param_3'], 'Y') !== false) {
$item['ab__use_category_link'] = 'Y';
} else {
unset($item['param_3']);
}
if (!empty($item['subitems'])) {
foreach ($item['subitems'] as $key => &$subitem) {
if (isset($subitem['param_id'])) {
fn_abt__ut2_add_more_to_static_data($subitem);
unset($subitem['parent_id']);
} else {
unset($item['subitems'][$key]);
}
}
} else {
unset($item['subitems']);
}
}
function fn_abt__ut2_unset_static_data(&$item)
{
$arr = [
'id_path',
'param_id',
'status',
'param_2',
'param_4',
'param_5',
'parent_id',
'abt__ut2_mwi__icon',
];
foreach ($arr as $unset) {
if (isset($item[$unset])) {
unset($item[$unset]);
}
}
}
}
