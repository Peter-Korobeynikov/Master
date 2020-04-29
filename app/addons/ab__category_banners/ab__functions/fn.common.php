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
use Tygh\Registry;
function fn_ab__cb_parse_time($time)
{
if (is_numeric($time)) {
return $time;
}
if (strpos($time, ':') !== false) {
list($h, $m) = explode(':', $time);
return intval($h) * 3600 + intval($m) * 60;
}
return false;
}
function fn_ab__cb_cron_links()
{
$cron_key = trim(Registry::get('addons.ab__category_banners.cron_key'));
if (!fn_ab__cb_is_valid_cron_key($cron_key)) {
return __('ab__cb.errors.cron_key', ['value' => $cron_key]);
}
$cron_cmd = '*/5 * * * * php ' . Registry::get('config.dir.root') . '/' . Registry::get('config.admin_index') . ' --dispatch=ab__category_banners.turn_off_expired_banners --cron_key=' . $cron_key;
$cron_url = fn_url('ab__category_banners.turn_off_expired_banners?cron_key=' . $cron_key);
return __('ab__cb.cron_links', array('[cron_cmd]' => $cron_cmd, '[cron_url]' => $cron_url));
}
function fn_ab__cb_check_key($cron_key)
{
$key = trim(Registry::get('addons.ab__category_banners.cron_key'));
$cron_key = trim($cron_key);
return ($cron_key == $key);
}
function fn_ab__cb_is_valid_cron_key($key)
{
return (strlen($key) >= 10 && strlen($key) <= 20);
}
