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
foreach (glob(Registry::get('config.dir.addons') . '/abt__unitheme2/functions/fn.abt__ut2_*.php') as $functions) {
require_once $functions;
}
function fn_abt__ut2_install()
{
$objects = [
['t' => '?:pages',
'i' => [
['n' => 'abt__ut2_microdata_schema_type', 'p' => 'varchar(32) NOT NULL DEFAULT \'\''],
],
],
['t' => '?:bm_grids',
'i' => [
['n' => 'abt__ut2_extended', 'p' => 'char(1) NOT NULL DEFAULT \'0\''],
['n' => 'abt__ut2_padding', 'p' => 'varchar(20) NOT NULL DEFAULT \'\''],
],
],
['t' => '?:bm_grids',
'i' => [
['n' => 'abt__ut2_show_in_tabs', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
['n' => 'abt__ut2_use_ajax', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
],
],
['t' => '?:banners',
'i' => call_user_func(function () {
$devices_enabled_fields = [
'tablet' => fn_get_schema('abt__ut2_banners', 'tablet', 'php', true),
'mobile' => fn_get_schema('abt__ut2_banners', 'mobile', 'php', true),
];
$fields = [
'use_avail_period' => ['p' => 'char(1) NOT NULL DEFAULT \'N\'', 'add_sql' => ['ALTER TABLE ?:banners CHANGE `type` `type` CHAR(20) NOT NULL DEFAULT \'G\'']],
'avail_from' => ['p' => 'int(11) NOT NULL DEFAULT \'0\''],
'avail_till' => ['p' => 'int(11) NOT NULL DEFAULT \'0\''],
'button_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'button_text_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'button_text_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'button_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'button_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'title_font_size' => ['p' => 'varchar(7) NOT NULL DEFAULT \'18px\''],
'title_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'title_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'title_font_weight' => ['p' => 'varchar(4) NOT NULL DEFAULT \'300\''],
'title_tag' => ['p' => 'enum(\'div\',\'h1\',\'h2\',\'h3\') NOT NULL DEFAULT \'div\''],
'background_image_size' => ['p' => 'enum(\'cover\',\'contain\') NOT NULL DEFAULT \'cover\''],
'title_shadow' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'description_font_size' => ['p' => 'varchar(7) NOT NULL DEFAULT \'13px\''],
'description_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'description_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'description_bg_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'description_bg_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'main_image' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'background_image' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'object' => ['p' => 'enum(\'image\',\'video\') NOT NULL DEFAULT \'image\''],
'background_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'background_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'class' => ['p' => 'varchar(100) NOT NULL DEFAULT \'\''],
'color_scheme' => ['p' => 'enum(\'light\',\'dark\') NOT NULL DEFAULT \'light\''],
'content_valign' => ['p' => 'enum(\'top\',\'center\',\'bottom\') NOT NULL DEFAULT \'center\''],
'content_align' => ['p' => 'enum(\'left\',\'center\',\'right\') NOT NULL DEFAULT \'center\''],
'content_full_width' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'content_bg' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'padding' => ['p' => 'varchar(27) NOT NULL DEFAULT \'\''],
'how_to_open' => ['p' => 'enum(\'in_this_window\',\'in_new_window\',\'in_popup\') NOT NULL DEFAULT \'in_this_window\''],
'data_type' => ['p' => 'enum(\'url\',\'blog\',\'promotion\') NOT NULL DEFAULT \'url\''],
'youtube_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'youtube_autoplay' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'youtube_hide_controls' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
];
$t = [];
foreach ($fields as $f => $data) {
if (!preg_match('/_image$/', $f)) {
$data['n'] = "abt__ut2_{$f}";
$t[] = $data;
}
foreach ($devices_enabled_fields as $device => $device_fields) {
if (in_array($f, $device_fields)) {
if (!preg_match('/_image$/', $f)) {
$data['n'] = "abt__ut2_{$device}_{$f}";
$t[] = $data;
}
$t[] = ['n' => "abt__ut2_{$device}_{$f}_use_own", 'p' => 'char(1) NOT NULL DEFAULT \'N\''];
}
}
}
$t[] = ['n' => 'abt__ut2_tablet_use', 'p' => 'char(1) NOT NULL DEFAULT \'N\''];
$t[] = ['n' => 'abt__ut2_mobile_use', 'p' => 'char(1) NOT NULL DEFAULT \'N\''];
return $t;
}),
],
['t' => '?:banner_descriptions',
'i' => call_user_func(function () {
$devices_enabled_fields = [
'tablet' => fn_get_schema('abt__ut2_banners', 'tablet'),
'mobile' => fn_get_schema('abt__ut2_banners', 'mobile'),
];
$fields = [
'button_text' => ['p' => 'varchar(50) NOT NULL DEFAULT \'\''],
'title' => ['p' => 'varchar(255) NOT NULL DEFAULT \'\''],
'url' => ['p' => 'varchar(255) NOT NULL DEFAULT \'\''],
'description' => ['p' => 'mediumtext'],
'youtube_id' => ['p' => 'varchar(15) NOT NULL DEFAULT \'\''],
];
$t = [];
foreach ($fields as $f => $data) {
$t[] = ['n' => "abt__ut2_{$f}", 'p' => $data['p']];
foreach ($devices_enabled_fields as $device => $device_fields) {
if (in_array($f, $device_fields)) {
$t[] = ['n' => "abt__ut2_{$device}_{$f}", 'p' => $data['p']];
$t[] = ['n' => "abt__ut2_{$device}_{$f}_use_own", 'p' => 'char(1) NOT NULL DEFAULT \'N\''];
}
}
}
return $t;
}),
],
['t' => '?:static_data',
'i' => [
['n' => 'abt__ut2_mwi__status', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
['n' => 'abt__ut2_mwi__text_position', 'p' => 'varchar(32) NOT NULL DEFAULT \'bottom\''],
['n' => 'abt__ut2_mwi__dropdown', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
['n' => 'abt__ut2_mwi__label_color', 'p' => 'varchar(11) NOT NULL DEFAULT \'\''],
['n' => 'abt__ut2_mwi__label_background', 'p' => 'varchar(11) NOT NULL DEFAULT \'\''],
],
],
['t' => '?:static_data_descriptions',
'i' => [
['n' => 'abt__ut2_mwi__desc', 'p' => 'mediumtext'],
['n' => 'abt__ut2_mwi__text', 'p' => 'mediumtext'],
['n' => 'abt__ut2_mwi__label', 'p' => 'varchar(100) NOT NULL DEFAULT \'\''],
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
if (!empty($f['add_sql']) && is_array($f['add_sql'])) {
foreach ($f['add_sql'] as $sql) {
db_query($sql);
}
}
}
}
}
if (!empty($o['indexes']) && is_array($o['indexes'])) {
foreach ($f['indexes'] as $index => $keys) {
$existing_indexes = db_get_array('SHOW INDEX FROM ?p WHERE key_name = ?s', $o['t'], $index);
if (empty($existing_indexes) && !empty($keys)) {
db_query('ALTER TABLE ?p ADD INDEX ?p (?p)', $o['t'], $index, $keys);
}
}
}
}
}
}
if (Registry::get('addons.buy_together.status') == 'A') {
db_query('ALTER TABLE ?:buy_together_descriptions MODIFY COLUMN name varchar(255)');
}
fn_abt__ut2_refresh_icons();
fn_abt__ut2_migration_v4113a_v4113b();
}
function fn_abt__ut2_migration_v4113a_v4113b()
{
$remove_fields = [
'?:bm_blocks' => [
'abt__ut2_show_on_desktop',
'abt__ut2_show_on_mobile',
],
];
foreach ($remove_fields as $table => $fields) {
$available_fields = db_get_fields('DESCRIBE ' . $table);
if (!empty($available_fields)) {
foreach ($fields as $field) {
if (in_array($field, $available_fields)) {
db_query("ALTER TABLE {$table} DROP COLUMN $field");
}
}
}
}
}
function fn_abt__unitheme2_get_products_post(&$products, $params, $lang_code)
{
if (AREA == 'C' && Registry::get('addons.discussion.status') == 'A' && empty($params['get_conditions']) && $products) {
$company_cond = '';
if (Registry::ifGet('addons.discussion.product_share_discussion', 'N') == 'N') {
$company_cond = fn_get_discussion_company_condition('?:discussion.company_id');
}
$posts = db_get_hash_single_array('SELECT p.product_id, ifnull(count(dp.post_id),0) as discussion_amount_posts
FROM ?:discussion
INNER JOIN ?:products as p ON (?:discussion.object_id = p.product_id)
INNER JOIN ?:discussion_posts as dp ON (?:discussion.thread_id = dp.thread_id AND ?:discussion.object_type = \'P\' ?p)
WHERE dp.status = \'A\' and p.product_id in (?n)
GROUP BY p.product_id', ['product_id', 'discussion_amount_posts'], $company_cond, array_keys($products));
foreach ($products as $p_id => $p) {
$products[$p_id]['discussion_amount_posts'] = !empty($posts[$p_id]) ? $posts[$p_id] : 0;
}
}
}
function fn_abt__unitheme2_get_products($params, &$fields, $sortings, $condition, &$join, $sorting, $group_by, $lang_code, $having)
{
$settings = fn_get_abt__ut2_settings();
$auth = &Tygh::$app['session']['auth'];
if (AREA == 'C' && $settings['product_list']['show_qty_discounts'] == 'Y') {
$join .= db_quote(' LEFT JOIN ?:product_prices AS opt_prices ON opt_prices.product_id = products.product_id AND opt_prices.lower_limit > 1 AND opt_prices.usergroup_id IN (?n)', $auth['usergroup_ids']);
$fields[] = ' (opt_prices.product_id IS NOT NULL) AS ab__is_qty_discount';
}
}
function fn_abt__unitheme2_get_products_pre(&$params, $items_per_page, $lang_code)
{
fn_abt__ut2_required_products_get_products_pre($params);
fn_abt__ut2_bestsellers_get_products_pre($params, $lang_code);
fn_abt__ut2_customers_also_bought_get_products_pre($params);
}
function fn_abt__unitheme2_update_addon_status_post($addon, $status, $show_notification, $on_install, $allow_unmanaged, $old_status, $scheme)
{
if ($addon == 'buy_together' && $status == 'A') {
db_query('ALTER TABLE `?:buy_together_descriptions` MODIFY COLUMN `name` VARCHAR(255)');
}
}
function fn_abt__unitheme2_description_tables_post(&$description_tables)
{
$description_tables[] = 'abt__ut2_settings';
}
function fn_abt__ut2_get_sub_or_parent_categories()
{
if (!empty($_REQUEST['category_id'])) {
$subcategories = fn_get_subcategories($_REQUEST['category_id']);
if (empty($subcategories)) {
$parent_category_id = db_get_field('SELECT parent_id FROM ?:categories WHERE company_id = ?i AND category_id = ?i', fn_get_runtime_company_id(), $_REQUEST['category_id']);
$subcategories = fn_get_subcategories($parent_category_id);
}
return $subcategories;
}
return false;
}
function fn_abt__ut2_get_light_menu($params)
{
$return = [];
uasort($params['item_ids'], 'abt_ut2_sort_item_positions');
foreach ($params['item_ids'] as $key => $menu) {
$get_params = [
'section' => 'A',
'get_params' => true,
'icon_name' => '',
'use_localization' => true,
'status' => 'A',
'request' => [
'menu_id' => $key,
],
'multi_level' => true,
'generate_levels' => true,
];
$get_params['abt__ut2_light_menu'] = Registry::get('settings.abt__device') == 'mobile';
$m = [];
$m['menus'] = fn_top_menu_form(fn_get_static_data($get_params));
$icons = fn_get_image_pairs(array_keys($m['menus']), 'abt__ut2/menu-with-icon', 'M', true, false);
foreach ($m['menus'] as $m_key => &$item) {
$item['image'] = array_shift($icons[$m_key]);
if (Registry::get('settings.abt__device') == 'desktop' && $params['properties']['abt_menu_icon_items'] == 'Y' && $item['subitems']) {
$subicons = fn_get_image_pairs(array_keys($item['subitems']), 'abt__ut2/menu-with-icon', 'M', true, false);
foreach ($item['subitems'] as $subkey => &$subitem) {
$subitem['image'] = array_shift($subicons[$subkey]);
}
}
}
if ($menu['abt__ut2_menu_state'] == 'hide_items') {
$m['menu_name'] = fn_get_menu_name($key);
}
$m['user_class'] = $menu['abt__ut2_custom_class'];
$return[$key] = $m;
}
return [$return];
}
function abt_ut2_sort_item_positions($a, $b)
{
return ($a['position'] - $b['position']);
}
function fn_abt__ut2_check_versions()
{
$ret = [
'core' => PRODUCT_NAME . ': version ' . PRODUCT_VERSION . ' ' . PRODUCT_EDITION . (PRODUCT_STATUS != '' ? (' (' . PRODUCT_STATUS . ')') : '') . (PRODUCT_BUILD != '' ? (' ' . PRODUCT_BUILD) : ''),
];
$theme = Tygh::$app['storefront']->theme_name;
$ret['theme'] = [
'id' => $theme,
'name' => __($theme),
];
if ($theme == 'abt__unitheme2') {
$data = json_decode(fn_get_contents(Registry::get('config.dir.root') . "/design/themes/$theme/manifest.json"), true);
if ($data !== false) {
$ret['theme']['manifest_version'] = "v{$data['ab']['version']} " . __('from') . " {$data['ab']['date']}";
$ret['theme']['addon_version'] = 'v' . fn_get_addon_version($theme);
}
}
fn_set_hook('abt__check_versions', $ret, $theme);
return $ret;
}
function fn_abt__ut2_refresh_icons($addon = 'abt__unitheme2')
{
$repo_path = Registry::get('config.dir.themes_repository') . $addon;
$file_content = fn_get_contents($repo_path . "/css/addons/{$addon}/icons.less");
$file_content = str_replace('media/custom_fonts', 'media/fonts/addons/' . $addon, $file_content);
file_put_contents(Registry::get('config.dir.design_backend') . "css/addons/{$addon}/front_icons.less", $file_content);
$extensions = ['eot', 'woff', 'ttf', 'svg'];
$fonts_dir = Registry::get('config.dir.design_backend') . "media/fonts/addons/{$addon}/";
fn_mkdir($fonts_dir);
for ($i = 0; $i < count($extensions); $i++) {
if (file_exists($repo_path . '/media/custom_fonts/uni2-icons.' . $extensions[$i])) {
copy($repo_path . '/media/custom_fonts/uni2-icons.' . $extensions[$i], $fonts_dir . 'uni2-icons.' . $extensions[$i]);
}
}
}
function fn_abt__unitheme2_check_is_installation_correct($is_mv = true)
{
$answ = ['is_ok' => true, 'descr' => 'ok'];
if ($is_mv) {
$theme_mv_addon = Registry::get('addons.abt__unitheme2_mv');
if (empty($theme_mv_addon)) {
$answ['is_ok'] = false;
$answ['descr'] = 'uninstalled';
}
if (!empty($theme_mv_addon) && $theme_mv_addon['status'] == 'D') {
$answ['is_ok'] = false;
$answ['descr'] = 'disabled';
}
}
return $answ;
}
