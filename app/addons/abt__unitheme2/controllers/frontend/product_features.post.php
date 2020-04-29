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
use Tygh\BlockManager\Location;
if (!defined('BOOTSTRAP')) { die('Access denied'); }
if (Registry::get('addons.ab__seo_brands.status') != 'A') {
if ($mode == 'view_all' && !empty($_REQUEST['filter_id'])) {
$brand_feature_id = Registry::get('settings.abt__ut2.general.brand_feature_id');
if ($brand_feature_id) {
$brand_filter_id = db_get_field('SELECT filter_id FROM ?:product_filters WHERE feature_id = ?i', $brand_feature_id);
if ($brand_filter_id == $_REQUEST['filter_id']){
$bc = Tygh::$app['view']->getTemplateVars('breadcrumbs');
array_pop($bc);
Tygh::$app['view']->assign('breadcrumbs', $bc);
fn_add_breadcrumb(__('abt__ut2.brands'));
Tygh::$app['view']->assign('page_title', Location::instance()->get('product_features.view_all', '', CART_LANGUAGE)['title']);
}
}
}
}
