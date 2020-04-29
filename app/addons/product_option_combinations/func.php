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

use Tygh\Enum\ProductTracking;
use Tygh\Registry;

function fn_product_option_combinations_apply_options_rules_post(&$product)
{
    if (!empty($product['tracking']) && $product['tracking'] == ProductTracking::TRACK_WITH_OPTIONS) {
        if (!$product['hide_stock_info']) {
            $combination = db_get_row("SELECT product_code, amount FROM ?:product_options_inventory WHERE combination_hash = ?s", $product['combination_hash']);

            if (!empty($combination['product_code'])) {
                $product['product_code'] = $combination['product_code'];
            }

            if (Registry::get('settings.General.inventory_tracking') == 'Y') {
                if (isset($combination['amount'])) {
                    $product['inventory_amount'] = $combination['amount'];
                } else {
                    $product['inventory_amount'] = $product['amount'] = 0;
                }
            }
        }
    }

    if (!$product['options_update']) {
        $product['options_update'] = db_get_field('SELECT COUNT(*) FROM ?:product_options_inventory WHERE product_id = ?i', $product['product_id']);
    }
}