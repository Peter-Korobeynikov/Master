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
$template_settings = [
'ab__fn_number_of_columns_desktop' => [
'type' => 'input',
'tooltip' => __('ab__fn_number_of_columns_desktop.tooltip'),
'default_value' => 6,
],
'ab__fn_number_of_columns_desktop_small' => [
'type' => 'input',
'tooltip' => __('ab__fn_number_of_columns_desktop_small.tooltip'),
'default_value' => 6,
],
'ab__fn_number_of_columns_tablet' => [
'type' => 'input',
'tooltip' => __('ab__fn_number_of_columns_tablet.tooltip'),
'default_value' => 5,
],
'ab__fn_number_of_columns_tablet_small' => [
'type' => 'input',
'tooltip' => __('ab__fn_number_of_columns_tablet_small.tooltip'),
'default_value' => 3,
],
'ab__fn_number_of_columns_mobile' => [
'type' => 'input',
'tooltip' => __('ab__fn_number_of_columns_mobile.tooltip'),
'default_value' => 3,
],
'ab__fn_icon_width' => [
'type' => 'input',
'default_value' => 100,
],
];
$schema['addons/ab__fast_navigation/blocks/ab__fast_navigation/ab__fn_two_level.tpl'] = [
'settings' => array_merge($template_settings, [
'ab__fn_init_scrollbar' => [
'type' => 'checkbox',
'default_value' => 'N',
],
'ab__fn_add_link' => [
'type' => 'checkbox',
'default_value' => 'Y',
'tooltip' => __('ab__fn_add_link.tooltip'),
],
'ab__fn_init_second_level_scroll' => [
'type' => 'checkbox',
'default_value' => 'N',
],
]),
];
$schema['addons/ab__fast_navigation/blocks/ab__fast_navigation/ab__fn_one_level.tpl'] = [
'settings' => array_merge([
'ab__fn_display_type' => [
'type' => 'selectbox',
'values' => [
'ab__fn_scroller' => 'ab__fn_scroller',
'ab__fn_grid' => 'ab__fn_grid',
],
'default_value' => 'ab__fn_scroller',
],
], $template_settings),
];
return $schema;
