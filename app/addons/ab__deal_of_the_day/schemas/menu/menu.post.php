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
$schema['central']['ab__addons']['items']['ab__deal_of_the_day'] = [
'attrs' => [
'class' => 'is-addon',
],
'href' => 'ab__dotd.help',
'position' => 1000,
'subitems' => [
'ab__dotd.settings' => [
'href' => 'addons.update&addon=ab__deal_of_the_day',
'position' => 0,
],
'ab__dotd.demodata' => [
'href' => 'ab__dotd.demodata',
'position' => 20,
],
'ab__dotd.layouts' => [
'href' => 'ab__dotd.layouts',
'position' => 30,
],
'ab__dotd.help' => [
'href' => 'ab__dotd.help',
'position' => 1000,
],
],
];
return $schema;
