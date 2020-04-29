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

namespace Tygh\Addons\Warehouses;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tygh\Tygh;

/**
 * Class ServiceProvider is intended to register services and components of the "Warehouses" add-on to the application
 * container.
 *
 * @package Tygh\Addons\Warehouses
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app['addons.warehouses.manager'] = function(Container $app) {
            return new Manager($app['db'], DESCR_SL);
        };

        $app['addons.warehouses.store_types'] = function(Container $app) {
            return [
                Manager::STORE_LOCATOR_TYPE_STORE     => __('warehouses.store_type_store'),
                Manager::STORE_LOCATOR_TYPE_PICKUP    => __('warehouses.store_type_pickup'),
                Manager::STORE_LOCATOR_TYPE_WAREHOUSE => __('warehouses.store_type_warehouse'),
            ];
        };
    }

    /**
     * @return \Tygh\Addons\Warehouses\Manager
     */
    public static function getManager()
    {
        return Tygh::$app['addons.warehouses.manager'];
    }

    /**
     * @return string[]
     */
    public static function getStoreTypes()
    {
        return Tygh::$app['addons.warehouses.store_types'];
    }
}
