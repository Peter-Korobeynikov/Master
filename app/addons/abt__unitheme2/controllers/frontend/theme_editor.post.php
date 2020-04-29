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
use Tygh\Themes\Themes;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

if (Registry::get('runtime.mode') == 'save'
&& !empty($_REQUEST['style']['abt__ut2_parent_style'])
&& !empty($_REQUEST['style']['name'])
&& $_REQUEST['style']['abt__ut2_parent_style'] != $_REQUEST['style']['name']
&& Registry::get('runtime.layout.style_id') == $_REQUEST['style']['name']
&& Themes::areaFactory('C')->getThemeName() == 'abt__unitheme2'
) {
$new_style = $_REQUEST['style']['name'] . '.less';
$old_style = $_REQUEST['style']['abt__ut2_parent_style'] . '.less';
fn_abt__ut2_copy_less_settings($new_style, $old_style);
}
return;
}

if (Registry::get('runtime.mode') == 'duplicate'
&& !empty($_REQUEST['name'])
&& Registry::get('runtime.layout.style_id') == $_REQUEST['name']
&& Themes::areaFactory('C')->getThemeName() == 'abt__unitheme2'
) {
$new_style = $_REQUEST['name'] . '.less';
$old_style = $_REQUEST['style_id'] . '.less';
fn_abt__ut2_copy_less_settings($new_style, $old_style);
}
function fn_abt__ut2_copy_less_settings($new_style, $old_style)
{
$company_id = Registry::get('runtime.company_id');
db_query('REPLACE INTO ?:abt__ut2_less_settings (`section`, `name`, `company_id`, `value`, `style`)
SELECT `section`, `name`, ?i, `value`, ?s
FROM ?:abt__ut2_less_settings
WHERE company_id = ?i AND style = ?s', $company_id, $new_style, $company_id, $old_style);
}
