<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

use Tygh\Addons\SchemesManager;
use Tygh\Languages\Languages;
use Tygh\Registry;
use Tygh\Settings;
use Tygh\Tools\Url;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_rus_exim_1c_install()
{
    $category = db_get_row('SELECT * FROM ?:categories');

    db_query('UPDATE ?:settings_objects SET value = ?s WHERE name = ?s', $category['category_id'], 'exim_1c_default_category');
}

function fn_settings_variants_addons_rus_exim_1c_exim_1c_order_statuses()
{
    $order_statuses = fn_get_simple_statuses('O', false, false, CART_LANGUAGE);

    return $order_statuses;
}

function fn_settings_variants_addons_rus_exim_1c_exim_1c_import_mode_offers()
{
    $modes = [];

    $is_product_variations_addon_enabled = Registry::get('addons.product_variations.status') === 'A';
    $import_mode = Registry::get('addons.rus_exim_1c.exim_1c_import_mode_offers');
    if ($import_mode === 'variations' || $is_product_variations_addon_enabled) {
        $modes = [
            'variations' => __('addons.rus_exim_1c.variations')
        ];
    }

    if ($import_mode !== 'variations' || isset($_REQUEST['show_all_settings'])) {
        $modes = [];
        if ($is_product_variations_addon_enabled) {
            $modes['variations'] = __('addons.rus_exim_1c.variations');
        }
        $modes['standart'] = __('addons.rus_exim_1c.standart');
        $modes['individual_option'] = __('addons.rus_exim_1c.individual_option');
        $modes['same_option'] = __('addons.rus_exim_1c.same_option');
        $modes['global_option'] = __('addons.rus_exim_1c.global_option');
        $modes['standart_general_price'] = __('addons.rus_exim_1c.standart_general_price');
    }
    return $modes;
}

function fn_update_default_category_settings($setting)
{
    if (isset($setting)) {
        Settings::instance()->updateValue($setting['setting_name'], $setting['setting_value']);
    }
}

function fn_get_default_category_settings($lang_code = DESCR_SL)
{
    $default_category = "";

    $settings = Settings::instance()->getValues('rus_exim_1c', 'ADDON');
    if (!empty($settings['general']['exim_1c_default_category'])) {
        $default_category = $settings['general']['exim_1c_default_category'];
    }

    return $default_category;
}

function fn_settings_variants_addons_rus_exim_1c_exim_1c_lang()
{
    $languages = Languages::getAll();

    foreach ($languages as $language) {
        $langs[$language['lang_code']] = $language['name'];
    }

    return $langs;
}

function fn_rus_exim_1c_get_information()
{
    $storefront_url = Registry::get('config.http_location');

    $exim_1c_info = '';
    if (!empty($storefront_url)) {
        $exim_1c_info = __('addons.rus_exim_1c.information', array(
            '[http_location]' => $storefront_url . '/' . 'exim_1c',
        )) . __('addons.rus_exim_1c.info_store');
    }

    return $exim_1c_info;
}

function fn_rus_exim_1c_get_information_shipping_features()
{
    $exim_1c_info_features = __('exim_1c_information_shipping_features');

    return $exim_1c_info_features;
}

/**
 * Gets the description for section the options of settings.
 *
 * @return string
 */
function fn_rus_exim_1c_get_options_information()
{
    $product_variations_name = SchemesManager::getName('product_variations', CART_LANGUAGE);
    $info = __('addons.rus_exim_1c.options_information_intro', [
        '[product]'   => PRODUCT_NAME,
        '[docs_url]'  => Registry::get('config.resources.docs_url'),
        '[addon_url]' => fn_url(Url::buildUrn(
            ['addons', 'manage'],
            ['q' => $product_variations_name]
        )),
    ]);

    $import_mode = Registry::get('addons.rus_exim_1c.exim_1c_import_mode_offers');
    if ($import_mode !== 'variations' || isset($_REQUEST['show_all_settings'])) {
        $info .= __('addons.rus_exim_1c.options_information_legacy', [
            '[convert_variations_url]' => fn_url(Url::buildUrn(
                ['product_variations_converter', 'process'],
                ['by_combinations' => 1, 'by_variations' => 0]
            )),
        ]);
    }

    return $info;
}

