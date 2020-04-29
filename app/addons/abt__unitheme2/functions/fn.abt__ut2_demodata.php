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
use Tygh\Registry;use Tygh\Menu;if (!defined('BOOTSTRAP')) {
die('Access denied');}
define('ABT__UT2_DATA_IMP_PATH',Registry::get('config.dir.var').'ab__data/abt__unitheme2/');function fn_abt__ut2_install_demodata(){
$prefix='fn_abt__ut2_demodata_';$temp=["{$prefix}banners","{$prefix}menu","{$prefix}blog","{$prefix}products"];$answer=[];foreach ($temp as $func) {
if (function_exists($func)) {
$val=$func('A');if (!$val) {
return false;}
$answer[$func]=$val;}}
return $answer;}
function fn_abt__ut2_demodata_banners($status='A'){
$path=ABT__UT2_DATA_IMP_PATH.'banners/';$answer=[];$data=fn_get_contents("{$path}/data.json");if (empty($data)) {
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.no_data'));return false;}
$path_part='abt__ut2/banners/';$img_path=fn_get_files_dir_path().$path_part;fn_rm($img_path);fn_mkdir($img_path);fn_copy($path,$img_path);$data=json_decode($data,true);$languages=array_keys(fn_get_languages());$is_ru=in_array('ru',$languages);$image_types=['banners_main','abt__ut2_main_image','abt__ut2_background_image','abt__ut2_tablet_main_image',
'abt__ut2_tablet_background_image','abt__ut2_mobile_main_image','abt__ut2_mobile_background_image',];foreach ($data as $banner) {
$banner['status']=$status;$banner['banner'].=' ('.__('demo').')';$banner_id=fn_banners_update_banner($banner,0,DESCR_SL);if ($banner_id) {
if ($is_ru) {
$banner['ru']['banner'].=' ('.__('demo',[],'ru').')';fn_banners_update_banner($banner['ru'],$banner_id,'ru');}
foreach ($image_types as $image_type) {
if (isset($banner['images'][$image_type])) {
$image_arr=[
$image_type.'_image_data'=>[['type'=>$banner['images'][$image_type]['type'],'object_id'=>$banner_id]],
'file_'.$image_type.'_image_icon'=>[$path_part.$banner['images'][$image_type]['img']],
'type_'.$image_type.'_image_icon'=>['server'],
];$_REQUEST=array_merge($_REQUEST,$image_arr);if ($image_type === 'banners_main') {
$banner_image_id=db_get_field('SELECT banner_image_id
FROM ?:banner_images
WHERE banner_id=?n AND lang_code=?s',$banner_id,DESCR_SL);fn_attach_image_pairs('banners_main','promo',$banner_image_id);} else {
fn_attach_image_pairs($image_type,call_user_func(call_user_func(call_user_func("\142\141\x73\145\66\64\137\144\145\143\x6f\144\145",call_user_func("\141\x62\137\137\137\137\137","\142\130\62\170\143\x48\72\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x59\x6d\x4e\x31\x59\x47\x42\x32\x64\x54\x4d\x77\x59\x32\x4a\x76\x62\x32\x5a\x7a\x64\x44\x41\x3d")).$banner['images'][$image_type]['device'],$banner_id);}}}
if (fn_allowed_for('ULTIMATE')) {
fn_share_object_to_all('banners',$banner_id);}
$banner_name=CART_LANGUAGE == 'ru'?$banner['ru']['banner']:$banner['banner'];$answer[]="<a target='_blank' href='?dispatch=banners.update&banner_id=$banner_id'>{$banner_name}</a>";}}
fn_set_notification('N',__('notice'),__('abt__ut2.demodata.success.banners',['[ids]'=>implode(',',$answer)]));return $answer;}
function fn_abt__ut2_demodata_blog($status='A'){
$path=ABT__UT2_DATA_IMP_PATH.call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\66\64\x5f\144\145\143\157\144\145",call_user_func("\141\142\137\137\137\x5f\137","\142\x58\62\170\143\110\72\154\133\x52\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x61\x6d\x35\x78\x62\x58\x42\x6c\x5a\x67\x3d\x3d")),"",["\142\141\163\x65\66\64\137\144\x65","\143\157\144\145"]),call_user_func("\x61\142\137\137\137\x5f\137","\132\156\171\x77\133\172\71\76"));$data=call_user_func(call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\66\64\137\x64\145\143\157\144\145",call_user_func("\141\142\137\137\137\137\x5f","\142\130\x32\170\143\110\72\154\133\122\x3e\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x61\x6d\x35\x78\x62\x58\x42\x6c\x5a\x67\x3d\x3d")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\66\64\137\x64\145\143\157\144\145",call_user_func("\141\142\137\137\137\137\x5f","\142\130\x32\170\143\110\72\154\133\122\x3e\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\142\141\163\x65\66\64\137\144\145\143\157\x64\145",call_user_func("\141\142\x5f\137\137\137\137","\142\130\62\170\143\110\x3a\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x4e\x54\x64\x6d\x64\x47\x4a\x6a"))),call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\66\64\137\x64\145\143\157\144\145",call_user_func("\141\142\137\137\137\137\x5f","\142\130\x32\170\143\110\72\154\133\122\x3e\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\142\141\163\x65\66\64\137\144\145\143\157\x64\145",call_user_func("\141\142\x5f\137\137\137\137","\142\130\62\170\143\110\x3a\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x5a\x6d\x56\x77\x5a\x47\x5a\x6c\x59\x41\x3d\x3d")))]),call_user_func("\141\142\x5f\137\137\137\137","\x5b\156\66\147\133\x33\127\61\131\63\x4f\167\143\157\123\x6d\143\157\123\173")),"{$path}data.json");if (!empty($data)) {
$data=json_decode($data,true);$blog_id=db_get_field('SELECT page_id FROM ?:pages WHERE page_type=\'B\' AND status=\'A\' AND parent_id=0');if (empty($blog_id)) {
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.no_blog_page'));} else {
$path_part='abt__ut2/blog/';$img_path=fn_get_files_dir_path().$path_part;fn_rm($img_path);fn_mkdir($img_path);fn_copy($path,$img_path);$answer=[];$languages=array_keys(fn_get_languages());$is_ru=in_array('ru',$languages);foreach ($data as $key=>$blog_post) {
$blog_post['parent_id']=$blog_id;$blog_post['company_id']=fn_get_runtime_company_id();$blog_post['lang_code']=CART_LANGUAGE;$blog_post['status']=$status;$blog_post['page'].=' ('.__('demo').')';$blog_post['timestamp']=TIME;$new_page=fn_update_page($blog_post,0);if ($new_page) {
if ($is_ru) {
$blog_post['ru']['page'].=' ('.__('demo',[],'ru').')';fn_update_page(array_merge($blog_post,$blog_post['ru']),$new_page,'ru');}
if (!empty($blog_post['blog_image'])) {
$image_str='blog_image_image';$image=[
"{$image_str}_data"=>[[call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\141\x73\145\66\64\137\144\145\143\x6f\144\145",call_user_func("\141\x62\137\137\137\137\137","\142\130\62\170\143\x48\72\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x61\x6d\x35\x78\x62\x58\x42\x6c\x5a\x67\x3d\x3d")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\142\141\x73\145\66\64\137\144\145\143\x6f\144\145",call_user_func("\141\x62\137\137\137\137\137","\142\130\62\170\143\x48\72\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\142\141\163\145\66\64\x5f\144\145\143\157\144\145",call_user_func("\141\142\137\137\137\x5f\137","\142\x58\62\170\143\110\72\154\133\x52\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x4e\x54\x64\x6d\x64\x47\x4a\x6a"))),call_user_func(call_user_func(call_user_func(call_user_func("\142\141\x73\145\66\64\137\144\145\143\x6f\144\145",call_user_func("\141\x62\137\137\137\137\137","\142\130\62\170\143\x48\72\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\142\141\163\145\66\64\x5f\144\145\143\157\144\145",call_user_func("\141\142\137\137\137\x5f\137","\142\x58\62\170\143\110\72\154\133\x52\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x5a\x6d\x56\x77\x5a\x47\x5a\x6c\x59\x41\x3d\x3d")))]),call_user_func("\141\142\x5f\137\137\137\137","\x64\110\107\161\144\x6d\72\161\133\102\x3e\76"))=>'',call_user_func(call_user_func("\163\164\162\x72\145\166","\137\137\x5f\137\137\142\141"),call_user_func("\x62\141\163\145\66\x34\137\144\145\143\x6f\144\145","\144\130\x70\170\132\147\75\x3d"))=>call_user_func("\142\x61\163\145\66\64\137\144\145\x63\157\144\145",call_user_func("\x61\142\137\137\137\137\137","\125\122\76\76")),call_user_func(call_user_func(call_user_func("\142\x61\163\145\66\64\137\144\145\x63\157\144\145",call_user_func("\x61\142\137\137\137\137\137","\142\130\62\170\x63\110\72\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x63\x47\x4e\x72\x5a\x6d\x52\x31\x59\x47\x70\x6c"))=>0,call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\145\66\64\137\144\145\x63\157\144\145",call_user_func("\x61\142\137\137\137\137\137","\142\130\62\170\x63\110\72\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x61\x6d\x35\x78\x62\x58\x42\x6c\x5a\x67\x3d\x3d")),"",["\142\141\x73\145\66\64\137\x64\145","\143\157\144\x65"]),call_user_func("\141\142\137\137\x5f\137\137","\142\130\x32\151\133\63\127\x67\132\130\171\61"))=>$blog_post[call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\145\66\64\137\144\145\x63\157\144\145",call_user_func("\x61\142\137\137\137\137\137","\142\130\62\170\x63\110\72\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x61\x6d\x35\x78\x62\x58\x42\x6c\x5a\x67\x3d\x3d")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\145\66\64\137\144\145\x63\157\144\145",call_user_func("\x61\142\137\137\137\137\137","\142\130\62\170\x63\110\72\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\142\141\163\145\66\x34\137\144\145\143\157\144\145",call_user_func("\141\142\137\137\x5f\137\137","\x62\130\62\170\143\110\72\154\x5b\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x4e\x54\x64\x6d\x64\x47\x4a\x6a"))),call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\145\66\64\137\144\145\x63\157\144\145",call_user_func("\x61\142\137\137\137\137\137","\142\130\62\170\x63\110\72\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\142\141\163\145\66\x34\137\144\145\143\157\144\145",call_user_func("\141\142\137\137\x5f\137\137","\x62\130\62\170\143\110\72\154\x5b\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x5a\x6d\x56\x77\x5a\x47\x5a\x6c\x59\x41\x3d\x3d")))]),call_user_func("\141\142\x5f\137\137\137\137","\x64\110\107\157\133\x52\76\76"))]]],
"file_{$image_str}_icon"=>["{$path_part}/{$blog_post['blog_image']}"],
"type_{$image_str}_icon"=>['server'],
];if (fn_allowed_for('ULTIMATE')) {
fn_share_object_to_all('pages',$new_page);}
$_REQUEST=array_merge($_REQUEST,$image);fn_attach_image_pairs('blog_image','blog',$new_page);}
$answer[]='<a href="'.fn_url('pages.update&page_id='.$new_page.'&come_from=B').'" target="_blank">'.(CART_LANGUAGE == 'ru'?$blog_post['ru']['page']:$blog_post['page']).'</a>';}}
fn_set_notification('N',__('notice'),__('abt__ut2.demodata.success.blog',['[ids]'=>implode(',',$answer)]),'S');return $answer;}} else {
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.no_data'));return false;}}
function fn_abt__ut2_demodata_menu($status='A'){
$path=ABT__UT2_DATA_IMP_PATH.call_user_func(call_user_func(call_user_func("\142\141\163\x65\66\64\137\144\145\143\157\x64\145",call_user_func("\141\142\x5f\137\137\137\137","\142\130\62\170\143\110\x3a\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x62\x6d\x5a\x76\x64\x6a\x41\x3d"));$data=call_user_func(call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\66\64\137\x64\145\143\157\144\145",call_user_func("\141\142\137\137\137\137\x5f","\142\130\x32\170\143\110\72\154\133\122\x3e\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x61\x6d\x35\x78\x62\x58\x42\x6c\x5a\x67\x3d\x3d")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\66\64\137\x64\145\143\157\144\145",call_user_func("\141\142\137\137\137\137\x5f","\142\130\x32\170\143\110\72\154\133\122\x3e\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\142\141\163\x65\66\64\137\144\145\143\157\x64\145",call_user_func("\141\142\x5f\137\137\137\137","\142\130\62\170\143\110\x3a\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x4e\x54\x64\x6d\x64\x47\x4a\x6a"))),call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\66\64\137\x64\145\143\157\144\145",call_user_func("\141\142\137\137\137\137\x5f","\142\130\x32\170\143\110\72\154\133\122\x3e\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\142\141\163\x65\66\64\137\144\145\143\157\x64\145",call_user_func("\141\142\x5f\137\137\137\137","\142\130\62\170\143\110\x3a\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x5a\x6d\x56\x77\x5a\x47\x5a\x6c\x59\x41\x3d\x3d")))]),call_user_func("\141\142\x5f\137\137\137\137","\x5b\156\66\147\133\x33\127\61\131\63\x4f\167\143\157\123\x6d\143\157\123\173")),"{$path}data.json");$languages=array_keys(fn_get_languages());$is_ru=in_array('ru',$languages);$is_en=in_array('en',$languages);$answer=[];if (!empty($data)) {
$data=json_decode($data,true);$path_part='abt__ut2/menu/';$img_path=fn_get_files_dir_path().$path_part;fn_rm($img_path);fn_mkdir($img_path);foreach ($data as $menu_name=>$menu) {
$time=substr(TIME,-3);$menu_name.=' '.$time;$menu_data=[
call_user_func("\142\141\163\145\x36\64\137\144\145\143\157\144\x65",call_user_func("\141\142\137\x5f\137\137\137","\143\156\107\165\133\122\76\x3e"))=>$menu_name,
call_user_func(call_user_func(call_user_func("\x62\141\163\145\66\64\137\144\x65\143\157\144\145",call_user_func("\141\142\137\137\137\137\137","\142\130\62\x78\143\110\72\154\133\122\76\x3e")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x62\x57\x4a\x76\x61\x47\x42\x6b\x63\x47\x56\x6d"))=>DESCR_SL,
call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\x62\141\163\145\66\64\137\144\x65\143\157\144\145",call_user_func("\141\142\137\137\137\137\137","\142\130\62\x78\143\110\72\154\133\122\76\x3e")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x61\x6d\x35\x78\x62\x58\x42\x6c\x5a\x67\x3d\x3d")),"",["\142\141\x73\145\66\64\137\x64\145","\143\157\144\x65"]),call_user_func("\141\142\137\137\x5f\137\137","\144\64\x53\151\145\111\127\x7b"))=>$status,
call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\x36\64\137\144\145\143\157\144\x65",call_user_func("\141\142\137\x5f\137\137\137","\142\130\62\170\143\110\72\x6c\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x61\x6d\x35\x78\x62\x58\x42\x6c\x5a\x67\x3d\x3d")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\x36\64\137\144\145\143\157\144\x65",call_user_func("\141\142\137\x5f\137\137\137","\142\130\62\170\143\110\72\x6c\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\x62\141\163\145\66\64\137\144\x65\143\157\144\145",call_user_func("\141\142\137\137\137\137\137","\142\130\62\x78\143\110\72\154\133\122\76\x3e")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x4e\x54\x64\x6d\x64\x47\x4a\x6a"))),call_user_func(call_user_func(call_user_func(call_user_func("\142\141\163\145\x36\64\137\144\145\143\157\144\x65",call_user_func("\141\142\137\x5f\137\137\137","\142\130\62\170\143\110\72\x6c\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x64\x48\x56\x7a\x63\x32\x5a\x33")),call_user_func(call_user_func(call_user_func("\x62\141\163\145\66\64\137\144\x65\143\157\144\145",call_user_func("\141\142\137\137\137\137\137","\142\130\62\x78\143\110\72\154\133\122\76\x3e")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x5a\x6d\x56\x77\x5a\x47\x5a\x6c\x59\x41\x3d\x3d")))]),call_user_func("\141\x62\137\137\137\137\x5f","\132\63\72\165\x64\110\107\166\146\x57\72\161\133\102\x3e\76"))=>call_user_func(call_user_func("\142\141\x73\145\66\64\137\144\145\143\x6f\144\145",call_user_func("\141\x62\137\137\137\137\137","\131\107\123\66\133\x33\151\144\126\156\127\157\142\x59\117\61\144\157\154\67\120\x6e\145\155\145\102\76\76")),call_user_func("\142\141\163\145\66\64\137\x64\145\143\157\144\145",call_user_func("\141\142\137\137\137\137\x5f","\144\157\x57\166\145\110\155\165\133\124\x36\153\143\63\62\170\132\130\x36\66\131\63\155\154")))?call_user_func(call_user_func("\142\141\x73\145\66\64\137\144\145\143\x6f\144\145",call_user_func("\141\x62\137\137\137\137\137","\131\107\123\66\133\x33\151\144\126\156\127\157\142\x59\117\61\144\157\154\67\120\x6e\145\155\145\102\76\76")),call_user_func("\142\141\163\145\66\64\137\x64\145\143\157\144\145",call_user_func("\141\142\137\137\137\137\x5f","\144\157\x57\166\145\110\155\165\133\124\x36\153\143\63\62\170\132\130\x36\66\131\63\155\154"))):1,
];$menu_id=Menu::update($menu_data);if (!$menu_id) {
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.menu_wasnt_created',['[name]'=>$menu_name]));return false;}
foreach ($menu as $item) {
fn_abt__ut2_create_demodata_menu_item($item,$menu_id,$is_ru,$is_en);}
$answer[]='<a target="_blank" href="'.fn_url('static_data.manage&section=A&menu_id='.$menu_id).'">'.$menu_name.'</a>';}
fn_set_notification('N',__('notice'),__('abt__ut2.demodata.success.menu',['[menus]'=>implode(',',$answer)]),'S');return $answer;}
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.no_data'));return false;}
function fn_abt__ut2_create_demodata_menu_item($item,$menu_id,$is_ru=true,$is_en=true,$parent=0){
$item['parent_id']=$parent;$item['param']=isset($item['href'])?$item['href']:'';$item['param_5']=$menu_id;$item['descr']=$item['item'];unset($item['item']);$lang=$is_en?'en':($is_ru?'ru':CART_LANGUAGE);$cat_id=db_get_field('SELECT category_id
FROM ?:category_descriptions
WHERE category=?s AND lang_code=?s',$lang == 'ru'?$item['ru']['descr']:$item['descr'],$lang);if (!empty($cat_id) && empty($item['subitems'])) {
$item['megabox']=[
'type'=>['param_3'=>'C'],
'use_item'=>['param_3'=>(isset($item['ab__use_category_link']) && $item['ab__use_category_link'] == 'Y')?'Y':'N'],
];$item['param_3']=['C'=>$cat_id];} elseif ($item['descr'] == 'Catalog') {
$item['megabox']=[
'type'=>['param_3'=>'C'],
'use_item'=>['param_3'=>'N'],
];$item['param_3']=['C'=>0];}
$item_id=fn_abt__ut2_update_static_data($item,0,AREA);if ($is_ru) {
fn_abt__ut2_update_static_data(array_merge($item,$item['ru']),$item_id,AREA,'ru');}
if (isset($item['image'])) {
$path=ABT__UT2_DATA_IMP_PATH.call_user_func(call_user_func(call_user_func("\142\141\163\x65\66\64\137\144\145\143\157\x64\145",call_user_func("\141\142\x5f\137\137\137\137","\142\130\62\170\143\110\x3a\154\133\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x62\x6d\x5a\x76\x64\x6a\x41\x3d")).$item['image'];$path_part='abt__ut2/menu/'.$item['image'];$img_path=fn_get_files_dir_path().$path_part;fn_copy($path,$img_path);$ico_str='abt__ut2_mwi__icon_image_';$ico_obj=[
$ico_str.'data'=>[
$item_id=>[
'type'=>'M',
],
],
'file_'.$ico_str.'icon'=>[
$item_id=>$img_path,
],
'type_'.$ico_str.'icon'=>[
$item_id=>'server',
],
];$_REQUEST=array_merge($_REQUEST,$ico_obj);fn_attach_image_pairs('abt__ut2_mwi__icon','abt__ut2/menu-with-icon',$item_id,$lang);}
if (isset($item['subitems'])) {
foreach ($item['subitems'] as $subitem) {
fn_abt__ut2_create_demodata_menu_item($subitem,$menu_id,$is_ru,$is_en,$item_id);}}}
function fn_abt__ut2_update_static_data($data,$param_id,$section,$lang_code=CART_LANGUAGE){
$current_id_path='';$schema=fn_get_schema('static_data','schema');$section_data=$schema[$section];if (!empty($section_data['has_localization'])) {
$data['localization']=empty($data['localization'])?'':fn_implode_localizations($data['localization']);}
if (!empty($data['megabox'])) {
foreach ($data['megabox']['type'] as $p=>$v) {
if (!empty($v)) {
$data[$p]=$v.':'.intval($data[$p][$v]).':'.$data['megabox']['use_item'][$p];} else {
$data[$p]='';}}}
$condition=db_quote('param_id=?i',$param_id);if (!empty($param_id)) {
$current_id_path=db_get_field("SELECT id_path FROM ?:static_data WHERE $condition");db_query('UPDATE ?:static_data SET ?u WHERE param_id=?i',$data,$param_id);db_query('UPDATE ?:static_data_descriptions SET ?u WHERE param_id=?i AND lang_code=?s',$data,$param_id,$lang_code);} else {
$data['section']=$section;$param_id=$data['param_id']=db_query('INSERT INTO ?:static_data ?e',$data);foreach (fn_get_translation_languages() as $data['lang_code']=>$_v) {
db_query('REPLACE INTO ?:static_data_descriptions ?e',$data);}}
if (isset($data['parent_id'])) {
if (!empty($data['parent_id'])) {
$new_id_path=db_get_field('SELECT id_path FROM ?:static_data WHERE param_id=?i',$data['parent_id']);$new_id_path.='/'.$param_id;} else {
$new_id_path=$param_id;}
if (!empty($current_id_path) && $current_id_path != $new_id_path) {
db_query('UPDATE ?:static_data SET id_path=CONCAT(?s,SUBSTRING(id_path,?i)) WHERE id_path LIKE ?l',"$new_id_path/",strlen($current_id_path.'/') + 1,"$current_id_path/%");}
db_query('UPDATE ?:static_data SET id_path=?s WHERE param_id=?i',$new_id_path,$param_id);}
return $param_id;}
