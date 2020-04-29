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
die;
}

function fn_ab__mb_get_icons($file_path)
{
$icons = [];
$file_content = fn_get_contents($file_path);
if (!empty($file_content)) {
$rule = "'\.ty-(.*?)\:before'i";
preg_match_all($rule, $file_content, $matches);
$icons['ty_icons'] = $matches[1];
}

fn_set_hook('ab__mb_get_icons_post', $icons, $file_path);
return $icons;
}
function fn_ab__mb_refresh_icons($forced = true) {
fn_ab__mb_refresh_icons_for_theme($forced, Registry::get('config.base_theme'), 'css/tygh/icons.less', '../media/');
if (!empty(Registry::get('addons.abt__youpitheme'))) {
fn_ab__mb_refresh_icons_for_theme($forced, 'abt__youpitheme', 'css/addons/ab__motivation_block/icons.less', '../../../media/', 'MaterialIcons-Regular', ['eot', 'woff2', 'woff', 'ttf'], 'custom_fonts');
}
}

function fn_ab__mb_refresh_icons_for_theme($forced, $theme_name, $styles_file_path, $file_font_path, $font_file_name = 'glyphs', $extensions = ['eot', 'woff', 'ttf', 'svg'], $repo_additional = 'fonts') {
$repo_path = Registry::get('config.dir.design_frontend') . $theme_name . '/';
$file_content = fn_get_contents($repo_path . $styles_file_path);
$ty_file_dir = Registry::get('config.dir.design_backend') . 'css/addons/ab__motivation_block/';
$ty_file_path = $ty_file_dir . $theme_name . '_icons.less';
if (!is_file($ty_file_path) || $forced) {
$file_content = str_replace($file_font_path . $repo_additional . '/', '../../../media/fonts/addons/ab__motivation_block/', $file_content);
file_put_contents($ty_file_path, $file_content);
$mb_fonts_dir = Registry::get('config.dir.design_backend') . 'media/fonts/addons/ab__motivation_block/';
fn_mkdir($mb_fonts_dir);
for ($i = 0; $i < count($extensions); $i++) {
fn_copy("{$repo_path}media/{$repo_additional}/{$font_file_name}.{$extensions[$i]}", "{$mb_fonts_dir}{$font_file_name}.{$extensions[$i]}");
}
}
}