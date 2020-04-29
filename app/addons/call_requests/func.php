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

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;
use Tygh\Navigation\LastView;

function fn_call_requests_info()
{
    if (isset(Tygh::$app['view'])) {
        return Tygh::$app['view']->fetch('addons/call_requests/settings/info.tpl');
    }
}

function fn_call_requests_get_phone()
{
    return Registry::ifGet('addons.call_requests.phone', Registry::get('settings.Company.company_phone'));
}

function fn_call_requests_get_splited_phone()
{
    $phone_number = fn_call_requests_get_phone();
    $length = Registry::get('addons.call_requests.phone_prefix_length');

    if (empty($length) || intval($length) == 0) {
        $length = 0;
    }

    return array(
        'prefix' => substr($phone_number, 0, $length),
        'postfix' => substr($phone_number, $length)
    );
}

function fn_get_call_requests($params = array(), $lang_code = CART_LANGUAGE)
{
    // Init filter
    $params = LastView::instance()->update('call_requests', $params);

    $params = array_merge(array(
        'items_per_page' => 0,
        'page' => 1,
    ), $params);

    $fields = array(
        'r.*',
        'o.status as order_status',
        'd.product',
    );

    $joins = array(
        db_quote("LEFT JOIN ?:users u USING(user_id)"),
        db_quote("LEFT JOIN ?:orders o USING(order_id)"),
        db_quote("LEFT JOIN ?:product_descriptions d ON d.product_id = r.product_id AND d.lang_code = ?s", $lang_code),
    );

    $sortings = array (
        'id' => 'r.request_id',
        'date' => 'r.timestamp',
        'status' => 'r.status',
        'name' => 'r.name',
        'phone' => 'r.phone',
        'user_id' => 'r.user_id',
        'user' => array('u.lastname', 'u.firstname'),
        'order' => 'r.order_id',
        'order_status' => 'o.status',
    );

    $condition = array();

    if (isset($params['id']) && fn_string_not_empty($params['id'])) {
        $params['id'] = trim($params['id']);
        $condition[] = db_quote("r.request_id = ?i", $params['id']);
    }

    if (isset($params['name']) && fn_string_not_empty($params['name'])) {
        $params['name'] = trim($params['name']);
        $condition[] = db_quote("r.name LIKE ?l", '%' . $params['name'] . '%');
    }

    if (isset($params['phone']) && fn_string_not_empty($params['phone'])) {
        $params['phone'] = trim($params['phone']);
        $condition[] = db_quote("r.phone LIKE ?l", '%' . $params['phone'] . '%');
    }

    if (!empty($params['status'])) {
        $condition[] = db_quote("r.status = ?s", $params['status']);
    }

    if (!empty($params['company_id'])) {
        $condition[] = db_quote("r.company_id = ?i", $params['company_id']);
    }

    if (!empty($params['order_status'])) {
        $condition[] = db_quote("o.status = ?s", $params['order_status']);
    }

    if (!empty($params['order_email']) && fn_string_not_empty($params['order_email'])) {
        $condition[] = db_quote('o.email = ?s', $params['order_email']);
    }

    if (!empty($params['user_id'])) {
        $condition[] = db_quote("r.user_id = ?s", $params['user_id']);
    }

    if (!empty($params['order_exists'])) {
        $sign = $params['order_exists'] == 'Y' ? '<>' : '=';
        $condition[] = db_quote("r.order_id ?p 0", $sign);
    }

    $fields_str = implode(', ', $fields);
    $joins_str = ' ' . implode(' ', $joins);
    $condition_str = $condition ? (' WHERE ' . implode(' AND ', $condition)) : '';
    $sorting_str = db_sort($params, $sortings, 'date', 'desc');

    $limit = '';
    if (!empty($params['items_per_page'])) {
        $params['total_items'] = db_get_field(
            "SELECT COUNT(r.request_id) FROM ?:call_requests r" . $joins_str . $condition_str
        );
        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }

    $items = db_get_array(
        "SELECT " . $fields_str
        . " FROM ?:call_requests r"
        . $joins_str
        . $condition_str
        . $sorting_str
        . $limit
    );

    if (!empty($items)) {
        $cart_product_ids = array();
        foreach ($items as &$item) {
            if (!empty($item['cart_products'])) {
                $item['cart_products'] = unserialize($item['cart_products']);
                foreach ($item['cart_products'] as $cart_product) {
                    $cart_product_ids[] = $cart_product['product_id'];
                }
            }
        }
        $cart_product_names = db_get_hash_single_array(
            "SELECT product_id, product FROM ?:product_descriptions WHERE product_id IN(?n) AND lang_code = ?s",
            array('product_id', 'product'), array_unique($cart_product_ids), $lang_code
        );
        foreach ($items as &$item) {
            if (!empty($item['cart_products'])) {
                foreach ($item['cart_products'] as &$cart_product) {
                    if (!empty($cart_product_names[$cart_product['product_id']])) {
                        $cart_product['product'] = $cart_product_names[$cart_product['product_id']];
                    }
                }
            }
        }
    }

    LastView::instance()->processResults('call_requests', $items, $params);

    return array($items, $params);
}

