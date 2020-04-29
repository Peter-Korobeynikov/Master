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
* This is commercial software, only users who have purchased a valid license and accept    *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
use Tygh\Registry;
if (!defined('BOOTSTRAP')) { die('Access denied'); }
function fn_ab__search_motivation_update_category_post($category_data, $category_id, $lang_code)
{
if (isset($category_data['search_phrases']) && fn_check_view_permissions('ab__search_motivation.update','POST')) {
$company_id = fn_allowed_for('ULTIMATE') ? $category_data['company_id'] : 0;
fn_ab__search_motivation_update_phrases($category_data['search_phrases'], $company_id, $category_id, $lang_code);
}
}
function fn_ab__search_motivation_get_category_data_post($category_id, $field_list, $get_main_pair, $skip_company_condition, $lang_code, &$category_data)
{
$category_data['search_phrases'] = db_get_field('SELECT search_phrases FROM ?:ab__search_motivation WHERE category_id = ?i AND lang_code = ?s', $category_id, $lang_code);
}
function fn_ab__search_motivation_delete_category_after($category_id)
{
db_query('DELETE FROM ?:ab__search_motivation WHERE category_id = ?i', $category_id);
}
function fn_ab__search_motivation_get_phrases_by_cid($category_id, $lang_code = CART_LANGUAGE)
{
$search_phrases = '';
$category_data = db_get_row('SELECT c.parent_id, sm.search_phrases
FROM ?:categories AS c
LEFT JOIN ?:ab__search_motivation AS sm ON sm.category_id = c.category_id AND sm.lang_code = ?s
WHERE c.category_id = ?i', $lang_code, $category_id);
if (!empty($category_data['search_phrases'])) {
$search_phrases = $category_data['search_phrases'];
} elseif (empty($category_data['search_phrases']) && !empty($category_data['parent_id'])) {
$search_phrases = fn_ab__search_motivation_get_phrases_by_cid($category_data['parent_id']);
}
return $search_phrases;
}
function fn_ab__search_motivation_update_phrases($search_phrases, $company_id = 0, $category_id = 0, $lang_code = CART_LANGUAGE)
{
db_query('REPLACE INTO ?:ab__search_motivation ?e', array(
'category_id' => $category_id,
'company_id' => $company_id,
'lang_code' => $lang_code,
'search_phrases' => $search_phrases
));
}
function fn_ab__search_motivation_get_default_phrases($lang_code = CART_LANGUAGE)
{
$company_id = fn_get_runtime_company_id();
$search_phrases = db_get_field('SELECT search_phrases FROM ?:ab__search_motivation WHERE category_id = 0 AND company_id = ?i AND lang_code = ?s AND TRIM(search_phrases) > ""', $company_id, $lang_code);
return $search_phrases;
}
function fn_ab__search_motivation_get_all_phrases($lang_code = CART_LANGUAGE)
{
$company_id = fn_get_runtime_company_id();
$search_phrases = db_get_fields('SELECT search_phrases FROM ?:ab__search_motivation WHERE category_id > 0 AND company_id = ?i AND lang_code = ?s AND TRIM(search_phrases) > ""', $company_id, $lang_code);
return empty($search_phrases) ? '' : implode("\n", $search_phrases);
}
function fn_ab__search_motivation_get_phrases()
{
$controller = Registry::get('runtime.controller');
$mode = Registry::get('runtime.mode');
if ($controller == 'categories' && $mode == 'view' && Registry::get('addons.ab__search_motivation.show_on_category_page') == 'Y') {
$category = Tygh::$app['view']->getTemplateVars('category_data');
$search_phrases = fn_ab__search_motivation_get_phrases_by_cid($category['category_id']);
} elseif ($controller == 'products' && $mode == 'view' && Registry::get('addons.ab__search_motivation.show_on_product_page') == 'Y') {
$product = Tygh::$app['view']->getTemplateVars('product');
$search_phrases = fn_ab__search_motivation_get_phrases_by_cid($product['main_category']);
} elseif (($controller == 'index' && $mode == 'index' && Registry::get('addons.ab__search_motivation.show_on_homepage') == 'Y') ||
($controller == 'checkout' && $mode == 'cart' && Registry::get('addons.ab__search_motivation.show_on_cart_page') == 'Y') ||
($controller == 'checkout' && $mode == 'checkout' && Registry::get('addons.ab__search_motivation.show_on_checkout_page') == 'Y')) {
$search_phrases = fn_ab__search_motivation_get_all_phrases();
}
if (isset($search_phrases) && empty($search_phrases)) {
$search_phrases = fn_ab__search_motivation_get_default_phrases();
}
if (empty($search_phrases)) {
return false;
}
$search_phrases = explode("\n", $search_phrases);
if (Registry::get('addons.ab__search_motivation.shuffle') == 'Y') {
shuffle($search_phrases);
}
return array_slice($search_phrases, 0, 10);
}