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

namespace Tygh\Addons\YandexCheckout;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tygh\Addons\YandexCheckout\HookHandlers\OrdersHookHandler;
use Tygh\Addons\YandexCheckout\HookHandlers\InitHookHandler;
use Tygh\Addons\YandexCheckout\Services\ReceiptService;

use Tygh\Addons\YandexCheckout\Services\ResponseService;
use Tygh\Application;
use Tygh\Tygh;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['addons.yandex_checkout.hook_handlers.orders'] = function (Application $application) {
            return new OrdersHookHandler();
        };

        $app['addons.yandex_checkout.hook_handlers.init'] = function (Application $application) {
            return new InitHookHandler();
        };

        $app['addons.yandex_checkout.receipt_service'] = function (Application $application) {
            return new ReceiptService(
                Tygh::$app['addons.rus_taxes.receipt_factory'],
                fn_get_schema('yandex_checkout', 'map_taxes')
            );
        };

        $app['addons.yandex_checkout.response_service'] = function (Application $application) {
            return new ResponseService();
        };

        $app['addons.yandex_checkout.max_waiting_time'] = function (Application $application) {
            return 30;
        };
    }

    /**
     * @return \Tygh\Addons\YandexCheckout\Services\ReceiptService
     */
    public static function getReceiptService()
    {
        return Tygh::$app['addons.yandex_checkout.receipt_service'];
    }

    /**
     * @return \Tygh\Addons\YandexCheckout\Services\ResponseService
     */
    public static function getResponseService()
    {
        return Tygh::$app['addons.yandex_checkout.response_service'];
    }

    /**
     * @return int
     */
    public static function getMaxWaitingTime()
    {
        return Tygh::$app['addons.yandex_checkout.max_waiting_time'];
    }

}