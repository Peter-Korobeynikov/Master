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
function fn_abt__ut2_bt_get_chains($params = [], $items_per_page = 0, $lang_code = CART_LANGUAGE)
{
$default_params = [
'page' => 1,
'items_per_page' => $items_per_page,
'match' => '',
];
$params = array_merge($default_params, $params);
$sortings = [
'name' => '?:buy_together_descriptions.name',
'status' => '?:buy_together.status',
];
$sorting = db_sort($params, $sortings, 'name', 'asc');
$condition = $limit = $join = '';
$fields = [
'?:buy_together.chain_id',
'?:buy_together.status',
'?:buy_together_descriptions.name',
];
if (isset($params['q']) && fn_string_not_empty($params['q'])) {
$params['q'] = trim($params['q']);
if ($params['match'] == 'any') {
$pieces = fn_explode(' ', $params['q']);
$search_type = ' OR ';
} elseif ($params['match'] == 'all') {
$pieces = fn_explode(' ', $params['q']);
$search_type = ' AND ';
} else {
$pieces = [$params['q']];
$search_type = '';
}
$_condition = [];
foreach ($pieces as $piece) {
if (strlen($piece) == 0) {
continue;
}
$_condition[] = db_quote('?:buy_together_descriptions.name LIKE ?l', "%$piece%");
}
if (!empty($_condition)) {
$condition .= ' AND (' . implode($search_type, $_condition) . ')';
}
}
if (!empty($params['base_product_id'])) {
$condition .= db_quote(' AND ?:buy_together.product_id = ?i', $params['base_product_id']);
}
if (!empty($params['additional_product_id'])) {
$tmp = 's:10:"product_id";s:' . strlen($params['additional_product_id']) . ':"' . $params['additional_product_id'] . '"';
$condition .= db_quote(' AND ?:buy_together.products LIKE ?l', "%$tmp%");
}
if (!empty($params['items_per_page'])) {
$params['total_items'] = db_get_field('SELECT COUNT(DISTINCT(?:buy_together.chain_id)) FROM ?:buy_together
LEFT JOIN ?:buy_together_descriptions ON ?:buy_together_descriptions.chain_id = ?:buy_together.chain_id AND ?:buy_together_descriptions.lang_code = ?s
WHERE 1 ?p', $lang_code, $condition);
$limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
}
$chains = db_get_hash_array('SELECT ?p FROM ?:buy_together
LEFT JOIN ?:buy_together_descriptions ON ?:buy_together_descriptions.chain_id = ?:buy_together.chain_id AND ?:buy_together_descriptions.lang_code = ?s
?p
WHERE 1 ?p ?p ?p', 'chain_id', implode(', ', $fields), $lang_code, $join, $condition, $sorting, $limit);
return [$chains, $params];
}
function fn_abt__unitheme2_buy_together_update_chain_post($item_id, $product_id, $item_data, $auth, $lang_code, $create)
{
if (!$create && !empty($_REQUEST['return_url'])) {
fn_redirect(fn_url($_REQUEST['return_url']));
}
}
