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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
use Tygh\Registry;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if ($mode === 'get_promos') {
$promotions = [];
if (!empty($_REQUEST['promotions_ids'])) {
$promotions = db_get_hash_array('SELECT ?:promotions.promotion_id, ?:promotion_descriptions.name
FROM ?:promotions
INNER JOIN ?:promotion_descriptions
ON ?:promotions.promotion_id = ?:promotion_descriptions.promotion_id AND lang_code = ?s
WHERE ?:promotions.promotion_id IN (?n) AND status = ?s', 'promotion_id', CART_LANGUAGE, $_REQUEST['promotions_ids'], 'A');
if (!empty($promotions)) {
$images = fn_get_image_pairs(array_keys($promotions), 'promotion', 'A');
foreach ($images as $id => $image) {
$promotions[$id]['image'] = reset($image);
$promotions[$id]['link'] = fn_url('promotions.view?promotion_id=' . $promotions[$id]['promotion_id']);
}
foreach ($promotions as $id => $promotion) {
$promotions[$id] = Tygh::$app['view']->assign('promo', $promotion)->fetch('addons/ab__deal_of_the_day/components/promo_in_product_list.tpl');
}
}
}
Tygh::$app['ajax']->assign('promotions', $promotions);
exit();
}
if ($mode === 'get_chains') {
list($chains, $search) = fn_ab__dotd_get_chains($_REQUEST);
Tygh::$app['view']->assign('chains', $chains);
$search['text_get_more'] = __('ab__dotd.get_more_combinations', [$search['more']]);
$search['text_showed'] = __('ab__dotd.showed_combinations', ['[n]' => $search['items_per_page'] * $search['page'], '[total]' => $search['total_items']]);
Tygh::$app['ajax']->assign('html', Tygh::$app['view']->fetch('addons/buy_together/blocks/product_tabs/buy_together.tpl'));
Tygh::$app['ajax']->assign('search', $search);
}
exit;
}
