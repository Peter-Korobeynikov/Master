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
namespace Tygh;
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
use Tygh\Languages\Languages;
class ABTUT2
{
public static function fn_update_microdata($microdata, $lang_code = DESCR_SL)
{
if (empty($microdata['field']) || empty($microdata['value'])) {
return false;
}
if (empty($microdata['id'])) {
$microdata['company_id'] = fn_allowed_for('ULTIMATE') ? fn_get_runtime_company_id() : 0;
$microdata['id'] = db_query('INSERT INTO ?:abt__ut2_microdata ?e', $microdata);
foreach (Languages::getAll() as $microdata['lang_code'] => $lang) {
db_replace_into('abt__ut2_microdata_description', $microdata);
}
} else {
db_query('UPDATE ?:abt__ut2_microdata SET ?u WHERE id = ?i', $microdata, $microdata['id']);
db_query('UPDATE ?:abt__ut2_microdata_description SET ?u WHERE id = ?i AND lang_code = ?s', $microdata, $microdata['id'], $lang_code);
}
return $microdata['id'];
}
public static function fn_delete_microdata($ids, $inverse = false)
{
if (!$inverse && empty($ids)) {
return false;
}
$condition = ($inverse) ? db_quote('id NOT IN (?n)', $ids) : db_quote('id IN (?n)', $ids);
$company_id = fn_allowed_for('ULTIMATE') ? fn_get_runtime_company_id() : 0;
$condition .= db_quote(' AND company_id = ?i', $company_id);
$ids_to_delete = db_get_fields('SELECT id FROM ?:abt__ut2_microdata WHERE ?p', $condition);
if (!empty($ids_to_delete)) {
db_query('DELETE FROM ?:abt__ut2_microdata WHERE id IN (?n)', $ids_to_delete);
db_query('DELETE FROM ?:abt__ut2_microdata_description WHERE id IN (?n)', $ids_to_delete);
}
}
public static function fn_get_microdata($lang_code = CART_LANGUAGE)
{
$company_id = fn_allowed_for('ULTIMATE') ? fn_get_runtime_company_id() : 0;
$fields = [
'?:abt__ut2_microdata.id',
'?:abt__ut2_microdata.field',
'?:abt__ut2_microdata_description.value',
];
$microdata = db_get_array('SELECT ' . implode(', ', $fields) . '
FROM ?:abt__ut2_microdata
INNER JOIN ?:abt__ut2_microdata_description ON ?:abt__ut2_microdata_description.id = ?:abt__ut2_microdata.id AND ?:abt__ut2_microdata_description.lang_code = ?s
WHERE ?:abt__ut2_microdata.company_id = ?i
ORDER BY ?:abt__ut2_microdata.id ASC', $lang_code, $company_id);
return $microdata;
}
}
