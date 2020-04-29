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

use Tygh\Addons\ProductVariations\Product\Sync\Table\OneToManyViaPrimaryKeyTable;
use Tygh\Enum\ProductFeatures;
use Tygh\Registry;
use Tygh\Addons\ProductVariations\Product\FeaturePurposes;
use Tygh\Enum\ProductFeatureStyles;
use Tygh\Enum\ProductFilterStyles;
use Tygh\Addons\ProductVariations\ServiceProvider;
use Tygh\Addons\ProductVariations\Product\Group\GroupFeatureCollection;
use Tygh\Addons\ProductVariations\Product\Group\GroupFeature;
use Tygh\Enum\ProductTracking;
use Tygh\Enum\ProductOptionTypes;
use Tygh\Storage;
use Tygh\Tygh;
use Tygh\Common\OperationResult;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * @var string $mode
 * @var string $action
 * @var array  $auth
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($mode === 'process') {
        Registry::set('runtime.company_id', 0);
        $time_limit = 1200;
        @set_time_limit($time_limit);

        $product_ids = isset($_REQUEST['product_ids']) ? (array) $_REQUEST['product_ids'] : [];
        $by_variations = isset($_REQUEST['by_variations']) ? (bool) $_REQUEST['by_variations'] : true;
        $by_combinations = isset($_REQUEST['by_combinations']) ? (bool) $_REQUEST['by_combinations'] : false;

        if (fn_is_expired_storage_data('start_variations_convert', $time_limit)) {
            register_shutdown_function(function () {
                fn_set_storage_data('start_variations_convert');
            });

            list($counter, $errors) = fn_product_variations_convert_process($by_variations, $by_combinations, $product_ids);

            /** @var \Smarty $smarty */
            $smarty = Tygh::$app['view'];

            $smarty->assign([
                'errors'          => $errors,
                'counter'         => $counter,
                'by_variations'   => $by_variations,
                'by_combinations' => $by_combinations
            ]);

            fn_set_notification(
                'I',
                __('product_variations.converter.progress.result.title'),
                $smarty->fetch('addons/product_variations/views/product_variations_converter/components/result.tpl')
            );
        } else {
            fn_set_notification('E', __('error'), __('product_variations.converter.progress.wait_another_process'));
        }

        $urn = 'product_variations_converter.process?';
        $urn .= http_build_query([
            'by_variations'   => $by_variations,
            'by_combinations' => $by_combinations,
            'product_ids'     => $product_ids
        ]);

        if (defined('AJAX_REQUEST') && AJAX_REQUEST) {
            /** @var \Tygh\Ajax $ajax */
            $ajax = Tygh::$app['ajax'];
            $ajax->assign('non_ajax_notifications', true);
            $ajax->assign('force_redirection', fn_url($urn));
        } else {
            return [CONTROLLER_STATUS_OK, $urn];
        }
    } elseif ($mode === 'merge') {
        $product_ids = isset($_REQUEST['product_ids']) ? (array) $_REQUEST['product_ids'] : [];
        $by_variations = isset($_REQUEST['by_variations']) ? (bool) $_REQUEST['by_variations'] : true;
        $by_combinations = isset($_REQUEST['by_combinations']) ? (bool) $_REQUEST['by_combinations'] : false;
        $feature_keys = isset($_REQUEST['feature_keys']) ? array_filter((array) $_REQUEST['feature_keys']) : [];

        $urn = 'product_variations_converter.process?';
        $urn .= http_build_query([
            'by_variations'   => $by_variations,
            'by_combinations' => $by_combinations,
            'product_ids'     => $product_ids
        ]);

        if (empty($feature_keys)) {
            return [CONTROLLER_STATUS_REDIRECT, $urn];
        }

        $options = fn_product_variations_convert_find_usage_options($by_variations, $by_combinations, $product_ids);

        $merged_features = [];
        $features = fn_product_variations_convert_get_features($options);

        foreach ($feature_keys as $key) {
            if (!isset($features[$key])) {
                continue;
            }

            $merged_features[$key] = $features[$key];
        }

        if (count($merged_features) <= 1) {
            return [CONTROLLER_STATUS_REDIRECT, $urn];
        }

        $option_ids = [];

        foreach ($merged_features as $feature) {
            foreach ($feature['options'] as $option) {
                $option_ids[$option['option_id']] = $option['option_id'];
            }
        }

        if (empty($option_ids)) {
            return [CONTROLLER_STATUS_REDIRECT, $urn];
        }

        db_query('UPDATE ?:product_options SET ?u WHERE option_id IN (?n)',
            ['value' => substr('pvc_' . md5(time()), 0, 32)],
            $option_ids
        );

        return [CONTROLLER_STATUS_OK, $urn];
    } elseif ($mode === 'unmerge') {
        $product_ids = isset($_REQUEST['product_ids']) ? (array) $_REQUEST['product_ids'] : [];
        $by_variations = isset($_REQUEST['by_variations']) ? (bool) $_REQUEST['by_variations'] : true;
        $by_combinations = isset($_REQUEST['by_combinations']) ? (bool) $_REQUEST['by_combinations'] : false;
        $feature_key = isset($_REQUEST['feature_key']) ? trim($_REQUEST['feature_key']) : null;

        $urn = 'product_variations_converter.process?';
        $urn .= http_build_query([
            'by_variations'   => $by_variations,
            'by_combinations' => $by_combinations,
            'product_ids'     => $product_ids
        ]);

        if (empty($feature_key)) {
            return [CONTROLLER_STATUS_REDIRECT, $urn];
        }

        $options = fn_product_variations_convert_find_usage_options($by_variations, $by_combinations, $product_ids);
        $features = fn_product_variations_convert_get_features($options);

        if (!isset($features[$feature_key]) || !$features[$feature_key]['is_merged']) {
            return [CONTROLLER_STATUS_REDIRECT, $urn];
        }

        $option_ids = [];

        foreach ($features[$feature_key]['options'] as $option) {
            $option_ids[$option['option_id']] = $option['option_id'];
        }

        if (empty($option_ids)) {
            return [CONTROLLER_STATUS_REDIRECT, $urn];
        }

        db_query('UPDATE ?:product_options SET ?u WHERE option_id IN (?n)',
            ['value' => ''],
            $option_ids
        );

        return [CONTROLLER_STATUS_OK, $urn];
    }

    return [CONTROLLER_STATUS_OK];
}

if ($mode === 'process') {
    /** @var \Tygh\SmartyEngine\Core $view */
    $view = Tygh::$app['view'];

    $product_ids = isset($_REQUEST['product_ids']) ? (array) $_REQUEST['product_ids'] : [];
    $items_per_page = isset($_REQUEST['items_per_page']) ? (int) $_REQUEST['items_per_page'] : 100;
    $page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;
    $by_variations = isset($_REQUEST['by_variations']) ? (bool) $_REQUEST['by_variations'] : true;
    $by_combinations = isset($_REQUEST['by_combinations']) ? (bool) $_REQUEST['by_combinations'] : false;

    $variations_products_count = $configurable_products_count = 0;
    $products_with_combinations_count = $combinations_count = 0;

    $old_struct_exists = fn_product_variations_convert_old_struct_exists();

    if ($old_struct_exists && $by_variations) {
        $configurable_products_count = fn_product_variations_convert_get_configurable_products_count();
        $variations_products_count = fn_product_variations_convert_get_variations_products_count();
    }

    if ($by_combinations) {
        $combinations_count = fn_product_variations_convert_get_combinations_count($product_ids);
        $products_with_combinations_count = fn_product_variations_convert_get_products_with_combinations_count($product_ids);
    }

    $options = fn_product_variations_convert_find_usage_options($by_variations, $by_combinations, $product_ids);
    $product_features = fn_product_variations_convert_get_features($options);

    $total_options_count = count($options);
    $total_product_features_count = count($product_features);

    $product_features = array_slice($product_features, ($page - 1) * $items_per_page, $items_per_page, true);

    $view->assign([
        'configurable_products_count'      => $configurable_products_count,
        'variations_products_count'        => $variations_products_count,
        'combinations_count'               => $combinations_count,
        'products_with_combinations_count' => $products_with_combinations_count,
        'options'                          => $options,
        'product_features'                 => $product_features,
        'total_options_count'              => $total_options_count,
        'total_product_features_count'     => $total_product_features_count,
        'data_exists'                      => !empty($options),
        'product_ids'                      => $product_ids,
        'by_variations'                    => $by_variations,
        'by_combinations'                  => $by_combinations,
        'search'                           => [
            'total_items'    => $total_product_features_count,
            'page'           => $page,
            'items_per_page' => $items_per_page
        ]
    ]);
}

