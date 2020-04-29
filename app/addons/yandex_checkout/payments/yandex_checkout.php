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

use Tygh\Addons\YandexCheckout\Payments\YandexCheckout;
use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied');}

$payment = new YandexCheckout($order_info['payment_method']['processor_params']['shop_id'], $order_info['payment_method']['processor_params']['scid'], Tygh::$app['addons.yandex_checkout.receipt_service']);

try {
    $response = $payment->createPayment($order_info, $processor_data);
    $confirmation_url = $response->getConfirmation()->getConfirmationUrl();

    fn_update_order_payment_info($order_info['order_id'], ['id' => $response->getId()]);
    fn_create_payment_form($confirmation_url, [], __('yandex_checkout.yandex_checkout'), true, 'get');
} catch (Exception $exception) {
    $pp_response['reason_text'] = $exception->getMessage();
    $pp_response['order_status'] = 'F';
}
