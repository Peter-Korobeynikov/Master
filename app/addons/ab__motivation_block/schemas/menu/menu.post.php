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
$schema['central']['ab__addons']['items']['ab__motivation_block'] = [
'attrs' => [
'class' => 'is-addon',
],
'href' => 'ab__motivation_block.manage',
'position' => 700,
'subitems' => [
'ab__mb.settings' => [
'href' => 'addons.update&addon=ab__motivation_block',
'position' => 0,
],
'ab__motivation_block.manage' => [
'href' => 'ab__motivation_block.manage',
'position' => 10,
],
'ab__motivation_block.icons' => [
'href' => 'ab__motivation_block.icons',
'position' => 20,
],
'ab__motivation_block.demodata' => [
'href' => 'ab__motivation_block.demodata',
'position' => 30,
],
'ab__motivation_block.help' => [
'href' => 'ab__motivation_block.help',
'position' => 40,
],
],
];
return $schema;