function fn_product_variations_convert_process($by_variations, $by_combinations, $product_ids)
{
    $old_struct_exists = fn_product_variations_convert_old_struct_exists();
    $configurable_products_count = $variations_products_count = 0;
    $combinations_count = $products_with_combinations_count = 0;

    $errors = [];
    $counter = [
        'features'                   => 0,
        'variations'                 => 0,
        'configurable_products'      => 0,
        'combinations'               => 0,
        'products_with_combinations' => 0
    ];

    if ($old_struct_exists && $by_variations) {
        $configurable_products_count = fn_product_variations_convert_get_configurable_products_count();
        $variations_products_count = fn_product_variations_convert_get_variations_products_count();
    }

    if ($by_combinations) {
        $combinations_count = fn_product_variations_convert_get_combinations_count($product_ids);
        $products_with_combinations_count = fn_product_variations_convert_get_products_with_combinations_count($product_ids);
    }

    $options = fn_product_variations_convert_find_usage_options($by_variations, $by_combinations, $product_ids);
    $features = fn_product_variations_convert_get_features($options);

    fn_set_progress(
        'parts',
        count($features)
        + $configurable_products_count
        + $variations_products_count
        + $products_with_combinations_count
    );

    fn_set_progress('title', __('product_variations.converter.progress.features.title'));
    Registry::set('runtime.product_variations_converter_mode', 'features');

    foreach ($features as &$feature) {
        fn_set_progress('echo', $feature['feature_name'], false);

        $result = fn_product_variations_convert_process_feature($feature);

        if ($result === false) {
            $errors[] = __('product_variations.converter.progress.features.error', [
                '[feature]'  => $feature['feature_name'],
            ]);
        } else {
            $counter['features']++;
            $feature = $result;
        }

        fn_set_progress('echo', $feature['feature_name']);
    }
    unset($feature);

    foreach ($options as &$option) {
        $feature_key = $option['feature_key'];

        if (!isset($features[$feature_key])) {
            continue;
        }

        $option['feature_id'] = $features[$feature_key]['feature_id'];

        foreach ($option['variants'] as &$variant) {
            $variant_key = $variant['feature_variant_key'];
            $variant['feature_variant_id'] = isset($features[$feature_key]['variants'][$variant_key]['feature_variant_id'])
                ? $features[$feature_key]['variants'][$variant_key]['feature_variant_id']
                : 0;
        }
        unset($variant);
    }
    unset($option);
    unset($features);

    Registry::del('runtime.product_variations_converter_mode');

    $parent_child_map = [];
    $parents_with_error = [];

    if ($variations_products_count) {
        Registry::set('runtime.product_variations_converter_mode', 'variations');
        fn_set_progress('title', __('product_variations.converter.progress.variations.title'));

        foreach (fn_product_variations_convert_get_child_products() as $products) {
            foreach ($products as $product) {
                fn_set_progress('echo', $product['product'], false);

                $product = fn_product_variations_convert_process_variation_product($product, $options);

                if (empty($product['feature_values'])) {
                    $parents_with_error[$product['parent_product_id']] = $product['parent_product_id'];
                    unset($parent_child_map[$product['parent_product_id']]);

                    $errors[] = __('product_variations.converter.progress.variations.error', [
                        '[product]' => $product['product']
                    ]);
                } else {
                    $parent_child_map[$product['parent_product_id']][$product['product_id']] = [
                        'product_id'          => $product['product_id'],
                        'parent_product_name' => $product['parent_product'],
                        'is_default'          => $product['__is_default_variation'] === 'Y',
                        'feature_values'      => $product['feature_values'],
                        'variation_options'   => $product['variation_options'],
                    ];

                    $counter['variations']++;
                }

                fn_set_progress('echo', $product['product']);
            }
            unset($products);
        }
        Registry::del('runtime.product_variations_converter_mode');
    }

    if ($parent_child_map) {
        fn_set_progress('title', __('product_variations.converter.progress.configurable_products.title'));
        Registry::set('runtime.product_variations_converter_mode', 'configurable_products');

        foreach ($parent_child_map as $parent_product_id => $children) {
            $first_child = reset($children);

            if (isset($parents_with_error[$parent_product_id])) {
                $errors[] = __('product_variations.converter.progress.configurable_products.error', [
                    '[product]' => $children['parent_product_name']
                ]);
                continue;
            }

            fn_set_progress('echo', $first_child['parent_product_name'], false);

            $result = fn_product_variations_convert_process_configurable_product($parent_product_id, $children);

            if ($result->isSuccess()) {
                $counter['configurable_products']++;
            } else {
                $result->showNotifications();
            }

            fn_set_progress('echo', $first_child['parent_product_name']);
            unset($parent_child_map[$parent_product_id]);
        }
        Registry::del('runtime.product_variations_converter_mode');
    }
    unset($parent_child_map);

    if ($combinations_count) {
        fn_set_progress('title', __('product_variations.converter.progress.products_with_combinations.title'));
        Registry::set('runtime.product_variations_converter_mode', 'combinations');

        list($products_combinations, $products_with_error) = fn_product_variations_convert_process_products_with_combinations($options, $product_ids);

        unset($features);
        unset($options);

        foreach ($products_with_error as $product_id => $product) {
            $errors[] = __('product_variations.converter.progress.products_with_combinations.error', [
                '[product]' => $product
            ]);
        }

        foreach ($products_combinations as $product_id => $combinations) {
            $combination = reset($combinations);

            fn_set_progress('echo', $combination['product'], false);

            $result = fn_product_variations_convert_process_product_with_combinations($product_id, $combinations);

            if ($result->isSuccess()) {
                $counter['products_with_combinations']++;
                $counter['combinations'] += count($combinations);
            } else {
                $errors[] = __('product_variations.converter.progress.products_with_combinations.error_with_reason', [
                    '[product]' => $combination['product'],
                    '[error]' => implode(PHP_EOL, $result->getErrors())
                ]);
            }

            fn_set_progress('echo', $combination['product']);
            unset($products_combinations[$product_id]);
        }
        Registry::del('runtime.product_variations_converter_mode');
    }
    unset($products_combinations);

    return [$counter, $errors];
}

