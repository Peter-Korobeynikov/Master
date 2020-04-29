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
use Tygh\BlockManager\Exim;
use Tygh\BlockManager\Layout;
use Tygh\BlockManager\Location;
if (!defined('BOOTSTRAP')) {
die('Access denied');
}

function fn_ab__deal_of_the_day_install()
{
fn_ab__dotd_install_rebuild_tables();
fn_ab__dotd_install_layouts();
}

function fn_ab__dotd_install_rebuild_tables()
{
$is_old_tables = db_has_table('ab__deal_of_the_day');
if ($is_old_tables) {
$data = db_get_array('SELECT * FROM ?:ab__deal_of_the_day');
if (!empty($data)) {
$ab__dotd = $ab__dotd_descriptions = [];
foreach ($data as $promotion) {
$ab__dotd[$promotion['promotion_id']] = [
'promotion_id' => $promotion['promotion_id'],
'filter' => empty($promotion['filter']) ? 'N' : $promotion['filter'],
'use_products_filter' => empty($promotion['use_products_filter']) ? 'Y' : $promotion['use_products_filter'],
'hide_products_block' => empty($promotion['hide_products_block']) ? 'N' : $promotion['hide_products_block'],
'use_schedule' => empty($promotion['use_schedule']) ? 'N' : $promotion['use_schedule'],
'ab__dotd_schedule' => empty($promotion['ab__dotd_schedule']) ? null : $promotion['ab__dotd_schedule'],
];
$ab__dotd_descriptions[] = [
'promotion_id' => $promotion['promotion_id'],
'h1' => empty($promotion['h1']) ? '' : $promotion['h1'],
'page_title' => empty($promotion['page_title']) ? '' : $promotion['page_title'],
'meta_description' => empty($promotion['meta_description']) ? '' : $promotion['meta_description'],
'meta_keywords' => empty($promotion['meta_keywords']) ? '' : $promotion['meta_keywords'],
'lang_code' => $promotion['lang_code'],
];
}
if (!empty($ab__dotd)) {
db_query('REPLACE INTO ?:ab__dotd ?m', $ab__dotd);
}
if (!empty($ab__dotd_descriptions)) {
db_query('REPLACE INTO ?:ab__dotd_descriptions ?m', $ab__dotd_descriptions);
}
}
db_query('DROP TABLE IF EXISTS ?:ab__deal_of_the_day');
}
}

function fn_ab__dotd_install_layouts()
{
$schema = fn_get_schema('ab__layouts', 'ab__deal_of_the_day','php',true);
foreach ($schema as $dispatch => $data) {
fn_ab__dotd_add_layouts($dispatch, $data['file_name']);
}
}

function fn_ab__dotd_add_layouts($dispatch, $file_name, $layout_id = 0)
{
$condition = '';
if ($layout_id > 0) {
$condition .= db_quote(' AND layout.layout_id = ?i', $layout_id);
}
$target_layouts = db_get_array(
'SELECT layout.layout_id, layout.theme_name, layout.storefront_id FROM ?:bm_layouts AS layout'
. ' LEFT JOIN ?:bm_locations AS location ON location.layout_id = layout.layout_id AND location.dispatch = ?s'
. ' WHERE location.location_id IS NULL ?p'
, $dispatch, $condition
);
if (!empty($target_layouts)) {
foreach ($target_layouts as $layout) {
Exim::instance($layout['storefront_id'], $layout['layout_id'])->importFromFile(fn_ab__dotd_get_full_layout_path($file_name, $layout['theme_name']));
}
}
}

function fn_ab__deal_of_the_day_uninstall()
{
$installed_themes = fn_get_installed_themes();
$design_dir = fn_get_theme_path('[themes]/', 'C');
foreach ($installed_themes as $theme_name) {
$path = $design_dir . $theme_name . '/layouts/addons/ab__deal_of_the_day';
if (is_dir($path)) {
fn_rm($path);
}
}
}
function fn_ab__dotd_get_full_layout_path($file_name, $theme_name)
{
static $path = 'var/ab__data/ab__deal_of_the_day/layouts';
$theme = file_exists($path . '/' . $theme_name . '/' . $file_name) ? $theme_name : 'responsive';
return $path . '/' . $theme . '/' . $file_name;
}
