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
function fn_abt__unitheme2_update_static_data(&$data, $param_id, $condition, $section, $lang_code)
{
if (Registry::get('runtime.mode') == 'update') {
$data['abt__ut2_mwi__text'] = $_POST['static_data']['abt__ut2_mwi__text'];
fn_attach_image_pairs('abt__ut2_mwi__icon', 'abt__ut2/menu-with-icon', $param_id, $lang_code);
}
}
function fn_abt__unitheme2_get_static_data($params, &$fields, $condition, $sorting, $lang_code)
{
$fields[] = 'sd.abt__ut2_mwi__status';
$fields[] = '?:static_data_descriptions.abt__ut2_mwi__label';
$fields[] = 'sd.abt__ut2_mwi__label_color';
$fields[] = 'sd.abt__ut2_mwi__label_background';
if (empty($params['abt__ut2_light_menu'])) {
$fields[] = '?:static_data_descriptions.abt__ut2_mwi__desc';
$fields[] = '?:static_data_descriptions.abt__ut2_mwi__text';
$fields[] = 'sd.abt__ut2_mwi__text_position';
$fields[] = 'sd.abt__ut2_mwi__dropdown';
}
}
function fn_abt__unitheme2_top_menu_form_post(&$top_menu, $level, $active)
{
static $abt__ut2_mwi_icon_get = 'N';
static $abt__ut2_mwi_icon_ids = [];
if ($abt__ut2_mwi_icon_get == 'N') {
$abt__ut2_mwi_icon_get = 'Y';
$abt__ut2_mwi_icon_ids = db_get_fields('SELECT object_id FROM ?:images_links WHERE object_type = \'abt__ut2/menu-with-icon\'');
}
if ($abt__ut2_mwi_icon_get == 'Y'
&& !empty($abt__ut2_mwi_icon_ids) && is_array($abt__ut2_mwi_icon_ids)
&& !empty($top_menu) && is_array($top_menu)) {
$ids = [];
foreach ($top_menu as $i => $m) {
if (in_array($i, $abt__ut2_mwi_icon_ids) && !empty($m['abt__ut2_mwi__status']) && $m['abt__ut2_mwi__status'] == 'Y') {
$ids[] = $i;
}
}
if (!empty($ids) && is_array($ids)) {
$images = fn_get_image_pairs($ids, 'abt__ut2/menu-with-icon', 'M', true, false);
foreach ($images as $i => $image) {
$img = array_shift($image);
$top_menu[$i]['abt__ut2_mwi__icon'] = $img['icon'][fn_get_storefront_protocol() . '_image_path'];
$top_menu[$i]['abt__ut2_mwi__icon_pair'] = $img;
}
}
}
}
function fn_abt__ut2_ajax_menu_save($data, $id, $lang_code = DESCR_SL)
{
static $init_cache = false;
$cache_name = 'abt__ut2_am';
$key = $id . '_' . $lang_code;
if (!$init_cache) {
$init_cache = true;
Registry::registerCache($cache_name, ['static_data', 'static_data_descriptions'], Registry::cacheLevel('static'), true);
}
Registry::set($cache_name . '.' . $key, $data);
}
function fn_abt__ut2_ajax_menu_get($key)
{
static $init_cache = false;
$cache_name = 'abt__ut2_am';
if (!$init_cache) {
$init_cache = true;
Registry::registerCache($cache_name, ['static_data', 'static_data_descriptions'], Registry::cacheLevel('static'), true);
}
static $data;
if (empty($data)) {
$data = Registry::get($cache_name);
}
return isset($data[$key]) ? $data[$key] : '';
}