function fn_product_variations_convert_process_configurable_product($parent_product_id, $children)
{
    static $images_links_sync;
    static $feature_values_sync;
    static $prices_sync;
    static $service;

    if ($images_links_sync === null) {
        $images_links_sync = OneToManyViaPrimaryKeyTable::create('images_links', ['object_id', 'image_id', 'detailed_id'], 'object_id', ['pair_id'], ['conditions' => ['object_type' => 'product']]);
    }

    if ($feature_values_sync === null) {
        $feature_values_sync = OneToManyViaPrimaryKeyTable::create('product_features_values', ['product_id', 'feature_id', 'variant_id', 'lang_code'], 'product_id');
    }

    if ($prices_sync === null) {
        $prices_sync = OneToManyViaPrimaryKeyTable::create('product_prices', ['product_id', 'usergroup_id', 'lower_limit'], 'product_id');
    }

    if ($service === null) {
        $service = ServiceProvider::getService();
    }

    $default_product = reset($children);

    foreach ($children as $child) {
        if ($child['is_default']) {
            $default_product = $child;
            break;
        }
    }

    $default_product_id = $default_product['product_id'];

    $images_links = db_get_hash_single_array(
        'SELECT COUNT(pair_id) AS cnt, object_id FROM ?:images_links'
        . ' WHERE object_type = ?s AND object_id IN (?n)'
        . ' GROUP BY object_id',
        ['object_id', 'cnt'], 'product', array_merge([$parent_product_id], array_keys($children))
    );

    if (!empty($images_links[$parent_product_id])) {
        $product_ids = [];

        foreach ($children as $product_id =>$child) {
            if (empty($images_links[$product_id])) {
                $product_ids[] = $product_id;
            }
        }

        if ($product_ids) {
            $images_links_sync->sync($parent_product_id, $product_ids);
        }
    }

    if (!empty($images_links[$default_product_id])) {
        $images_links_sync->sync($default_product_id, [$parent_product_id]);
    }

    $child_feature_values = [];
    $parent_feature_values = db_get_array('SELECT * FROM ?:product_features_values WHERE product_id = ?i', $parent_product_id);
    $exists_feature_ids = db_get_hash_multi_array('SELECT feature_id, product_id FROM ?:product_features_values WHERE product_id IN (?n) GROUP BY product_id, feature_id', ['product_id', 'feature_id'], array_keys($children));
    $option_variant_ids = [];
    $option_ids = [];
    $variation_options_map = [];

    foreach ($children as $child) {
        foreach ($parent_feature_values as $item) {
            if (isset($exists_feature_ids[$child['product_id']][$item['feature_id']])) {
                continue;
            }

            $item['product_id'] = $child['product_id'];
            $child_feature_values[] = $item;
        }

        foreach ($child['variation_options'] as $option_id => $variant_id) {
            $option_variant_ids[$variant_id] = $variant_id;
            $option_ids[$option_id] = $option_id;
            $variation_options_map[$child['product_id']][$option_id] = $variant_id;
        }
    }

    if ($child_feature_values) {
        $chunk_feature_values = array_chunk($child_feature_values, 1000, true);

        foreach ($chunk_feature_values as $item_feature_values) {
            db_query('INSERT INTO ?:product_features_values ?m', $item_feature_values);
        }
    }

    $feature_values_sync->sync($default_product_id, [$parent_product_id]);

    $base_fields = [
        'product_code', 'list_price', 'amount', 'tax_ids', 'weight', 'free_shipping',
        'shipping_freight', 'shipping_params', 'min_qty', 'max_qty',
        'qty_step', 'list_qty_count', 'avail_since'
    ];

    $is_commerceml_installed = Registry::isExist('addons.rus_exim_1c');

    if ($is_commerceml_installed) {
        $base_fields[] = 'external_id';
        $base_fields[] = 'update_1c';
    }

    $default_product_data = db_get_row('SELECT ?p FROM ?:products WHERE product_id = ?i', implode(', ', $base_fields), $default_product_id);

    db_query('UPDATE ?:products SET ?u WHERE product_id = ?i', $default_product_data, $parent_product_id);

    $prices_sync->sync($default_product_id, [$parent_product_id]);

    db_query('DELETE FROM ?:product_subscriptions WHERE product_id = ?i', $parent_product_id);
    db_query('UPDATE ?:product_subscriptions SET product_id = ?i WHERE product_id = ?i', $parent_product_id, $default_product_id);

    $parent_product_file_folder_ids = db_get_fields('SELECT folder_id FROM ?:product_file_folders WHERE product_id = ?i', $parent_product_id);
    $parent_product_file_ids = db_get_fields('SELECT file_id FROM ?:product_files WHERE product_id = ?i', $parent_product_id);

    db_query('UPDATE ?:product_file_folders SET product_id = ?i WHERE product_id = ?i', $parent_product_id, $default_product_id);
    db_query('UPDATE ?:product_files SET product_id = ?i WHERE product_id = ?i', $parent_product_id, $default_product_id);

    if (Storage::instance('downloads')->copy($default_product_id, $parent_product_id)) {
        Storage::instance('downloads')->delete($default_product_id);
    }

    if ($parent_product_file_folder_ids) {
        db_query('UPDATE ?:product_file_folders SET product_id = ?i WHERE folder_id IN (?n)', $default_product_id, $parent_product_file_folder_ids);
    }
    if ($parent_product_file_ids) {
        db_query('UPDATE ?:product_files SET product_id = ?i WHERE file_id IN (?n)', $default_product_id, $parent_product_file_ids);
    }

    $variation_options_map[$parent_product_id] = $variation_options_map[$default_product_id];
    unset($children[$default_product_id]);

    $product_ids = array_merge([$parent_product_id], array_keys($children));

    $feature_collection = new GroupFeatureCollection();

    foreach ($default_product['feature_values'] as $feature_id => $value) {
        $feature_collection->addFeature(GroupFeature::create($feature_id, FeaturePurposes::CREATE_VARIATION_OF_CATALOG_ITEM));
    }

    fn_product_variations_convert_cleanup_group_tables($product_ids);

    $result = $service->createGroup($product_ids, null, $feature_collection);

    if ($result->isSuccess()) {
        foreach ($product_ids as $product_id) {
            if (empty($variation_options_map[$product_id])) {
                continue;
            }

            $product_data = db_get_row('SELECT weight FROM ?:products WHERE product_id = ?i', $product_id);
            $prices = db_get_array('SELECT price, usergroup_id, lower_limit, percentage_discount FROM ?:product_prices WHERE product_id = ?i', $product_id);
            $ult_prices = [];

            if (fn_allowed_for('ULTIMATE')) {
                $ult_prices = db_get_array('SELECT price, usergroup_id, lower_limit, percentage_discount, company_id FROM ?:ult_product_prices WHERE product_id = ?i', $product_id);
            }

            foreach ($prices as $item) {
                $price = fn_apply_options_modifiers($variation_options_map[$product_id], $item['price'], 'P', [], ['product_data' => [
                    'product_id' => $product_id
                ]]);

                if ($price != $item['price']) {
                    db_query('UPDATE ?:product_prices SET price = ?d WHERE product_id = ?i AND usergroup_id = ?i AND lower_limit = ?i',
                        $price, $product_id, $item['usergroup_id'], $item['lower_limit']
                    );
                }
            }

            $company_id = Registry::get('runtime.company_id');

            foreach ($ult_prices as $item) {
                Registry::set('runtime.company_id', $item['company_id']);

                $price = fn_apply_options_modifiers($variation_options_map[$product_id], $item['price'], 'P', [], ['product_data' => [
                    'product_id' => $product_id
                ]]);

                Registry::set('runtime.company_id', $company_id);

                if ($price != $item['price']) {
                    db_query('UPDATE ?:ult_product_prices SET price = ?d WHERE product_id = ?i AND usergroup_id = ?i AND lower_limit = ?i AND company_id = ?i',
                        $price, $product_id, $item['usergroup_id'], $item['lower_limit'], $item['company_id']
                    );
                }
            }

            $weight = fn_apply_options_modifiers($variation_options_map[$product_id], $product_data['weight'], 'W', [], ['product_data' => [
                'product_id' => $product_id
            ]]);

            if ($weight != $product_data['weight']) {
                db_query('UPDATE ?:products SET weight = ?d WHERE product_id = ?i', $weight, $product_id);
            }
        }

        fn_delete_product($default_product_id);

        $order_items = db_get_array('SELECT item_id, order_id, extra FROM ?:order_details WHERE product_id = ?i', $parent_product_id);

        foreach ($order_items as $item) {
            $extra = (array) @unserialize($item['extra']);

            if (empty($extra['variation_product_id']) || $extra['variation_product_id'] == $default_product_id) {
                continue;
            }

            db_query('UPDATE ?:order_details SET product_id = ?i WHERE item_id = ?i AND order_id = ?i',
                $extra['variation_product_id'], $item['item_id'], $item['order_id']
            );
        }

        // Avoids to bug from old convertation mechanism
        $variants_images = db_get_hash_single_array(
            'SELECT image.image_path, image.image_id FROM ?:images AS image'
            . ' INNER JOIN ?:images_links AS links ON links.object_type = ?s AND links.image_id = image.image_id'
            . ' WHERE links.object_id IN (?n)',
            ['image_path', 'image_id'], 'variant_image', $option_variant_ids
        );

        $products_images = db_get_hash_single_array(
            'SELECT image.image_path, image.image_id FROM ?:images AS image'
            . ' INNER JOIN ?:images_links AS links ON links.object_type = ?s AND links.detailed_id = image.image_id'
            . ' WHERE links.object_id IN (?n)',
            ['image_path', 'image_id'], 'product', $product_ids
        );

        $intersect_variants_images = array_intersect_key($variants_images, $products_images);

        if ($intersect_variants_images) {
            db_query('DELETE FROM ?:images WHERE image_id IN (?n)', $intersect_variants_images);
        }

        $global_options = $global_option_ids = [];
        $global_option_ids = db_get_fields(
            'SELECT option_id FROM ?:product_options WHERE product_id = ?i AND option_id NOT IN (?n)',
            $parent_product_id, $option_ids
        );

        fn_product_variations_delete_options($option_ids, $parent_product_id);

        if ($global_option_ids) {
            db_query(
                "UPDATE ?:product_options_descriptions SET internal_option_name = CONCAT(option_name, ' (', ?s, ')') WHERE option_id IN (?n)",
                $default_product['parent_product_name'], $global_option_ids
            );

            db_query('UPDATE ?:product_options SET product_id = 0 WHERE product_id = ?i AND option_id IN (?n)', $parent_product_id, $global_option_ids);

            foreach ($product_ids as $product_id) {
                foreach ($global_option_ids as $option_id) {
                    $global_options[] = [
                        'option_id' => $option_id,
                        'product_id' => $product_id,
                    ];
                }
            }

            if ($global_options) {
                db_query('INSERT INTO ?:product_global_option_links ?m', $global_options);
            }
        }

        db_query(
            'UPDATE ?:products SET ?u WHERE product_id IN (?n)',
            [
                'status'                 => 'A',
                '__variation_code'       => null,
                '__variation_options'    => null,
                '__is_default_variation' => 'N',
                'updated_timestamp'      => time(),
                'tracking'               => ProductTracking::TRACK_WITHOUT_OPTIONS
            ],
            $product_ids
        );
    }

    return $result;
}

