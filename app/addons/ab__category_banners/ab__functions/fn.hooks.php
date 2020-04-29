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
function fn_ab__category_banners_get_products_pre(&$params, $items_per_page, $lang_code)
{
if (AREA == 'C' && !empty($params['cid'])) {
$params['items_per_page'] = empty($params['items_per_page']) ? $items_per_page : $params['items_per_page'];
if (!empty($params['items_per_page'])) {
$combination = Registry::get('ab__category_banners.combination');
if (!empty($combination)) {
foreach ($combination as $position => $banner) {
if ($params['items_per_page'] < $position) {
unset($combination[$position]);
}
}
$count_banners = count($combination);
if ($params['items_per_page'] > $count_banners) {
if (Registry::ifGet('addons.ab__category_banners.decrease_items_per_page', 'N') == 'Y') {
$params['items_per_page'] -= $count_banners;
}
Registry::set('ab__category_banners.combination', $combination);
Tygh::$app['view']->assign('ab__cb_banner_exists', true);
}
}
}
}
}
