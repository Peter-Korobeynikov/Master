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
$schema = [
'section' => 'products',
'name' => __('ab__video_gallery'),
'pattern_id' => 'ab__video_gallery',
'key' => ['video_id'],
'order' => 99,
'table' => 'ab__video_gallery',
'permissions' => [
'import' => 'manage_catalog',
'export' => 'view_catalog',
],
'references' => [
'ab__video_gallery_descriptions' => [
'reference_fields' => ['video_id' => '#key', 'lang_code' => '#lang_code'],
'join_type' => 'LEFT',
],
'images_links' => [
'reference_fields' => ['object_id' => '#key', 'object_type' => 'ab__vg_video', 'type' => 'M'],
'join_type' => 'LEFT',
'import_skip_db_processing' => true,
],
],
'import_get_primary_object_id' => [
'get_video_id' => [
'function' => 'fn_ab__vg_exim_get_video_id',
'args' => ['$pattern', '$alt_keys', '$object', '$skip_get_primary_object_id'],
'import_only' => true,
],
],
'pre_processing' => [
'remove_videos' => [
'function' => 'fn_ab__vg_exim_remove_videos',
'args' => ['@remove_videos', '$import_data', '$processed_data'],
'import_only' => true,
],
],
'range_options' => [
'selector_url' => 'products.manage',
'object_name' => __('products'),
],
'options' => [
'lang_code' => [
'title' => 'language',
'type' => 'languages',
'default_value' => [DEFAULT_LANGUAGE],
],
'remove_videos' => [
'title' => 'ab__vg.exim.remove_videos',
'description' => 'ab__vg.exim.remove_videos.text',
'type' => 'checkbox',
'import_only' => true,
],
],
'export_fields' => [
'Video ID' => [
'alt_key' => true,
'db_field' => 'video_id',
],
'Product ID' => [
'required' => true,
'db_field' => 'product_id',
],
'Language' => [
'table' => 'ab__video_gallery_descriptions',
'db_field' => 'lang_code',
'type' => 'languages',
'alt_key' => false,
'required' => true,
'multilang' => true,
],
'Youtube ID' => [
'required' => true,
'table' => 'ab__video_gallery_descriptions',
'db_field' => 'youtube_id',
'multilang' => true,
],
'Title' => [
'table' => 'ab__video_gallery_descriptions',
'db_field' => 'title',
'multilang' => true,
],
'Description' => [
'table' => 'ab__video_gallery_descriptions',
'db_field' => 'description',
'multilang' => true,
],
'Position' => [
'db_field' => 'pos',
],
'Status' => [
'db_field' => 'status',
],
'Icon Type' => [
'db_field' => 'icon_type',
],
'Image' => [
'db_field' => 'image_id',
'table' => 'images_links',
'process_get' => ['fn_exim_export_image', '#this', 'ab__vg_video', '@images_path'],
'process_put' => ['fn_exim_import_images', '@images_path', '%Thumbnail%', '#this', '0', 'M', '#ab__video_gallery.video_id', 'ab__vg_video'],
],
],
];
return $schema;