function fn_update_call_request($data, $request_id = 0)
{
    if (isset($data['cart_products'])) {
        if (!empty($data['cart_products']) && is_array($data['cart_products'])) {
            foreach ($data['cart_products'] as $key => $product) {
                if (empty($product['product_id'])) {
                    unset($data['cart_products'][$key]);
                }
            }
            $data['cart_products'] = !empty($data['cart_products']) ? serialize($data['cart_products']) : '';
        } else {
            $data['cart_products'] = '';
        }
    }

    if ($request_id) {
        db_query("UPDATE ?:call_requests SET ?u WHERE request_id = ?i", $data, $request_id);
    } else {
        if (empty($data['timestamp'])) {
            $data['timestamp'] = TIME;
        }
        if (empty($data['company_id']) && $company_id = Registry::get('runtime.company_id')) {
            $data['company_id'] = $company_id;
        }
        $request_id = db_query("INSERT INTO ?:call_requests ?e", $data);
    }

    return $request_id;
}

function fn_delete_call_request($request_id)
{
    return db_query("DELETE FROM ?:call_requests WHERE request_id = ?i", $request_id);
}

/**
 * Creates call request
 *
 * @param array $params         Call request parameters
 * @param array $product_data   Product data
 * @param array $cart           Array of cart content and user information necessary for purchase
 * @param array $auth           Array of user authentication data (e.g. uid, usergroup_ids, etc.)
 *
 * @return array
 */
function fn_do_call_request($params, $product_data, &$cart, &$auth)
{
    $result = array();

    $params['cart_products'] = fn_call_request_get_cart_products($cart);

    if (!empty($params['product_id']) && !empty($params['email'])) {
        $params['order_id'] = fn_call_requests_placing_order($params, $product_data, $cart, $auth);;
    }

    if (fn_allowed_for('ULTIMATE')) {
        $company_id = Registry::get('runtime.company_id');
    } elseif (!empty($params['order_id'])) {
        $company_id = db_get_field('SELECT company_id FROM ?:orders WHERE order_id = ?i', $params['order_id']);
    } elseif (!empty($params['product_id'])) {
        $company_id = db_get_field('SELECT company_id FROM ?:products WHERE product_id = ?i', $params['product_id']);
    } else {
        $company_id = 0;
    }

    $params['company_id'] = $company_id;

    /**
     * Allows to perform some actions before call request is processed
     *
     * @param array $params         Call request parameters
     * @param array $product_data   Product data
     * @param array $cart           Array of cart content and user information necessary for purchase
     * @param array $auth           Array of user authentication data (e.g. uid, usergroup_ids, etc.)
     * @param int   $company_id     Company identifier
     */
    fn_set_hook('do_call_request', $params, $product_data, $cart, $auth, $company_id);

    $request_id = fn_update_call_request($params);

    $params['request_id'] = $request_id;

    /** @var \Tygh\Notifications\EventDispatcher $event_dispatcher */
    $event_dispatcher = Tygh::$app['event.dispatcher'];

    if (empty($params['product_id'])) {

        $event_dispatcher->dispatch('call_requests.request_created', [
            'call_request_data' => $params
        ]);

    } elseif (empty($params['order_id'])) { // Buy with one click when user didn't fill out the email field.

        $event_dispatcher->dispatch('call_requests.request_about_product_created', [
            'call_request_data' => $params
        ]);
    }

    if (!empty($params['order_id'])) {
        $result['notice'] = __('call_requests.order_placed', array('[order_id]' => $params['order_id']));
    } else {
        $result['notice'] = __('call_requests.request_recieved');
    }

    /**
     * Allows to perform some actions after call request is processed
     *
     * @param array $params       Parameters
     * @param array $product_data Product data
     * @param array $cart         Cart data
     * @param array $auth         Authentication data
     * @param array $result       Operation result
     */
    fn_set_hook('call_requests_do_call_request_post', $params, $product_data, $cart, $auth, $result);

    return $result;
}

function fn_call_request_get_cart_products(&$cart)
{
    $products = array();

    if (!empty($cart['products'])) {
        foreach ($cart['products'] as $product) {
            $products[] = array(
                'product_id' => $product['product_id'],
                'amount'     => $product['amount'],
                'price'      => $product['price'],
            );
        }
    }

    return $products;
}

