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
function fn_abt__ut2_upgrades_copy_layouts()
{
$edition = 'ultimate';
$layouts = glob(Registry::get('config.dir.themes_repository') . "abt__unitheme2/layouts/layouts_{$edition}_*.xml");
if (!empty($layouts)) {
foreach (['responsive', 'abt__unitheme2'] as $theme) {
foreach ($layouts as $layout) {
$dir = Registry::get('config.dir.design_frontend') . $theme . '/layouts/';
if (is_dir($dir)) {
fn_copy($layout, $dir);
}
}
}
}
}
function fn_abt__ut2_upgrades_set_notifications($version, $notifications = [])
{
if (!empty($notifications)) {
$data = [];
$i = 0;
foreach ($notifications as $notification) {
$i++;
$data[] = [
'title' => "$i. " . __("abt__ut2.upgrade_notifications.{$version}.{$notification}.title"),
'text' => __("abt__ut2.upgrade_notifications.{$version}.{$notification}.text"),
];
}
fn_set_notification('I', __('abt__ut2.upgrade_notifications.title', ['[ver]' => $version]), Tygh::$app['view']->assign('ver', $version)
->assign('notifications', $data)
->fetch('addons/abt__unitheme2/views/upgrade_notifications/list.tpl'), 'S');
}
}
function fn_abt__ut2_upgrades_color_scheme_migration($params)
{
$styles_path = Registry::get('config.dir.design_frontend') . 'abt__unitheme2/styles/data/';
$files = glob($styles_path . '*.less');
foreach ($files as $file) {
$styles = fn_get_contents($file);
foreach ($params as $var_name => $param) {
$old_str = "{$var_name}: {$param['old_default_value']}";
$new_str = "{$var_name}: {$param['new_default_value']}";
$styles = str_replace($old_str, $new_str, $styles);
}
file_put_contents($file, $styles, LOCK_EX);
}
}
