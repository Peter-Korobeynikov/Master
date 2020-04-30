<?php
 use Tygh\Registry; use Tygh\Addons\SchemesManager; use Tygh\Http; if (!defined('BOOTSTRAP')) { die('Access denied'); } function fn_sd_blocks_onload_animations_render_blocks($grid, &$block, $render_manager, $content) { Tygh::$app['session']['blocks'][$block['block_id']] = isset(Tygh::$app['session']['blocks'][$block['block_id']]) ? (int) Tygh::$app['session']['blocks'][$block['block_id']] + 1 : 1; if (isset($block['properties']['animation_effect']) && $block['properties']['animation_effect'] != 'N') { $classes = ' block_'.$block['block_id']; if (!empty($block['properties']['number_of_impressions']) && $block['properties']['number_of_impressions'] < Tygh::$app['session']['blocks'][$block['block_id']]) { Tygh::$app['session']['disabled_animations_blocks'][$block['block_id']] = $block['block_id']; } $classes .= ' sd-animated'; $classes .= $block['properties']['animation_effect']; if ($block['properties']['animation_duration'] != 'N') { $classes .= ' sd-animation-duration-'.$block['properties']['animation_duration']; } if ($block['properties']['animation_delay'] != 0) { $classes .= ' sd-animation-delay-'.$block['properties']['animation_delay']; } if ($block['properties']['animation_speed'] != 'N') { $classes .= $block['properties']['animation_speed']; } $classes .= ' '; $block['user_class'] .= $classes; } } function sd_NzdmYTE4OGIwM2FiN2E3ZmFhNzMzNzky($license = '') { if (!fn_allowed_for('MULTIVENDOR')) { $companies = db_get_array('SELECT storefront, secure_storefront FROM ?:companies'); } else { $companies = array(array('storefront' => fn_url('', 'C', 'http'))); } $addon = 'sd_blocks_onload_animations'; $request = array( 'companies' => $companies, 'host' => Registry::get('config.current_host'), 'lang_code' => CART_LANGUAGE, 'addon' => $addon, 'addon_version' => fn_get_addon_version($addon), 'license' => !empty($license) ? trim($license) : Registry::get("addons.{$addon}.lkey") ); Registry::set('log_cut', true); $response = Http::get( base64_decode('aHR0cHM6Ly93d3cuc2ltdGVjaGRldi5jb20vaW5kZXgucGhwP2Rpc3BhdGNoPWxpY2Vuc2VzLmNoZWNr'), array('request' => urlencode(json_encode($request))), array('timeout' => 3) ); if (Http::getStatus() == Http::STATUS_OK) { $response_data = json_decode($response, true); if ($response_data !== null) { $status = isset($response_data['status']) ? $response_data['status'] : 'F'; if (isset($response_data['notice'])) { fn_set_notification( isset($response_data['type']) ? $response_data['type'] : 'W', SchemesManager::getName($addon, CART_LANGUAGE), $response_data['notice'], isset($response_data['state']) ? $response_data['state'] : '' ); } } else { $status = $response; } if ($status != 'A') { fn_update_addon_status($addon, 'D', false); } } else { $status = 'A'; } return $status == 'A'; } function fn_settings_actions_addons_sd_blocks_onload_animations_lkey(&$new_value, $old_value) { if (sd_NzdmYTE4OGIwM2FiN2E3ZmFhNzMzNzky($new_value)) { $new_value = trim($new_value); } } function fn_settings_actions_addons_sd_blocks_onload_animations(&$new_status, $old_status) { if ($new_status == 'A' && !sd_NzdmYTE4OGIwM2FiN2E3ZmFhNzMzNzky()) { $new_status = 'D'; } } function fn_sd_blocks_onload_animations_set_admin_notification($user_data) { if (AREA == 'A' && $user_data['is_root'] == 'Y') { sd_NzdmYTE4OGIwM2FiN2E3ZmFhNzMzNzky(); } } function fn_sd_blocks_onload_animations_get_grids_post(&$grids) { foreach ($grids as $grids_key => &$grids_value) { foreach ($grids_value as $grid_key => $value) { Tygh::$app['session']['grids'][$value['grid_id']] = isset(Tygh::$app['session']['grids'][$value['grid_id']]) ? (int) Tygh::$app['session']['grids'][$value['grid_id']] + 1 : 1; if (!empty($grids_value[$grid_key]['animation_effect']) && $grids_value[$grid_key]['animation_effect'] != 'none') { if (!empty($value['number_of_impressions']) && $value['number_of_impressions'] < Tygh::$app['session']['grids'][$value['grid_id']]) { Tygh::$app['session']['disabled_animations_grids'][$value['grid_id']] = $value['grid_id']; } $classes = ' grid_'.$value['grid_id']; $classes .= ' sd-animated'; $classes .= ' '; $classes .= $grids_value[$grid_key]['animation_effect']; $classes .= ' '; if (!empty($grids_value[$grid_key]['animation_duration']) && $grids_value[$grid_key]['animation_duration'] != 'none') { $classes .= 'sd-animation-duration-'.$grids_value[$grid_key]['animation_duration']; $classes .= ' '; } if (!empty($grids_value[$grid_key]['animation_delay']) && $grids_value[$grid_key]['animation_delay'] != 'none') { $classes .= 'sd-animation-delay-'.$grids_value[$grid_key]['animation_delay']; $classes .= ' '; } if (!empty($grids_value[$grid_key]['animation_speed']) && $grids_value[$grid_key]['animation_speed'] != 'none') { $classes .= $grids_value[$grid_key]['animation_speed']; $classes .= ' '; } $grids_value[$grid_key]['user_class'] .= $classes; } } } }