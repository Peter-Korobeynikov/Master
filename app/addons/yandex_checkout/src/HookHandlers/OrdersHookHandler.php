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

namespace Tygh\Addons\YandexCheckout\HookHandlers;

use Tygh\Addons\YandexCheckout\Enum\PaymentStatus;
use Tygh\Addons\YandexCheckout\Payments\YandexCheckout;
use Tygh\Enum\YesNo;
use Tygh\Tygh;
use Exception;

class OrdersHookHandler
{
    /**
     * The "change_order_status_post" hook handler.
     *
     * Actions performed:
     *     - Checks order for needs to create a second receipt after prepayment
     *
     * @see \fn_change_order_status()
     */
    public function onCompletePurchase($order_id, $status_to, $status_from, $force_notification, $place_order, $order_info, $edp_data)
    {
        $processor_id = db_get_field('SELECT processor_id FROM ?:payment_processors WHERE processor_script = ?s AND addon = ?s', 'yandex_checkout.php', 'yandex_checkout');
        if ($order_info['payment_method']['processor_id'] != $processor_id) {
            return;
        }
        if (YesNo::toBool($order_info['payment_method']['processor_params']['send_receipt'])
            && $status_to === $order_info['payment_method']['processor_params']['final_success_status']
        ) {
            $payment_processor = new YandexCheckout($order_info['payment_method']['processor_params']['shop_id'], $order_info['payment_method']['processor_params']['scid'], Tygh::$app['addons.yandex_checkout.receipt_service']);
            try {
                $payment_info = $payment_processor->getPaymentInfo($order_info['payment_info']['id']);
                if ($payment_info->getStatus() === PaymentStatus::SUCCEEDED) {
                    $payment_processor->createReceipt($order_info);
                    fn_update_order_payment_info($order_id, ['status' => PaymentStatus::SUCCEEDED]);
                }
            } catch (Exception $exception) {
                fn_set_notification('E', __('error'), $exception->getMessage());
            }
        }
    }
}