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
* This is commercial software, only users who have purchased a valid license and accept    *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
$schema['central']['ab__addons']['items']['ab__search_motivation'] = array(
'attrs' => array(
'class'=>'is-addon',
),
'href' => 'ab__search_motivation.manage',
'position' => 2,
'subitems' => array(
'ab__sm.settings' => array(
'href' => 'addons.update&addon=ab__search_motivation',
'position' => 0
),
'ab__search_motivation.manage' => array(
'href' => 'ab__search_motivation.manage',
'position' => 20
),
'ab__search_motivation.help' => array(
'href' => 'ab__search_motivation.help',
'position' => 100
),
)
);
return $schema;