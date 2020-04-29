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
$content_settings = [
'ab__fn_show_common_btn' => [
'type' => 'template',
'tooltip' => __('ab__fn_show_common_btn.tooltip'),
'template' => 'addons/ab__fast_navigation/block_settings/show.tpl',
],
'ab__fn_common_btn_text' => [
'type' => 'template',
'tooltip' => __('ab__fn.common_btn_text.tooltip'),
'template' => 'addons/ab__fast_navigation/block_settings/text.tpl',
],
'ab__fn_show_common_btn_link' => [
'type' => 'template',
'tooltip' => __('ab__fn_show_common_btn_link.tooltip'),
'template' => 'addons/ab__fast_navigation/block_settings/link.tpl',
],
'ab__fn_common_btn_class' => [
'type' => 'template',
'tooltip' => __('ab__fn_common_btn_class.tooltip'),
'template' => 'addons/ab__fast_navigation/block_settings/classes.tpl',
],
'ab__fn_common_btn_type' => [
'type' => 'template',
'tooltip' => __('ab__fn_common_btn_type.tooltip'),
'template' => 'addons/ab__fast_navigation/block_settings/appearance.tpl',
],
];
$schema['ab__fast_navigation'] = [
'content' => array_merge([
'items' => [
'type' => 'function',
'function' => ['fn_ab__fn_get_menu_items'],
],
'menu' => [
'type' => 'template',
'template' => 'views/menus/components/block_settings.tpl',
'hide_label' => true,
'data_function' => ['fn_get_menus'],
], ], $content_settings),
'templates' => 'addons/ab__fast_navigation/blocks/ab__fast_navigation',
'wrappers' => 'blocks/wrappers',
'cache' => [
'update_handlers' => ['static_data', 'static_data_descriptions'],
],
'multilanguage' => true,
];
$schema['ab__fast_navigation_categories'] = [
'content' => array_merge([
'items' => [
'type' => 'function',
'function' => ['fn_ab__fn_get_subcategory_items'],
],
], $content_settings),
'templates' => 'addons/ab__fast_navigation/blocks/ab__fast_navigation',
'wrappers' => 'blocks/wrappers',
'cache' => [
'update_handlers' => ['categories', 'category_descriptions'],
'session_handlers' => ['%CURRENT_CATEGORY_ID%'],
'request_handlers' => ['%CATEGORY_ID%'],
],
'multilanguage' => true,
'show_on_locations' => ['categories', 'categories.view'],
];
return $schema;
