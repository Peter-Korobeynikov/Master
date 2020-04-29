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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
function fn_abt__unitheme2_render_blocks(&$grid, &$block, $that, &$content)
{
$device = Registry::get('settings.abt__device');
if (
AREA == 'C'
&& $grid['abt__ut2_show_in_tabs'] == 'Y'
&& $block['status'] == 'A'
&& !empty($block['availability'][$device == 'mobile' ? 'phone' : $device])
) {
$block['tab_id'] = 'abt__ut2_grid_tab_' . $grid['grid_id'] . '_' . $block['block_id'];
$block['abt__ut2_use_ajax'] = $grid['abt__ut2_use_ajax'];
$tab_data = ['title' => $block['name']];
if ($grid['abt__ut2_use_ajax'] == 'Y') {
$tab_data['ajax'] = true;
$tab_data['block'] = $grid['grid_id'] . '_' . $block['block_id'];
$tab_data['abt__ut2_grid_tabs'] = true;
$snapping = '';
if (!empty($_REQUEST['dispatch'])) {
if ($_REQUEST['dispatch'] == 'products.view' && intval($_REQUEST['product_id'])) {
$snapping = '_' . $block['snapping_id'] . '_products_' . $_REQUEST['product_id'];
} elseif ($_REQUEST['dispatch'] == 'categories.view' && intval($_REQUEST['category_id'])) {
$snapping = '_' . $block['snapping_id'] . '_categories_' . $_REQUEST['category_id'];
}
}
$block['tab_id'] .= $snapping;
$tab_data['block'] .= $snapping;
$block['first'] = empty($content);
} else {
$tab_data['js'] = true;
}
Registry::set("navigation.{$grid['grid_id']}." . $block['tab_id'], $tab_data);
}
}
function fn_abt__unitheme2_render_block_content_after($block_schema, $block, &$block_content)
{
if (AREA == 'C' && Registry::get('runtime.controller') !== 'abt__ut2_grid_tabs' && !empty($block['tab_id'])) {
$clean_content = trim(strip_tags($block_content));
if (empty($clean_content)) {
$tabs = Registry::get('navigation.' . $block['grid_id']);
unset($tabs[$block['tab_id']]);
Registry::set('navigation.' . $block['grid_id'], $tabs);
} elseif ($block['abt__ut2_use_ajax'] != 'Y' || !empty($block['first']) || defined('AJAX_REQUEST')) {
$block_content = '<div id="content_' . $block['tab_id'] . '">' . $block_content . '</div>';
} else {
$block_content = '<div id="content_' . $block['tab_id'] . '"><span></span></div>';
}
}
}
