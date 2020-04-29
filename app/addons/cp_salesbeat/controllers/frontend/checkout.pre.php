<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

$cart = & Tygh::$app['session']['cart'];

if($mode=='update_steps') {

    if(!empty($_REQUEST['update_salesbeat_step'])) {

        if(!empty($_REQUEST['delivery_method_id'])) {
            $shipping_id=db_get_field('SELECT shipping_id FROM ?:shippings WHERE salesbeat_id=?s AND status=?s',$_REQUEST['delivery_method_id'],'A');
        }

        if(!empty($_REQUEST['delivery_method_id'])) {
            if(!empty($_REQUEST['pvz_id'])) {
                if(!empty($auth['user_id'])) {
                    db_query('UPDATE ?:user_profiles SET s_pvz_id=?s WHERE user_id=?i',$_REQUEST['pvz_id'],$auth['user_id']);
                }
                $cart['user_data']['s_pvz_id']=$_REQUEST['pvz_id'];
                $cart['pvz_id']=$_REQUEST['pvz_id'];
            } else {
                if(!empty($auth['user_id'])) {
                    db_query('UPDATE ?:user_profiles SET s_pvz_id=?s WHERE user_id=?i','',$auth['user_id']);
                }
                $cart['user_data']['s_pvz_id']='';
                $cart['pvz_id']="";
            }
        }

        if(!empty($_REQUEST['city_code'])) {
            if(!empty($auth['user_id'])) {
                db_query('UPDATE ?:user_profiles SET s_city_id=?s WHERE user_id=?i',$_REQUEST['city_code'],$auth['user_id']);
            }
            $cart['user_data']['s_city_id']=$_REQUEST['city_code'];
            $cart['city_id']=$_REQUEST['city_code'];
        }


            if(!empty($_REQUEST['city_name'])) {
                fn_salesbeat_update_profile_field($cart,'city',$_REQUEST['city_name'],$auth);
                if($cart['ship_to_another']==false) {
                    if(!empty($auth['user_id'])) {
                        db_query('UPDATE ?:user_profiles SET b_city=?s,s_city=?s WHERE user_id=?i',$_REQUEST['city_name'],$_REQUEST['city_name'],$auth['user_id']);
                    }
                } else {
                    if(!empty($auth['user_id'])) {
                        db_query('UPDATE ?:user_profiles SET s_city=?s WHERE user_id=?i',$_REQUEST['city_name'],$auth['user_id']);
                    }
                }
            }

            if(!empty($_REQUEST['index'])) {
                fn_salesbeat_update_profile_field($cart,'zipcode',$_REQUEST['index'],$auth);
                if($cart['ship_to_another']==false) {
                    if(!empty($auth['user_id'])) {
                        db_query('UPDATE ?:user_profiles SET b_zipcode=?s,s_zipcode=?s WHERE user_id=?i',$_REQUEST['index'],$_REQUEST['index'],$auth['user_id']);
                    }
                } else {
                    if(!empty($auth['user_id'])) {
                        db_query('UPDATE ?:user_profiles SET s_zipcode=?s WHERE user_id=?i',$_REQUEST['index'],$auth['user_id']);
                    }
                }
            }


            if(!empty($_REQUEST['street'])&&!empty(Registry::get('addons.cp_salesbeat.street'))) {
                fn_salesbeat_update_profile_field($cart,end(explode('s_',Registry::get('addons.cp_salesbeat.street'))),$_REQUEST['street'],$auth);
                $cart['user_data'][Registry::get('addons.cp_salesbeat.street')]=$_REQUEST['street'];
            }

            if(!empty($_REQUEST['house'])&&!empty(Registry::get('addons.cp_salesbeat.house'))) {
                fn_salesbeat_update_profile_field($cart,end(explode('s_',Registry::get('addons.cp_salesbeat.house'))),$_REQUEST['house'],$auth);
            }

            if(!empty($_REQUEST['house_block'])&&!empty(Registry::get('addons.cp_salesbeat.house_block'))) {
                fn_salesbeat_update_profile_field($cart,end(explode('s_',Registry::get('addons.cp_salesbeat.house_block'))),$_REQUEST['house_block'],$auth);
            }

            if(!empty($_REQUEST['flat'])&&!empty(Registry::get('addons.cp_salesbeat.flat'))) {
                fn_salesbeat_update_profile_field($cart,end(explode('s_',Registry::get('addons.cp_salesbeat.flat'))),$_REQUEST['flat'],$auth);
            }

            if(!empty($_REQUEST['region_name'])&&!empty(Registry::get('addons.cp_salesbeat.region'))) {
                fn_salesbeat_update_profile_field($cart,end(explode('s_',Registry::get('addons.cp_salesbeat.region'))),$_REQUEST['region_name'],$auth);
            }

            if(!empty($_REQUEST['comment'])) {
                $cart['notes']=$_REQUEST['comment'];
            }

            $shipping_address_changed = false;

            
            if (!empty($shipping_id)) {
                fn_checkout_update_shipping($cart, array($shipping_id));

                $cart['calculate_shipping'] = true;
                fn_calculate_cart_content($cart, $auth, 'A', true, 'F', true);
                fn_delete_notification(__('text_shipping_rates_changed'));
            }

            // notify guest users about changed address
            if ($shipping_address_changed && empty($auth['user_id'])) {
                fn_set_notification('W', __('important'), __('shipping_address_changed'));
                // Billing and Shipping Address step will be shown
                // if address in checkout estimator is not complete
                unset($cart['edit_step']);
            }


            unset(Tygh::$app['session']['shipping_hash']);
            fn_save_cart_content($cart, $auth['user_id']);

    }
}
