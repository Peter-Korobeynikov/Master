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

use Tygh\Addons\ProductVariations\ServiceProvider;
use Tygh\Addons\ProductVariations\Product\FeaturePurposes;
use Tygh\Addons\ProductVariations\Product\Group\GroupFeature;
use Tygh\Addons\ProductVariations\Product\Group\GroupFeatureCollection;
use Tygh\Addons\ProductVariations\Product\Type\Type;
use Tygh\Registry;
use Illuminate\Support\Collection;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * @var string $mode
 * @var string $action
 * @var array  $auth
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($mode === 'update') {
        $product_id = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0;
        $product_ids = isset($_REQUEST['product_ids']) ? (array) array_filter($_REQUEST['product_ids']) : [];

        if (!$product_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $product_data = fn_get_product_data($product_id, $auth, CART_LANGUAGE, '', false, false, false, false, false, false);

        if (!$product_data) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $service = ServiceProvider::getService();
        $group_repository = ServiceProvider::getGroupRepository();

        $group_id = $group_repository->findGroupIdByProductId($product_id);

        if ($group_id) {
            $result = $service->attachProductsToGroup($group_id, $product_ids);
        } else {
            $result = $service->createGroup(array_merge([$product_id], $product_ids));
        }

        $result->showNotifications();

        if (defined('AJAX_REQUEST') && AJAX_REQUEST) {
            /** @var \Tygh\Ajax $ajax */
            $ajax = Tygh::$app['ajax'];
            $ajax->assign('force_redirection', fn_url('products.update?selected_section=variations&product_id=' . $product_id));
        } else {
            return [CONTROLLER_STATUS_OK, 'products.update?selected_section=variations&product_id=' . $product_id];
        }
    } elseif ($mode === 'add_product') {
        $group_id = isset($_REQUEST['group_id']) ? (int) $_REQUEST['group_id'] : 0;
        $product_id = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0;

        if (!$product_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        if ($group_id) {
            $service = ServiceProvider::getService();
            $group_repository = ServiceProvider::getGroupRepository();

            if ($group_repository->findGroupIdByProductId($product_id)) {
                return [CONTROLLER_STATUS_NO_PAGE];
            }

            $result = $service->attachProductsToGroup($group_id, [$product_id]);
            $result->showNotifications();
        }

        if (defined('AJAX_REQUEST') && AJAX_REQUEST) {
            /** @var \Tygh\Ajax $ajax */
            $ajax = Tygh::$app['ajax'];
            $ajax->assign('force_redirection', fn_url('products.update?selected_section=variations&product_id=' . $product_id));
        } else {
            return [CONTROLLER_STATUS_OK, 'products.update?selected_section=variations&product_id=' . $product_id];
        }
    } elseif ($mode === 'delete_product') {
        $product_id = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0;

        if (!$product_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $service = ServiceProvider::getService();
        $group_repository = ServiceProvider::getGroupRepository();

        $group_id = $group_repository->findGroupIdByProductId($product_id);

        if (!$group_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $result = $service->detachProductFromGroup($group_id, $product_id);
        $result->showNotifications();

        if (defined('AJAX_REQUEST') && AJAX_REQUEST) {
            /** @var \Tygh\Ajax $ajax */
            $ajax = Tygh::$app['ajax'];
            $ajax->assign('force_redirection', fn_url('products.update?selected_section=variations&product_id=' . $product_id));
        } else {
            return [CONTROLLER_STATUS_OK, 'products.update?selected_section=variations&product_id=' . $product_id];
        }
    } elseif ($mode === 'delete') {
        $product_id = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0;

        if (!$product_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $service = ServiceProvider::getService();
        $group_repository = ServiceProvider::getGroupRepository();

        $group_id = $group_repository->findGroupIdByProductId($product_id);

        if (!$group_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $result = $service->removeGroup($group_id);
        $result->showNotifications();

        if (defined('AJAX_REQUEST') && AJAX_REQUEST) {
            /** @var \Tygh\Ajax $ajax */
            $ajax = Tygh::$app['ajax'];
            $ajax->assign('force_redirection', fn_url('products.update?selected_section=variations&product_id=' . $product_id));
        } else {
            return [CONTROLLER_STATUS_OK, 'products.update?selected_section=variations&product_id=' . $product_id];
        }
    } elseif ($mode === 'mark_main_product') {
        $product_id = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0;

        if (!$product_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $service = ServiceProvider::getService();
        $group_repository = ServiceProvider::getGroupRepository();

        $group_id = $group_repository->findGroupIdByProductId($product_id);

        if (!$group_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $result = $service->setDefaultProduct($group_id, $product_id);
        $result->showNotifications();

        $redirect_url = 'products.update?selected_section=variations&product_id=' . $product_id;

        if (defined('AJAX_REQUEST') && AJAX_REQUEST) {
            /** @var \Tygh\Ajax $ajax */
            $ajax = Tygh::$app['ajax'];
            $ajax->assign('force_redirection', $redirect_url);
        } else {
            return [CONTROLLER_STATUS_OK, $redirect_url];
        }
    } elseif ($mode === 'generate') {
        $product_id = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0;
        $combination_ids = isset($_REQUEST['combination_ids']) ? (array) $_REQUEST['combination_ids'] : [];

        if (!$product_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $group_repository = ServiceProvider::getGroupRepository();
        $service = ServiceProvider::getService();

        $group_id = $group_repository->findGroupIdByProductId($product_id);

        if ($group_id) {
            $result = $service->generateProductsAndAttachToGroup($group_id, $product_id, $combination_ids);
        } else {
            $result = $service->generateProductsAndCreateGroup($product_id, $combination_ids);
        }

        $result->showNotifications();

        $redirect_url = 'products.update?selected_section=variations&product_id=' . $product_id;

        if (defined('AJAX_REQUEST') && AJAX_REQUEST) {
            /** @var \Tygh\Ajax $ajax */
            $ajax = Tygh::$app['ajax'];
            $ajax->assign('force_redirection', $redirect_url);
        } else {
            return [CONTROLLER_STATUS_OK, $redirect_url];
        }
    }

    return [CONTROLLER_STATUS_OK];
}

if ($mode === 'manage') {
    $product_id = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0;

    if (!defined('AJAX_REQUEST')) {
        return [CONTROLLER_STATUS_REDIRECT, 'products.update?selected_section=variations&product_id=' . $product_id];
    }

    /** @var \Tygh\SmartyEngine\Core $view */
    $view = Tygh::$app['view'];

    $product_data = fn_get_product_data($product_id, $auth, CART_LANGUAGE, '', false, false, false, false, false, false, true);

    if (!$product_data) {
        return [CONTROLLER_STATUS_NO_PAGE];
    }

    $group_repository = ServiceProvider::getGroupRepository();
    $product_repository = ServiceProvider::getProductRepository();

    $group = $group_repository->findGroupByProductId($product_id);

    if ($group) {
        $parent_to_child_map = [];

        foreach ($group->getProducts() as $group_product) {
            if (!$group_product->getParentProductId()) {
                continue;
            }

            $parent_to_child_map[$group_product->getParentProductId()] = $group_product->getProductId();
        }

        $params = array_merge($_REQUEST, [
            'sort_by' => 'null',
            'pid'     => $group->getProductIds(),
        ]);

        // FIXME its need to master products
        if (fn_allowed_for('MULTIVENDOR')) {
            $runtime_company_id = Registry::get('runtime.company_id');
            Registry::set('runtime.company_id', 0);
        }

        list($products, $search) = fn_get_products($params);
        fn_gather_additional_products_data($products, [
            'get_icon'            => true,
            'get_detailed'        => true,
            'get_options'         => false,
            'get_discounts'       => false,
            'get_features'        => false,
            'get_product_type'    => true
        ]);

        // FIXME its need to master products
        if (fn_allowed_for('MULTIVENDOR')) {
            Registry::set('runtime.company_id', $runtime_company_id);
        }

        $selected_features = $product_repository->findFeaturesByFeatureCollection($group->getFeatures());
        $selected_features = $product_repository->loadFeaturesVariants($selected_features);

        foreach ($products as &$product) {
            $product['has_children'] = isset($parent_to_child_map[$product['product_id']]);
        }
        unset($product);

        $products = $product_repository->loadProductsFeatures($products, $group->getFeatures());

        $products = Collection::make($products)->sortBy(function ($item) {
            $key_1 = [];
            $key_2 = [];

            foreach ($item['variation_features'] as $feature) {
                if (FeaturePurposes::isCreateCatalogItem($feature['purpose'])) {
                    $key_1[] = $feature['variant_position'];
                    $key_1[] = $feature['variant_id'];
                } else {
                    $key_2[] = $feature['variant_position'];
                    $key_2[] = $feature['variant_id'];
                }
            }

            if ($item['parent_product_id']) {
                $key_1[] = 1;
            } else {
                $key_1[] = 0;
            }

            $key_2[] = $item['product_id'];

            return implode('_', array_merge($key_1, $key_2));
        })->all();

        $view->assign([
            'product_id'        => $product_id,
            'product'           => $product_data,
            'group'             => $group,
            'products'          => $products,
            'search'            => $search,
            'selected_features' => $selected_features
        ]);
    } else {
        $features = $product_repository->findAvailableFeatures($product_id);
        $group_codes = $group_repository->findGroupCodesByFeatureIds(array_keys($features));

        $view->assign([
            'product_id'  => $product_id,
            'product'     => $product_data,
            'features'    => $features,
            'group_codes' => $group_codes
        ]);
    }
} elseif ($mode === 'update') {
    $product_id = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0;

    if (!defined('AJAX_REQUEST')) {
        return [CONTROLLER_STATUS_REDIRECT, 'products.update?selected_section=variations&product_id=' . $product_id];
    }

    /** @var \Tygh\SmartyEngine\Core $view */
    $view = Tygh::$app['view'];

    $product_data = fn_get_product_data($product_id, $auth, CART_LANGUAGE, '', false, false, false, false, false, false);

    if (!$product_data) {
        return [CONTROLLER_STATUS_NO_PAGE];
    }

    $product_type = Type::createByProduct($product_data);

    $group_repository = ServiceProvider::getGroupRepository();
    $product_repository = ServiceProvider::getProductRepository();
    $service = ServiceProvider::getService();

    $group = $group_repository->findGroupByProductId($product_id);
    $search = $products = $combinations = $selected_features = [];
    $count_available_combinations = 0;

    if ($group) {
        $group_features = $group->getFeatures();
        $product_ids = $group->getProductIds();
        $feature_ids = $group->getFeatureIds();
    } else {
        $features = $product_repository->findAvailableFeatures($product_id);
        $group_features = GroupFeatureCollection::createFromFeatureList($features);
        $feature_ids = array_keys($features);
        $product_ids = [$product_id];
    }

    if ($feature_ids) {
        $selected_features = $product_repository->findFeaturesByFeatureCollection($group_features);

        $params = array_merge($_REQUEST, [
            'product_type'            => [Type::PRODUCT_TYPE_SIMPLE],
            'has_not_variation_group' => true,
            'has_features'            => $feature_ids,
            'exclude_pid'             => $product_ids,
            'subcats'                 => 'Y'
        ]);

        if (!isset($params['cid'])) {
            $params['cid'] = $product_data['main_category'];
        }

        list($products, $search) = fn_get_products($params, Registry::get('settings.Appearance.admin_elements_per_page'));

        fn_gather_additional_products_data($products, [
            'get_icon'            => true,
            'get_detailed'        => true,
            'get_options'         => false,
            'get_discounts'       => false,
            'get_features'        => false
        ]);

        $products = $product_repository->loadProductsFeatures($products, $group_features);

        if ($product_type->isAllowGenerateVariations()) {
            $combinations_count = array_reduce(array_map(function ($feature_id) {
                return (int) fn_get_product_feature_variants([
                    'feature_id'             => $feature_id,
                    'fetch_total_count_only' => true
                ]);
            }, $feature_ids), function ($carry, $item) { return $carry * $item; }, 1);

            if ($combinations_count > 5000) { // FIXME Will be refactored, see 1-24713
                $view->assign('is_too_many_combinations', true);
            } else {
                if ($group) {
                    $combinations = $service->getFeaturesVariantsCombinationsByGroup($group);
                } else {
                    $combinations = $service->getFeaturesVariantsCombinations($group_features, [$product_id]);
                }

                foreach ($combinations as $combination) {
                    if (!$combination['exists']) {
                        $count_available_combinations++;
                    }
                }
            }
        } else {
            $combinations = [];
            $count_available_combinations = 0;
        }
    }

    $view->assign([
        'product_data'                 => $product_data,
        'group'                        => $group,
        'selected_features'            => $selected_features,
        'feature_ids'                  => $feature_ids,
        'products'                     => $products,
        'combinations'                 => $combinations,
        'count_available_combinations' => $count_available_combinations,
        'search'                       => $search,
        'is_allow_generate_variations' => $product_type->isAllowGenerateVariations()
    ]);

    if (!empty($product_data['product_features'])) {
        $feature_params = [
            'feature_id' => array_keys($product_data['product_features']),
            'plain' => true,
            'statuses' => array('A', 'H'),
            'variants' => true,
            'exclude_group' => true,
            'exclude_filters' => false
        ];
        // Preload variants selected at search form. They will be shown at AJAX variants loader as pre-selected.
        if (!empty($_REQUEST['feature_variants'])) {
            $feature_params['variants_only'] = $_REQUEST['feature_variants'];
        }

        list($features, $features_search) = fn_get_product_features($feature_params, PRODUCT_FEATURES_THRESHOLD);

        if ($features_search['total_items'] <= PRODUCT_FEATURES_THRESHOLD) {
            $view->assign('feature_items', $features);
        } else {
            $view->assign('feature_items_too_many', true);
        }
    }
}