function fn_rus_exim_1c_get_orders($params, $fields, $sortings, &$condition, $join, $group)
{
    if (isset($params['place']) && $params['place'] == 'exim_1c') {
        $order_id = trim(Settings::instance()->getValue('exim_1c_from_order_id', 'rus_exim_1c', $params['company_id']));
        if (!empty($order_id)) {
            $condition .= db_quote(' AND ?:orders.order_id >= ?i', $order_id);
        }
    }
}

function fn_rus_exim_1c_update_product_feature_variant($feature_id, $feature_type, $variant, $lang_code, &$variant_id)
{
    $join = db_quote('INNER JOIN ?:product_feature_variants fv ON fv.variant_id = fvd.variant_id');
    $n_variant_id = db_get_field("SELECT fvd.variant_id FROM ?:product_feature_variant_descriptions AS fvd $join WHERE variant = ?s AND feature_id = ?i", $variant['variant'], $feature_id);

    if (!empty($n_variant_id)) {
        $variant_id = $n_variant_id;
    }
}

/**
 * Sets company_id depending on the current user.
 *
 * @param  array  $user_data  The array with information about the user.
 *
 * @return void
 */
function fn_commerceml_change_company_store($user_data)
{
    if (PRODUCT_EDITION == 'ULTIMATE') {
        if (Registry::get('runtime.simple_ultimate')) {
            $company_id = Registry::get('runtime.forced_company_id');
        } else {
            if ($user_data['company_id'] != 0) {
                $company_id = $user_data['company_id'];
                Registry::set('runtime.company_id', $company_id);
            }
        }
    } elseif ($user_data['user_type'] == 'V') {
        if ($user_data['company_id'] != 0) {
            $company_id = $user_data['company_id'];
            Registry::set('runtime.company_id', $company_id);
        }
    } else {
        Registry::set('runtime.company_id', 0);
    }
}

function fn_rus_exim_1c_look_through_variants_update_combination($combination, &$_data, $product_id, $amount, $options, $variants)
{
    $old_data = db_get_row(
        'SELECT external_id '
        . 'FROM ?:product_options_inventory '
        . 'WHERE product_id = ?i AND combination_hash = ?i AND temp = ?s',
        $product_id, $_data['combination_hash'], 'Y'
    );

    $_data['external_id'] = isset($old_data['external_id']) ? $old_data['external_id'] : '';
}

function fn_rus_exim_1c_before_dispatch()
{
    if (!empty($_REQUEST['dispatch']) && $_REQUEST['dispatch'] == 'exim_1c') {
        if (!empty($_REQUEST[Tygh::$app['session']->getName()])) {
            unset($_REQUEST[Tygh::$app['session']->getName()]);
        }
    }
}

/**
 * The "store_locator_delete_store_location_post" hook handler.
 *
 * Actions performed:
 *  - Removes releated external ID
 *
 * @see fn_delete_store_location
 */
function fn_rus_exim_1c_store_locator_delete_store_location_post($store_location_id, $affected_rows, $deleted)
{
    db_query('DELETE FROM ?:rus_exim_1c_warehouses WHERE warehouse_id = ?i', $store_location_id);
}

/**
 * The "store_locator_get_store_location_before_select" hook handler.
 *
 * Actions performed:
 *  - Joins warehouse external ID field
 *
 * @see fn_get_store_location
 */
function fn_rus_exim_1c_store_locator_get_store_location_before_select($store_locator_id, $lang_code, &$fields, &$join, $condition)
{
    $fields[] = 'GROUP_CONCAT(?:rus_exim_1c_warehouses.external_id) AS external_1c_ids';
    $join .= 'LEFT JOIN ?:rus_exim_1c_warehouses ON ?:store_locations.store_location_id = ?:rus_exim_1c_warehouses.warehouse_id ';
}

/**
 * The "store_locator_get_store_location_post" hook handler.
 *
 * Actions performed:
 *  - Conversions external ids data to array
 *
 * @see fn_get_store_location
 */
function fn_rus_exim_1c_store_locator_get_store_location_post($store_location_id, $lang_code, &$store_location)
{
    if (empty($store_location_id) || empty($store_location['external_1c_ids'])) {
        return;
    }
    $store_location['external_1c_ids'] = explode(',', $store_location['external_1c_ids']);
}

/**
 * The "store_locator_update_store_location_post" hook handler.
 *
 * Actions performed:
 *  - Saves external ID
 *
 * @see fn_update_store_location
 */
