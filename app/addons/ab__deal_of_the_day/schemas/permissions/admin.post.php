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
$schema['ab__deal_of_the_day'] = [
'modes' => [
'view' => [
'permissions' => 'ab__dotd_view_seodata',
],
'manage' => [
'permissions' => 'ab__dotd_manage_seodata',
],
],
];
$schema['ab__dotd'] = [
'modes' => [
'help' => [
'permissions' => 'ab__dotd_manage_seodata',
],
'demodata' => [
'permissions' => 'ab__dotd_manage_seodata',
],
'layouts' => [
'permissions' => 'ab__dotd_manage_seodata',
],
],
'permissions' => ['GET' => 'ab__dotd_view_seodata', 'POST' => 'ab__dotd_manage_seodata'],
];
return $schema;
