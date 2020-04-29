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
die('Access denied');}
use Tygh\Registry;use Tygh\Ab_landingCategories\Demodata;use Tygh\Enum\ProductTracking;function fn_ab__lc_install(){
$objects=[
[
'table'=>'?:categories',
'field'=>'ab__lc_catalog_image_control',
'sql'=>'ALTER TABLE ?:categories ADD ab__lc_catalog_image_control CHAR(5) NOT NULL DEFAULT \'none\'',
],
[
'table'=>'?:categories',
'field'=>'ab__lc_landing',
'sql'=>'ALTER TABLE ?:categories ADD ab__lc_landing CHAR(1) NOT NULL DEFAULT \'N\'',
],
[
'table'=>'?:categories',
'field'=>'ab__lc_subsubcategories',
'sql'=>'ALTER TABLE ?:categories ADD ab__lc_subsubcategories int(4) NOT NULL DEFAULT 0',
],
[
'table'=>'?:categories',
'field'=>'ab__lc_menu_id',
'sql'=>'ALTER TABLE ?:categories ADD ab__lc_menu_id int(8) NOT NULL DEFAULT 0',
],
[
'table'=>'?:categories',
'field'=>'ab__lc_how_to_use_menu',
'sql'=>'ALTER TABLE ?:categories ADD ab__lc_how_to_use_menu char(1) NOT NULL DEFAULT \'N\'',
],
[
'table'=>'?:categories',
'field'=>'ab__lc_inherit_control',
'sql'=>'ALTER TABLE ?:categories ADD ab__lc_inherit_control char(1) NOT NULL DEFAULT \'N\'',
],
];if (!empty($objects) && is_array($objects)) {
foreach ($objects as $object) {
$fields=db_get_fields('DESCRIBE '.$object['table']);if (!empty($fields) && is_array($fields)) {
$is_present_field=false;foreach ($fields as $f) {
if ($f == $object['field']) {
$is_present_field=true;break;}}
if (!$is_present_field) {
db_query($object['sql']);if (!empty($object['add_sql'])) {
foreach ($object['add_sql'] as $sql) {
db_query($sql);}}}}}}}
function fn_ab__lc_link(){
return '<a href="'.fn_url('categories.ab__lc_catalog','C').'" target="_blank">'.__('ab__lc_catalog').'</a>';}
function fn_ab__landing_categories_dispatch_assign_template(){
if (AREA == 'C'
&& Registry::get('addons.ab__landing_categories.add_catalog_to_breadcrumbs') == 'Y'
&& in_array(Registry::get('runtime.controller'),['products','categories'])
&& Registry::get('runtime.mode') != 'ab__lc_catalog'
) {
fn_add_breadcrumb(__('ab__lc.breadcrumb_catalog'),'categories.ab__lc_catalog');}}
function fn_ab__landing_categories_update_category_post($category_data,$category_id,$lang_code){
if (AREA == 'A') {
call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'Z29gYnV1YmRpYGpuYmhmYHFianN0')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YmNgYG1kYGRidWJtcGhgamRwbw==')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YmNgYG1kYGRidWJtcGhgamRwbw==')),$category_id,$lang_code); }}
function fn_ab__landing_categories_get_category_data_post($category_id,$field_list,$get_main_pair,$skip_company_condition,$lang_code,&$category_data){
if (AREA == 'A') {
$category_data[call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YmNgYG1kYGRidWJtcGhgamRwbw=='))]=call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'Z29gaGZ1YGpuYmhmYHFianN0')),$category_id,call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YmNgYG1kYGRidWJtcGhgamRwbw==')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'Tg==')),true,false,$lang_code);}}
function fn_ab__landing_categories_get_categories($params,$join,&$condition,&$fields,$group_by,$sortings,$lang_code){
if (AREA == 'C') {
$fields[]='?:categories.ab__lc_catalog_image_control';} elseif (AREA == call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'Qg==')) && !empty($_REQUEST[call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YmNgYG1kYG1ib2Vqb2g='))]) && $_REQUEST[call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YmNgYG1kYG1ib2Vqb2g='))] == call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'Wg=='))) {
$landing_categories=call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'ZWNgaGZ1YGdqZm1ldA==')),'SELECT id_path FROM ?:categories WHERE ab__lc_landing=\'Y\'');if (empty($landing_categories)) {
$condition.=call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'IUJPRSEx'));} else {
$list=[];foreach ($landing_categories as $landing_category) {
$list=call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YnNzYnpgbmZzaGY=')),$list,(array) call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'ZnlxbXBlZg==')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'MA==')),$landing_category));}
$condition.=call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'ZWNgcnZwdWY=')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'IUJPRSFAO2RidWZocHNqZnQvZGJ1Zmhwc3pgamUhSk8hKUBvKg==')),call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YnNzYnpgdm9qcnZm')),$list));call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'Z29gdGZ1YG9wdWpnamRidWpwbw==')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'WA==')),call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YGA=')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'eGJzb2pvaA=='))),call_user_func(call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YGA=')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gbc`8g365g````')),call_user_func(call_user_func(ab_____('tus`sfqmbdf'),'7f254f','',ab_____('8g365gcbtf758g365g`efdpef')),'YmNgYG1kL2RidWZocHN6L21qdHUvdXBwbXVqcQ=='))));}}}
function fn_ab__landing_categories_get_products_before_select(&$params,$join,&$condition,$u_condition,$inventory_join_cond,$sortings,$total,$items_per_page,$lang_code,$having){
static $ab_lc=false;if (AREA == 'C' && !$ab_lc
&& $_SERVER['REQUEST_METHOD'] == 'GET'
&& Registry::get('runtime.controller').'.'.Registry::get('runtime.mode') == 'categories.view'
&& !empty($params['cid']) && !is_array($params['cid'])
&& 'Y' == db_get_field('SELECT IFNULL(ab__lc_landing,\'N\') FROM ?:categories WHERE category_id=?i',$params['cid'])
) {
$condition.=' AND 0 ';$ab_lc=true;$structure=fn_ab__lc_get_structure([],$params['cid']);Tygh::$app['view']->assign('ab__lc_landing_categories',$structure);}}
function fn_ab__lc_get_structure($input_structure,$category_id,$inherit_control=true){
$structure=[];if ($category_id > 0) {
$cd=db_get_row('SELECT IFNULL(ab__lc_landing,\'N\') as is_landing_category,IFNULL(ab__lc_how_to_use_menu,\'N\') as how_to_use_menu,ab__lc_menu_id as menu_id,ab__lc_inherit_control as inherit_control,id_path FROM ?:categories WHERE category_id=?i',$category_id);if ($cd['is_landing_category'] == 'Y') {
$first_categories=[];$menu=[];$cats=[];$menu_id=intval($cd['menu_id']);if ($menu_id > 0 && $menu_id == db_get_field('SELECT menu_id FROM ?:menus WHERE menu_id=?i ?p',$menu_id,fn_get_company_condition())) {
$_REQUEST['menu_id']=$menu_id;$p=[
'section'=>'A',
'status'=>'A',
'generate_levels'=>true,
'get_params'=>true,
'multi_level'=>true,
'plain'=>false,
];$menu=fn_top_menu_form(fn_get_static_data($p));unset($_REQUEST['menu_id']);foreach ($menu as &$m) {
if (!empty($m['param_3'])) {
list($type,$object_id,$extra)=explode(':',$m['param_3']);if ($type == 'C') {
$first_categories['m_'.$m['param_id']]=$m['category_id']=$object_id;}}}}
$max_nesting_level=2 + count((array) explode('/',$cd['id_path']));$p=[
'category_id'=>$category_id,
'get_images'=>false,
'simple'=>true,
'max_nesting_level'=>$max_nesting_level,
];list($cats)=fn_get_categories($p);$cats=fn_ab__lc_standardize($cats);$first_categories=array_merge($first_categories,array_keys($cats));if ($inherit_control && !empty($cats) && $cd['inherit_control'] == 'Y') {
foreach ($cats as $c_k=>$c_v) {
if (!empty($c_v['subitems'])) {
$cats[$c_k]['subitems']=call_user_func(__FUNCTION__,$c_v['subitems'],$c_v['category_id'],$inherit_control);}}}
switch ($cd['how_to_use_menu']) {
case 'N':
$structure=$cats;break;case 'R':
if (!empty($menu)) {
$structure=$menu;} else {
$structure=$cats;}
break;case 'A':
if (!empty($cats)) {
foreach ($cats as $scat) {
$structure[]=$scat;}}
if (!empty($menu)) {
foreach ($menu as $sm) {
$structure[]=$sm;}}
break;case 'P':
if (!empty($menu)) {
foreach ($menu as $sm) {
$structure[]=$sm;}}
if (!empty($cats)) {
foreach ($cats as $scat) {
$structure[]=$scat;}}
break;}
$first_categories=array_unique($first_categories);if (!empty($first_categories)) {
$ab__lc_catalog_icons=fn_get_image_pairs($first_categories,'ab__lc_catalog_icon','M',true,false);$main_pairs=fn_get_image_pairs($first_categories,'category','M',true,true);$ab__lc_catalog_image_controls=db_get_hash_single_array('SELECT category_id,ab__lc_catalog_image_control FROM ?:categories WHERE category_id in (?a)',['category_id','ab__lc_catalog_image_control'],$first_categories);foreach ($structure as &$i) {
if (isset($i['category_id'])) {
$i['ab__lc_catalog_image_control']=!empty($ab__lc_catalog_image_controls[$i['category_id']])?$ab__lc_catalog_image_controls[$i['category_id']]:'';if (!empty($ab__lc_catalog_icons[$i['category_id']])) {
$img=$ab__lc_catalog_icons[$i['category_id']];$i['ab__lc_catalog_icon']=array_shift($img);}
if (!empty($main_pairs[$i['category_id']])) {
$img=$main_pairs[$i['category_id']];$i['main_pair']=array_shift($img);}}}
foreach ($structure as $k1=>$m1) {
if (!empty($m1['subitems'])) {
foreach ($m1['subitems'] as $k2=>$m2) {
if (!isset($m2['param_id'])) {
unset($structure[$k1]['subitems'][$k2]);} else {
if (!empty($m2['subitems'])) {
foreach ($m2['subitems'] as $k3=>$m3) {
if (!isset($m3['param_id'])) {
unset($structure[$k1]['subitems'][$k2]['subitems'][$k3]);}}}}}}}}}}
return (!empty($structure))?$structure:$input_structure;}
function fn_ab__lc_standardize($items,$id_name='category_id',$name='category',$children_name='subcategories',$href_prefix='categories.view?category_id='){
$result=[];foreach ($items as $v) {
$result[$v[$id_name]]=[
'category_id'=>$v[$id_name],
'param_id'=>$v[$id_name],
'item'=>$v[$name],
'href'=>$href_prefix.$v[$id_name],
];if (!empty($v[$children_name])) {
$result[$v[$id_name]]['subitems']=fn_ab__lc_standardize($v[$children_name],$id_name,$name,$children_name,$href_prefix);}}
return $result;}
function fn_ab__lc_get_menu_name($id){
$name=Tygh\Menu::getName($id);if (!strlen(trim($name))) {
$name=__('no_data');}
return $name;}
function fn_ab__lc_get_catalog(){
$menu=[];$mode='categories';$category_id=0;$max_nesting_level=3;$menu_id=0;if (Registry::ifGet('addons.ab__landing_categories.catalog_menu',0) > 0) {
$menu_id=Registry::get('addons.ab__landing_categories.catalog_menu');$mode='menu';}
$first_categories=[];if ($mode == 'menu') {
$_REQUEST['menu_id']=$menu_id;$p=[
'status'=>'A',
'section'=>'A',
'generate_levels'=>true,
'get_params'=>true,
'multi_level'=>true,
'plain'=>false,
];$menu=fn_top_menu_form(fn_get_static_data($p));unset($_REQUEST['menu_id']);foreach ($menu as &$m) {
if (!empty($m['param_3'])) {
list($type,$object_id,$extra)=explode(':',$m['param_3']);if ($type == 'C') {
$first_categories[$m['param_id']]=$m['category_id']=$object_id;}}}} elseif ($mode == 'categories') {
$p=[
'category_id'=>$category_id,
'get_images'=>false,
'simple'=>true,
'max_nesting_level'=>$max_nesting_level,
];list($menu)=fn_get_categories($p);$menu=fn_ab__lc_standardize($menu);$first_categories=array_keys($menu);}
if (!empty($first_categories)) {
$ab__lc_catalog_icons=fn_get_image_pairs($first_categories,'ab__lc_catalog_icon','M',true,false);$main_pairs=fn_get_image_pairs($first_categories,'category','M',true,true);$ab__lc_catalog_image_controls=db_get_hash_single_array('SELECT category_id,ab__lc_catalog_image_control FROM ?:categories WHERE category_id in (?a)',['category_id','ab__lc_catalog_image_control'],$first_categories);foreach ($menu as &$i) {
if (isset($i['category_id'])) {
$i['ab__lc_catalog_image_control']=!empty($ab__lc_catalog_image_controls[$i['category_id']])?$ab__lc_catalog_image_controls[$i['category_id']]:'';if (!empty($ab__lc_catalog_icons[$i['category_id']])) {
$img=$ab__lc_catalog_icons[$i['category_id']];$i['ab__lc_catalog_icon']=array_shift($img);}
if (!empty($main_pairs[$i['category_id']])) {
$img=$main_pairs[$i['category_id']];$i['main_pair']=array_shift($img);}
if (empty($i['href'])) {
$i['href']=fn_url('categories.view&category_id='.$i['category_id']);}}}
foreach ($menu as $k1=>$m1) {
if (!empty($m1['subitems'])) {
foreach ($m1['subitems'] as $k2=>$m2) {
if (!isset($m2['param_id'])) {
unset($menu[$k1]['subitems'][$k2]);} else {
if (!empty($m2['subitems'])) {
foreach ($m2['subitems'] as $k3=>$m3) {
if (!isset($m3['param_id'])) {
unset($menu[$k1]['subitems'][$k2]['subitems'][$k3]);}}}}}}}}
return $menu;}
function fn_ab__landing_categories_install_demodata($param='A'){
$answers=[];foreach (Demodata::$install_functions as $func) {
$val=Demodata::$func($param);if (!$val) {
return false;}
$answers[$func]=$val;}
return $answers;}
function fn_ab__lc_get_category_tree($variant_id=0){
$categories=[];if ($variant_id) {
$condition=$join='';if (Registry::get('settings.General.inventory_tracking') == 'Y' &&
Registry::get('settings.General.show_out_of_stock_products') == 'N'
) {
$join='LEFT JOIN ?:product_options_inventory as i ON i.product_id=p.product_id';$condition=db_quote(' AND (CASE p.tracking' .
' WHEN ?s THEN i.amount > 0' .
' WHEN ?s THEN p.amount > 0' .
' ELSE 1' .
' END)',ProductTracking::TRACK_WITH_OPTIONS,ProductTracking::TRACK_WITHOUT_OPTIONS);}
$paths=db_get_fields("SELECT id_path
FROM ?:categories
WHERE category_id in (SELECT DISTINCT category_id
FROM ?:products_categories
WHERE product_id in (SELECT p.product_id
FROM ?:product_features_values as pfv
INNER JOIN ?:products as p ON (p.product_id=pfv.product_id AND p.status='A'){$join}
WHERE variant_id=?i {$condition}
)
)",$_REQUEST['variant_id']);if ($paths) {
$all_categories=[];foreach ($paths as $path) {
$all_categories=array_merge($all_categories,(array) explode('/',$path));}
$all_categories=array_unique($all_categories);list($categories)=fn_get_categories([
'get_images'=>false,
'status'=>['A'],
'get_frontend_urls'=>false,
'add_root'=>false,
'item_ids'=>implode(',',$all_categories),]);}}
return $categories;}
function fn_ab__lc_prepare_url_params($category_id,$variant_id=0){
static $filter=[];static $landing_categories=null;$feature_hash='';if (is_null($landing_categories)){
$categories=db_get_fields('SELECT category_id FROM ?:categories WHERE ab__lc_landing=\'Y\'');$landing_categories=!empty($categories)?$categories:[];}
if ($variant_id) {
if (!isset($filter[$variant_id])) {
$filter[$variant_id]=db_get_row('SELECT pf.filter_id,pf.categories_path
FROM ?:product_filters as pf
INNER JOIN ?:product_feature_variants as pfv ON pf.feature_id=pfv.feature_id
WHERE pfv.variant_id=?i',$variant_id);if (!empty($filter[$variant_id]['categories_path'])) {
$filter[$variant_id]['categories_path']=(array) explode(',',$filter[$variant_id]['categories_path']);}}
if (!empty($filter[$variant_id])
&& (empty($filter[$variant_id]['categories_path']) || in_array($category_id,$filter[$variant_id]['categories_path']))
&& (empty($landing_categories) || !in_array($category_id,$landing_categories))
) {
$feature_hash='&features_hash='.fn_generate_filter_hash([$filter[$variant_id]['filter_id']=>$variant_id]);}}
return "categories.view&category_id={$category_id}{$feature_hash}";}
function fn_ab__landing_categories_ab__as_other_objects(&$objects){
if (Registry::get('addons.ab__landing_categories.ab__as_add_to_sitemap') == 'Y') {
$objects['ab__landing_categories']=['Y'];}}
function fn_ab__landing_categories_sitemap_link_object(&$link,$object,$value){
if ($object == 'ab__landing_categories') {
$link='categories.ab__lc_catalog';}}
