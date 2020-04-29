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
$company = Registry::get('settings.Company');
if (Registry::get('runtime.company_id')) {
$company_id = Registry::get('runtime.company_id');
} else {
$company_id = fn_get_company_id_by_name($company['company_name']);
}
$logos = fn_get_logos($company_id);
$schema = [
'name' => [
'default_value' => $company['company_name'],
'group' => '',
],
'url' => [
'default_value' => fn_url('', 'C', fn_get_storefront_protocol(), DESCR_SL),
'group' => '',
],
'logo' => [
'default_value' => $logos['theme']['image']['image_path'],
'group' => '',
],
'sameAs' => [
'default_value' => '',
'group' => '',
],
'streetAddress' => [
'default_value' => $company['company_address'],
'group' => 'address',
],
'postalCode' => [
'default_value' => $company['company_zipcode'],
'group' => 'address',
],
'addressLocality' => [
'default_value' => fn_get_country_name($company['company_country'], CART_LANGUAGE) . ', ' . fn_get_state_name($company['company_state'], $company['company_country']) . ', ' . __('abt__ut2.city_short') . ' ' . $company['company_city'],
'group' => 'address',
],
'telephone' => [
'default_value' => $company['company_phone'],
'group' => '',
],
'email' => [
'default_value' => $company['company_support_department'],
'group' => '',
],
];
return $schema;