function fn_rus_exim_1c_store_locator_update_store_location_post($store_location_data, $store_location_id, $lang_code)
{
    if (!isset($store_location_data['external_1c_ids'])) {
        return;
    }

    $store_location_data['external_1c_ids'] = array_unique(array_filter($store_location_data['external_1c_ids']));

    db_query('DELETE FROM ?:rus_exim_1c_warehouses WHERE warehouse_id = ?i', $store_location_id);

    if (empty($store_location_data['external_1c_ids'])) {
        return;
    }

    $params = [
        'get_external_1c_ids' => true,
        'external_1c_ids'     => $store_location_data['external_1c_ids'],
    ];

    list($warehouses_with_existing_ids, ) = fn_get_store_locations($params);

    if (!empty($warehouses_with_existing_ids)) {
        $notification_data = [];

        foreach ($warehouses_with_existing_ids as $warehouse) {
            foreach ($warehouse['external_1c_ids'] as $external_id) {
                $notification_data[] = __('rus_exim_1c.store_locator_already_used_external_id_link', [
                    '[external_id]' => $external_id,
                    '[warehouse_link]' => fn_url(
                        Url::buildUrn('store_locator.update', [
                            'store_location_id' => $warehouse['store_location_id']
                        ])
                    ),
                    '[warehouse_name]' => $warehouse['name']
                ]);
            }
        }

        fn_set_notification('W', __('warning'), __('rus_exim_1c.store_locator_external_id_already_used', [
            '[warehouses_list]' => implode(' ', $notification_data)
        ]));
    }

    $warehouses_data =[];

    foreach ($store_location_data['external_1c_ids'] as $external_id) {
        $warehouses_data[] = [
            'warehouse_id' => $store_location_id,
            'external_id'  => $external_id
        ];
    }
    db_query('INSERT INTO ?:rus_exim_1c_warehouses ?m', $warehouses_data);

    if (fn_allowed_for('ULTIMATE')) {
        fn_ult_update_share_object($store_location_id, 'store_locations', $store_location_data['company_id']);
    }
}

/**
 * The "get_product_feature_data_before_select" hook handler.
 *
 * Actions performed:
 *  - Joins product feature external ID field
 *
 * @see fn_get_product_feature_data
 */
function fn_rus_exim_1c_get_product_feature_data_before_select(&$fields, $join, $condition, $feature_id, $get_variants, $get_variant_images, $lang_code)
{
    $fields[] = '?:product_features.external_id';
}

/**
 * The "get_product_feature_variants" hook handler.
 *
 * Actions performed:
 *  - Joins product feature variant external ID field
 *
 * @see fn_get_product_feature_variants
 */
function fn_rus_exim_1c_get_product_feature_variants(&$fields, $join, $condition, $feature_id, $get_variants, $get_variant_images, $lang_code)
{
    $fields[] = '?:product_feature_variants.external_id';
}

/**
 * The "get_store_locations_before_select" hook handler.
 *
 * Actions performed:
 *  - Adds additional parameter external_id to searching conditions
 *
 * @see fn_get_store_locations
 */
function fn_rus_exim_1c_get_store_locations_before_select($params, &$fields, &$joins, &$conditions)
{
    if (empty($params['get_external_1c_ids']) && empty($params['external_1c_ids'])) {
        return;
    }

    $joins['rus_exim_1c_warehouses'] = 'LEFT JOIN ?:rus_exim_1c_warehouses AS rus_exim_1c_warehouses'
        . ' ON ?:store_locations.store_location_id = rus_exim_1c_warehouses.warehouse_id';

    if (!empty($params['get_external_1c_ids'])) {
        $fields['external_1c_ids'] = 'GROUP_CONCAT(rus_exim_1c_warehouses.external_id) AS external_1c_ids';
    }

    if (!empty($params['external_1c_ids'])) {
        $conditions['rus_exim_1c_warehouses'] = db_quote('rus_exim_1c_warehouses.external_id IN (?a)', $params['external_1c_ids']);
    }
}

/**
 * The "store_locator_get_store_locations_post" hook handler.
 *
 * Actions performed:
 *  - Conversions external ids of each store location to array
 *
 * @see fn_get_store_locations
 */
function fn_rus_exim_1c_store_locator_get_store_locations_post($params, $items_per_page, $lang_code, &$data)
{
    if (empty($data)) {
        return;
    }

    foreach ($data as &$store_location) {
        if (empty($store_location['external_1c_ids'])) {
            continue;
        }
        $store_location['external_1c_ids'] = explode(',', $store_location['external_1c_ids']);
    }
    unset($store_location);
}