function fn_product_variations_convert_process_variation_product(array $product, array $options)
{
    $variation_options = (array) @json_decode($product['__variation_options'], true);
    $feature_values = [];

    foreach ($variation_options as $option_id => $variant_id) {
        if (empty($options[$option_id]['feature_id'])
            || empty($options[$option_id]['variants'][$variant_id]['feature_variant_id'])
        ) {
            return $product;
        }

        $feature_values[$options[$option_id]['feature_id']] = $options[$option_id]['variants'][$variant_id]['feature_variant_id'];
    }

    if (!$feature_values) {
        return $product;
    }

    $product['feature_values'] = $feature_values;
    $product['variation_options'] = $variation_options;

    ServiceProvider::getProductRepository()->updateProductFeaturesValues($product['product_id'], $feature_values);

    return $product;
}

function fn_product_variations_convert_process_product_with_combinations($product_id, $combinations)
{
    static $product_repository;
    static $service;

    if ($product_repository === null) {
        $product_repository = ServiceProvider::getProductRepository();
    }

    if ($service === null) {
        $service = ServiceProvider::getService();
    }

    $product_exceptions = fn_get_product_exceptions($product_id);
    $product_row = db_get_row('SELECT exceptions_type, weight, product_code FROM ?:products WHERE product_id = ?i', $product_id);
    $prices = db_get_array('SELECT price, usergroup_id, lower_limit, percentage_discount FROM ?:product_prices WHERE product_id = ?i', $product_id);
    $ult_prices = [];

    if (fn_allowed_for('ULTIMATE')) {
        $ult_prices = db_get_array('SELECT price, usergroup_id, lower_limit, percentage_discount, company_id FROM ?:ult_product_prices WHERE product_id = ?i', $product_id);
    }

    if ($product_exceptions) {
        foreach ($combinations as $key => $combination) {
            $is_allow = true;

            if ($product_row['exceptions_type'] == 'F') {
                foreach ($product_exceptions as $exception) {
                    foreach ($exception['combination'] as $option_id => &$variant_id) {
                        if ($variant_id == OPTION_EXCEPTION_VARIANT_ANY || $variant_id == OPTION_EXCEPTION_VARIANT_NOTHING) {
                            $variant_id = isset($combination['variation_options'][$option_id]) ? $combination['variation_options'][$option_id] : null;
                        }
                    }
                    unset($variant_id);
                    if ($exception['combination'] == $combination['variation_options']) {
                        $is_allow = false;
                        break;
                    }
                }
            } elseif ($product_row['exceptions_type'] == 'A') {
                $is_allow = false;
                foreach ($product_exceptions as $exception) {
                    foreach ($exception['combination'] as $option_id => &$variant_id) {
                        if ($variant_id == OPTION_EXCEPTION_VARIANT_ANY) {
                            $variant_id = isset($combination['variation_options'][$option_id]) ? $combination['variation_options'][$option_id] : null;
                        }
                    }
                    unset($variant_id);
                    if ($exception['combination'] == $combination['selected_options']) {
                        $is_allow = true;
                        break;
                    }
                }
            }

            if (!$is_allow) {
                unset($combinations[$key]);
            }
        }
    }

    if (empty($combinations)) {
        $result = new OperationResult(false);
        $result->addError('combination', __('product_variations.converter.progress.products_with_combinations.no_available_combinations'));
        return $result;
    }

    if (count($combinations) == 1) {
        $result = new OperationResult(false);
        $result->addError('combination', __('product_variations.converter.progress.products_with_combinations.only_one_combination'));
        return $result;
    }

    ksort($combinations);
    $default_combination = reset($combinations);
    $combination_id_map = $combination_ids = [];
    $combination_hash_list = [];

    $feature_values = $default_combination['feature_values'];
    $option_ids = [];
    $company_id = Registry::get('runtime.company_id');

    $product_repository->updateProductFeaturesValues($product_id, $feature_values);

    foreach ($combinations as $key => &$combination) {
        if (empty($combination['product_code'])) {
            $combination['product_code'] = $product_row['product_code'];
        }

        $combination['prices'] = [];
        $combination_hash_list[] = $combination['combination_hash'];

        foreach ($prices as $item) {
            $item['price'] = fn_apply_options_modifiers($combination['variation_options'], $item['price'], 'P', [], ['product_data' => [
                'product_id' => $product_id
            ]]);
            $combination['prices'][] = $item;
        }

        foreach ($ult_prices as $item) {
            Registry::set('runtime.company_id', $item['company_id']);

            $item['price'] = fn_apply_options_modifiers($combination['variation_options'], $item['price'], 'P', [], ['product_data' => [
                'product_id' => $product_id
            ]]);
            $combination['ult_prices'][] = $item;

            Registry::set('runtime.company_id', $company_id);
        }

        $combination['weight'] = fn_apply_options_modifiers($combination['variation_options'], $product_row['weight'], 'W', [], ['product_data' => [
            'product_id' => $product_id
        ]]);

        foreach ($combination['variation_options'] as $option_id => $variant_id) {
            $option_ids[$option_id] = $option_id;
        }

        $option_combination_id = $product_repository->generateCombinationId($combination['variation_options']);

        $combination_id_map[$combination['combination_id']] = $key;
        $combination_id_map['option_' . $option_combination_id] = $key;

        if ($default_combination['combination_id'] !== $combination['combination_id']) {
            $combination_ids[] = $combination['combination_id'];
        }
    }
    Registry::set('runtime.company_id', $company_id);
    unset($combination);

    $feature_collection = new GroupFeatureCollection();

    foreach ($feature_values as $feature_id => $value) {
        $feature_collection->addFeature(GroupFeature::create($feature_id, FeaturePurposes::CREATE_VARIATION_OF_CATALOG_ITEM));
    }

    fn_product_variations_convert_cleanup_group_tables([$product_id]);

    $result = $service->generateProductsAndCreateGroup($product_id, $combination_ids, $feature_collection);

    if ($result->isSuccess()) {
        $combinations_images = db_get_hash_multi_array(
            'SELECT object_id, detailed_id FROM ?:images_links WHERE object_type = ?s AND object_id IN (?a)',
            ['object_id', 'detailed_id'], 'product_option', $combination_hash_list
        );

        /** @var \Tygh\Addons\ProductVariations\Product\Group\Group $group */
        $group = $result->getData('group');

        foreach ($group->getProducts()->getProducts() as $group_product) {
            $feature_values = [];

            foreach ($group_product->getFeatureValues() as $feature_value) {
                $feature_values[$feature_value->getFeatureId()] = $feature_value->getVariantId();
            }

            $combination_id = $product_repository->generateCombinationId($feature_values);

            if (isset($combination_id_map[$combination_id], $combinations[$combination_id_map[$combination_id]])) {
                $combination_key = $combination_id_map[$combination_id];
                $combination = $combinations[$combination_key];
                $combination_hash = $combination['combination_hash'];

                $combinations[$combination_key]['product_id'] = $group_product->getProductId();

                db_query(
                    'UPDATE ?:products SET ?u WHERE product_id = ?i',
                    [
                        'product_code'      => $combination['product_code'],
                        'amount'            => $combination['amount'],
                        'weight'            => $combination['weight'],
                        'updated_timestamp' => time(),
                        'tracking'          => ProductTracking::TRACK_WITHOUT_OPTIONS
                    ],
                    $group_product->getProductId()
                );

                if (!empty($combination['prices'])) {
                    db_query('DELETE FROM ?:product_prices WHERE product_id = ?i', $group_product->getProductId());

                    foreach ($combination['prices'] as &$item) {
                        $item['product_id'] = $group_product->getProductId();
                    }
                    unset($item);

                    db_query('INSERT INTO ?:product_prices ?m', $combination['prices']);
                }

                if (!empty($combination['ult_prices'])) {
                    db_query('DELETE FROM ?:ult_product_prices WHERE product_id = ?i', $group_product->getProductId());

                    foreach ($combination['ult_prices'] as &$item) {
                        $item['product_id'] = $group_product->getProductId();
                    }
                    unset($item);

                    db_query('INSERT INTO ?:ult_product_prices ?m', $combination['ult_prices']);
                }

                if (!empty($combinations_images[$combination_hash])) {
                    $image_ids = array_keys($combinations_images[$combination_hash]);
                    $image_id = reset($image_ids); //only main image

                    db_query('DELETE FROM ?:images_links WHERE object_id = ?i AND object_type = ?s AND type = ?s',
                        $group_product->getProductId(), 'product', 'M'
                    );

                    db_replace_into('images_links', [
                        'object_id'   => $group_product->getProductId(),
                        'object_type' => 'product',
                        'type'        => 'M',
                        'detailed_id' => $image_id
                    ]);
                }
            }

            if (fn_allowed_for('ULTIMATE')) {
                db_query('UPDATE ?:ult_product_descriptions AS ult_descr, ?:product_descriptions AS descr'
                    . ' SET ult_descr.product = descr.product'
                    . ' WHERE descr.product_id = ?i AND ult_descr.product_id = descr.product_id AND ult_descr.lang_code = descr.lang_code',
                    $group_product->getProductId()
                );
            }
        }

        $global_options = $global_option_ids = [];
        $global_option_ids = db_get_fields(
            'SELECT option_id FROM ?:product_options WHERE product_id = ?i AND option_id NOT IN (?n)',
            $product_id, $option_ids
        );

        fn_product_variations_delete_options($option_ids, $product_id);

        if ($global_option_ids) {
            db_query(
                "UPDATE ?:product_options_descriptions SET internal_option_name = CONCAT(option_name, ' (', ?s, ')') WHERE option_id IN (?n)",
                $default_combination['product'], $global_option_ids
            );

            db_query('UPDATE ?:product_options SET product_id = 0 WHERE product_id = ?i', $product_id);

            foreach ($group->getProductIds() as $item_product_id) {
                foreach ($global_option_ids as $option_id) {
                    $global_options[] = [
                        'option_id'  => $option_id,
                        'product_id' => $item_product_id,
                    ];
                }
            }

            if ($global_options) {
                db_query('INSERT INTO ?:product_global_option_links ?m', $global_options);
            }
        }

        fn_delete_product_option_combinations($product_id);

        $order_items = db_get_array('SELECT item_id, order_id, extra FROM ?:order_details WHERE product_id = ?i', $product_id);

        foreach ($order_items as $order_item) {
            $extra = (array) @unserialize($order_item['extra']);

            if (empty($extra['product_options'])) {
                continue;
            }

            $option_variant_ids = array_intersect_key($extra['product_options'], $default_combination['variation_options']);

            if (empty($option_variant_ids)) {
                continue;
            }

            $combination_id = 'option_' . $product_repository->generateCombinationId($option_variant_ids);

            if (!isset($combination_id_map[$combination_id]) || !isset($combinations[$combination_id_map[$combination_id]])) {
                continue;
            }

            $combination = $combinations[$combination_id_map[$combination_id]];

            if (empty($combination['product_id'])) {
                continue;
            }

            foreach ($option_variant_ids as $option_id => $variant_id) {
                unset($extra['product_options'][$option_id]);

                foreach ($extra['product_options_value'] as $key => $value) {
                    if (isset($value['option_id']) && $value['option_id'] == $option_id) {
                        unset($extra['product_options_value'][$key]);
                    }
                }
            }

            db_query('UPDATE ?:order_details SET product_id = ?i, extra = ?s WHERE item_id = ?i AND order_id = ?i',
                $combination['product_id'], serialize($extra), $order_item['item_id'], $order_item['order_id']
            );
        }
    }

    return $result;
}

