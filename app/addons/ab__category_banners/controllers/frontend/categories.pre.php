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
if ($mode == 'view' && !empty($_REQUEST['category_id'])) {
$_statuses = array('A', 'H');
$_condition = fn_get_localizations_condition('localization', true);
$preview = fn_is_preview_action($auth, $_REQUEST);
if (!$preview) {
$_condition .= ' AND (' . fn_find_array_in_set($auth['usergroup_ids'], 'usergroup_ids', true) . ')';
$_condition .= db_quote(' AND status IN (?a)', $_statuses);
}
if (fn_allowed_for('ULTIMATE')) {
$_condition .= fn_get_company_condition('?:categories.company_id');
}
$category_exists = db_get_field('SELECT category_id FROM ?:categories WHERE category_id = ?i ?p', $_REQUEST['category_id'], $_condition);
if (!empty($category_exists)) {
$selected_layout = fn_get_products_layout($_REQUEST);
fn_ab__cb_pick_banner_by_cid($_REQUEST['category_id'], $selected_layout);
} else {
return array(CONTROLLER_STATUS_NO_PAGE);
}
}
