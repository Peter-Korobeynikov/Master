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

$schema['addons/ab__motivation_block/blocks/components/item_templates/geo_maps.tpl'] = [
'name' => __('ab__mb.template_path.templates.geo_maps'),
'tooltip' => __('ab__mb.template_path.templates.geo_maps.tooltip'),
'view_var_name' => 'ab__mb_geo_maps',
'conditions' => [
'active_addons' => [
'geo_maps',
],
'addons_settings' => [
'geo_maps' => [
'show_shippings_on_product' => 'Y',
],
],
],
];
$schema['addons/ab__motivation_block/blocks/components/item_templates/payment_methods.tpl'] = [
'name' => __('ab__mb.template_path.templates.payment_methods'),
'tooltip' => __('ab__mb.template_path.templates.payment_methods.tooltip'),
'view_var_name' => 'ab__mb_payment_variants',
];
$schema['addons/ab__motivation_block/blocks/components/item_templates/product_categories_list.tpl'] = [
'name' => __('ab__mb.template_path.templates.categories_list'),
'tooltip' => __('ab__mb.template_path.templates.categories_list.tooltip'),
'view_var_name' => 'ab__mb_categories_list',
'settings' => 'product_categories_list',
'conditions' => [
'functions' => ['fn_ab__mb_templates_categories_list_available'],
],
];
$schema['addons/ab__motivation_block/blocks/components/item_templates/product_tags_list.tpl'] = [
'name' => __('ab__mb.template_path.templates.tags_list'),
'tooltip' => __('ab__mb.template_path.templates.tags_list.tooltip'),
'view_var_name' => 'ab__mb_tags_list',
'settings' => 'product_tags_list',
'conditions' => [
'active_addons' => [
'tags',
],
],
];
return $schema;
