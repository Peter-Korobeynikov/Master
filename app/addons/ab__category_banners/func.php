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
use Tygh\Languages\Languages;
use Tygh\Registry;
foreach (glob(Registry::get('config.dir.addons') . '/ab__category_banners/ab__functions/*.php') as $functions) {
require_once $functions;
}
function fn_ab__update_category_banner($category_banner_data, $category_banner_id, $lang_code = DESCR_SL)
{
if (!empty($category_banner_data['from_date'])) {
$category_banner_data['from_date'] = fn_parse_date($category_banner_data['from_date']) + fn_ab__cb_parse_time($category_banner_data['from_time']);
}
if (!empty($category_banner_data['to_date'])) {
$category_banner_data['to_date'] = fn_parse_date($category_banner_data['to_date']) + fn_ab__cb_parse_time($category_banner_data['to_time']);
}
if (isset($category_banner_data['repeat'])) {
foreach ($category_banner_data['repeat'] as &$day) {
if (!empty($day['time_from'])) {
$day['time_from'] = fn_ab__cb_parse_time($day['time_from']);
}
if (!empty($day['time_to'])) {
$day['time_to'] = fn_ab__cb_parse_time($day['time_to']);
}
}
$category_banner_data['repeat'] = serialize($category_banner_data['repeat']);
}
if (!empty($category_banner_id)) {
db_query('UPDATE ?:ab__category_banners SET ?u WHERE category_banner_id = ?i', $category_banner_data, $category_banner_id);
$lang_codes = array($lang_code);
} else {
$category_banner_id = $category_banner_data['category_banner_id'] = db_query('REPLACE INTO ?:ab__category_banners ?e', $category_banner_data);
$lang_codes = array_keys(Languages::getAll());
}
if (!empty($category_banner_id) && !empty($lang_codes)) {
$prev_image_id = 0;
foreach ($lang_codes as $v) {
$category_banner_data['lang_code'] = $v;
$category_banner_image_id = db_get_field('SELECT category_banner_image_id FROM ?:ab__category_banner_images_and_descr WHERE category_banner_id = ?i AND lang_code = ?s', $category_banner_id, $v);
if (empty($category_banner_image_id)) {
$category_banner_image_id = db_query('INSERT INTO ?:ab__category_banner_images_and_descr ?e', $category_banner_data);
} else {
db_query('UPDATE ?:ab__category_banner_images_and_descr SET ?u WHERE category_banner_image_id = ?i', $category_banner_data, $category_banner_image_id);
}
if (empty($prev_image_id)) {
fn_attach_image_pairs('category_banners_main', 'category_banner', $category_banner_image_id, $v);
fn_attach_image_pairs('category_banners_list_image', 'category_banner', $category_banner_image_id, $v);
$prev_image_id = $category_banner_image_id;
} else {
fn_clone_image_pairs($category_banner_image_id, $prev_image_id, 'category_banner', $v);
}
}
if (!empty($category_banner_data['category_ids'])) {
db_query('DELETE FROM ?:ab__category_banner_categories WHERE category_banner_id = ?i', $category_banner_id);
foreach (explode(',', $category_banner_data['category_ids']) as $cid) {
db_query('INSERT INTO ?:ab__category_banner_categories ?e', array(
'category_banner_id' => $category_banner_id,
'category_id' => $cid,
));
}
}
}
return $category_banner_id;
}
function fn_ab__delete_category_banner($category_banner_id)
{
if (!empty($category_banner_id)) {
$category_banner_image_ids = db_get_fields('SELECT category_banner_image_id FROM ?:ab__category_banner_images_and_descr WHERE category_banner_id = ?i', $category_banner_id);
foreach ($category_banner_image_ids as $image_id) {
fn_delete_image_pairs($image_id, 'category_banner');
}
db_query('DELETE FROM ?:ab__category_banners WHERE category_banner_id = ?i', $category_banner_id);
db_query('DELETE FROM ?:ab__category_banner_categories WHERE category_banner_id = ?i', $category_banner_id);
db_query('DELETE FROM ?:ab__category_banner_images_and_descr WHERE category_banner_id = ?i', $category_banner_id);
return true;
}
return false;
}
function fn_ab__get_category_banners($params = array(), $items_per_page = 0, $lang_code = CART_LANGUAGE)
{
$default_params = array(
'page' => 1,
'items_per_page' => $items_per_page,
);
$params = array_merge($default_params, $params);
$sortings = array(
'name' => '?:ab__category_banner_images_and_descr.category_banner',
'status' => '?:ab__category_banners.status',
'to_date' => '?:ab__category_banners.to_date',
'from_date' => '?:ab__category_banners.from_date',
);
$sorting = db_sort($params, $sortings, 'name', 'asc');
$condition = $limit = $join = '';
if (AREA == 'C') {
$condition .= ' AND ?:ab__category_banners.status = \'A\' ';
$condition .= ' AND ?:ab__category_banner_images_and_descr.category_banner_image_id IS NOT NULL ';
}
if (!empty($params['item_ids'])) {
$condition .= db_quote(' AND ?:ab__category_banners.category_banner_id IN (?n)', explode(',', $params['item_ids']));
}
if (!empty($params['timestamp'])) {
$condition .= db_quote(' AND (IF(?:ab__category_banners.from_date, ?:ab__category_banners.from_date <= ?i, 1) AND IF(?:ab__category_banners.to_date, ?:ab__category_banners.to_date >= ?i, 1))', $params['timestamp'], $params['timestamp']);
}
if (!empty($params['cid'])) {
$join .= db_quote(' LEFT JOIN ?:ab__category_banner_categories AS ab__category_banner_categories ON ab__category_banner_categories.category_banner_id = ?:ab__category_banners.category_banner_id ');
$category_condition = db_quote('ab__category_banner_categories.category_id = ?i', $params['cid']);
$cur_id_path = db_get_field('SELECT id_path FROM ?:categories WHERE category_id = ?i', $params['cid']);
if (!empty($cur_id_path)) {
$parent_categories_ids = explode('/', $cur_id_path);
$join .= db_quote(' LEFT JOIN ?:ab__category_banner_categories AS ab__category_banner_categories1 ON ab__category_banner_categories1.category_banner_id = ?:ab__category_banners.category_banner_id');
$category_condition .= db_quote(' OR (ab__category_banner_categories1.category_id IN (?n) AND ?:ab__category_banners.include_subcategories = ?s)', $parent_categories_ids, 'Y');
}
$condition .= db_quote(' AND (?p)', $category_condition);
}
if (!empty($params['layout'])) {
$image_type = ($params['layout'] == 'products_multicolumns') ? 'M' : 'L';
$join .= db_quote(' INNER JOIN ?:images_links AS il ON il.object_type = ?s AND il.object_id = ?:ab__category_banner_images_and_descr.category_banner_image_id AND il.pair_id IS NOT NULL AND il.type = ?s', 'category_banner', $image_type);
}
$fields = array(
'?:ab__category_banners.category_banner_id',
'?:ab__category_banners.repeat',
'?:ab__category_banners.position',
'?:ab__category_banners.target_blank',
'?:ab__category_banners.include_subcategories',
'?:ab__category_banners.status',
'?:ab__category_banners.to_date',
'?:ab__category_banners.from_date',
'?:ab__category_banner_images_and_descr.category_banner',
'?:ab__category_banner_images_and_descr.url',
'?:ab__category_banner_images_and_descr.category_banner_image_id',
);
if (!empty($params['items_per_page'])) {
$params['total_items'] = db_get_field('SELECT COUNT(DISTINCT(?:ab__category_banners.category_banner_id)) FROM ?:ab__category_banners WHERE 1 ?p', $condition);
$limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
}
$category_banners = db_get_hash_array('SELECT ?p FROM ?:ab__category_banners
INNER JOIN ?:ab__category_banner_images_and_descr ON ?:ab__category_banner_images_and_descr.category_banner_id = ?:ab__category_banners.category_banner_id AND ?:ab__category_banner_images_and_descr.lang_code = ?s
' . $join . '
WHERE 1 ?p ?p ?p', 'category_banner_id', implode(', ', $fields), $lang_code, $condition, $sorting, $limit);
if (AREA == 'C' && !empty($params['layout'])) {
if ($params['layout'] == 'products_multicolumns') {
$pair_type = 'M';
$pair_var_name = 'main_pair';
} else {
$pair_type = 'L';
$pair_var_name = 'list_pair';
}
$images = fn_get_image_pairs(fn_array_column($category_banners, 'category_banner_image_id'), 'category_banner', $pair_type, true, false);
foreach ($category_banners as $category_banner_id => $data) {
if (!empty($images[$data['category_banner_image_id']])) {
$category_banners[$category_banner_id][$pair_var_name] = reset($images[$data['category_banner_image_id']]);
}
}
}
return array($category_banners, $params);
}
function fn_ab__get_category_banner_data($category_banner_id, $lang_code = CART_LANGUAGE)
{
$fields = array(
'?:ab__category_banners.category_banner_id',
'?:ab__category_banners.repeat',
'?:ab__category_banners.position',
'?:ab__category_banners.target_blank',
'?:ab__category_banners.include_subcategories',
'?:ab__category_banners.status',
'?:ab__category_banners.to_date',
'?:ab__category_banners.from_date',
'GROUP_CONCAT(?:ab__category_banner_categories.category_id) as category_ids',
'?:ab__category_banner_images_and_descr.category_banner',
'?:ab__category_banner_images_and_descr.url',
'?:ab__category_banner_images_and_descr.category_banner_image_id',
);
$join = db_quote('LEFT JOIN ?:ab__category_banner_images_and_descr ON ?:ab__category_banner_images_and_descr.category_banner_id = ?:ab__category_banners.category_banner_id AND ?:ab__category_banner_images_and_descr.lang_code = ?s', $lang_code);
$join .= 'LEFT JOIN ?:ab__category_banner_categories ON ?:ab__category_banner_categories.category_banner_id = ?:ab__category_banners.category_banner_id';
$condition = db_quote('?:ab__category_banners.category_banner_id = ?i', $category_banner_id);
$category_banner = db_get_row('SELECT ?p FROM ?:ab__category_banners ?p WHERE ?p', implode(',', $fields), $join, $condition);
if (empty($category_banner)) {
return false;
}
$category_banner['main_pair'] = fn_get_image_pairs($category_banner['category_banner_image_id'], 'category_banner', 'M', true, false, $lang_code);
$category_banner['list_pair'] = fn_get_image_pairs($category_banner['category_banner_image_id'], 'category_banner', 'L', true, false, $lang_code);
$category_banner['repeat'] = unserialize($category_banner['repeat']);
if (AREA == 'C') {
if (empty($category_banner['position'])) {
$category_banner['position'] = Registry::get('addons.ab__category_banners.item_nth');
}
}
return $category_banner;
}
function fn_ab__cb_pick_banner_by_cid($category_id, $layout = 'products_multicolumns')
{
list($category_banners) = fn_ab__get_category_banners(array(
'cid' => $category_id,
'timestamp' => time(),
'layout' => $layout,
));
$day = date('N', TIME);
$time = TIME - strtotime('midnight', TIME);
$proper_banners = [];
$map = [];
$default_position = Registry::get('addons.ab__category_banners.item_nth');
while (!empty($category_banners)) {
$category_banner = array_shift($category_banners);
$repeat = unserialize($category_banner['repeat']);
if (empty($category_banner['position'])) {
$category_banner['position'] = $default_position;
}
$category_banner['positions_array'] = fn_ab__cb_parse_position_to_array($category_banner['position']);
if ($repeat[$day]['active'] == 'Y'
&& (empty($repeat[$day]['time_from']) || $repeat[$day]['time_from'] <= $time)
&& (empty($repeat[$day]['time_to']) || $repeat[$day]['time_to'] >= $time)
&& !empty($category_banner['positions_array'])
) {
$proper_banners[$category_banner['category_banner_id']] = $category_banner;
foreach ($category_banner['positions_array'] as $position) {
$map[$position][] = $category_banner['category_banner_id'];
}
}
}
$combinations = [];
$hashes_list = [];
$total_steps = count($map);
for ($i = 0; $i < $total_steps; $i++) {
$combinations[$i] = [];
$hashes_list[$i] = '';
foreach ($map as $position => $banners_ids) {
foreach ($banners_ids as $banner_id) {
if (in_array($banner_id, $combinations[$i]) || (!empty($combinations[$i-1][$position]) && $combinations[$i-1][$position] == $banner_id)) {
continue;
}
$hashes_list[$i] = isset($hashes_list[$i]) ? $hashes_list[$i] : '';
$new_hash = $hashes_list[$i] . '|' . $position . '_' . $banner_id;
if (in_array($new_hash, $hashes_list)) {
continue;
}
$combinations[$i][$position] = $banner_id;
$hashes_list[$i] = $new_hash;
break;
}
}
$key = key($map);
$val = $map[$key];
unset($map[$key]);
$map[$key] = $val;
}
if (!empty($hashes_list)) {
if (empty($_SESSION['ab__category_banners']['last_hash_by_cid'][$category_id])) {
$_SESSION['ab__category_banners']['last_hash_by_cid'][$category_id] = '';
}
$last_hash = &$_SESSION['ab__category_banners']['last_hash_by_cid'][$category_id];
if (!empty($last_hash) && count($hashes_list) > 1 && in_array($last_hash, $hashes_list) && $last_hash != end($hashes_list)) {
$key = array_search($last_hash, $hashes_list) + 1;
$last_hash = $hashes_list[$key];
} else {
$key = 0;
$last_hash = reset($hashes_list);
}
$combination = $combinations[$key];
ksort($combination);
foreach ($combination as $position => $banner_id) {
$combination[$position] = $proper_banners[$banner_id];
}
Registry::set('ab__category_banners.combination', $combination);
Registry::set('ab__category_banners.hash', $last_hash);
}
}
function fn_ab__cb_insert_category_banner(&$html, $layout = 'products_multicolumns')
{
if (!empty($html) && !empty($_REQUEST['category_id'])) {
$combination = Registry::get('ab__category_banners.combination');
$pattern = ($layout == 'products_multicolumns') ? '@\"(?:ty-)?column(\d)[^"]*\"@' : '@\"ty-product-list[^"]*\"@';
if (preg_match($pattern, $html, $match)) {
$item_class = trim($match[0], '\'"');
if (!empty($match[1])) {
$html = preg_replace("/<div class={$match[0]}>[\s]*<\/div>/", '', $html);
}
$doc = new DOMDocument();
$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
$xpath = new DomXPath($doc);
$products_nodes = $xpath->query('/'."/div[@class={$match[0]}]");
if (empty($products_nodes->length)) {
return;
}
$parent_node = $products_nodes->item(0)->parentNode;
foreach ($combination as $position => $category_banner) {
$products_nodes = $xpath->query('/'."/div[contains(@class, {$match[0]})]");
$count_items = $products_nodes->length;
if ($position <= ($count_items + 1)) {
$target_node = $products_nodes->item($position - 1);
if ($position > $count_items && $layout === 'products_without_options') {
$target_node = $products_nodes->item($position - 2)->nextSibling;
Tygh::$app['view']->assign('move_hr_to_the_top', true);
}
Tygh::$app['view']->assign('category_banner', $category_banner);
Tygh::$app['view']->assign('layout', $layout);
Tygh::$app['view']->assign('item_class', $item_class);
$banner_html = mb_convert_encoding(Tygh::$app['view']->fetch('addons/ab__category_banners/components/category_banner.tpl'), 'HTML-ENTITIES', 'UTF-8');
$banner_node = new DOMDocument();
$banner_node->loadHTML($banner_html);
$parent_node->insertBefore($doc->importNode($banner_node->documentElement, true), $target_node);
$count_items++;
}
}
if (!empty($match[1])) {
$empty_items = $match[1] - ($count_items % $match[1]);
if ($empty_items < $match[1]) {
for ($i = 0; $i < $empty_items; $i++) {
$div = $doc->createElement('div');
$div->setAttribute('class', $item_class);
$parent_node->appendChild($div);
}
}
}
$html = $doc->saveHTML();
}
}
}
function fn_ab__cb_get_cache_key()
{
$hash = Registry::get('ab__category_banners.hash');
return empty($hash) ? '' : $hash;
}
function fn_ab__cb_change_status($category_banner_ids, $status)
{
if (empty($category_banner_ids)) {
return false;
}
db_query('UPDATE ?:ab__category_banners SET status = ?s WHERE category_banner_id IN (?n)', $status, $category_banner_ids);
return true;
}
function fn_ab__cb_parse_position_to_array($position_string)
{
$positions = array();
foreach (explode(',', $position_string) as $item) {
if (strpos($item, '-') !== false) {
list($from, $to) = explode('-', $item);
$positions = array_merge($positions, range(trim($from), trim($to)));
} else {
$positions[] = trim($item);
}
}
$positions = array_unique($positions);
$positions = array_filter($positions, function($i) {
return is_numeric($i);
});
return $positions;
}