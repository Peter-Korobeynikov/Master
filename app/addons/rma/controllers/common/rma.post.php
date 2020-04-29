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
use Tygh\Navigation\LastView;
use Tygh\Enum\Addons\Rma\ReturnOperationStatuses;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/* POST data processing */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'add_return') {

        if (!empty($_REQUEST['returns'])) {
            $order_id = (int) $_REQUEST['order_id'];
            $returns = (array) $_REQUEST['returns'];
            $action = $_REQUEST['action'];
            $comment = $_REQUEST['comment'];

            $order_info = fn_get_order_info($order_id);

            if (empty($order_info)) {
                return array(CONTROLLER_STATUS_NO_PAGE);
            }

            $user_id = (int) $order_info['user_id'];
            $order_lang_code = $order_info['lang_code'];

            if (AREA != 'A' && !fn_is_order_allowed($order_id, $auth)) {
                return array(CONTROLLER_STATUS_DENIED);
            }

            $total_amount = 0;
            foreach ($returns as $k => $v) {
                if (isset($v['chosen']) && $v['chosen'] == 'Y') {
                    $total_amount += $v['amount'];
                }
            }

            $_data = array(
                'order_id' => $order_id,
                'user_id' => $user_id,
                'action' => $action,
                'timestamp' => TIME,
                'status' => ReturnOperationStatuses::REQUESTED,
                'total_amount' => $total_amount,
                'comment' => $comment
            );
            $return_id = db_query('INSERT INTO ?:rma_returns ?e', $_data);

            $order_items = db_get_hash_array("SELECT item_id, order_id, extra, price, amount FROM ?:order_details WHERE order_id = ?i", 'item_id', $order_id);
            foreach ($returns as $item_id => $v) {
                if (isset($v['chosen']) && $v['chosen'] == 'Y') {
                    if (true == fn_rma_declined_product_correction($order_id, $k, $v['available_amount'], $v['amount'])) {
                        $_item = $order_items[$item_id];
                        $extra = @unserialize($_item['extra']);
                        $_data = array (
                            'return_id' => $return_id,
                            'item_id' => $item_id,
                            'product_id' => $v['product_id'],
                            'reason' => !empty($v['reason']) ? $v['reason'] : '',
                            'amount' => $v['amount'],
                            'product_options' => !empty($extra['product_options_value']) ? serialize($extra['product_options_value']) : '',
                            'price' => fn_format_price((((!isset($extra['exclude_from_calculate'])) ? $_item['price'] : 0) * $_item['amount']) / $_item['amount']),
                            'product' => !empty($extra['product']) ? $extra['product'] : fn_get_product_name($v['product_id'], $order_lang_code)
                        );

                        db_query('INSERT INTO ?:rma_return_products ?e', $_data);

                        if (!isset($extra['returns'])) {
                            $extra['returns'] = array();
                        }
                        $extra['returns'][$return_id] = array(
                            'amount' => $v['amount'],
                            'status' => ReturnOperationStatuses::REQUESTED
                        );
                        db_query('UPDATE ?:order_details SET ?u WHERE item_id = ?i AND order_id = ?i', array('extra' => serialize($extra)), $item_id, $order_id);
                    }
                }
            }

            //Send mail
            $return_info = fn_get_return_info($return_id);
            $order_info = fn_get_order_info($order_id);
            fn_send_return_mail($return_info, $order_info, array('C' => true, 'A' => true, 'S' => true));
        }

        return array(CONTROLLER_STATUS_OK, 'rma.details?return_id=' . $return_id);
    }
}

if (empty($auth['user_id']) && !isset($auth['order_ids']) && AREA == 'C') {
    return array(CONTROLLER_STATUS_REDIRECT, 'auth.login_form?return_url=' . urlencode(Registry::get('config.current_url')));
}