function fn_product_variations_convert_process_products_with_combinations($options, $product_ids)
{
    static $product_repository;

    if ($product_repository === null) {
        $product_repository = ServiceProvider::getProductRepository();
    }

    $products_with_error = $products_combinations = [];

    foreach (fn_product_variations_convert_get_products_using_combinations($product_ids) as $products) {
        foreach ($products as $product) {
            $variation_options = fn_get_product_options_by_combination($product['combination']);
            $feature_values = [];

            foreach ($variation_options as $option_id => $variant_id) {
                if (empty($options[$option_id]['feature_id'])
                    || empty($options[$option_id]['variants'][$variant_id]['feature_variant_id'])
                ) {
                    continue;
                }

                $feature_values[$options[$option_id]['feature_id']] = $options[$option_id]['variants'][$variant_id]['feature_variant_id'];
            }

            $key = $product['position'] . '_' . $product['combination_hash'];

            if (count($variation_options) !== count($feature_values) || empty($feature_values)) {
                $products_with_error[$product['product_id']] = $product['product'];
                unset($products_combinations[$product['product_id']]);
            } elseif (!isset($products_with_error[$product['product_id']])) {
                $products_combinations[$product['product_id']][$key] = [
                    'product'           => $product['product'],
                    'feature_values'    => $feature_values,
                    'variation_options' => $variation_options,
                    'amount'            => $product['amount'],
                    'product_code'      => $product['product_code'],
                    'combination_hash'  => $product['combination_hash'],
                    'combination_id'    => $product_repository->generateCombinationId($feature_values)
                ];
            }
        }
        unset($products);
    }

    return [$products_combinations, $products_with_error];
}

