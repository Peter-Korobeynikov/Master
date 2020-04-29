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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
function fn_abt__ut2_required_products_get_products_pre(&$params)
{
if (AREA == 'C' || (isset($params['area']) && $params['area'] == 'C')) {
if (!empty($params['for_required_product'])) {
if (!empty($params['extend'])) {
array_push($params['extend'], 'description');
} else {
$params['extend'] = ['description'];
}
}
}
}
function fn_abt__ut2_bestsellers_get_products_pre(&$params, $lang_code)
{
if (defined('AJAX_REQUEST')
&& (AREA == 'C' || (isset($params['area']) && $params['area'] == 'C'))
&& !empty($params['block_data']['content']['items']['filling']) && in_array($params['block_data']['content']['items']['filling'], ['similar', 'bestsellers'])
&& !empty($_REQUEST['object_type']) && $_REQUEST['object_type'] == 'products'
&& !empty($_REQUEST['object_id'])
) {
$auth = Tygh::$app['session']['auth'];
$product = fn_get_product_data($_REQUEST['object_id'], $auth, $lang_code, '?:products.product_id', false, false, false, false, false, false);
Tygh::$app['view']->assign('product', $product);
if ($params['block_data']['content']['items']['filling'] == 'similar') {
$params['main_product_id'] = $_REQUEST['object_id'];
}
if ($params['block_data']['content']['items']['filling'] == 'bestsellers') {
$params['cid'] = $product['main_category'];
}
}
}
function fn_abt__ut2_customers_also_bought_get_products_pre(&$params)
{
if (defined('AJAX_REQUEST')
&& (AREA == 'C' || (isset($params['area']) && $params['area'] == 'C'))
&& !empty($params['block_data']['content']['items']['filling']) && $params['block_data']['content']['items']['filling'] == 'also_bought'
&& !empty($_REQUEST['object_type']) && $_REQUEST['object_type'] == 'products'
&& !empty($_REQUEST['object_id'])
) {
$params['also_bought_for_product_id'] = $_REQUEST['object_id'];
}
}
