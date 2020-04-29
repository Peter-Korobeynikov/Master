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

fn_define('KEEP_UPLOADED_FILES', true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    return [CONTROLLER_STATUS_OK];
}

//
// Product options combination inventory tracking
//
if ($mode == 'inventory') {
    list($inventory, $search) = fn_get_product_options_inventory($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'));

    $product_options = fn_get_product_options($_REQUEST['product_id'], DESCR_SL, true, true);
    $product_inventory = db_get_field("SELECT tracking FROM ?:products WHERE product_id = ?i", $_REQUEST['product_id']);

    Tygh::$app['view']->assign('product_inventory', $product_inventory);
    Tygh::$app['view']->assign('product_options', $product_options);
    Tygh::$app['view']->assign('inventory', $inventory);
    Tygh::$app['view']->assign('search', $search);
}