function fn_product_variations_convert_process_feature(array $feature)
{
    $feature_id = isset($feature['feature_id']) ? $feature['feature_id'] : 0;

    if ($feature_id) {
        $feature_data = db_get_row('SELECT * FROM ?:product_features WHERE feature_id = ?i', $feature_id);

        if (empty($feature_data)) {
            $feature_id = 0;
        }
    } else {
        $feature_data = [];
    }

    if (empty($feature_id)) {
        $feature_id = fn_update_product_feature([
            'feature_code'    => $feature['feature_code'],
            'purpose'         => FeaturePurposes::CREATE_VARIATION_OF_CATALOG_ITEM,
            'status'          => 'A',
            'feature_style'   => ProductFeatureStyles::DROP_DOWN,
            'filter_style'    => ProductFilterStyles::CHECKBOX,
            'feature_type'    => ProductFeatures::TEXT_SELECTBOX,
            'company_id'      => fn_allowed_for('MULTIVENDOR') ? 0 : reset($feature['company_ids']),
            'description'     => $feature['feature_name'],
            'position'        => $feature['position'],
            'categories_path' => implode(',', $feature['category_ids'])
        ], 0);

        if (!$feature_id) {
            return false;
        }

        $option = reset($feature['options']);

        db_query(
            'UPDATE ?:product_features_descriptions AS pfd, ?:product_options_descriptions AS pod'
            . ' SET pfd.description = pod.option_name'
            . ' WHERE pfd.lang_code = pod.lang_code AND pfd.feature_id = ?i AND pod.option_id = ?i',
            $feature_id, $option['option_id']
        );

        $feature['feature_id'] = $feature_id;
    } else {
        $category_ids = array_unique(array_merge(fn_explode(',', $feature_data['categories_path']), $feature['category_ids']));

        db_query(
            'UPDATE ?:product_features SET ?u WHERE feature_id = ?i',
            [
                'feature_code'      => $feature['feature_code'],
                'purpose'           => FeaturePurposes::CREATE_VARIATION_OF_CATALOG_ITEM,
                'feature_style'     => ProductFeatureStyles::DROP_DOWN,
                'filter_style'      => ProductFilterStyles::CHECKBOX,
                'categories_path'   => implode(',', $category_ids)
            ],
            $feature_id
        );
    }

    if (fn_allowed_for('ULTIMATE')) {
        foreach ($feature['company_ids'] as $company_id) {
            fn_ult_update_share_object($feature_id, 'product_features', $company_id);
        }
    }

    foreach ($feature['variants'] as &$variant) {
        if (!empty($variant['feature_variant_id'])) {
            continue;
        }

        $variant_id = fn_update_product_feature_variant($feature_id, ProductFeatures::TEXT_SELECTBOX, [
            'variant' => $variant['variant']
        ]);

        if (!$variant_id) {
            return false;
        }

        db_query(
            'UPDATE ?:product_feature_variant_descriptions AS pfvd, ?:product_option_variants_descriptions AS povd'
            . ' SET pfvd.variant = povd.variant_name'
            . ' WHERE pfvd.lang_code = povd.lang_code AND pfvd.variant_id = ?i AND povd.variant_id = ?i AND povd.variant_name <> ?s',
            $variant_id, $variant['variant_id'], ''
        );

        $variant['feature_variant_id'] = $variant_id;
    }
    unset($variant);

    return $feature;
}

function fn_product_variations_convert_get_configurable_products_count()
{
    return (int) db_get_field(
        'SELECT COUNT(*) FROM ?:products WHERE product_type = ?s AND __variation_options IS NOT NULL',
        'C'
    );
}

function fn_product_variations_convert_get_variations_products_count()
{
    return (int) db_get_field(
        'SELECT COUNT(*) FROM ?:products WHERE product_type = ?s AND __variation_options IS NOT NULL',
        'V'
    );
}

function fn_product_variations_convert_get_products_with_combinations_count(array $product_ids = [])
{
    $condition = '';

    if ($product_ids) {
        $condition = db_quote('WHERE product_id IN (?n)', $product_ids);
    }

    return (int) db_get_field(
        'SELECT COUNT(*) FROM'
        . '(SELECT product_id FROM ?:product_options_inventory ?p GROUP BY product_id) AS b',
        $condition
    );
}

function fn_product_variations_convert_get_combinations_count(array $product_ids = [])
{
    $condition = '';

    if ($product_ids) {
        $condition = db_quote('WHERE product_id IN (?n)', $product_ids);
    }

    return (int) db_get_field('SELECT COUNT(*) FROM ?:product_options_inventory ?p', $condition);
}


function fn_product_variations_convert_old_struct_exists()
{
    $product_columns = fn_get_table_fields('products');
    $product_columns = array_combine($product_columns, $product_columns);

    $required_columns = ['__variation_code', '__variation_options', '__is_default_variation'];

    foreach ($required_columns as $column) {
        if (!isset($product_columns[$column])) {
            return false;
        }
    }

    return true;
}

