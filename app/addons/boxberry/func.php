<?php

function fn_boxberry_pre_place_order(&$cart, $allow, $product_groups)
{
    foreach($cart['product_groups'] as $group_key => &$group) {
        if (!empty($group['chosen_shippings'])) {
            foreach($group['chosen_shippings'] as &$shipping) {
                if ($shipping['module'] == 'boxberry') {
                    $shipping['point_id'] = $cart['shippings_extra']['boxberry'][$group_key][$shipping['shipping_id']]['point_id'];
                }
            }
        }
    }
}