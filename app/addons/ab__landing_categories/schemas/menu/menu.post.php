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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
$schema['central']['ab__addons']['items']['ab__landing_categories'] = [
'attrs' => ['class' => 'is-addon'],
'position' => 11000,
'href' => 'ab__lc.help',
'subitems' => [
'ab__lc.settings' => [
'href' => 'addons.update&addon=ab__landing_categories',
'position' => 0,
],
'ab__lc.category.list' => [
'href' => 'categories.manage&ab__lc_landing=Y',
'position' => 100,
],
'ab__lc.demodata' => [
'href' => 'ab__lc.demodata',
'position' => 200,
],
'ab__lc.help' => [
'href' => 'ab__lc.help',
'position' => 10000,
],

],
];
return $schema;
