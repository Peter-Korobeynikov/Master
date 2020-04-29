<?php
/*****************************************************************************
*                                                                            *
*                   All rights reserved! eCom Labs LLC                       *
* http://www.ecom-labs.com/about-us/ecom-labs-modules-license-agreement.html *
*                                                                            *
*****************************************************************************/

use Tygh\Enum\ProductTracking;
use Tygh\Registry;
use Tygh\Storage;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

Tygh::$app['session']['wishlist'] = isset(Tygh::$app['session']['wishlist']) ? Tygh::$app['session']['wishlist'] : array();
$wishlist = & Tygh::$app['session']['wishlist'];
$auth = & Tygh::$app['session']['auth'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Add product to the wishlist
    if ($mode == 'add') {
        fn_update_session_wishlist($wishlist, $auth);
    }
}

if (($mode == 'clear') || ($mode == 'delete' && !empty($_REQUEST['cart_id']))) {
    fn_update_session_wishlist($wishlist, $auth);
} else if ($mode == 'delete_content' && !empty($_REQUEST['cart_id'])) {
    fn_delete_wishlist_product($wishlist, $_REQUEST['cart_id']);

    fn_save_cart_content($wishlist, $auth['user_id'], 'W');

    fn_update_session_wishlist($wishlist, $auth);
}

function fn_update_session_wishlist(&$wishlist, $auth)
{
    if (!empty($wishlist['products'])) {
        $wishlist['amount'] = 0;
        $wishlist['display_subtotal'] = 0;
    
        $products = !empty($wishlist['products']) ? $wishlist['products'] : array();
    
        if (!empty($products)) {
            foreach ($products as $k => &$v) {
                $v['display_price'] = fn_get_product_price($v['product_id'],$v['amount'],$auth);
    
                $wishlist['amount'] += $v['amount'];
                $wishlist['display_subtotal'] += $v['display_price'] * $v['amount'];
            }
        }
    
        fn_gather_additional_products_data($products, array('get_icon' => true, 'get_detailed' => true, 'get_options' => false, 'get_discounts' => false, 'detailed_params' => false));
    
        $wishlist['products'] = $products;
    
        if ($wishlist['amount'] == 0) {
            unset($wishlist['amount']);
        }
    
        if ($wishlist['display_subtotal'] == 0) {
            unset($wishlist['display_subtotal']);
        }
    } else {
        unset($wishlist['amount']);
    }
}