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
$schema['ab__deal_of_the_day'] = [
'content' => [
'promotion' => [
'type' => 'enum',
'items_function' => 'fn_ab__dotd_get_promotion_data',
'hide_label' => true,
'remove_indent' => true,
'fillings' => [
'manually' => [
'picker' => 'addons/ab__deal_of_the_day/pickers/promotions/picker.tpl',
'picker_params' => [
'multiple' => false,
'status' => 'A',
'zone' => 'catalog',
'default_name' => __('ab__dotd.choose_promotion'),
],
],
],
],
],
'settings' => [
'show_price' => [
'type' => 'checkbox',
'default_value' => 'Y',
],
'enable_quick_view' => [
'type' => 'checkbox',
'default_value' => 'N',
],
'not_scroll_automatically' => [
'type' => 'checkbox',
'default_value' => 'N',
],
'scroll_per_page' => [
'type' => 'checkbox',
'default_value' => 'N',
],
'speed' => [
'type' => 'input',
'default_value' => 400,
],
'pause_delay' => [
'type' => 'input',
'default_value' => 3,
],
'item_quantity' => [
'type' => 'input',
'default_value' => 5,
],
'hide_add_to_cart_button' => [
'type' => 'checkbox',
'default_value' => 'N',
],
],
'templates' => [
'addons/ab__deal_of_the_day/blocks/ab__deal_of_the_day.tpl' => [],
],
'wrappers' => 'blocks/wrappers',
'cache' => [
'update_handlers' => [
'promotions',
'promotion_descriptions',
'categories',
'products_categories',
'products',
'product_descriptions',
'product_prices',
'products_categories',
'product_popularity',
'product_options',
'product_options_descriptions',
'product_option_variants',
'product_option_variants_descriptions',
'product_options_exceptions',
'product_options_inventory',
'product_global_option_links',
'ab__dotd',
'ab__dotd_descriptions',
'ab__dotd_periods',
],
'cookie_handlers' => ['%ALL%'],
'callable_handlers' => [
'currency' => ['fn_get_secondary_currency'],
'date' => ['date', ['Y-m-d-H']],
],
],
];
if (fn_allowed_for('ULTIMATE')) {
$schema['ab__deal_of_the_day']['cache']['update_handlers'][] = 'ult_product_prices';
$schema['ab__deal_of_the_day']['cache']['update_handlers'][] = 'ult_product_descriptions';
$schema['ab__deal_of_the_day']['cache']['update_handlers'][] = 'ult_product_option_variants';
}
$schema['ab__promotions'] = [
'content' => [
'items' => [
'type' => 'enum',
'items_function' => 'fn_ab__dotd_get_promotions',
'hide_label' => true,
'remove_indent' => true,
'fillings' => [
'manually' => [
'picker' => 'addons/ab__deal_of_the_day/pickers/promotions/picker.tpl',
'picker_params' => [
'multiple' => true,
'status' => 'A',
'positions' => true,
],
],
'ab__dotd_sorted_promotions' => [
'params' => [
'active' => true,
],
'settings' => [
'sort_by' => [
'type' => 'selectbox',
'values' => [
'' => 'ab__dotd.sort.created',
'to_date' => 'ab__dotd.sort.to_date',
'priority' => 'ab__dotd.sort.priority',
'name' => 'ab__dotd.sort.name',
],
'default_value' => '',
],
'sort_order' => [
'type' => 'selectbox',
'values' => [
'asc' => 'asc',
'desc' => 'desc',
],
'default_value' => 'desc',
],
'limit' => [
'type' => 'input',
'default_value' => 3,
],
],
],
'ab__dotd_expired_promotions' => [
'params' => [
'expired_only' => true,
'active' => false,
],
'settings' => [
'sort_by' => [
'type' => 'selectbox',
'values' => [
'' => 'ab__dotd.sort.created',
'to_date' => 'ab__dotd.sort.to_date',
'priority' => 'ab__dotd.sort.priority',
'name' => 'ab__dotd.sort.name',
],
'default_value' => '',
],
'sort_order' => [
'type' => 'selectbox',
'values' => [
'asc' => 'asc',
'desc' => 'desc',
],
'default_value' => 'desc',
],
'limit' => [
'type' => 'input',
'default_value' => 3,
],
],
],
'ab__dotd_awaited_promotions' => [
'params' => [
'awaited_only' => true,
'active' => false,
],
'settings' => [
'sort_by' => [
'type' => 'selectbox',
'values' => [
'' => 'ab__dotd.sort.created',
'to_date' => 'ab__dotd.sort.to_date',
'priority' => 'ab__dotd.sort.priority',
'name' => 'ab__dotd.sort.name',
],
'default_value' => '',
],
'sort_order' => [
'type' => 'selectbox',
'values' => [
'asc' => 'asc',
'desc' => 'desc',
],
'default_value' => 'desc',
],
'limit' => [
'type' => 'input',
'default_value' => 3,
],
],
],
],
],
],
'templates' => 'addons/ab__deal_of_the_day/blocks/promotions',
'wrappers' => 'blocks/wrappers',
'cache' => [
'update_handlers' => [
'promotions',
'promotion_descriptions',
'ab__dotd',
'ab__dotd_descriptions',
'ab__dotd_periods',
],
'callable_handlers' => [
'date' => ['date', ['Y-m-d-H']],
],
],
];
$schema['product_filters']['cache']['request_handlers'][] = 'promotion_id';
$schema['product_filters']['cache']['update_handlers'][] = 'promotions';
$schema['product_filters']['cache']['update_handlers'][] = 'ab__dotd';
$schema['product_filters']['cache']['update_handlers'][] = 'ab__dotd_descriptions';
$schema['product_filters']['cache']['update_handlers'][] = 'ab__dotd_periods';
$schema['product_filters']['content']['items']['fillings']['manually']['params']['request']['promotion_id'] = '%PROMOTION_ID%';
$schema['ab__promotion_main_data'] = [
'content' => [
'promotion' => [
'type' => 'function',
'function' => ['fn_ab__promotion_main_data_get_promotion_data'],
],
],
'templates' => [
'addons/ab__deal_of_the_day/blocks/ab__promotion_main_data.tpl' => [],
],
'wrappers' => 'blocks/wrappers',
'cache' => [
'update_handlers' => [
'promotions',
'promotion_descriptions',
'ab__dotd',
'ab__dotd_descriptions',
'ab__dotd_periods',
],
'request_handlers' => ['promotion_id'],
'cookie_handlers' => ['%ALL%'],
'callable_handlers' => [
'date' => ['date', ['Y-m-d-H']],
],
],
];
$schema['ab__multi_deal_of_the_day'] = [
'content' => [
'promotion' => [
'type' => 'enum',
'items_function' => 'fn_ab__dotd_get_multi_deal_block',
'hide_label' => true,
'remove_indent' => true,
'fillings' => [
'manually' => [
'picker' => 'addons/ab__deal_of_the_day/pickers/promotions/picker.tpl',
'picker_params' => [
'multiple' => true,
'status' => 'A',
'zone' => 'catalog',
'use_priority' => true,
],
],
],
],
],
'settings' => [
'show_price' => [
'type' => 'checkbox',
'default_value' => 'Y',
],
'enable_quick_view' => [
'type' => 'checkbox',
'default_value' => 'N',
],
'not_scroll_automatically' => [
'type' => 'checkbox',
'default_value' => 'N',
],
'scroll_per_page' => [
'type' => 'checkbox',
'default_value' => 'N',
],
'speed' => [
'type' => 'input',
'default_value' => 400,
],
'pause_delay' => [
'type' => 'input',
'default_value' => 3,
],
'item_quantity' => [
'type' => 'input',
'default_value' => 5,
],
'thumbnail_width' => [
'type' => 'input',
'default_value' => 80,
],
'outside_navigation' => [
'type' => 'checkbox',
'default_value' => 'Y',
],
'ab__show_additional_product_images' => [
'type' => 'checkbox',
'default_value' => 'N',
],
'hide_add_to_cart_button' => [
'type' => 'checkbox',
'default_value' => 'N',
],
],
'templates' => [
'addons/ab__deal_of_the_day/blocks/ab__deal_of_the_day.tpl' => [],
],
'wrappers' => 'blocks/wrappers',
'cache' => [
'update_handlers' => [
'promotions',
'promotion_descriptions',
'categories',
'products_categories',
'products',
'product_descriptions',
'product_prices',
'products_categories',
'product_popularity',
'product_options',
'product_options_descriptions',
'product_option_variants',
'product_option_variants_descriptions',
'product_options_exceptions',
'product_options_inventory',
'product_global_option_links',
'ab__dotd',
'ab__dotd_descriptions',
'ab__dotd_periods',
],
'cookie_handlers' => ['%ALL%'],
'callable_handlers' => [
'currency' => ['fn_get_secondary_currency'],
'date' => ['date', ['Y-m-d-H']],
'promotion_id' => ['fn_ab__dotd_multi_block_cache', ['$block_data']]
],
],
];
return $schema;
