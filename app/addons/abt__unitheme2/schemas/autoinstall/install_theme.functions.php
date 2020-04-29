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
use Tygh\BlockManager\Layout;
use Tygh\Registry;
use Tygh\Themes\Styles;
use Tygh\Themes\Themes;
use Tygh\Settings;
function fn_autoinstall_theme($theme, $style)
{
ob_start();
fn_install_theme_files($theme, $theme);
$_REQUEST['theme_name'] = $theme;
$_REQUEST['allow_overwrite'] = 'Y';
$_REQUEST['style'] = $style;
$_REQUEST['settings_values'] = [
58 => 58,
307 => 307,
63 => 63,
147 => 147,
148 => 148,
149 => 149,
288 => 288,
290 => 290,
184 => 184,
185 => 185,
186 => 186,
187 => 187,
292 => 292,
293 => 293,
190 => 190,
191 => 191,
192 => 192,
193 => 193,
194 => 194,
195 => 195,
196 => 196,
197 => 197,
];
$is_exist = Layout::instance()->getList([
'theme_name' => $_REQUEST['theme_name'],
]);
$company_id = Registry::get('runtime.company_id');
if (fn_allowed_for('ULTIMATE') && empty($company_id)) {
$company_id = fn_get_runtime_company_id();
Registry::set('runtime.company_id', $company_id);
}
if (empty($is_exist)) {
fn_install_theme($_REQUEST['theme_name'], $company_id);
} else {
Settings::instance()->updateValue('theme_name', $_REQUEST['theme_name'], '', true, $company_id);
}
if (isset($_REQUEST['allow_overwrite']) && !empty($_REQUEST['settings_values'])) {
Themes::factory($_REQUEST['theme_name'])->overrideSettings($_REQUEST['settings_values']);
}
$layout = Layout::instance($company_id)->getDefault($_REQUEST['theme_name']);
Styles::factory(fn_get_theme_path('[theme]', 'C'))->setStyle($layout['layout_id'], $_REQUEST['style']);
fn_clear_cache('assets');
fn_init_layout(['s_layout' => $layout['layout_id']]);
fn_clear_cache('assets');
ob_end_clean();
return __('ab__ut2.autoinstall.theme');
}
