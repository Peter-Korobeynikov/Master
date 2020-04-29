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
use Tygh\Navigation\LastView;
use Tygh\Enum\Addons\Ab_motivationBlock\ObjectTypes as Ab_objectTypes;
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
define('AB__MB_DATA_PATH', Registry::get('config.dir.var') . 'ab__data/ab__motivation_block/');
foreach (glob(Registry::get('config.dir.addons') . 'ab__motivation_block/functions/ab__mb.*.php') as $functions) {
require_once $functions;
}
function fn_ab__mb_install()
{
$objects = [
['t' => '?:ab__mb_motivation_items',
'i' => [
['n' => 'exclude_categories', 'p' => 'char(1) NOT NULL default \'N\''],
['n' => 'exclude_destinations', 'p' => 'char(1) NOT NULL default \'N\''],
['n' => 'template_path', 'p' => 'text CHARACTER SET utf8'],
['n' => 'template_settings', 'p' => 'text CHARACTER SET utf8 NOT NULL'],
],
],
];
if (!empty($objects) && is_array($objects)) {
foreach ($objects as $o) {
$fields = db_get_fields('DESCRIBE ' . $o['t']);
if (!empty($fields) && is_array($fields)) {
if (!empty($o['i']) && is_array($o['i'])) {
foreach ($o['i'] as $f) {
if (!in_array($f['n'], $fields)) {
db_query('ALTER TABLE ?p ADD ?p ?p', $o['t'], $f['n'], $f['p']);
if (!empty($f['add_sql']) && is_array($f['add_sql'])) {
foreach ($f['add_sql'] as $sql) {
db_query($sql);
}
}
}
}
}
}
}
}
fn_ab__mb_migration_from_v220_to_v230();
fn_ab__mb_migration_from_v230_to_v240();
fn_ab__mb_refresh_icons();
}
function fn_ab__mb_update_motivation_item($motivation_item_data, $motivation_item_id, $lang_code = DESCR_SL, $company_id = 0)
{
$exists = db_get_field('SELECT motivation_item_id FROM ?:ab__mb_motivation_items WHERE motivation_item_id = ?i AND company_id = ?i', $motivation_item_id, $company_id);

fn_set_hook('ab__mb_update_motivation_item_pre', $motivation_item_data, $motivation_item_id, $lang_code, $company_id, $exists);
if (!empty($motivation_item_data['template_settings'])) {
$motivation_item_data['template_settings'] = serialize($motivation_item_data['template_settings']);
}
if (empty($exists)) {
$motivation_item_data['company_id'] = fn_allowed_for('ULTIMATE') ? fn_get_runtime_company_id() : $company_id;
$motivation_item_data['motivation_item_id'] = $motivation_item_id = db_query('INSERT INTO ?:ab__mb_motivation_items ?e', $motivation_item_data);
foreach (Languages::getAll() as $motivation_item_data['lang_code'] => $v) {
db_query('INSERT INTO ?:ab__mb_motivation_item_descriptions ?e', $motivation_item_data);
}
} else {
db_query('UPDATE ?:ab__mb_motivation_items SET ?u WHERE motivation_item_id = ?i AND company_id = ?i', $motivation_item_data, $motivation_item_id, $company_id);
db_query('UPDATE ?:ab__mb_motivation_item_descriptions SET ?u WHERE motivation_item_id = ?i AND lang_code = ?s', $motivation_item_data, $motivation_item_id, $lang_code);
db_query('DELETE FROM ?:ab__mb_motivation_item_objects WHERE motivation_item_id = ?i', $motivation_item_id);
}
$categories_arr = ab__mb_update_prepare_objects_array($motivation_item_id, $motivation_item_data, Ab_objectTypes::CATEGORY);
$destinations_arr = ab__mb_update_prepare_objects_array($motivation_item_id, $motivation_item_data, Ab_objectTypes::DESTINATION);
db_query('INSERT INTO ?:ab__mb_motivation_item_objects ?m', array_merge($categories_arr, $destinations_arr));
if (fn_allowed_for('ULTIMATE') || $company_id == 0) {
fn_attach_image_pairs('ab__mb_img', 'motivation_item', $motivation_item_id);
}
return $motivation_item_id;
}
function ab__mb_update_prepare_objects_array($motivation_item_id, $motivation_item_data, $object_type = Ab_objectTypes::CATEGORY)
{
$return_arr = [];
$_tmp = 'categories';
if ($object_type == Ab_objectTypes::DESTINATION) {
$_tmp = 'destinations';
}
if (!empty($motivation_item_data["{$_tmp}_ids"])) {
foreach (explode(',', $motivation_item_data["{$_tmp}_ids"]) as $obj_id) {
$return_arr[] = [
'motivation_item_id' => $motivation_item_id,
'object_id' => $obj_id,
'object_type' => $object_type,
];
}
} else {
$return_arr[] = [
'motivation_item_id' => $motivation_item_id,
'object_id' => 0,
'object_type' => $object_type,
];
}
return $return_arr;
}
function fn_ab__mb_delete_motivation_item($motivation_item_id)
{
if (!empty($motivation_item_id)) {
db_query('DELETE FROM ?:ab__mb_motivation_items WHERE motivation_item_id = ?i', $motivation_item_id);
db_query('DELETE FROM ?:ab__mb_motivation_item_descriptions WHERE motivation_item_id = ?i', $motivation_item_id);
db_query('DELETE FROM ?:ab__mb_motivation_item_objects WHERE motivation_item_id = ?i', $motivation_item_id);
return true;
}
return false;
}
function fn_ab__mb_get_motivation_items($params = [], $items_per_page = 0, $area = AREA, $lang_code = CART_LANGUAGE)
{
$params = LastView::instance()->update('ab__motivation_items', $params);
$default_params = [
'page' => 1,
'items_per_page' => $items_per_page,
'area' => $area,
'sort_by' => 'position',
'sort_order' => 'asc',
];
$params = array_merge($default_params, $params);
$sortings = [
'name' => '?:ab__mb_motivation_item_descriptions.name',
'status' => '?:ab__mb_motivation_items.status',
'position' => '?:ab__mb_motivation_items.position',
];
$sorting = db_sort($params, $sortings, $params['sort_by'], $params['sort_order']);
$fields = [
'?:ab__mb_motivation_items.*',
'?:ab__mb_motivation_item_descriptions.name',
'?:ab__mb_motivation_item_descriptions.description',
'?:ab__mb_motivation_item_descriptions.lang_code',
];
$group_by = [
'?:ab__mb_motivation_items.motivation_item_id',
];
$condition = $limit = $join = '';
if ($params['area'] == 'C') {
$condition .= db_quote(' AND ?:ab__mb_motivation_items.status = ?s', 'A');
$params['destination_id'] = Tygh::$app['location']->getDestinationId();
$templates_schema = fn_get_schema('ab__mb', 'item_templates');
}
if (!empty($params['item_ids'])) {
$condition .= db_quote(' AND ?:ab__mb_motivation_items.motivation_item_id IN (?n)', explode(',', $params['item_ids']));
}
if (!empty($params['vendor_edit'])) {
$condition .= db_quote(' AND ?:ab__mb_motivation_items.vendor_edit = ?s', 'Y');
}
if (!empty($params['destination_id'])) {
$join .= db_quote('LEFT JOIN ?:ab__mb_motivation_item_objects as destination_objects_not_exclude
ON ?:ab__mb_motivation_items.motivation_item_id = destination_objects_not_exclude.motivation_item_id
AND destination_objects_not_exclude.object_type = ?s
AND ?:ab__mb_motivation_items.exclude_destinations = "N"', Ab_objectTypes::DESTINATION, [0, $params['destination_id']]);
$join .= db_quote('LEFT JOIN ?:ab__mb_motivation_item_objects as destination_objects_exclude
ON ?:ab__mb_motivation_items.motivation_item_id = destination_objects_exclude.motivation_item_id
AND destination_objects_exclude.object_type = ?s
AND destination_objects_exclude.object_id IN (?n)
AND ?:ab__mb_motivation_items.exclude_destinations = "Y"', Ab_objectTypes::DESTINATION, [$params['destination_id']]);
$condition .= db_quote(' AND ((destination_objects_not_exclude.object_id IN (?n) OR destination_objects_not_exclude.object_id IS NULL) AND (destination_objects_exclude.motivation_item_id IS NULL))', [0, $params['destination_id']]);
}
if (!empty($params['category_ids'])) {
$join .= db_quote('LEFT JOIN ?:ab__mb_motivation_item_objects as category_objects_not_exclude
ON ?:ab__mb_motivation_items.motivation_item_id = category_objects_not_exclude.motivation_item_id
AND category_objects_not_exclude.object_type = ?s
AND ?:ab__mb_motivation_items.exclude_categories = "N"', Ab_objectTypes::CATEGORY);
$join .= db_quote('LEFT JOIN ?:ab__mb_motivation_item_objects as category_objects_exclude
ON ?:ab__mb_motivation_items.motivation_item_id = category_objects_exclude.motivation_item_id
AND category_objects_exclude.object_type = ?s
AND category_objects_exclude.object_id IN (?n)
AND ?:ab__mb_motivation_items.exclude_categories = "Y"', Ab_objectTypes::CATEGORY, $params['category_ids']);
$condition .= db_quote(' AND ((category_objects_not_exclude.object_id IN (?n) OR category_objects_not_exclude.object_id IS NULL) AND (category_objects_exclude.motivation_item_id IS NULL))', array_merge([0], $params['category_ids']));
}
if (isset($params['ab__mb_template']) && $params['ab__mb_template'] != 'ignore') {
$condition .= db_quote('AND ?:ab__mb_motivation_items.template_path = ?s', $params['ab__mb_template']);
if ($params['ab__mb_template'] == '') {
$condition .= 'OR ?:ab__mb_motivation_items.template_path IS NULL';
}
}
if (isset($params['name']) && fn_string_not_empty($params['name'])) {
$condition .= db_quote(' AND ?:ab__mb_motivation_item_descriptions.name LIKE ?l', '%' . trim($params['name']) . '%');
}
if (fn_allowed_for('ULTIMATE')) {
$condition .= fn_get_company_condition('?:ab__mb_motivation_items.company_id');
} elseif (!empty($params['company_id'])) {
$fields[] = 'vd.description AS vendor_description';
$fields[] = 'vd.status as vendor_status';
$join .= db_quote(' LEFT JOIN ?:ab__mb_vendors_descriptions AS vd ON vd.motivation_item_id = ?:ab__mb_motivation_items.motivation_item_id AND vd.company_id = ?i AND vd.lang_code = ?s', $params['company_id'], $lang_code);
}
$join .= db_quote(' INNER JOIN ?:ab__mb_motivation_item_descriptions
ON ?:ab__mb_motivation_item_descriptions.motivation_item_id = ?:ab__mb_motivation_items.motivation_item_id
AND ?:ab__mb_motivation_item_descriptions.lang_code = ?s', $lang_code);
if (!empty($params['items_per_page'])) {
$params['total_items'] = db_get_field('SELECT COUNT(DISTINCT(?:ab__mb_motivation_items.motivation_item_id)) FROM ?:ab__mb_motivation_items ?p WHERE 1 ?p', $join, $condition);
$limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
}

fn_set_hook('ab__mb_get_motivation_items_before_select', $params, $fields, $join, $condition, $group_by, $lang_code);
$motivation_items = db_get_hash_array('SELECT ?p FROM ?:ab__mb_motivation_items ?p WHERE 1 ?p GROUP BY ?p ?p ?p', 'motivation_item_id', implode(', ', $fields), $join, $condition, implode(', ', $group_by), $sorting, $limit);
if (empty($motivation_items)) {
return [[], $params];
}
$item_ids = array_keys($motivation_items);
$image_pairs = fn_get_image_pairs($item_ids, 'motivation_item', 'M');
foreach ($image_pairs as $key => $image) {
$motivation_items[$key]['main_pair'] = reset($image);
}
$motivation_items_objects = fn_ab__mb_get_mativation_items_objects($item_ids, Ab_objectTypes::getAll());
$templates_assign = [];
foreach ($motivation_items as $item_id => &$item) {
if (fn_allowed_for('MULTIVENDOR')) {
if (!empty($item['vendor_description'])) {
if ($params['area'] == 'C' && $item['vendor_status'] == 'D') {
unset($motivation_items[$item_id]);
}
$item['description'] = $item['vendor_description'];
}
}
if ($params['area'] == 'C') {
if (!empty($item['template_path']) && !empty($templates_schema[$item['template_path']])) {
$templates_assign[$templates_schema[$item['template_path']]['view_var_name']] = true;
}
if (!empty($item['template_settings'])) {
$item['template_settings'] = unserialize($item['template_settings']);
}
} else {
foreach (Ab_objectTypes::getAll() as $type) {
$_tmp = 'categories';
if ($type == 'D') {
$_tmp = 'destinations';
}
$item["{$_tmp}_ids"] = $motivation_items_objects[$item_id][$type]['object_ids'];
}
$categories = fn_get_categories_list($item['categories_ids']);
$item['categories'] = implode(', ', $categories);
$destinations = fn_ab__mb_get_destinations_list($item['destinations_ids']);
$item['destinations'] = implode(', ', $destinations);
}
}
if ($params['area'] == 'C') {
Tygh::$app['view']->assign('ab__mb_viewed_templates', $templates_assign);
}
return [$motivation_items, $params];
}
function fn_ab__mb_get_motivation_item_data($motivation_item_id, $lang_code = CART_LANGUAGE)
{
$fields = [
'?:ab__mb_motivation_items.*',
'?:ab__mb_motivation_item_descriptions.name',
'?:ab__mb_motivation_item_descriptions.description',
];
$join = db_quote('LEFT JOIN ?:ab__mb_motivation_item_descriptions
ON ?:ab__mb_motivation_item_descriptions.motivation_item_id = ?:ab__mb_motivation_items.motivation_item_id
AND ?:ab__mb_motivation_item_descriptions.lang_code = ?s', $lang_code);
$condition = db_quote('?:ab__mb_motivation_items.motivation_item_id = ?i', $motivation_item_id);
if (fn_allowed_for('ULTIMATE')) {
$condition .= fn_get_company_condition('?:ab__mb_motivation_items.company_id');
} else {
$condition .= db_quote(' AND ?:ab__mb_motivation_items.company_id = ?i', 0);
}

fn_set_hook('ab__mb_get_motivation_item_data_before_select', $fields, $join, $condition, $motivation_item_id, $lang_code);
$motivation_item = db_get_row('SELECT ?p FROM ?:ab__mb_motivation_items ?p WHERE ?p', implode(',', $fields), $join, $condition);
if (empty($motivation_item)) {
return false;
}
$objects = fn_ab__mb_get_mativation_items_objects([$motivation_item_id], Ab_objectTypes::getAll())[$motivation_item_id];
if (!empty($objects[Ab_objectTypes::DESTINATION])) {
$motivation_item['destinations_ids'] = $objects[Ab_objectTypes::DESTINATION]['object_ids'];
}
if (!empty($objects[Ab_objectTypes::CATEGORY])) {
$motivation_item['categories_ids'] = $objects[Ab_objectTypes::CATEGORY]['object_ids'];
}
$motivation_item['main_pair'] = fn_get_image_pairs($motivation_item['motivation_item_id'], 'motivation_item', 'M');
if (!empty($motivation_item['template_settings'])) {
$motivation_item['template_settings'] = unserialize($motivation_item['template_settings']);
}
return $motivation_item;
}
function fn_ab__mb_get_mativation_items_objects($motivation_item_ids, $types)
{
$fields = [
'?:ab__mb_motivation_item_objects.motivation_item_id',
'?:ab__mb_motivation_item_objects.object_type',
'GROUP_CONCAT(?:ab__mb_motivation_item_objects.object_id) as object_ids',
];
$join = '';
$condition = db_quote('?:ab__mb_motivation_item_objects.motivation_item_id IN (?n) AND ?:ab__mb_motivation_item_objects.object_type IN (?a)', $motivation_item_ids, $types);
$group_by_fields = [
'?:ab__mb_motivation_item_objects.motivation_item_id',
'?:ab__mb_motivation_item_objects.object_type',
];
$group_by = db_quote('GROUP BY ?p', implode(', ', $group_by_fields));
return db_get_hash_multi_array('SELECT ?p FROM ?:ab__mb_motivation_item_objects ?p WHERE 1 AND ?p ?p', ['motivation_item_id', 'object_type'], implode(', ', $fields), $join, $condition, $group_by);
}
function fn_ab__mb_update_by_vendor($motivation_item_data, $motivation_item_id, $lang_code, $company_id)
{
if (empty($motivation_item_data) || empty($motivation_item_data['description'])) {
return false;
}
db_replace_into('ab__mb_vendors_descriptions', [
'motivation_item_id' => $motivation_item_id,
'company_id' => $company_id,
'lang_code' => $lang_code,
'description' => $motivation_item_data['description'],
'status' => $motivation_item_data['status'],
]);
}
function fn_ab__motivation_block_install_blocks($status = 'A')
{
$path = AB__MB_DATA_PATH . 'blocks/';
$company_id = fn_get_runtime_company_id();
$storefront = Tygh::$app['storefront'];
$theme_name = $storefront->theme_name;
if (!is_file($path . $theme_name . '.json')) {
$theme_name = 'responsive';
}
$data = json_decode(fn_get_contents($path . $theme_name . '.json'), true);
if (!empty($data)) {
$notifications = [];
$langs = Languages::getAll();
$is_ru = in_array('ru', array_keys($langs));
foreach ($data as $block) {
$block['status'] = !empty($block['status']) ? $block['status'] : $status;
if (!empty($block['template_path'])) {
if (strpos($block['template_path'], 'product_categories_list.tpl') !== false) {
$block['template_settings'] = [
'brand_feature_id' => fn_ab__mb_get_default_brand_setting_id(),
];
}
}
$block_id = fn_ab__mb_update_motivation_item($block, 0, CART_LANGUAGE, $company_id);
if ($is_ru) {
fn_ab__mb_update_motivation_item($block['ru'], $block_id, 'ru', $company_id);
}
$notifications[] = '<a href="' . fn_url('ab__motivation_block.update&motivation_item_id=' . $block_id) . '">' . (CART_LANGUAGE == 'ru' ? $block['ru']['name'] : $block['name']) . '</a>';
}
fn_set_notification('N', __('notice'), __('ab__mb.demodata.successes.blocks', ['[blocks]' => implode(', ', $notifications)]));
return $notifications;
}
fn_set_notification('E', __('error'), __('ab__mb.demodata.errors.no_data'));
return false;
}
function fn_ab__motivation_block_prepare_block_to_cloning($block = [])
{
unset($block['motivation_item_id']);
$block['status'] = 'D';
$block['name'] .= ' [CLONE]';
return $block;
}
function fn_ab__mb_clone_element($item_id)
{
$old_data = fn_ab__mb_get_motivation_item_data($item_id);
if (empty($old_data)) {
return false;
}
$company_id = fn_get_runtime_company_id();
$old_data = fn_ab__motivation_block_prepare_block_to_cloning($old_data);
$new_id = fn_ab__mb_update_motivation_item($old_data, 0, CART_LANGUAGE, $company_id);
foreach (array_keys(Languages::getAll()) as $lang_code) {
if ($lang_code != CART_LANGUAGE) {
$temp = fn_ab__motivation_block_prepare_block_to_cloning(fn_ab__mb_get_motivation_item_data($item_id, $lang_code));
fn_ab__mb_update_motivation_item($temp, $new_id, $lang_code, $company_id);
}
}
return $new_id;
}
function fn_ab__mb_change_element_status($elements, $status)
{
db_query('UPDATE ?:ab__mb_motivation_items SET status = ?s WHERE motivation_item_id in (?n)', $status, (array) $elements);
}
function fn_ab__mb_get_destinations_list($destinations_ids, $lang_code = CART_LANGUAGE)
{
static $max_destinations = 10;
$d_names = [];
if (!empty($destinations_ids)) {
$d_ids = fn_explode(',', $destinations_ids);
$tr_d_ids = array_slice($d_ids, 0, $max_destinations);
foreach ($tr_d_ids as $tr_d_id) {
$d_names[] = fn_get_destination_name($tr_d_id, $lang_code);
}
if (sizeof($tr_d_ids) < sizeof($d_ids)) {
$d_names[] = '... (' . sizeof($d_ids) . ')';
}
} else {
$d_names[] = __('ab__mb_all_destinations');
}
return $d_names;
}
function fn_ab__mb_get_templates_array()
{
$templates_schema = fn_get_schema('ab__mb', 'item_templates');
$templates_arr = [];
foreach ($templates_schema as $path => $info) {
$disabled = false;
if (!empty($info['conditions'])) {
$conds_arr = $info['conditions'];
if (!empty($conds_arr['active_addons'])) {
foreach ($conds_arr['active_addons'] as $addon_name) {
if (empty(Registry::get("addons.{$addon_name}")) || Registry::get("addons.{$addon_name}.status") !== 'A') {
$disabled = true;
}
}
}
if (!empty($conds_arr['addons_settings'])) {
foreach ($conds_arr['addons_settings'] as $addon_name => $addon_settings) {
foreach ($addon_settings as $setting_name => $value) {
if (Registry::get("addons.{$addon_name}.{$setting_name}") !== $value) {
$disabled = true;
}
}
}
}
if (!empty($conds_arr['functions'])) {
foreach ($conds_arr['functions'] as $function) {
if (function_exists($function) && call_user_func($function) !== true) {
$disabled = true;
}
}
}
}
$templates_arr[] = [
'template_path' => $path,
'template_name' => $info['name'],
'tooltip' => !empty($info['tooltip']) ? $info['tooltip'] : '',
'disabled' => $disabled,
'settings' => isset($info['settings']) ? $info['settings'] : false,
];
}
return $templates_arr;
}
