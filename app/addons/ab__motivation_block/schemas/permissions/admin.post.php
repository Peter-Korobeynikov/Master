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
$schema['ab__motivation_block'] = [
'modes' => [
'help' => [
'permissions' => 'ab__mb.data.manage',
],
'demodata' => [
'permissions' => 'ab__mb.data.manage',
],
'change' => [
'permissions' => 'ab__mb.data.manage',
],
'icons' => [
'permissions' => 'ab__mb.data.view',
],
'manage' => [
'permissions' => ['GET' => 'ab__mb.data.view', 'POST' => 'ab__mb.data.manage'],
],
'update' => [
'permissions' => ['GET' => 'ab__mb.data.view', 'POST' => 'ab__mb.data.manage'],
],
],
'permissions' => ['GET' => 'ab__mb.data.view', 'POST' => 'ab__mb.data.manage'],
];
$schema['tools']['modes']['update_status']['param_permissions']['table']['ab__mb_motivation_items'] = 'ab__mb.data.manage';
return $schema;
