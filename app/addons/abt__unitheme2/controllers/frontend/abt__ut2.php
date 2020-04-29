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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if ($mode === 'ajax_block_upload' && defined('AJAX_REQUEST')) {
$res = [];
switch ($action) {
case 'social_buttons':
Registry::set('config.current_url', $_REQUEST['object_dispatch'] . '&' . $_REQUEST['object_type'] . '_id=' . $_REQUEST['object_id']);
Tygh::$app['view']->assign('provider_settings', fn_get_sb_provider_settings([
'object_id' => $_REQUEST['object_id'],
'object' => 'products',
'language' => CART_LANGUAGE,
]));
$res = ['html' => Tygh::$app['view']->fetch('addons/social_buttons/blocks/components/providers.tpl')];
break;
case 'load_menu':
$result_ids = (array) explode(',', $_REQUEST['result_ids']);
if (!empty($result_ids)) {
foreach ($result_ids as $result_id) {
Tygh::$app['ajax']->assignHtml($result_id, fn_abt__ut2_ajax_menu_get($result_id));
}
}
exit;
default:
$res = ['html' => 'no data'];
}
Tygh::$app['ajax']->assign('result', $res);
}
}