if ($mode == 'details' && !empty($_REQUEST['return_id'])) {
    $return_id = intval($_REQUEST['return_id']);

    // [Breadcrumbs]
    if (AREA != 'A') {
        fn_add_breadcrumb(__('return_requests'), "rma.returns");
        fn_add_breadcrumb(__('return_info'));
    }
    // [/Breadcrumbs]

    Registry::set('navigation.tabs', array (
        'return_products' => array (
            'title' => __('return_products_information'),
            'js' => true
        ),
        'declined_products' => array (
            'title' => __('declined_products_information'),
            'js' => true
        ),
    ));

    $return_info = fn_get_return_info($return_id);

    if ((AREA == 'C') && (empty($return_info) || $return_info['user_id'] != $auth['user_id'] || !fn_is_order_allowed($return_info['order_id'], $auth))) {
        return array(CONTROLLER_STATUS_DENIED);
    }

    if (AREA == 'A') {
        Registry::set('navigation.tabs.comments', array (
            'title' => __('comments'),
            'js' => true
        ));
        Registry::set('navigation.tabs.actions', array (
            'title' => __('actions'),
            'js' => true
        ));

        Tygh::$app['view']->assign('is_refund', fn_is_refund_action($return_info['action']));
        Tygh::$app['view']->assign('order_info', fn_get_order_info($return_info['order_id']));
    }
    $return_info['extra'] = !empty($return_info['extra']) ? unserialize($return_info['extra']) : array();
    if (!is_array($return_info['extra'])) {
        $return_info['extra'] = array();
    }

    Tygh::$app['view']->assign('reasons', fn_get_rma_properties( RMA_REASON ));
    Tygh::$app['view']->assign('actions', fn_get_rma_properties( RMA_ACTION ));
    Tygh::$app['view']->assign('return_info', $return_info);

} elseif ($mode == 'print_slip' && !empty($_REQUEST['return_id'])) {

    echo(fn_rma_print_packing_slips($_REQUEST['return_id'], $auth));
    exit;

} elseif ($mode == 'returns') {

    // [Breadcrumbs]
    if (AREA != 'A') {
        fn_add_breadcrumb(__('return_requests'));
    }
    // [/Breadcrumbs]

    $params = $_REQUEST;
    if (AREA == 'C') {
        $params['user_id'] = $auth['user_id'];

        if (empty($params['user_id']) && !empty($auth['order_ids'])) {
            $params['order_ids'] = $auth['order_ids'];
        }

        if (!empty($params['order_ids']) && !empty($params['order_id']) && !fn_is_order_allowed($params['order_id'], $auth)) {
            unset($params['order_id']);
        }
    }

    list($return_requests, $search) = fn_rma_get_returns($params, Registry::get('settings.Appearance.' . (AREA == 'A' ? 'admin_' : '') . 'elements_per_page'));
    Tygh::$app['view']->assign('return_requests', $return_requests);
    Tygh::$app['view']->assign('search', $search);

    fn_rma_generate_sections('requests');

    Tygh::$app['view']->assign('actions', fn_get_rma_properties(RMA_ACTION));

} elseif ($mode == 'create_return' && !empty($_REQUEST['order_id'])) {
    $order_id = intval($_REQUEST['order_id']);

    // [Breadcrumbs]
    if (AREA != 'A') {
        fn_add_breadcrumb(__('order').' #'.$order_id, "orders.details?order_id=$order_id");
        fn_add_breadcrumb(__('return_registration'));
    }
    // [/Breadcrumbs]

    $order_info = fn_get_order_info($order_id);
    $order_returnable_products = fn_get_order_returnable_products($order_info['products'], $order_info['products_delivery_date']);
    $order_info['products'] = $order_returnable_products['items'];

    if (!isset($order_info['allow_return'])) {
        return array(CONTROLLER_STATUS_DENIED);
    }

    if (AREA != 'A' && !fn_is_order_allowed($order_id, $auth)) {
        return array(CONTROLLER_STATUS_DENIED);
    }

    Tygh::$app['view']->assign('order_info', $order_info);
    Tygh::$app['view']->assign('reasons', fn_get_rma_properties( RMA_REASON ));
    Tygh::$app['view']->assign('actions', fn_get_rma_properties( RMA_ACTION ));
}
