<?php

use Tygh\Registry;

require_once dirname(__FILE__) . '/tinkoff/TinkoffMerchantAPI.php';

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

if (defined('PAYMENT_NOTIFICATION')) {
    $request = json_decode(file_get_contents('php://input'));
    $request->Success = $request->Success ? 'true' : 'false';
    $request = (array)$request;

    if (isset($request['OrderId'])) {
        $order_id = (int)$request['OrderId'];
    } else {
        $order_id = $_COOKIE['tinkoff_order_id'];
    }

    $order_info = fn_get_order_info($order_id);
    $processor_data = $order_info['payment_method'];

    if ($mode == 'notification') {
        $request['Password'] = $processor_data['processor_params']['secret_key'];
        ksort($request);

        $sorted = $request;
        $original_token = $sorted['Token'];
        unset($sorted['Token']);

        $values = implode('', array_values($sorted));
        $token = hash('sha256', $values);

        if ($token == $original_token) {
            if ($request['Status'] == 'AUTHORIZED' && $order_info['status'] == 'P') {
                die('OK');
            }
            switch ($request['Status']) {
                case 'AUTHORIZED':
                    $order_status = 'O';
                    break; /*Деньги на карте захолдированы. Корзина очищается.*/
                case 'CONFIRMED':
                    $order_status = 'P';
                    break; /*Платеж подтвержден.*/
                case 'CANCELED':
                    $order_status = 'I';
                    break; /*Платеж отменен*/
                case 'REJECTED':
                    $order_status = 'F';
                    break; /*Платеж отклонен.*/
                case 'REVERSED':
                    $order_status = 'D';
                    break; /*Платеж отменен*/
                case 'REFUNDED':
                    $order_status = 'I';
                    break; /*Произведен возврат денег клиенту*/
            }

            if (!isset($order_status)) {
                die('NOTOK');
            }

            fn_change_order_status($order_id, $order_status);

            if ($order_status == 'P') {
                $pp_response = array();
                $pp_response['order_status'] = $order_status;
                $pp_response['reason_text'] = 'Success payment';
                $pp_response['transaction_id'] = isset($request['paymentId']) ? $request['paymentId'] : '';
                fn_finish_payment($order_id, $pp_response);
            }

            die('OK');
        } else {
            die('NOTOK');
        }
    } elseif ($mode == 'success' || $mode == 'failed') {
        fn_order_placement_routines('route', $order_id, false);
    }

} else {
    $order_info = fn_get_order_info($order_id);

    switch ($processor_data['processor_params']['taxation']) {
        case 'tinkoff_osn':
            $taxation = 'osn';
            break;
        case 'tinkoff_usn_income':
            $taxation = 'usn_income';
            break;
        case 'tinkoff_usn_income_outcome':
            $taxation = 'usn_income_outcome';
            break;
        case 'tinkoff_envd':
            $taxation = 'envd';
            break;
        case 'tinkoff_esn':
            $taxation = 'esn';
            break;
        case 'tinkoff_patent':
            $taxation = 'patent';
            break;
        default:
            $taxation = 'error';
    }

    switch ($processor_data['processor_params']['payment_method']) {
        case 'tinkoff_full_prepayment':
            $paymentMethod = 'full_prepayment';
            break;
        case 'tinkoff_prepayment':
            $paymentMethod = 'prepayment';
            break;
        case 'tinkoff_advance':
            $paymentMethod = 'advance';
            break;
        case 'tinkoff_full_payment':
            $paymentMethod = 'full_payment';
            break;
        case 'tinkoff_partial_payment':
            $paymentMethod = 'partial_payment';
            break;
        case 'tinkoff_credit':
            $paymentMethod = 'credit';
            break;
        case 'tinkoff_credit_payment':
            $paymentMethod = 'credit_payment';
            break;
        default:
            $paymentMethod = 'error';
    }

    switch ($processor_data['processor_params']['payment_object']) {
        case 'tinkoff_commodity':
            $paymentObject = 'commodity ';
            break;
        case 'tinkoff_excise':
            $paymentObject = 'excise';
            break;
        case 'tinkoff_job':
            $paymentObject = 'job';
            break;
        case 'tinkoff_service':
            $paymentObject = 'service ';
            break;
        case 'tinkoff_gambling_bet':
            $paymentObject = 'gambling_bet';
            break;
        case 'tinkoff_gambling_prize':
            $paymentObject = 'gambling_prize ';
            break;
        case 'tinkoff_lottery':
            $paymentObject = 'lottery';
            break;
        case 'tinkoff_lottery_prize':
            $paymentObject = 'lottery_prize ';
            break;
        case 'tinkoff_intellectual_activity':
            $paymentObject = 'intellectual_activity ';
            break;
        case 'tinkoff_payment':
            $paymentObject = 'payment';
            break;
        case 'tinkoff_agent_commission':
            $paymentObject = 'agent_commission';
            break;
        case 'tinkoff_composite':
            $paymentObject = 'composite';
            break;
        case 'tinkoff_another':
            $paymentObject = 'another';
            break;
        default:
            $paymentObject = 'error';
    }

    $arrOrderItems = $order_info['products'];
    $shipping = fn_order_shipping_cost($order_info);

    $items = array();

    foreach ($arrOrderItems as $arrItem) {
        $tax_rate = getTax();

        if (!empty($order_info['taxes'])) {
            foreach ($order_info['taxes'] as $tax_id => $tax) {
                foreach ($tax['applies']['items']['P'] as $idProduct => $productTax) {
                    if ($idProduct == $arrItem['item_id']) {
                        $tax_rate = getTax((int)round($tax['rate_value']));
                    }
                }
            }
        }

        if (!empty($order_info['taxes'])) {
            foreach ($order_info['taxes'] as $tax) {
                if ($tax['price_includes_tax'] == 'N') {
                    $_tax = fn_format_price($arrItem['price'] * ($tax['rate_value'] / 100));
                }
            }
        }

        $item = [
            'Name' => mb_substr($arrItem['product'], 0, 64),
            'Price' => round(fn_format_price($arrItem['price']) * 100),
            'Quantity' => round($arrItem['amount'], 2),
            'Amount' => round(fn_format_price($arrItem['price'] + $_tax) * $arrItem['amount'] * 100),
            'PaymentMethod' => trim($paymentMethod),
            'PaymentObject' => trim($paymentObject),
            'Tax' => $tax_rate,
        ];
        array_push($items, $item);
    }

    $isShipping = false;

    if (floatval($order_info['shipping_cost']) > 0) {

        foreach ($order_info['shipping'] as $key => $value) {
            $shipping_vat = getTax();

            if (!empty($value['taxes'])) {
                foreach ($value['taxes'] as $tax) {
                    if ($tax['price_includes_tax'] == 'N') {
                        $_taxShipping = fn_format_price($shipping * ($tax['rate_value'] / 100));
                    }
                    $shipping_vat = getTax((int)round($tax['rate_value']));
                }
            }

        }

        $shipping_item = [
            'Name' => mb_substr($order_info['shipping'][0]['shipping'], 0, 64),
            'Price' => round(fn_format_price($shipping * 100)),
            'Quantity' => 1,
            'Amount' => round(fn_format_price($shipping + $_taxShipping) * 100),
            'PaymentMethod' => trim($paymentMethod),
            'PaymentObject' => 'service',
            'Tax' => $shipping_vat,

        ];
        array_push($items, $shipping_item);
        $isShipping = true;
    }

    $amount = round($order_info['total'] * 100);

    $receipt = array(
        'EmailCompany' => mb_substr($processor_data['processor_params']['email_company'],0,64),
        'Email' => $order_info['email'],
        'Taxation' => $taxation,
        'Items' => balanceAmount($isShipping, $items, $amount),
    );

    if ($processor_data['processor_params']['cheque'] == 'tinkoff_yes') {
        $arrFields = array(
            'OrderId' => $order_info['order_id'],
            'Amount' => $amount,
            'DATA' => array('Email' => $order_info['email'], 'Connection_type' => 'cscart',),
            'Receipt' => $receipt,
        );
    } else {
        $arrFields = array(
            'OrderId' => $order_info['order_id'],
            'Amount' => $amount,
            'DATA' => array('Email' => $order_info['email'], 'Connection_type' => 'cscart',),
        );
    }

    if ($processor_data['processor_params']['language'] == 'tinkoff_en') {
        $arrFields['Language'] = 'en';
    }

    $Tinkoff = new TinkoffMerchantAPI($processor_data['processor_params']['merchant_id'], $processor_data['processor_params']['secret_key']);
    $request = $Tinkoff->buildQuery('Init', $arrFields);
    logs($arrFields, $request);
    $request = json_decode($request);

    setcookie('tinkoff_order_id', $order_id);
    setcookie('tinkoff_redirect', (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/index.php?dispatch=payment_notification.success&payment=tinkoff');

    if (isset($request->PaymentURL)) {
        fn_change_order_status($order_id, 'O');
        header('Location: ' . $request->PaymentURL);
    } else {
        fn_change_order_status($order_id, 'F');
        fn_order_placement_routines('route', $order_id, false);
    }
}

function logs($arrFields, $request)
{
    // log send
    $log = '[' . date('D M d H:i:s Y', time()) . '] ';
    $log .= json_encode($arrFields, JSON_UNESCAPED_UNICODE);
    $log .= "\n";
    file_put_contents(dirname(__FILE__) . "/tinkoff.log", $log, FILE_APPEND);

    $log = '[' . date('D M d H:i:s Y', time()) . '] ';
    $log .= $request;
    $log .= "\n";
    file_put_contents(dirname(__FILE__) . "/tinkoff.log", $log, FILE_APPEND);
}

function getTax($tax = null)
{
    if ($tax === 20) {
        return $vat = 'vat20';
    } elseif ($tax === 18) {
        return $vat = 'vat18';
    } elseif ($tax === 10) {
        return $vat = 'vat10';
    } elseif ($tax === 0) {
        return $vat = 'vat0';
    } else {
        return $vat = 'none';
    }
}

function balanceAmount($isShipping, $items, $amount)
{
    $itemsWithoutShipping = $items;

    if ($isShipping) {
        $shipping = array_pop($itemsWithoutShipping);
    }

    $sum = 0;

    foreach ($itemsWithoutShipping as $item) {
        $sum += $item['Amount'];
    }

    if (isset($shipping)) {
        $sum += $shipping['Amount'];
    }

    if ($sum != $amount) {
        $sumAmountNew = 0;
        $difference = $amount - $sum;
        $amountNews = array();

        foreach ($itemsWithoutShipping as $key => $item) {
            $itemsAmountNew = $item['Amount'] + floor($difference * $item['Amount'] / $sum);
            $amountNews[$key] = $itemsAmountNew;
            $sumAmountNew += $itemsAmountNew;
        }

        if (isset($shipping)) {
            $sumAmountNew += $shipping['Amount'];
        }

        if ($sumAmountNew != $amount) {
            $max_key = array_keys($amountNews, max($amountNews))[0];    // ключ макс значения
            $amountNews[$max_key] = max($amountNews) + ($amount - $sumAmountNew);
        }

        foreach ($amountNews as $key => $item) {
            $items[$key]['Amount'] = $amountNews[$key];
        }
    }

    return $items;
}

exit;