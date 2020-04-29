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
function fn_autoinstall_extradata_menu()
{
$m = db_get_field('SELECT menu_id FROM ?:menus_descriptions WHERE name like \'AB: Main menu%\' ORDER BY menu_id LIMIT 1');
$m && db_query('UPDATE ?:bm_blocks_content SET content = ?s WHERE block_id in (?n)', serialize(['menu' => $m]), db_get_fields('SELECT block_id FROM ?:bm_blocks WHERE properties LIKE \'%abt__ut2_dropdown_vertical_mwi%\' OR properties LIKE \'%abt__ut2_dropdown_horizontal_mwi%\''));
$m = db_get_field('SELECT menu_id FROM ?:menus_descriptions WHERE name like \'AB: Top menu%\' ORDER BY menu_id LIMIT 1');
$m && db_query('UPDATE ?:bm_blocks_content SET content = ?s WHERE block_id = ?i', serialize(['menu' => $m]), db_get_field('SELECT block_id FROM ?:bm_snapping WHERE grid_id in (SELECT grid_id FROM ?:bm_grids WHERE user_class LIKE \'%top-header-menu%\') LIMIT 1'));
}
function fn_autoinstall_extradata_blog($blog_page_id = 0)
{
if ($blog_page_id) {
$setting = [
'section' => 'general',
'name' => 'blog_page_id',
'company_id' => 1,
'value' => serialize($blog_page_id),
];
foreach (['ru', 'en', 'ar'] as $setting['lang_code']) {
db_query('REPLACE INTO ?:abt__ut2_settings ?e', $setting);
}
}
}
