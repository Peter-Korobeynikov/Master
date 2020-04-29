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

use Tygh\Enum\Addons\StorefrontRestApi\PaymentTypes;
use Tygh\Registry;

defined('BOOTSTRAP') or die('Access denied');

/**
 * This schema describes payment processors that can be used to perform the order settlement via Storefront REST API.
 *
 * Structure:
 *
 * [
 *     payment_processor_script => [
 *       'type'  => Payment type.
 *                  @see \Tygh\Enum\Addons\StorefrontRestApi\PaymentTypes
 *       'class' => FQDN of the class to perform payment.
 *                  Must implement \Tygh\Addons\StorefrontRestApi\Payments\IRedirectionPayment or
 *                  \Tygh\Addons\StorefrontRestApi\Payments\IDirectPayment interface
 *     ]
 * ]
 */
$schema = array();

$addons = Registry::get('addons');

if (isset($addons['paypal']['status']) && $addons['paypal']['status'] === 'A') {
    $schema['paypal_express.php'] = array(
        'type'  => PaymentTypes::REDIRECTION,
        'class' => '\Tygh\Addons\StorefrontRestApi\Payments\PaypalExpress',
    );
}

if (isset($addons['rus_payments']['status']) && $addons['rus_payments']['status'] === 'A') {
    $schema['yandex_money.php'] = array(
        'type'  => PaymentTypes::REDIRECTION,
        'class' => '\Tygh\Addons\StorefrontRestApi\Payments\YandexCheckpoint',
    );
}

return $schema;