function fn_product_variations_convert_get_child_products($limit = 1000)
{
    $offset = 0;

    $join_main_category_conditions = '';
    $join_additional_category_conditions = '';

    if (fn_allowed_for('ULTIMATE')) {
        $join_main_category_conditions = 'AND mc.company_id = p.company_id';
        $join_additional_category_conditions = 'AND ac.company_id = p.company_id';
    }

    do {
        $products = db_get_hash_array(
            'SELECT p.product_id, p.__variation_options, p.parent_product_id, pd.product, p.company_id, p.__is_default_variation,'
            . ' ppd.product AS parent_product, mc.category_id AS main_category_id, ac.category_id AS additional_category_id,'
            . ' mcd.category AS main_category, acd.category AS additional_category'
            . ' FROM ?:products AS p'
            . ' LEFT JOIN ?:product_descriptions AS pd ON pd.product_id = p.product_id AND pd.lang_code = ?s'
            . ' LEFT JOIN ?:product_descriptions AS ppd ON ppd.product_id = p.parent_product_id AND pd.lang_code = ?s'
            . ' LEFT JOIN ?:products_categories AS pcm ON pcm.product_id = p.parent_product_id AND pcm.link_type = ?s'
            . ' LEFT JOIN ?:products_categories AS pca ON pca.product_id = p.parent_product_id AND pca.link_type = ?s AND pca.position = 0'
            . ' LEFT JOIN ?:categories AS mc ON mc.category_id = pcm.category_id ?p'
            . ' LEFT JOIN ?:categories AS ac ON ac.category_id = pca.category_id ?p'
            . ' LEFT JOIN ?:category_descriptions AS mcd ON mcd.category_id = mc.category_id AND mcd.lang_code = ?s'
            . ' LEFT JOIN ?:category_descriptions AS acd ON acd.category_id = ac.category_id AND acd.lang_code = ?s'
            . ' WHERE p.product_type = ?s AND p.__variation_options IS NOT NULL AND p.parent_product_id > 0 ORDER BY p.product_id ASC LIMIT ?i OFFSET ?i',
            'product_id',
            CART_LANGUAGE, CART_LANGUAGE, 'M', 'A', $join_main_category_conditions, $join_additional_category_conditions,
            CART_LANGUAGE, CART_LANGUAGE, 'V', $limit, $offset
        );

        yield $products;
        $offset += $limit;
    } while ($products);
}

function fn_product_variations_convert_get_products_using_combinations($filter_product_ids, $limit = 1000)
{
    $offset = 0;

    $join_main_category_conditions = '';
    $join_additional_category_conditions = '';

    if (fn_allowed_for('ULTIMATE')) {
        $join_main_category_conditions = 'AND mc.company_id = p.company_id';
        $join_additional_category_conditions = 'AND ac.company_id = p.company_id';
    }

    $condition = '';

    if ($filter_product_ids) {
        $condition = db_quote('WHERE poi.product_id IN (?n)', $filter_product_ids);
    }

    do {
        $products = db_get_array(
            'SELECT p.product_id, poi.product_code, poi.amount, poi.combination, poi.position, poi.combination_hash, pd.product,'
            . ' p.company_id, mc.category_id AS main_category_id, ac.category_id AS additional_category_id,'
            . ' mcd.category AS main_category, acd.category AS additional_category'
            . ' FROM ?:products AS p'
            . ' INNER JOIN ?:product_options_inventory AS poi ON poi.product_id = p.product_id'
            . ' LEFT JOIN ?:product_descriptions AS pd ON pd.product_id = p.product_id AND pd.lang_code = ?s'
            . ' LEFT JOIN ?:products_categories AS pcm ON pcm.product_id = p.product_id AND pcm.link_type = ?s'
            . ' LEFT JOIN ?:products_categories AS pca ON pca.product_id = p.product_id AND pca.link_type = ?s AND pca.position = 0'
            . ' LEFT JOIN ?:categories AS mc ON mc.category_id = pcm.category_id ?p'
            . ' LEFT JOIN ?:categories AS ac ON ac.category_id = pca.category_id ?p'
            . ' LEFT JOIN ?:category_descriptions AS mcd ON mcd.category_id = mc.category_id AND mcd.lang_code = ?s'
            . ' LEFT JOIN ?:category_descriptions AS acd ON acd.category_id = ac.category_id AND acd.lang_code = ?s'
            . ' ?p'
            . ' ORDER BY p.product_id ASC LIMIT ?i OFFSET ?i',
            CART_LANGUAGE, 'M', 'A', $join_main_category_conditions, $join_additional_category_conditions,
            CART_LANGUAGE, CART_LANGUAGE, $condition, $limit, $offset
        );

        yield $products;
        $offset += $limit;
    } while ($products);
}

