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
* This is commercial software, only users who have purchased a valid license and  accept   *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
use Tygh\Registry;
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
return;
}
if ($mode == 'list') {
$promotions = Tygh::$app['view']->getTemplateVars('promotions');
if (!empty($promotions)) {
$promotion_ids = array_keys($promotions);
$images = fn_get_image_pairs($promotion_ids, 'promotion', 'A');
if (!empty($images)) {
foreach ($images as $promotion_id => $image) {
if (empty($images[$promotion_id])) {
continue;
}
$promotions[$promotion_id]['list_pair'] = reset($images[$promotion_id]);
}
}
}
list($chains, $chains_search) = fn_ab__dotd_get_chains();
Tygh::$app['view']->assign('promotions', $promotions);
Tygh::$app['view']->assign('chains', $chains);
Tygh::$app['view']->assign('show_chains', true);
Tygh::$app['view']->assign('chains_search', $chains_search);
} elseif ($mode == 'view') {
$promotion = fn_ab__dotd_get_cached_promotion_data($_REQUEST['promotion_id']);
if (empty($promotion) || $promotion['status'] == 'D') {
return [CONTROLLER_STATUS_NO_PAGE];
}
Tygh::$app['view']->assign('promotion', $promotion);
if (!empty($promotion['meta_description']) || !empty($promotion['meta_keywords'])) {
Tygh::$app['view']->assign('meta_description', $promotion['meta_description']);
Tygh::$app['view']->assign('meta_keywords', $promotion['meta_keywords']);
}
if (!empty($promotion['page_title'])) {
Tygh::$app['view']->assign('page_title', $promotion['page_title']);
}
fn_add_breadcrumb(__('promotions'), 'promotions.list');
fn_add_breadcrumb($promotion['name']);
if (empty($promotion['hide_products_block']) || $promotion['hide_products_block'] != 'Y') {
$selected_layout = fn_get_products_layout($_REQUEST);
Tygh::$app['view']->assign('selected_layout', $selected_layout);
$selected_category_id = empty($_REQUEST['ab_category_id']) ? 0 : $_REQUEST['ab_category_id'];
Tygh::$app['view']->assign('selected_category_id', $selected_category_id);
list($where, $joins) = fn_ab__dotd_build_promotion_conditions_query($promotion['conditions']);
if (isset($promotion['filter']) && $promotion['filter'] !== 'N') {
$cids = db_get_fields('SELECT DISTINCT pc.category_id FROM ?:products AS products' .
' LEFT JOIN ?:products_categories AS pc ON products.product_id = pc.product_id ?p' .
' WHERE ?p', implode(' ', $joins), $where);
$categories = fn_ab__dotd_get_root_categories($cids);
Tygh::$app['view']->assign('categories', $categories);
}
$params = $_REQUEST;
$params['extend'] = ['description'];
$params['ab__dotd_joins'] = $joins;
$params['ab__dotd_where'] = $where;
if (!empty($selected_category_id)) {
$params['cid'] = $selected_category_id;
$params['subcats'] = 'Y';
}
list($products, $search) = fn_get_products($params, Registry::get('settings.Appearance.products_per_page'));
if (!empty($products)) {
fn_gather_additional_products_data($products, [
'get_icon' => true,
'get_detailed' => true,
'get_options' => true,
'get_discounts' => true,
'get_features' => false,
]);
}
Tygh::$app['view']->assign('products', $products);
Tygh::$app['view']->assign('search', $search);
}
}
