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
function fn_ab__hpd_install()
{
$objects = [
['t' => '?:product_tabs',
'i' => [
['n' => 'ab__smc_hide_content', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
['n' => 'ab__smc_override', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
['n' => 'ab__smc_height', 'p' => 'int(5) UNSIGNED NOT NULL DEFAULT 250'],
],
],
['t' => '?:product_tabs_descriptions',
'i' => [
['n' => 'ab__smc_show_more', 'p' => 'VARCHAR(50) NOT NULL DEFAULT \'\''],
['n' => 'ab__smc_show_less', 'p' => 'VARCHAR(50) NOT NULL DEFAULT \'\''],
],
],
];
if (!empty($objects) && is_array($objects)) {
foreach ($objects as $o) {
$fields = db_get_fields('DESCRIBE ' . $o['t']);
if (!empty($fields) && is_array($fields)) {
if (!empty($o['i']) && is_array($o['i'])) {
foreach ($o['i'] as $f) {
if (!in_array($f['n'], $fields)) {
db_query('ALTER TABLE ?p ADD ?p ?p', $o['t'], $f['n'], $f['p']);
}
}
}
}
}
}
fn_ab__hpd_youpitheme_migration();
}
function fn_ab__hpd_youpitheme_migration() {
$db_describe = db_get_hash_array('DESCRIBE ?:product_tabs', 'Field');
if (!empty($db_describe['abt__yt__hide_content'])) {
db_query('UPDATE ?:product_tabs SET ab__smc_hide_content = abt__yt__hide_content');
}
}
function fn_ab__hpd_update_tabs($more, $less, $id, $lang_code)
{
db_query('UPDATE ?:product_tabs_descriptions
SET ab__smc_show_more = ?s, ab__smc_show_less = ?s
WHERE tab_id = ?i AND lang_code = ?s', trim($more), trim($less), $id, $lang_code);
}
function fn_ab__hide_product_description_product_tab_updated($tab_id)
{
if (isset($_REQUEST['tab_data']['ab__smc_show_more']) && isset($_REQUEST['tab_data']['ab__smc_show_less'])) {
fn_ab__hpd_update_tabs($_REQUEST['tab_data']['ab__smc_show_more'], $_REQUEST['tab_data']['ab__smc_show_less'], $tab_id, DESCR_SL);
}
}
function fn_ab__hide_product_description_product_tab_created($tab_id)
{
if (isset($_REQUEST['tab_data']['ab__smc_show_more']) && isset($_REQUEST['tab_data']['ab__smc_show_less'])) {
foreach (array_keys(fn_get_translation_languages()) as $lang) {
fn_ab__hpd_update_tabs($_REQUEST['tab_data']['ab__smc_show_more'], $_REQUEST['tab_data']['ab__smc_show_less'], $tab_id, $lang);
}
}
}