function fn_product_variations_convert_find_usage_options($by_variations = true, $by_combinations = true, array $filter_product_ids = [])
{
    $options = $option_ids = [];

    if ($by_variations) {
        $old_struct_exists = fn_product_variations_convert_old_struct_exists();

        if ($old_struct_exists) {
            foreach (fn_product_variations_convert_get_child_products() as $products) {
                foreach ($products as $product) {
                    $variation_options = (array) @json_decode($product['__variation_options'], true);

                    foreach ($variation_options as $option_id => $variant_id) {
                        $option_ids[$option_id] = $option_id;

                        $category_id = $product['main_category_id'] ? $product['main_category_id'] : $product['additional_category_id'];
                        $category_name = $product['main_category'] ? $product['main_category'] : $product['additional_category'];

                        if (!isset($options[$option_id])) {
                            $options[$option_id] = [
                                'option_id'         => $option_id,
                                'product_id'        => $product['parent_product_id'],
                                'category_id'       => $category_id,
                                'category_name'     => $category_name,
                                'company_id'        => $product['company_id'],
                                'product_name'      => $product['parent_product'],
                                'variation_name'    => $product['product'],
                                'option_name'       => null,
                                'option_inner_name' => null,
                                'position'          => null,
                                'feature_key'       => null,
                                'variant_ids'       => [],
                                'variants'          => [],
                            ];
                        }

                        $options[$option_id]['variant_ids'][$variant_id] = $variant_id;
                        $options[$option_id]['category_ids'][$category_id] = $category_id;
                        $options[$option_id]['category_names'][$category_id] = $category_name;
                        $options[$option_id]['company_ids'][$product['company_id']] = $product['company_id'];
                    }
                }
            }
        }
    }

    if ($by_combinations) {
        foreach (fn_product_variations_convert_get_products_using_combinations($filter_product_ids) as $products) {
            foreach ($products as $product) {
                $variation_options = fn_get_product_options_by_combination($product['combination']);

                foreach ($variation_options as $option_id => $variant_id) {
                    $option_ids[$option_id] = $option_id;

                    $category_id = $product['main_category_id'] ? $product['main_category_id'] : $product['additional_category_id'];
                    $category_name = $product['main_category'] ? $product['main_category'] : $product['additional_category'];

                    if (!isset($options[$option_id])) {
                        $options[$option_id] = [
                            'option_id'         => $option_id,
                            'product_id'        => $product['product_id'],
                            'category_id'       => $product['main_category_id'] ? $product['main_category_id'] : $product['additional_category_id'],
                            'category_name'     => $product['main_category'] ? $product['main_category'] : $product['additional_category'],
                            'combination_hash'  => $product['combination_hash'],
                            'company_id'        => $product['company_id'],
                            'product_name'      => $product['product'],
                            'option_name'       => null,
                            'option_inner_name' => null,
                            'position'          => null,
                            'variant_ids'       => [],
                            'variants'          => []
                        ];
                    }

                    $options[$option_id]['variant_ids'][$variant_id] = $variant_id;
                    $options[$option_id]['category_ids'][$category_id] = $category_id;
                    $options[$option_id]['category_names'][$category_id] = $category_name;
                    $options[$option_id]['company_ids'][$product['company_id']] = $product['company_id'];
                }
            }
        }
    }

    if ($option_ids) {
        $raw_options = db_get_array(
            'SELECT po.option_id, po.position, po.product_id, po.option_type, po.company_id, pod.option_name, pod.internal_option_name, po.value FROM ?:product_options AS po'
            . ' LEFT JOIN ?:product_options_descriptions AS pod ON pod.option_id = po.option_id AND pod.lang_code = ?s'
            . ' WHERE po.option_id IN (?n)',
            CART_LANGUAGE, $option_ids
        );

        foreach ($raw_options as $item) {
            $option_id = $item['option_id'];

            if (!isset($options[$option_id])) {
                continue;
            }

            $options[$option_id]['option_name'] = $item['option_name'];
            $options[$option_id]['option_type'] = $item['option_type'];
            $options[$option_id]['position'] = $item['position'];
            $options[$option_id]['value'] = $item['value'];
            $options[$option_id]['option_inner_name'] = $item['internal_option_name'];

            if (strpos($options[$option_id]['value'], 'pvc_') === 0) {
                $options[$option_id]['feature_key'] = $options[$option_id]['value'];
                $options[$option_id]['is_merged'] = true;
            } elseif (empty($item['product_id'])) {
                $options[$option_id]['feature_key'] = md5(sprintf('%s_%s_global', $option_id, $options[$option_id]['option_name']));
            } else {
                $options[$option_id]['feature_key'] = md5(sprintf('%s_%s_local', $options[$option_id]['category_id'], $options[$option_id]['option_name']));
            }
        }
        unset($raw_options);

        foreach ($options as &$option) {
            $variant_ids = $option['variant_ids'];

            if (empty($variant_ids)) {
                continue;
            }

            if (isset($option['option_type']) && $option['option_type'] === ProductOptionTypes::CHECKBOX) {
                $raw_variants = db_get_array(
                    'SELECT pov.variant_id, pov.position FROM ?:product_option_variants AS pov'
                    . ' WHERE pov.option_id = ?i AND pov.variant_id IN (?n) ORDER BY pov.position ASC',
                    $option['option_id'], $variant_ids
                );

                foreach ($raw_variants as &$item) {
                    $item['variant_name'] = $item['position'] == 0 ? __('no') : __('yes');
                }
                unset($item);
            } else {
                $raw_variants = db_get_array(
                    'SELECT pov.variant_id, povd.variant_name FROM ?:product_option_variants AS pov'
                    . ' LEFT JOIN ?:product_option_variants_descriptions AS povd ON povd.variant_id = pov.variant_id AND povd.lang_code = ?s'
                    . ' WHERE pov.option_id = ?i AND pov.variant_id IN (?n) ORDER BY pov.position ASC',
                    CART_LANGUAGE, $option['option_id'], $variant_ids
                );
            }

            foreach ($raw_variants as $variant) {
                $option['variants'][$variant['variant_id']] = [
                    'variant_id'          => $variant['variant_id'],
                    'variant'             => $variant['variant_name'],
                    'feature_variant_key' => md5($variant['variant_name'])
                ];
            }
            unset($raw_variants);
        }
        unset($option);

        if (fn_allowed_for('ULTIMATE')) {
            $options_company_id_map = db_get_hash_multi_array(
                'SELECT share_company_id, share_object_id FROM ?:ult_objects_sharing'
                . ' WHERE share_object_type = ?s AND share_object_id IN (?n)',
                ['share_object_id', 'share_company_id', 'share_company_id'],
                'product_options', $option_ids
            );

            foreach ($options as &$option) {
                if (empty($option['option_id'])) {
                    continue;
                }

                $option_id = $option['option_id'];

                if (!isset($options_company_id_map[$option_id])) {
                    continue;
                }

                foreach ($options_company_id_map[$option_id] as $company_id) {
                    $option['company_ids'][$company_id] = $company_id;
                }
            }
            unset($option);
        }
    }

    return $options;
}

function fn_product_variations_convert_get_features($options)
{
    $result = [];

    $features_map = db_get_hash_single_array(
        'SELECT feature_code, feature_id FROM ?:product_features WHERE feature_code != ?s AND purpose IN (?a)',
        ['feature_code', 'feature_id', 'feature_id'],
        '', [FeaturePurposes::CREATE_CATALOG_ITEM, FeaturePurposes::CREATE_VARIATION_OF_CATALOG_ITEM]
    );

    $features_variants_map = [];

    foreach ($options as $option) {
        $key = $option['feature_key'];

        if (!isset($result[$key])) {
            $result[$key] = [
                'feature_code'   => (string) $key,
                'feature_name'   => (string) $option['option_name'],
                'position'       => (int) $option['position'],
                'is_merged'      => !empty($option['is_merged']),
                'variants'       => [],
                'category_ids'   => [],
                'category_names' => [],
                'company_ids'    => [],
                'options'        => [],
                'feature_id'     => isset($features_map[$key]) ? $features_map[$key] : null
            ];

            if ($result[$key]['feature_id']) {
                $variants = db_get_array(
                    'SELECT pfv.variant_id, pfvd.variant FROM ?:product_feature_variants AS pfv'
                    . ' LEFT JOIN ?:product_feature_variant_descriptions AS pfvd ON pfvd.variant_id = pfv.variant_id AND pfvd.lang_code = ?s'
                    . ' WHERE pfv.feature_id = ?i',
                    CART_LANGUAGE, $result[$key]['feature_id']
                );

                foreach ($variants as $variant) {
                    $features_variants_map[$result[$key]['feature_id']][md5($variant['variant'])] = $variant['variant_id'];
                }
            }
        }

        $result[$key]['category_ids'] = array_replace($result[$key]['category_ids'], $option['category_ids']);
        $result[$key]['category_names'] = array_replace($result[$key]['category_names'], $option['category_names']);
        $result[$key]['company_ids'] = array_replace($result[$key]['company_ids'], $option['company_ids']);

        $feature_id = $result[$key]['feature_id'];
        $result[$key]['options'][] = array_intersect_key($option, array_flip(['option_id', 'option_name', 'product_id', 'product_name', 'product_id', 'value', 'is_merged']));

        foreach ($option['variants'] as $variant) {
            $variant_key = $variant['feature_variant_key'];
            $variant['feature_variant_id'] = isset($features_variants_map[$feature_id][$variant_key]) ? $features_variants_map[$feature_id][$variant_key] : null;
            $result[$key]['variants'][$variant_key] = array_intersect_key($variant, array_flip(['variant', 'feature_variant_id', 'variant_id']));
        }
    }

    return $result;
}

function fn_product_variations_convert_cleanup_group_tables(array $product_ids)
{
    $group_ids = db_get_fields('SELECT group_id FROM ?:product_variation_group_products WHERE product_id IN (?n) GROUP BY group_id', $product_ids);
    db_query('DELETE FROM ?:product_variation_data_identity_map WHERE product_id IN (?n)', $product_ids);

    if ($group_ids) {
        db_query('DELETE FROM ?:product_variation_groups WHERE id IN (?n)', $group_ids);
        db_query('DELETE FROM ?:product_variation_group_features WHERE group_id IN (?n)', $group_ids);
        db_query('DELETE FROM ?:product_variation_group_products WHERE group_id IN (?n)', $group_ids);
    }
}

function fn_product_variations_delete_options($option_ids, $product_id)
{
    if (empty($option_ids)) {
        return;
    }

    $options = db_get_hash_array('SELECT option_id, product_id FROM ?:product_options WHERE option_id IN(?n)', 'option_id', $option_ids);

    foreach ($options as $option) {
        if ($option['product_id']) {
            fn_delete_product_option($option['option_id'], $product_id);
        } else {
            fn_delete_global_option_link($product_id, $option['option_id']);
        }
    }
}
