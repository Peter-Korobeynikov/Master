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

namespace Tygh\Addons\MobileApp;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tygh\Addons\MobileApp\Notifications\Factory;
use Tygh\Addons\MobileApp\Notifications\Sender;
use Tygh\Http;
use Tygh\Registry;

class ServiceProvider implements ServiceProviderInterface
{
    /** @inheritdoc */
    public function register(Container $app)
    {
        $app['addons.mobile_app.notifications.sender'] = function (Container $app) {
            return new Sender(
                fn_mobile_app_get_mobile_app_settings(),
                new Http()
            );
        };

        $app['addons.mobile_app.notifications.factory'] = function (Container $app) {
            return new Factory();
        };
    }
}