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
use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suffix = null;

    if ($mode == 'add_combinations') {
        if (is_array($_REQUEST['add_inventory'])) {
            foreach ($_REQUEST['add_inventory'] as $k => $v) {
                $_data = [
                    'product_id'  => $_REQUEST['product_id'],
                    'combination' => $_REQUEST['add_options_combination'][$k],
                    'amount'      => isset($_REQUEST['add_inventory'][$k]['amount']) ? $_REQUEST['add_inventory'][$k]['amount'] : 0,
                ];

                $_data = fn_array_merge($v, $_data);

                fn_update_option_combination($_data);
            }
        }

        $suffix = ".inventory?product_id={$_REQUEST['product_id']}";
    }

    if ($mode == 'update_combinations') {
        if (!empty($_REQUEST['inventory'])) {
            foreach ($_REQUEST['inventory'] as $k => $v) {
                fn_update_option_combination($v, $k);
            }
        }

        $suffix = ".inventory?product_id={$_REQUEST['product_id']}";
    }

    if ($mode == 'm_delete_combinations') {
        foreach ($_REQUEST['combination_hashes'] as $v) {
            fn_delete_option_combination($v);
        }

        $suffix = ".inventory?product_id={$_REQUEST['product_id']}";
    }

    if ($mode == 'rebuild_combinations') {
        fn_rebuild_product_options_inventory($_REQUEST['product_id']);

        $suffix = ".inventory?product_id={$_REQUEST['product_id']}";
    }

    if ($mode == 'delete_combination') {
        if (!empty($_REQUEST['combination_hash'])) {
            fn_delete_product_combination($_REQUEST['combination_hash']);
        }

        $suffix = ".inventory?product_id={$_REQUEST['product_id']}";
    }

    if (isset($suffix)) {
        return [CONTROLLER_STATUS_REDIRECT, 'product_options' . $suffix];
    } else {
        return [CONTROLLER_STATUS_OK];
    }
}