function fn_call_requests_placing_order($params, $product_data, &$cart, &$auth)
{
    // Save cart
    $buffer_cart = $cart;
    $buffer_auth = $auth;

    $cart = array(
        'products' => array(),
        'recalculate' => false,
        'payment_id' => 0, // skip payment
        'is_call_request' => true,
    );

    $firstname = $params['name'];
    $lastname = '';
    $cart['user_data']['email'] = $params['email'];
    if (!empty($firstname) && strpos($firstname, ' ')) {
        list($firstname, $lastname) = explode(' ', $firstname);
    }
    $cart['user_data']['firstname'] = $firstname;
    $cart['user_data']['b_firstname'] = $firstname;
    $cart['user_data']['s_firstname'] = $firstname;
    $cart['user_data']['lastname'] = $lastname;
    $cart['user_data']['b_lastname'] = $lastname;
    $cart['user_data']['s_lastname'] = $lastname;
    $cart['user_data']['phone'] = $params['phone'];
    $cart['user_data']['b_phone'] = $params['phone'];
    $cart['user_data']['s_phone'] = $params['phone'];
    foreach (array('b_address', 's_address', 'b_city', 's_city', 'b_country', 's_country', 'b_state', 's_state') as $key) {
        if (!isset($cart['user_data'][$key])) {
            $cart['user_data'][$key] = ' ';
        }
    }

    if (!isset($cart['storefront_id'])) {
        /** @var \Tygh\Storefront\Storefront $storefront */
        $cart['storefront_id'] = Tygh::$app['storefront']->storefront_id;
    }

    if (empty($product_data[$params['product_id']]['amount'])) {
        $product_data[$params['product_id']] = array(
            'product_id' => $params['product_id'],
            'amount' => 1,
        );
    }

    fn_add_product_to_cart($product_data, $cart, $auth);

    fn_calculate_cart_content($cart, $auth, 'A', true, 'F', true);

    $order_id = 0;
    if ($res = fn_place_order($cart, $auth)) {
        list($order_id) = $res;
    }

    // Restore cart
    $cart = $buffer_cart;
    $auth = $buffer_auth;

    return $order_id;
}

function fn_call_requests_get_responsibles()
{
    $company_condition = '';
    if ($company_id = Registry::get('runtime.company_id')) {
        $company_condition = db_quote(' AND company_id = ?i', $company_id);
    }

    $items = db_get_hash_single_array(
        "SELECT user_id, CONCAT(lastname, ' ', firstname) as name FROM ?:users WHERE user_type = ?s ?p",
        array('user_id', 'name'), 'A', $company_condition
    );

    return $items;
}

function fn_call_requests_addon_install()
{
    // Order statuses
    $existing_status_id = fn_get_status_id('Y', STATUSES_ORDER);
    if (!$existing_status_id) {
        fn_update_status('', array(
            'status' => 'Y',
            'is_default' => 'Y',
            'description' => __('call_requests.awaiting_call'),
            'email_subj' => __('call_requests.awaiting_call'),
            'email_header' => __('call_requests.awaiting_call'),
            'params' => array(
                'color' => '#cc4125',
                'notify' => 'Y',
                'notify_department' => 'Y',
                'repay' => 'Y',
                'inventory' => 'D',
            ),
        ), STATUSES_ORDER);
    }
}

function fn_settings_variants_addons_call_requests_order_status()
{
    $data = array(
        '' => ' -- '
    );

    foreach (fn_get_statuses(STATUSES_ORDER) as $status) {
        $data[$status['status']] = $status['description'];
    }

    return $data;
}

function fn_call_requests_settings_variants_image_verification_use_for(&$objects)
{
    $objects['call_request'] = __('call_requests.use_for_call_requests');
}

/* Hooks */

function fn_call_requests_init_templater_post(&$view)
{
    $view->addPluginsDir(Registry::get('config.dir.addons') . 'call_requests/functions/smarty_plugins');
}

function fn_call_requests_allow_place_order(&$total, &$cart)
{
    if (!empty($cart['is_call_request'])) {
        // Need to skip shipping
        $cart['shipping_failed'] = false;
        $cart['company_shipping_failed'] = false;
    }
}

function fn_call_requests_place_order(&$order_id, &$action, &$order_status, &$cart, &$auth)
{
    if (!empty($cart['is_call_request'])) {
        $order_status = Registry::get('addons.call_requests.order_status');
    }
}

function fn_call_requests_delete_company(&$company_id, &$result)
{
    return db_query("DELETE FROM ?:call_requests WHERE company_id = ?i", $company_id);
}

/**
 * Hook handler for GPDR add-on: saves accepted agreement to the log
 */
function fn_gdpr_call_requests_do_call_request_post($params, $product_data, $cart, $auth, $result)
{
    if (AREA !== 'C') {
        return false;
    }

    $email = isset($params['email']) ? $params['email'] : '';
    $user_id = isset($auth['user_id']) ? (int) $auth['user_id'] : 0;

    if (empty($email) && !empty($user_id)) {
        $user_info = fn_get_user_info($auth['user_id']);
        $email = isset($user_info['email']) ? $user_info['email'] : '';
    }

    $params = array(
        'user_id' => $user_id,
        'email' => $email,
    );

    return fn_gdpr_save_user_agreement($params, 'call_requests');
}