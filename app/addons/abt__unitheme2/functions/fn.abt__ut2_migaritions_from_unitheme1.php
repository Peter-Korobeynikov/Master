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
use Tygh\Languages\Po;
use Tygh\Languages\Values as LanguageValues;
function fn_abt__ut2_mfu1_generate_array_from_core_po_files($diff_arr, $files = ['core', 'addons/call_requests', 'addons/wishlist', 'addons/bestsellers', 'addons/discussion'])
{
$langs = scandir(Registry::get('config.dir.var') . 'langs');
$langs = array_diff($langs, ['.', '..']);
$answer = [];
foreach ($langs as $lang) {
$core = [];
foreach ($files as $filename) {
$core = array_merge($core, Po::getValues(Registry::get('config.dir.var') . "langs/$lang/$filename.po"));
}
$answer[$lang] = array_intersect_key($core, $diff_arr);
}
return $answer;
}
function fn_abt__ut2_mfu1_get_empty_langs_var($not_existing, $all_core_vars, $langs)
{
$langs_array = [];
$not_existing = array_flip($not_existing);
foreach ($langs as $lang) {
$langs_array[$lang] = array_intersect_key($all_core_vars[$lang], $not_existing);
}
return $langs_array;
}
function fn_abt__ut2_mfu1_check_is_var_in_system($vars_array)
{
$no_vars_to_print = true;
$tmpl = [];
foreach ($vars_array as $var) {
$str = explode('::', $var)[1];
$tmpl[] = $str;
}
$vars_in_system = db_get_fields('SELECT name
FROM ?:language_values
WHERE name IN (?a)
AND lang_code = ?s', $tmpl, CART_LANGUAGE);
for ($i = 0; $i < count($vars_in_system); $i++) {
$vars_in_system[$i] = 'Languages::' . $vars_in_system[$i];
}
$diff = array_diff($vars_array, $vars_in_system);
if (!empty($diff)) {
$no_vars_to_print = false;
}
return $no_vars_to_print ? $no_vars_to_print : $diff;
}
function fn_abt__ut2_mfu1_update_ut1_deleted_lang_vars()
{
$lang_vars = $_REQUEST['lang_vars'];
$params = ['clear' => false];
$is_Y = false;
foreach ($lang_vars as $key => $var) {
if ($var['set'] == 'Y') {
$is_Y = true;
foreach ($var['langs'] as $language => $value) {
LanguageValues::updateLangVar([
[
'name' => $key,
'value' => $value,
],
], $language, $params);
}
}
}
return $is_Y;
}
