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

fn_register_hooks(
    'get_orders',
    'update_product_feature_variant',
    'look_through_variants_update_combination',
    'before_dispatch',
    'store_locator_delete_store_location_post',
    'store_locator_get_store_location_before_select',
    'store_locator_update_store_location_post',
    'get_product_feature_data_before_select',
    'get_product_feature_variants',
    'get_store_locations_before_select',
    'store_locator_get_store_location_post',
    'store_locator_get_store_locations_post'
);
