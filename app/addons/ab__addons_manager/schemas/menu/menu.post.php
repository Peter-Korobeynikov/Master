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
if (!defined('BOOTSTRAP')) {die('Access denied');}
$schema['central']['ab__addons']['position'] = 10000;
$schema['central']['ab__addons']['items']['ab__addons_manager'] = array(
'attrs' => array('class'=>'is-addon'),
'href' => 'ab__am.addons',
'position' => 1,
'subitems' => array(
'ab__am.addons' => array(
'attrs' => array(
'class'=>'is-addon'
),
'href' => 'ab__am.addons',
'position' => 10
),
'ab__am.our_store' => array(
'is_promo' => true,
'attrs' => array(
'class'=>'is-addon',
'href'=> array (
'target' => '_blank',
),
),
'href' => 'https://cs-cart.alexbranding.com',
'position' => 20
),
),
);
return $schema;
