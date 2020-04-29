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
use Tygh\Registry;
function fn_ab__category_banners_export_banners($banner_ids)
{
$dir = Registry::get('config.dir.var') . 'ab__data/ab__category_banners/demodata/banners';
fn_rm($dir);
fn_mkdir($dir);
$banners = array();
$banner_ids = explode(',', $banner_ids);
foreach ($banner_ids as $id) {
$banner = fn_ab__get_category_banner_data($id, 'en');
$banner = fn_ab__category_banners_make_export_image($banner, $dir, 'category_banners_main_image', 'main_pair');
$banner = fn_ab__category_banners_make_export_image($banner, $dir, 'category_banners_list_image', 'list_pair');
$banner = fn_ab__category_banners_unset_banners_data($banner);
$banner['ru'] = fn_ab__category_banners_unset_banners_data(fn_ab__get_category_banner_data($id));
unset($banner['ru']['repeat']);
$banners[] = $banner;
}
$banner_ids = implode(', ', $banner_ids);
fn_put_contents("{$dir}/data.json", json_encode($banners, JSON_PRETTY_PRINT));
fn_set_notification('N', __('notice'), "Banners {$banner_ids} was exported successfully!");
return true;
}
function fn_ab__category_banners_make_export_image($banner, $dir, $name, $ext_name)
{
$img = $banner[$ext_name]['icon'];
$image_name = "{$name}-{$banner['category_banner_id']}." . strtolower(pathinfo($img['absolute_path'], PATHINFO_EXTENSION));
$banner['images'][$name] = $image_name;
fn_copy($img['absolute_path'], "{$dir}/$image_name");
return $banner;
}
function fn_ab__category_banners_unset_banners_data($banner)
{
$arr = array(
'category_banner_id',
'status',
'to_date',
'from_date',
'category_ids',
'url',
'category_banner_image_id',
'main_pair',
'list_pair',
);
foreach ($arr as $pos) {
unset($banner[$pos]);
}
return $banner;
}
