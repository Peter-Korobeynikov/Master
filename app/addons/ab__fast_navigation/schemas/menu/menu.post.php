<?php
/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2019   *
* / /_\ | | _____  _| |_/ /_ __ __ _ _ __   __| |_ _ __   __ _   | |_ ___  __ _ _ __ ___   *
* |  _  | |/ _ \ \/ / ___ \ '__/ _` | '_ \ / _` | | '_ \ / _` |  | __/ _ \/ _` | '_ ` _ \  *
* | | | | |  __/>  <| |_/ / | | (_| | | | | (_| | | | | | (_| |  | ||  __/ (_| | | | | | | *
* \_| |_/_|\___/_/\_\____/|_|  \__,_|_| |_|\__,_|_|_| |_|\__, |  \___\___|\__,_|_| |_| |_| *
*                                                         __/ |                            *
*                                                        |___/                             *
* ---------------------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license and  accept   *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
$schema['central']['ab__addons']['items']['ab__fast_navigation'] = [
'attrs' => [
'class' => 'is-addon',
],
'position' => 10000,
'href' => 'ab__fast_navigation.help',
'subitems' => [
'ab__fast_navigation.demodata' => [
'href' => 'ab__fast_navigation.demodata',
'position' => 10,
],
'ab__fast_navigation.help' => [
'href' => 'ab__fast_navigation.help',
'position' => 1000,
],
/*'ab__fast_navigation.export' => array(
'attrs' => array(
'class'=>'is-addon'
),
'href' => 'ab__fast_navigation.export',
'position' => 300
)*/
],
];
return $schema;
