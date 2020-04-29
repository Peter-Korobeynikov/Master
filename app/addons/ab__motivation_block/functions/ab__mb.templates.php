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
function fn_ab__mb_disabled_option_choosen($field_name = 'template')
{
fn_set_notification('W', __('warning'), __('ab__mb.errors.disabled_option_choosen', ['[field]' => __($field_name)]));
}
function fn_ab__mb_templates_categories_list_available()
{
return db_get_field('SELECT COUNT(*) FROM ?:categories') > 1 ? true : false;
}
function fn_ab__mb_get_default_brand_setting_id() {
$feature_id = 0;
$storefront = Tygh::$app['storefront'];
$theme_name = $storefront->theme_name;
if (strpos($theme_name, 'abt__unitheme2') !== false) {
$feature_id = fn_ab__mb_get_brand_feature_tmp('abt__ut2.general.brand_feature_id');
} elseif (strpos($theme_name, 'abt__youpitheme') !== false) {
$feature_id = fn_ab__mb_get_brand_feature_tmp('abt__yt.general.brand_feature_id');
}
return $feature_id ? $feature_id : db_get_field("SELECT feature_id FROM ?:product_features WHERE status = 'A' AND feature_type = 'E' AND company_id = ?i", fn_get_runtime_company_id());
}
function fn_ab__mb_get_brand_feature_tmp($setting_name) {
return Registry::ifGet("settings.$setting_name", 18);
}
function fn_ab__mb_get_brand_feature($params) {
$header_feature = [];
if (!empty($params['product']['header_features'][$params['feature_id']])) {
$header_feature = $params['product']['header_features'][$params['feature_id']];
}
if (empty($header_feature)) {
$header_feature = db_get_array('SELECT variant_descriptions.variant, v.variant_id, f.filter_id FROM ?:product_features_values AS v
LEFT JOIN ?:product_feature_variant_descriptions AS variant_descriptions ON variant_descriptions.variant_id = v.variant_id
LEFT JOIN ?:product_filters AS f ON f.feature_id = v.feature_id
WHERE v.product_id = ?i
AND v.feature_id = ?i
AND f.feature_id = ?i
AND variant_descriptions.lang_code = ?s
AND v.lang_code = ?s', $params['product']['product_id'], $params['feature_id'], $params['feature_id'], CART_LANGUAGE, CART_LANGUAGE);
if (!empty($header_feature[0])) {
$header_feature = $header_feature[0];
$header_feature['features_hash'] = "{$header_feature['filter_id']}-{$header_feature['variant_id']}";
}
}
return $header_feature;
}
function fn_ab__mb_get_tags($add_params = []) {
$tags = [];
if (function_exists('fn_get_tags')) {
$params = array_merge([
'status' => ['A'],
'object_type' => 'P',
], $add_params);
list($tags) = fn_get_tags($params);
}
return $tags;
}
function fn_ab__mb_templates_tags_exists() {
$tags = fn_ab__mb_get_tags();
return boolval($tags);
}