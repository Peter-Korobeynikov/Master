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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
function fn_abt__unitheme2_get_banner_data_post($banner_id, $lang_code, &$banner)
{
if (!empty($banner) && in_array($banner['type'], [ABT__UT2_BANNER_TYPE])) {
foreach (['', '_tablet', '_mobile'] as $device) {
$banner["abt__ut2{$device}_main_image"] = fn_get_image_pairs($banner['banner_id'], rtrim('abt__ut2/banners/' . ltrim($device ? $device : 'all', '_'), '/'), 'M', true, true);
$banner["abt__ut2{$device}_background_image"] = fn_get_image_pairs($banner['banner_id'], rtrim('abt__ut2/banners/' . ltrim($device ? $device : 'all', '_'), '/'), 'A', true, true);
$banner["abt__ut2{$device}_background_image"] = reset($banner["abt__ut2{$device}_background_image"]);
}
}
}
function fn_abt__unitheme2_get_banners($params, &$condition, $sorting, $limit, $lang_code)
{
if (AREA == 'C') {
$sub_cond = db_quote(' ?:banners.type like ?s AND ( ?:banners.abt__ut2_use_avail_period = \'N\'
OR
(
?:banners.abt__ut2_use_avail_period = \'Y\'
AND (?:banners.abt__ut2_avail_from = 0 OR ?:banners.abt__ut2_avail_from <= ' . TIME . ')
AND (?:banners.abt__ut2_avail_till = 0 OR ?:banners.abt__ut2_avail_till >= ' . TIME . ')
)
)', ABT__UT2_BANNER_TYPE);
$condition .= db_quote(" AND IF (?:banners.type not like ?s, 'available', IF ($sub_cond, 'available', 'not available') ) = 'available' ", ABT__UT2_BANNER_TYPE);
}
}
function fn_abt__unitheme2_get_banners_post(&$banners, $params)
{
if (AREA == 'C') {
$devices_enabled_fields = [
'tablet' => fn_get_schema('abt__ut2_banners', 'tablet'),
'mobile' => fn_get_schema('abt__ut2_banners', 'mobile'),
];
$device = Registry::get('settings.abt__device');
foreach ($banners as &$banner) {
if ($banner['type'] == ABT__UT2_BANNER_TYPE) {
$banner = array_merge($banner, fn_get_banner_data($banner['banner_id']));
$banner['abt__ut2_device_settings'] = 'desktop';
$device = Registry::get('settings.abt__device');
if (in_array($device, ['tablet', 'mobile'])
&& !empty($devices_enabled_fields[$device])
&& $banner["abt__ut2_{$device}_use"] == 'Y'
) {
$banner['abt__ut2_device_settings'] = $device;
foreach ($devices_enabled_fields[$device] as $field) {
if ($banner["abt__ut2_{$device}_{$field}_use_own"] == 'Y') {
$banner["abt__ut2_{$field}"] = $banner["abt__ut2_{$device}_{$field}"];
}
}
}
}
}
}
}
function fn_abt__unitheme2_get_banner_data($banner_id, $lang_code, &$fields, $joins, $condition)
{
static $abt__ut_fields = [];
if (empty($abt__ut_fields)) {
$devices_enabled_fields = [
'tablet' => fn_get_schema('abt__ut2_banners', 'tablet'),
'mobile' => fn_get_schema('abt__ut2_banners', 'mobile'),
];
$abt__ut_fields[] = '?:banners.abt__ut2_use_avail_period';
$abt__ut_fields[] = '?:banners.abt__ut2_avail_from';
$abt__ut_fields[] = '?:banners.abt__ut2_avail_till';
$abt__ut_fields[] = '?:banners.abt__ut2_button_use';
$abt__ut_fields[] = '?:banners.abt__ut2_button_text_color';
$abt__ut_fields[] = '?:banners.abt__ut2_button_text_color_use';
$abt__ut_fields[] = '?:banners.abt__ut2_button_color';
$abt__ut_fields[] = '?:banners.abt__ut2_button_color_use';
$abt__ut_fields[] = '?:banners.abt__ut2_title_color';
$abt__ut_fields[] = '?:banners.abt__ut2_title_color_use';
$abt__ut_fields[] = '?:banners.abt__ut2_title_font_size';
$abt__ut_fields[] = '?:banners.abt__ut2_title_font_weight';
$abt__ut_fields[] = '?:banners.abt__ut2_title_tag';
$abt__ut_fields[] = '?:banners.abt__ut2_background_image_size';
$abt__ut_fields[] = '?:banners.abt__ut2_title_shadow';
$abt__ut_fields[] = '?:banners.abt__ut2_description_font_size';
$abt__ut_fields[] = '?:banners.abt__ut2_description_color';
$abt__ut_fields[] = '?:banners.abt__ut2_description_color_use';
$abt__ut_fields[] = '?:banners.abt__ut2_description_bg_color';
$abt__ut_fields[] = '?:banners.abt__ut2_description_bg_color_use';
$abt__ut_fields[] = '?:banners.abt__ut2_object';
$abt__ut_fields[] = '?:banners.abt__ut2_background_color';
$abt__ut_fields[] = '?:banners.abt__ut2_background_color_use';
$abt__ut_fields[] = '?:banners.abt__ut2_class';
$abt__ut_fields[] = '?:banners.abt__ut2_color_scheme';
$abt__ut_fields[] = '?:banners.abt__ut2_content_valign';
$abt__ut_fields[] = '?:banners.abt__ut2_content_align';
$abt__ut_fields[] = '?:banners.abt__ut2_content_full_width';
$abt__ut_fields[] = '?:banners.abt__ut2_content_bg';
$abt__ut_fields[] = '?:banners.abt__ut2_padding';
$abt__ut_fields[] = '?:banners.abt__ut2_how_to_open';
$abt__ut_fields[] = '?:banners.abt__ut2_data_type';
$abt__ut_fields[] = '?:banners.abt__ut2_youtube_use';
$abt__ut_fields[] = '?:banners.abt__ut2_youtube_autoplay';
$abt__ut_fields[] = '?:banners.abt__ut2_youtube_hide_controls';
$abt__ut_fields[] = '?:banner_descriptions.abt__ut2_button_text';
$abt__ut_fields[] = '?:banner_descriptions.abt__ut2_title';
$abt__ut_fields[] = '?:banner_descriptions.abt__ut2_url';
$abt__ut_fields[] = '?:banner_descriptions.abt__ut2_description';
$abt__ut_fields[] = '?:banner_descriptions.abt__ut2_youtube_id';
foreach (['tablet', 'mobile'] as $device) {
$abt__ut_fields[] = "?:banners.abt__ut2_{$device}_use";
foreach ($devices_enabled_fields[$device] as $f) {
$table = '?:banners';
if (in_array($f, ['button_text', 'title', 'url', 'description', 'youtube_id'])) {
$table = '?:banner_descriptions';
}
if (!preg_match('/_image$/', $f)) {
$abt__ut_fields[] = "{$table}.abt__ut2_{$device}_{$f}";
}
$abt__ut_fields[] = "{$table}.abt__ut2_{$device}_{$f}_use_own";
}
}
}
$fields = array_merge($fields, $abt__ut_fields);
}
function fn_abt__ut2_build_youtube_link($d, $only_params = false)
{
$link = "https://www.youtube.com/embed/{$d['abt__ut2_youtube_id']}";
$params = [];
$params['rel'] = 'rel=0';
$params['showinfo'] = 'showinfo=0';
$params['modestbranding'] = 'modestbranding=1';
if (!empty($d['abt__ut2_youtube_autoplay']) && $d['abt__ut2_youtube_autoplay'] == 'Y') {
$params['autoplay'] = 'autoplay=1';
$params['mute'] = 'mute=1';
}
if (!empty($d['abt__ut2_youtube_hide_controls']) && $d['abt__ut2_youtube_hide_controls'] == 'Y') {
$params['controls'] = 'controls=0';
}
$p = implode('&', $params);
return ($only_params) ? $p : $link . '?' . $p;
}
function fn_abt__ut2_is_disabled_field($field, $enabled_fields)
{
$result = false;
if (!empty($enabled_fields)) {
$result = !in_array($field, $enabled_fields);
}
return $result;
}
