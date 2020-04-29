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

use Illuminate\Support\Collection;
use Tygh\Registry;
use Tygh\Enum\VendorStatuses;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'search') {
    fn_add_breadcrumb(__('store_locator.stores_and_pickup_points'));

    $sl_search = isset($_REQUEST['sl_search']) ? (array) $_REQUEST['sl_search'] : [];
    $params = [
        'status' => 'A',
    ];

    if (fn_allowed_for('MULTIVENDOR')) {
        /** @var \Tygh\Storefront\Storefront $storefront */
        $storefront = Tygh::$app['storefront'];
        $params['company_id'] = $storefront->getCompanyIds();
        $sl_search['company_id'] = $storefront->getCompanyIds();
    }

    $cities = fn_get_store_location_cities($params);

    if (empty($sl_search) && Registry::get('addons.geo_maps.status') === 'A') {
        $geolocation_data = fn_geo_maps_get_customer_stored_geolocation();
        if (isset($geolocation_data['city']) && in_array($geolocation_data['city'], $cities)) {
            $sl_search['city'] = $geolocation_data['city'];
        }
    }

    $sl_search['company_status'] = VendorStatuses::ACTIVE;
    list($store_locations, $search) = fn_get_store_locations($sl_search);
    $grouped_locations = (new Collection($store_locations))->groupBy('city')->toArray();

    Tygh::$app['view']->assign([
        'store_locations'       => $grouped_locations,
        'store_locations_count' => count($store_locations),
        'cities'                => $cities,
        'sl_search'             => $sl_search,
    ]);
}
