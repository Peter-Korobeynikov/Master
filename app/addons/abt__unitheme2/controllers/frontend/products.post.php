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
exit;
}
if ($mode == 'view') {
if (Registry::get('addons.social_buttons.status') == 'A') {
$social_buttons_settings = Registry::get('settings.social_buttons');
if (!empty($social_buttons_settings) && is_array($social_buttons_settings)) {
$provider_settings = Tygh::$app['view']->getTemplateVars('provider_settings');
foreach ($social_buttons_settings as $provider => $settings) {
$provider_display = $provider . '_display_on';
if (empty($settings[$provider_display]['products'])) {
unset($provider_settings[$provider]);
}
}
Tygh::$app['view']->assign('provider_settings', $provider_settings);
}
}
}
