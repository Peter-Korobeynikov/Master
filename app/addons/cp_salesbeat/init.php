<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    'get_available_shippings',
    'shippings_calculate_rates_post',
    'shippings_get_shippings_list_conditions',
    'pre_place_order',
    'pre_get_cart_product_data',
    'calculate_cart_items'
);
