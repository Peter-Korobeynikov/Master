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

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

$order_info     = fn_get_order_info($order_id);
$processor_data = $order_info['payment_method'];
$url            = ($processor_data['processor_params']['test'] == 'Y') ? 'https://' . KVK_API_TEST_URL : 'https://' . KVK_API_URL;
$request_url    = $url . '/api/partners/v1/lightweight/create';
$request_data   = array();

$request_data['shopId']        = ($processor_data['processor_params']['test'] == 'Y') ? 'test_shop' : $processor_data['processor_params']['kvk_shop_id'];
$request_data['customerEmail'] = $order_info['email'] ? $order_info['email'] : '';
$request_data['customerPhone'] = $order_info['b_phone'] ? $order_info['b_phone'] : '';
$request_data['integrationType'] = 'CSCart';

if(isset($processor_data['processor_params']['kvk_show_case_id'])){
    $request_data['showcaseId'] = $processor_data['processor_params']['kvk_show_case_id'];
}

$count = 0;
foreach ($order_info['products'] as $k => $item) {
    $price = fn_format_price(($item['subtotal'] - fn_external_discounts($item)) / $item['amount']);
    $category = db_get_field("SELECT ?:category_descriptions.category FROM ?:category_descriptions LEFT JOIN ?:products_categories ON ?:category_descriptions.category_id = ?:products_categories.category_id WHERE ?:products_categories.product_id = ?i AND ?:products_categories.link_type = ?s AND ?:category_descriptions.lang_code = ?s", $item['product_id'], 'M', $order_info['lang_code']);

    $request_data['itemName_' . $count] = $item['product'];
    $request_data['itemPrice_' . $count] = fn_format_rate_value($price, 'F', 2, '.', '', '');
    $request_data['itemQuantity_' . $count] = $item['amount'];
    $request_data['itemCategory_' . $count] = $category;

    $count += 1;
}

if (!empty($order_info['shipping_cost'])) {
    $request_data['itemName_' . $count] = __('shipping_cost');
    $request_data['itemQuantity_' . $count] = 1;
    $request_data['itemPrice_' . $count] = fn_format_rate_value($order_info['shipping_cost'], 'F', 2, '.', '', '');

    $count += 1;
}

if (!empty($order_info['taxes'])) {
    foreach ($order_info['taxes'] as $tax) {
        if ($tax['price_includes_tax'] == 'N') {

            $request_data['itemName_' . $count] = __('tax');
            $request_data['itemQuantity_' . $count] = 1;
            $request_data['itemPrice_' . $count] = fn_format_rate_value($tax['tax_subtotal'], 'F', 2, '.', '', '');

            $count += 1;
        }
    }
}

if (!empty($order_info['subtotal_discount'])) {
    $request_data['itemName_' . $count] = __('discount');
    $request_data['itemQuantity_' . $count] = 1;
    $request_data['itemPrice_' . $count] = '-' . fn_format_rate_value($order_info['subtotal_discount'], 'F', 2, '.', '', '');
    $count += 1;
}

if (!empty($order_info['gift_certificates'])) {
    foreach ($order_info['gift_certificates'] as $certificate_data) {
        $request_data['itemName_' . $count] = __('gift_certificate');
        $request_data['itemQuantity_' . $count] = 1;
        $request_data['itemPrice_' . $count] = fn_format_rate_value($certificate_data['amount'], 'F', 2, '.', '', '');

        $count += 1;
    }
}

if (!empty($order_info['use_gift_certificates'])) {
    foreach ($order_info['use_gift_certificates'] as $key => $data) {
        $request_data['itemName_' . $count] = __('gift_certificates');
        $request_data['itemQuantity_' . $count] = 1;
        $request_data['itemPrice_' . $count] = '-' . fn_format_rate_value($data['amount'], 'F', 2, '.', '', '');

        $count += 1;
    }
}

$surcharge = isset($order_info['payment_surcharge']) ? intval($order_info['payment_surcharge']) : 0;
if ($surcharge != 0) {

    $request_data['itemName_' . $count] = __('payment_surcharge');
    $request_data['itemQuantity_' . $count] = 1;
    $request_data['itemPrice_' . $count] = fn_format_rate_value($order_info['payment_surcharge'], 'F', 2, '.', '', '');

    $count += 1;
}

$order_total = fn_format_rate_value($order_info['total'], 'F', 2, '.', '', '');

$request_data['sum'] = $order_total;

fn_change_order_status($order_id, 'O');
fn_clear_cart(Tygh::$app['session']['cart']);
fn_create_payment_form($request_url, $request_data, 'KupivKredit', true, $method = 'post', $parse_url = false, $target = 'form', $connection_message = __('rus_kupivkredit.redirect_to_create_order'));

exit;
