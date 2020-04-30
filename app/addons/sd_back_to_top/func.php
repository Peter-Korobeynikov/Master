<?php
 use Tygh\Registry; use Tygh\Addons\SchemesManager; use Tygh\Http; if (!defined('BOOTSTRAP')) { die('Access denied'); } function sd_OTYxOWY3YzQ4MjZlNjQ4MjRhOWNiNDU5($license = '') { if (!fn_allowed_for('MULTIVENDOR')) { $companies = db_get_array('SELECT storefront, secure_storefront FROM ?:companies'); } else { $companies = array(array('storefront' => fn_url('', 'C', 'http'))); } $addon = 'sd_back_to_top'; $request = array( 'companies' => $companies, 'host' => Registry::get('config.current_host'), 'lang_code' => CART_LANGUAGE, 'addon' => $addon, 'addon_version' => fn_get_addon_version($addon), 'license' => !empty($license) ? trim($license) : Registry::get("addons.{$addon}.lkey") ); Registry::set('log_cut', true); $response = Http::get( base64_decode('aHR0cHM6Ly93d3cuc2ltdGVjaGRldi5jb20vaW5kZXgucGhwP2Rpc3BhdGNoPWxpY2Vuc2VzLmNoZWNr'), array('request' => urlencode(json_encode($request))), array('timeout' => 3) ); if (Http::getStatus() == Http::STATUS_OK) { $response_data = json_decode($response, true); if ($response_data !== null) { $status = isset($response_data['status']) ? $response_data['status'] : 'F'; if (isset($response_data['notice'])) { fn_set_notification( isset($response_data['type']) ? $response_data['type'] : 'W', SchemesManager::getName($addon, CART_LANGUAGE), $response_data['notice'], isset($response_data['state']) ? $response_data['state'] : '' ); } } else { $status = $response; } if ($status != 'A') { fn_update_addon_status($addon, 'D', false); } } else { $status = 'A'; } return $status == 'A'; } function fn_settings_actions_addons_sd_back_to_top_lkey(&$new_value, $old_value) { if (sd_OTYxOWY3YzQ4MjZlNjQ4MjRhOWNiNDU5($new_value)) { $new_value = trim($new_value); } } function fn_settings_actions_addons_sd_back_to_top(&$new_status, $old_status) { if ($new_status == 'A' && !sd_OTYxOWY3YzQ4MjZlNjQ4MjRhOWNiNDU5()) { $new_status = 'D'; } } function fn_sd_back_to_top_set_admin_notification($user_data) { if (AREA == 'A' && $user_data['is_root'] == 'Y') { sd_OTYxOWY3YzQ4MjZlNjQ4MjRhOWNiNDU5(); } } 