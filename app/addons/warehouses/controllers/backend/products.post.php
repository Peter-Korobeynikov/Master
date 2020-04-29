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

use Tygh\Addons\Warehouses\ServiceProvider;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return [CONTROLLER_STATUS_OK];
}

if ($mode == 'update') {
    $company_id = null;

    $product_data = Tygh::$app['view']->getTemplateVars('product_data');
    $company_id = isset($_REQUEST['product_data']['company_id']) ? $_REQUEST['product_data']['company_id'] : $product_data['company_id'];

    if (fn_allowed_for('ULTIMATE') && !Registry::get('runtime.company_id')) {
        return [CONTROLLER_STATUS_OK];
    }

    /** @var Tygh\Addons\Warehouses\Manager $manager */
    $manager = Tygh::$app['addons.warehouses.manager'];

    $warehouses = $manager->getWarehouses($company_id);
    if (empty($warehouses)) {
        return [CONTROLLER_STATUS_OK];
    }

    /** @var Tygh\Addons\Warehouses\ProductStock $product_stock */
    $product_stock = $manager->getProductWarehousesStock($_REQUEST['product_id']);

    $warehouses_amounts = $product_stock->getStockAsArray();

    if ($product_stock->hasStockSplitByWarehouses()) {
        $product_warehouses_amount = $product_stock->getAmount();
        Tygh::$app['view']->assign('product_warehouses_amount', $product_warehouses_amount);
    }

    Tygh::$app['view']->assign(
        [
            'warehouses'         => $warehouses,
            'warehouses_amounts' => $warehouses_amounts,
            'store_types'        => ServiceProvider::getStoreTypes(),
        ]
    );

    // Quantity tab is not avaliable if product is common or about to be.
    $is_quantity_tab_avaliable = !isset($company_id) || !empty($company_id);
    if ($is_quantity_tab_avaliable) {
        Registry::set('navigation.tabs.warehouses_quantity', [
            'title' => __('warehouses.quantity'),
            'js' => true,
        ]);
    }
}

if ($mode === 'm_update') {
    /** @var \Tygh\SmartyEngine\Core $view */
    $view = Tygh::$app['view'];
    /** @var array $products_data */
    $products_data = $view->getTemplateVars('products_data');
    $readonly_fields = [];

    /** @var Tygh\Addons\Warehouses\Manager $manager */
    $manager = Tygh::$app['addons.warehouses.manager'];

    foreach ($products_data as $product) {
        $product_id = $product['product_id'];
        $product_stock = $manager->getProductWarehousesStock($product_id);
        $readonly_fields[$product_id]['amount'] = $product_stock->hasStockSplitByWarehouses();
    }

    $view->assign('readonly_fields', $readonly_fields);
}
