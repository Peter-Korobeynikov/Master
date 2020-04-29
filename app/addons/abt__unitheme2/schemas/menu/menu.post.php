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
use Tygh\Registry;
$schema['central']['ab__addons']['items']['abt__unitheme2'] = [
'attrs' => ['class' => 'is-addon'],
'href' => 'abt__ut2.help',
'position' => 2,
'subitems' => [
'abt__ut2.settings' => [
'href' => 'abt__ut2.settings',
'position' => 100,
],
'abt__ut2.less_settings' => [
'href' => 'abt__ut2.less_settings',
'position' => 200,
],
'abt__ut2.microdata' => [
'href' => 'abt__ut2.microdata',
'position' => 300,
],
'abt__ut2.icons' => [
'href' => 'abt__ut2.icons',
'position' => 400,
],
'abt__ut2.demodata' => [
'href' => 'abt__ut2.demodata',
'position' => 500,
],
'abt__ut2.help' => [
'href' => 'abt__ut2.help',
'position' => 10000,
],
],
];
if (is_array(fn_abt__ut2_mfu1_check_is_var_in_system(fn_get_schema('abt__ut2_mfu1', 'po')))) {
$schema['central']['ab__addons']['items']['abt__unitheme2']['subitems']['abt__ut2.migaritions_from_unitheme1.update_po'] = [
'href' => 'abt__ut2.migaritions_from_unitheme1.update_po',
'position' => 600,
];
}
if (Registry::get('addons.banners.status') == 'A') {
$schema['central']['marketing']['items']['banners']['subitems']['abt__ut2.banners.manage'] = [
'attrs' => [
'class' => 'is-addon',
],
'href' => 'banners.manage?type=' . ABT__UT2_BANNER_TYPE,
'position' => 100,
];
}
if (Registry::get('addons.buy_together.status') == 'A') {
$schema['central']['products']['items']['abt__ut2_buy_together.generate'] = [
'attrs' => [
'class' => 'is-addon',
],
'href' => 'abt__ut2_buy_together.generate',
'position' => 700,
];
$schema['central']['products']['items']['abt__ut2_buy_together.manage'] = [
'attrs' => [
'class' => 'is-addon',
],
'href' => 'abt__ut2_buy_together.manage',
'position' => 800,
];
}
return $schema;
