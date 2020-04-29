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
use Tygh\Languages\Languages;
use Tygh\Registry;
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
function fn_ab__video_gallery_install()
{
$columns = db_get_fields('DESCRIBE ?:ab__video_gallery_descriptions');
if (!in_array('youtube_id', $columns)) {
db_query('ALTER TABLE ?:ab__video_gallery_descriptions ADD `youtube_id` varchar(16) NOT NULL default \'\'');
}
$columns = db_get_fields('DESCRIBE ?:ab__video_gallery');
if (in_array('youtube_id', $columns)) {
$videos = db_get_array('SELECT video_id, youtube_id FROM ?:ab__video_gallery');
foreach ($videos as $video) {
db_query('UPDATE ?:ab__video_gallery_descriptions SET youtube_id = ?s WHERE video_id = ?i', $video['youtube_id'], $video['video_id']);
}
db_query('ALTER TABLE ?:ab__video_gallery DROP COLUMN `youtube_id`');
}
db_query('UPDATE ?:ab__video_gallery SET icon_type = ?s WHERE icon_type = ?i', 'snapshot', 'button');
}
function fn_ab__vg_delete_video($video_id)
{
if (!empty($video_id)) {
db_query('DELETE FROM ?:ab__video_gallery WHERE video_id = ?i', $video_id);
db_query('DELETE FROM ?:ab__video_gallery_descriptions WHERE video_id = ?i', $video_id);
fn_delete_image_pairs($video_id, 'ab__vg_video');
}
}
function fn_ab__vg_update_video($video, $video_id, $lang_code = DESCR_SL)
{
if (empty($video['youtube_id'])) {
return false;
}
if (!empty($video_id)) {
$video['lang_code'] = $lang_code;
db_replace_into('ab__video_gallery', $video);
db_replace_into('ab__video_gallery_descriptions', $video);
} else {
$video_id = $video['video_id'] = db_query('INSERT INTO ?:ab__video_gallery ?e', $video);
foreach (Languages::getAll() as $video['lang_code'] => $language) {
db_query('INSERT INTO ?:ab__video_gallery_descriptions ?e', $video);
}
}
return $video_id;
}
function fn_ab__vg_get_videos($product_id, $lang_code = CART_LANGUAGE)
{
static $videos = [];
if (empty($product_id)) {
return false;
}
if (!isset($videos[$product_id])) {
$fields = [
'?:ab__video_gallery.video_id',
'?:ab__video_gallery.product_id',
'?:ab__video_gallery.status',
'?:ab__video_gallery.pos',
'?:ab__video_gallery.icon_type',
'?:ab__video_gallery_descriptions.youtube_id',
'?:ab__video_gallery_descriptions.title',
'?:ab__video_gallery_descriptions.description',
];
if (AREA === 'C' && Registry::ifGet('addons.product_variations.status', 'D') === 'A') {
$parent_product_id = db_get_field('SELECT parent_product_id FROM ?:products WHERE product_id = ?i', $product_id);
if (!empty($parent_product_id)) {
$product_id = $parent_product_id;
}
}
$condition = db_quote('?:ab__video_gallery.product_id = ?i', $product_id);
if (AREA == 'C') {
$condition .= ' AND ?:ab__video_gallery.status = "A"';
}
$product_videos = db_get_hash_array('SELECT ' . implode(',', $fields) . ' FROM ?:ab__video_gallery
LEFT JOIN ?:ab__video_gallery_descriptions ON ?:ab__video_gallery_descriptions.video_id = ?:ab__video_gallery.video_id AND ?:ab__video_gallery_descriptions.lang_code = ?s
WHERE ?p ORDER BY ?:ab__video_gallery.pos ASC', 'video_id', $lang_code, $condition);
if (!empty($product_videos)) {
$images = fn_get_image_pairs(array_keys($product_videos), 'ab__vg_video', 'M', true, false, $lang_code);
if (!empty($images)) {
foreach ($product_videos as &$video) {
if (!empty($images[$video['video_id']])) {
$video['icon'] = reset($images[$video['video_id']]);
}
}
}
}
$videos[$product_id] = $product_videos;
}
return $videos[$product_id];
}
function fn_ab__vg_update_settings($settings, $product_id)
{
fn_ab__vg_delete_settings($product_id);
foreach ($settings as $var => $value) {
db_replace_into('ab__video_gallery_settings', [
'product_id' => $product_id,
'var' => $var,
'value' => $value,
]);
}
}
function fn_ab__vg_get_setting($product_id)
{
if (empty($product_id)) {
return false;
}
$settings = db_get_hash_single_array('SELECT var, value FROM ?:ab__video_gallery_settings WHERE product_id = ?i', ['var', 'value'], $product_id);
return $settings;
}
function fn_ab__vg_delete_settings($product_id)
{
if (!empty($product_id)) {
db_query('DELETE FROM ?:ab__video_gallery_settings WHERE product_id = ?i', $product_id);
}
}
function fn_ab__video_gallery_get_products_post(&$products, $params, $lang_code)
{
if (Registry::ifGet('addons.ab__video_gallery.show_in_lists', 'N') == 'Y' && !empty($products)) {
$products_ids = fn_array_column($products, 'product_id');
$array = db_get_hash_array('SELECT video_id, product_id FROM ?:ab__video_gallery WHERE product_id IN (?n)', 'product_id', $products_ids);
foreach ($products as &$product) {
$product['ab__vg_videos'] = !empty($array[$product['product_id']]);
}
}
}
function fn_ab__video_gallery_delete_product_post($product_id, $product_deleted)
{
if ($product_deleted) {
$video_ids = db_get_fields('SELECT video_id FROM ?:ab__video_gallery WHERE product_id = ?i', $product_id);
if (!empty($video_ids)) {
foreach ($video_ids as $video_id) {
fn_ab__vg_delete_video($video_id);
}
}
fn_ab__vg_delete_settings($product_id);
}
}
function fn_ab__video_gallery_update_product_post($product_data, $product_id, $lang_code, $create)
{
if (isset($product_data['ab__vg_settings'])) {
fn_ab__vg_update_settings($product_data['ab__vg_settings'], $product_id);
}
if (isset($product_data['ab__vg_videos'])) {
$product_video_ids = [];
foreach ($product_data['ab__vg_videos'] as $key => $video) {
$video['product_id'] = $product_id;
$video_id = fn_ab__vg_update_video($video, $video['video_id'], $lang_code);
if ($video_id) {
$product_video_ids[$key] = $video_id;
}
}
$old_video_ids = db_get_fields('SELECT video_id FROM ?:ab__video_gallery WHERE product_id = ?i AND video_id NOT IN (?n)', $product_id, $product_video_ids);
foreach ($old_video_ids as $old_video_id) {
fn_ab__vg_delete_video($old_video_id);
}
fn_attach_image_pairs('video_icon', 'ab__vg_video', 0, $lang_code, $product_video_ids);
}
}
function fn_ab__video_gallery_clone_product($product_id, $pid)
{
$videos = fn_ab__vg_get_videos($product_id);
if (!empty($videos)) {
foreach ($videos as $video) {
$old_id = $video['video_id'];
$video['video_id'] = 0;
$video['product_id'] = $pid;
$new_id = fn_ab__vg_update_video($video, 0);
fn_clone_image_pairs($new_id, $old_id, 'ab__vg_video');
}
}
$settings = fn_ab__vg_get_setting($product_id);
fn_ab__vg_update_settings($settings, $pid);
}
function fn_ab__vg_exim_get_video_id($pattern, &$alt_keys, &$object, &$skip_get_primary_object_id)
{
if (!empty($skip_get_primary_object_id)) {
return;
}
if (empty($alt_keys['video_id'])) {
$video_id = db_get_field('SELECT v.video_id FROM ?:ab__video_gallery AS v
INNER JOIN ?:ab__video_gallery_descriptions AS vd ON v.video_id = vd.video_id AND vd.lang_code = ?s AND vd.youtube_id = ?s
WHERE v.product_id = ?i', $object['lang_code'], $object['youtube_id'], $object['product_id']);
if (!empty($video_id)) {
$alt_keys['video_id'] = $object['video_id'] = $video_id;
} else {
$skip_get_primary_object_id = true;
}
}
}
function fn_ab__vg_exim_remove_videos($remove_videos, $import_data, $processed_data)
{
if ($remove_videos == 'Y' && !empty($import_data)) {
$updated_products = [];
foreach ($import_data as $item) {
if (empty($updated_products[$item['product_id']])) {
$updated_products[$item['product_id']] = $item['product_id'];
$video_ids = db_get_fields('SELECT video_id FROM ?:ab__video_gallery WHERE product_id = ?i', $item['product_id']);
if (!empty($video_ids)) {
foreach ($video_ids as $video_id) {
fn_ab__vg_delete_video($video_id);
}
}
}
}
}
}
