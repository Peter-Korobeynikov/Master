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
$addon = basename(__FILE__, '.php');
$style = 'Default';
$styles = glob(Registry::get('config.dir.themes_repository') . $addon . '/styles/data/*.less');
if (!empty($styles)) {
array_walk($styles, function (&$style, $key) {
$style = basename($style, '.less');
});
$style = $styles[array_rand($styles)];
}
$schema = [
'config' => [
'addon' => $addon,
'theme' => $addon,
'style' => $style,
'blog_page_id' => 7,
],
'steps' => [
'install_theme' => [
['fn_autoinstall_theme', '@addon', '@style'],
],
'install_demodata' => [
'fn_autoinstall_demodata',
],
'install_extradata' => [
['fn_autoinstall_extradata_menu'],
['fn_autoinstall_extradata_blog', '@blog_page_id'],
],
],
];
return $schema;
