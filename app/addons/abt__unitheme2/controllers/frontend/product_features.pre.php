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
if (!defined('BOOTSTRAP')) { die('Access denied'); }
if ($mode == 'view_all' and empty($_REQUEST['filter_id'])) {
$brand_feature_id = Registry::get('settings.abt__ut2.general.brand_feature_id');
if ($brand_feature_id){
$brand_filter_id = db_get_field('SELECT filter_id FROM ?:product_filters WHERE feature_id = ?i', $brand_feature_id);
if ($brand_filter_id){
$_REQUEST['filter_id'] = $brand_filter_id;
}
}
}
if (Registry::get('addons.ab__seo_brands.status') != 'A') {
if ($mode == 'view') {
$variant_data = fn_get_product_feature_variant($_REQUEST['variant_id']);
if (!empty($variant_data)){
fn_add_breadcrumb(__('abt__ut2.brands'), fn_url('product_features.view_all'));
}
}
}