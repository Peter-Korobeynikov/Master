<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_settings_variants_addons_cp_salesbeat_region() {
    $_profile_fields=array(''=>__('none'));
    $profile_fields = fn_get_profile_fields('ALL', array(), DESCR_SL);
    if(!empty($profile_fields)) {
        foreach($profile_fields as $k=> $section) {
            if(!empty($section)&&$k=='S') {
                 foreach($section as $field) {
                    if(!empty($field['field_name'])) {
                        $_profile_fields[$field['field_name']]=$field['description'];
                    } else {
                        $_profile_fields[$field['field_id']]=$field['description'];
                    }
                 }
            }
        }
    }
	return $_profile_fields;
}

function fn_settings_variants_addons_cp_salesbeat_street() {
    $_profile_fields=array(''=>__('none'));
    $profile_fields = fn_get_profile_fields('ALL', array(), DESCR_SL);
    if(!empty($profile_fields)) {
        foreach($profile_fields as $k=> $section) {
            if(!empty($section)&&$k=='S') {
                 foreach($section as $field) {
                    if(!empty($field['field_name'])) {
                        $_profile_fields[$field['field_name']]=$field['description'];
                    } else {
                        $_profile_fields[$field['field_id']]=$field['description'];
                    }
                 }
            }
        }
    }
	return $_profile_fields;
}

function fn_settings_variants_addons_cp_salesbeat_house() {
    $_profile_fields=array(''=>__('none'));
    $profile_fields = fn_get_profile_fields('ALL', array(), DESCR_SL);
    if(!empty($profile_fields)) {
        foreach($profile_fields as $k=> $section) {
            if(!empty($section)&&$k=='S') {
                 foreach($section as $field) {
                    if(!empty($field['field_name'])) {
                        $_profile_fields[$field['field_name']]=$field['description'];
                    } else {
                        $_profile_fields[$field['field_id']]=$field['description'];
                    }
                 }
            }
        }
    }
	return $_profile_fields;
}

function fn_settings_variants_addons_cp_salesbeat_house_block() {
    $_profile_fields=array(''=>__('none'));
    $profile_fields = fn_get_profile_fields('ALL', array(), DESCR_SL);
    if(!empty($profile_fields)) {
        foreach($profile_fields as $k=> $section) {
            if(!empty($section)&&$k=='S') {
                 foreach($section as $field) {
                    if(!empty($field['field_name'])) {
                        $_profile_fields[$field['field_name']]=$field['description'];
                    } else {
                        $_profile_fields[$field['field_id']]=$field['description'];
                    }
                 }
            }
        }
    }
	return $_profile_fields;
}

function fn_settings_variants_addons_cp_salesbeat_flat() {
    $_profile_fields=array(''=>__('none'));
    $profile_fields = fn_get_profile_fields('ALL', array(), DESCR_SL);
    if(!empty($profile_fields)) {
        foreach($profile_fields as $k=> $section) {
            if(!empty($section)&&$k=='S') {
                 foreach($section as $field) {
                    if(!empty($field['field_name'])) {
                        $_profile_fields[$field['field_name']]=$field['description'];
                    } else {
                        $_profile_fields[$field['field_id']]=$field['description'];
                    }
                 }
            }
        }
    }
	return $_profile_fields;
}

function fn_salesbeat_request($url, $params, $action, $get_fields = false,$get_headers=false) {

    $api_key = trim(Registry::get('addons.cp_salesbeat.api_key'));
    
    if($action=='GET')
    $url=fn_cp_salesbeat_add_url_param($url,'token',$api_key);

    if (empty($api_key)) {
        fn_set_notification('E', __('error'), __('salesbeat_empty_settings'));
        return false;
    }
    
    if(!empty($params)) {
        $json = json_encode($params);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt($ch, CURLOPT_URL, $url);

    switch($action) {
        case "POST":
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            break;
        case "GET":
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            break;
        case "PUT":
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            break;
        case "DELETE":
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
        default:
            break;
    }

    curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, $get_headers);
    $output = curl_exec($ch);

    return $output; 
}

function fn_cp_salesbeat_add_url_param($url,$name,$value)
{
    $query = parse_url($url, PHP_URL_QUERY);
    if ($query) {
        $url .= '&'.$name.'='.$value;
    } else {
        $url .= '?'.$name.'='.$value;
    }
    return $url;
}

function fn_cp_salesbeat_add_url_params_string($url,$params)
{
    $query = parse_url($url, PHP_URL_QUERY);
    if ($query) {
        $url .= '&'.$params;
    } else {
        $url .= '?'.$params;
    }
    return $url;
}

function fn_cp_get_salesbeat_deliveries_list() {
    $delivery_methods=fn_salesbeat_request('https://app.salesbeat.pro/api/v1/get_all_delivery_methods',array(),'GET');
    $delivery_methods=json_decode($delivery_methods);
    if(!empty($delivery_methods->delivery_methods)) {
        return $delivery_methods->delivery_methods;
    } else {
        return false;
    }
}

function fn_cp_salesbeat_get_available_shippings($company_id, &$fields, $join, $condition) {
    $fields[]='salesbeat_id';
}

function fn_export_to_salesbeat_bulk($order_ids) {

    if(!is_array($order_ids)) {
        $order_ids=explode(',',$order_ids);
    }

    $items=array();

    foreach($order_ids as $order_id) {

        $order_data=fn_get_order_info($order_id);
        
        if($order_data['courier_called']=='Y') {
             fn_set_notification('E',__('error'),__('sb_courier_already_called').' '.__('order').' #'.$order_id);
             continue;
        }

        if(empty($order_data['city_id'])&&!empty($order_data['user_id'])) {
            $order_data['city_id']=db_get_field('SELECT s_city_id FROM ?:user_profiles WHERE user_id=?i AND profile_type=?s',$order_data['user_id'],'P');
        }

        if(empty($order_data['city_id'])) {
            fn_set_notification('E',__('error'),__('sb_recipient_city_error').' '.__('order').' #'.$order_id);
            continue;
        }

        if(!empty($order_data)) {

            $products=array();
            $item=array();

            foreach($order_data['products'] as $product) {
                $product_data=fn_get_product_data($product['product_id'],$_SESSION['auth']);
                $products[]=array(
                    "id"=>$product['product_id'],
                    "name"=>$product['product'],
                    "price_insurance"=>$product['price'],
                    "price_to_pay"=>$product['price'],
                    "weight"=>$product_data['weight']*Registry::get('settings.General.weight_symbol_grams')              
                );
            }
            if(!empty($order_data['fields'])) {
                foreach($order_data['fields'] as $field_id => $field) {
                    $field_name=db_get_field('SELECT field_name FROM ?:profile_fields WHERE field_id=?i',$field_id);
                    $order_data[$field_name]=$field;
                }
            }


            $item=array(
                "order"=> array(
                    "delivery_method_code"=>$order_data['shipping'][0]['salesbeat_id'],
                    "id"=>$order_data['order_id'],
                    "delivery_price"=>$order_data['shipping'][0]['rate'],
                    "delivery_from_shop"=>false
                ),
                "products"=>$products,
                "recipient"=> array (
                    "city_id"=>$order_data['city_id'],
                    "full_name"=>$order_data['s_firstname'].' '.$order_data['s_lastname'],
                    "phone"=>$order_data['s_phone'],
                    "email"=>$order_data['email'],
                    "courier"=> array (
                        "street"=>!empty(Registry::get('addons.cp_salesbeat.street')) ? $order_data[Registry::get('addons.cp_salesbeat.street')] : '',
                        "house"=>!empty(Registry::get('addons.cp_salesbeat.house')) ? $order_data[Registry::get('addons.cp_salesbeat.house')] : '',
                        "flat"=>!empty(Registry::get('addons.cp_salesbeat.flat')) ? $order_data[Registry::get('addons.cp_salesbeat.flat')] : '',
                        "house_block"=>!empty(Registry::get('addons.cp_salesbeat.house_block')) ? $order_data[Registry::get('addons.cp_salesbeat.house_block')] : '',
                        "date"=>date('Y-m-d'),
                    ),
                )
            );


            if(!empty($order_data['pvz_id'])) {
                $item['recipient']['pvz']['id']=$order_data['pvz_id'];
            }

            $items[]=$item;

        }

    }

    if(!empty($items)) {
        $data=array(
            "secret_token"=>Registry::get('addons.cp_salesbeat.secret_token'),
            "test_mode"=>!empty(Registry::get('addons.cp_salesbeat.test_mode'))&&Registry::get('addons.cp_salesbeat.test_mode')=='Y' ? true : false,
            "items"=>$items
        );

        $_result=fn_salesbeat_request('https://app.salesbeat.pro/delivery_order/create/bulk/',$data,'POST');
        $result=json_decode($_result);

    } else {
        return false;
    }

    if(!empty($result->success)) {

        if(!empty($result->items)) {
            foreach($result->items as $order) {

                $old_shipment=db_get_field('SELECT sb_id FROM ?:orders WHERE order_id=?i',$order->shop_order_id);

                db_query('UPDATE ?:orders SET sb_track_code=?s , sb_id=?s WHERE order_id=?i',$order->track_code,$order->salesbeat_order_id,$order->shop_order_id);

                if(!empty($old_shipment)) {
                    $shipment_id=db_get_field('SELECT shipment_id FROM ?:shipments WHERE tracking_number=?s',$old_shipment);

                    if(!empty($shipment_id)) {
                        fn_delete_shipments($shipment_id);
                    }
                }

                $shipment=array(
                    'tracking_number'=>$order->salesbeat_order_id,
                    'shipping_id'=>explode(',',$order_data['shipping_ids'])[0],
                    'order_id'=>$order->shop_order_id,
                );

                $shipment['status']='S';

                fn_update_shipment($shipment, 0, 0, true);
            }
        }

        return $result;

    } elseif(!empty($result->error_list)||!empty($result->error_message)) {

        if(!empty($result->error_message)){
            fn_set_notification('E',__('error'),$result->error_message.' '.__('order').' #'.$order_id);
        }

        if(!empty($result->error_list)) {
            foreach($result->error_list as $error) {
                fn_set_notification('E',__('error'),$error->message.' '.__('order').' #'.$order_id);
            }
        } 

        return false;
    }    

    return false;
}


function fn_export_to_salesbeat($order_id) {

    $order_data=fn_get_order_info($order_id);
    
    if($order_data['courier_called']=='Y') {
         fn_set_notification('E',__('error'),__('sb_courier_already_called').' '.__('order').' #'.$order_id);
         return false;
    }


    if(empty($order_data['city_id'])&&!empty($order_data['user_id'])) {
        $order_data['city_id']=db_get_field('SELECT s_city_id FROM ?:user_profiles WHERE user_id=?i AND profile_type=?s',$order_data['user_id'],'P');
    }

    if(empty($order_data['city_id'])) {
        fn_set_notification('E',__('error'),__('sb_recipient_city_error').' '.__('order').' #'.$order_id);
        return false;
    }

    if(!empty($order_data)) {

        $products=array();

        foreach($order_data['products'] as $product) {
            $product_data=fn_get_product_data($product['product_id'],$_SESSION['auth']);
            $products[]=array(
                "id"=>$product['product_id'],
                "name"=>$product['product'],
                "price_insurance"=>$product['price'],
                "price_to_pay"=>$product['price'],
                "weight"=>$product_data['weight']*Registry::get('settings.General.weight_symbol_grams')              
            );
        }
        if(!empty($order_data['fields'])) {
            foreach($order_data['fields'] as $field_id => $field) {
                $field_name=db_get_field('SELECT field_name FROM ?:profile_fields WHERE field_id=?i',$field_id);
                $order_data[$field_name]=$field;
            }
        }
        $data=array(
            "secret_token"=>Registry::get('addons.cp_salesbeat.secret_token'),
            "test_mode"=>!empty(Registry::get('addons.cp_salesbeat.test_mode'))&&Registry::get('addons.cp_salesbeat.test_mode')=='Y' ? true : false,
            "order"=> array(
                "delivery_method_code"=>$order_data['shipping'][0]['salesbeat_id'],
                "id"=>$order_data['order_id'],
                "delivery_price"=>$order_data['shipping'][0]['rate'],
                "delivery_from_shop"=>false
            ),
            "recipient"=> array (
                "city_id"=>$order_data['city_id'],
                "full_name"=>$order_data['s_firstname'].' '.$order_data['s_lastname'],
                "phone"=>$order_data['s_phone'],
                "email"=>$order_data['email'],
                "courier"=> array (
                    "street"=>!empty(Registry::get('addons.cp_salesbeat.street')) ? $order_data[Registry::get('addons.cp_salesbeat.street')] : '',
                    "house"=>!empty(Registry::get('addons.cp_salesbeat.house')) ? $order_data[Registry::get('addons.cp_salesbeat.house')] : '',
                    "flat"=>!empty(Registry::get('addons.cp_salesbeat.flat')) ? $order_data[Registry::get('addons.cp_salesbeat.flat')] : '',
                    "house_block"=>!empty(Registry::get('addons.cp_salesbeat.house_block')) ? $order_data[Registry::get('addons.cp_salesbeat.house_block')] : '',
                    "date"=>date('Y-m-d'),
                ),
            )
        );

        if(!empty($order_data['pvz_id'])) {
            $data['recipient']['pvz']['id']=$order_data['pvz_id'];
        }

        $data['products']=$products;

        $_result=fn_salesbeat_request('https://app.salesbeat.pro/delivery_order/create/',$data,'POST');
        $result=json_decode($_result);

        if(!empty($result->success)) {

            $old_shipment=db_get_field('SELECT sb_id FROM ?:orders WHERE order_id=?i',$order_data['order_id']);

            db_query('UPDATE ?:orders SET sb_track_code=?s , sb_id=?s WHERE order_id=?i',$result->track_code,$result->salesbeat_order_id,$order_data['order_id']);

            if(!empty($old_shipment)) {
                $shipment_id=db_get_field('SELECT shipment_id FROM ?:shipments WHERE tracking_number=?s',$result->salesbeat_order_id);

                if(!empty($shipment_id)) {
                    fn_delete_shipments($shipment_id);
                }
            }

            $shipment=array(
                'tracking_number'=>$result->salesbeat_order_id,
                'shipping_id'=>explode(',',$order_data['shipping_ids'])[0],
                'order_id'=>$order_data['order_id'],
            );

            if($result->status=='sent') {
                $shipment['status']='S';
            }

            fn_update_shipment($shipment, 0, 0, true);

            return true;
        } elseif(!empty($result->error_list)||!empty($result->error_message)) {

            if(!empty($result->error_message)){
                fn_set_notification('E',__('error'),$result->error_message.' '.__('order').' #'.$order_id);
            }

            if(!empty($result->error_list)) {
                foreach($result->error_list as $error) {
                    fn_set_notification('E',__('error'),$error->message.' '.__('order').' #'.$order_id);
                }
            } 

            return false;
        }    
    }

    return false;
}


function fn_salesbeat_call_courier($order_id,$date='',$time='') {

    $order_data=fn_get_order_info($order_id);

    if($order_data['courier_called']=='Y') {
         fn_set_notification('E',__('error'),__('sb_courier_already_called').' '.__('order').' #'.$order_id);
         return false;
    }

    if(!empty($order_data['sb_id'])&&fn_cp_check_sb_shipment($order_data['sb_id'])) {

         $data=array(
            "secret_token"=>Registry::get('addons.cp_salesbeat.secret_token'),
            "test_mode"=>!empty(Registry::get('addons.cp_salesbeat.test_mode'))&&Registry::get('addons.cp_salesbeat.test_mode')=='Y' ? true : false,
            "orders"=> array(
                array(
                "salesbeat_order_id"=>$order_data['sb_id']
                )
            )
        );

        $_result=fn_salesbeat_request('https://app.salesbeat.pro/delivery_order/pick_up/',$data,'POST');
        $result=json_decode($_result);

        if(!empty($result->success)) {
            db_query('UPDATE ?:orders SET courier_called=?s WHERE order_id=?i','Y',$order_id);
            return true;
        } elseif(!empty($result->error_list)||!empty($result->error_message)) {

            if(!empty($result->error_message)){
                fn_set_notification('E',__('error'),$result->error_message.' '.__('order').' #'.$order_id);
            }

            if(!empty($result->error_list)) {
                foreach($result->error_list as $error) {
                    fn_set_notification('E',__('error'),$error->message.' '.__('order').' #'.$order_id);
                }
            } 

            return false;
        }       
        
    } else {
        fn_set_notification('E',__('error'),__('order').' #'.$order_id.' '.__('sb_export_first'));
        return false;
    }
}

function fn_cp_salesbeat_shippings_get_shippings_list_conditions($group, $shippings, &$fields, $join, $condition, $order_by) {
    $fields[]="?:shippings.salesbeat_id";
    $fields[]="?:shippings.show_pvz";
}

function fn_cp_salesbeat_shippings_calculate_rates_post($shippings, &$rates) {
    foreach($shippings as $shipping) {
        if(!empty($shipping['salesbeat_id'])) {
            $sb_rate=fn_cp_salesbeat_calculate_shipping_rate($shipping);
            if(!empty($sb_rate)) {
                foreach($rates as $k=>&$rate) {
                    if($rate['keys']['shipping_id']==$shipping['shipping_id']) {
                        $rate['price']=$sb_rate;
                    }
                }
            }
        }
    }
}

function fn_cp_salesbeat_calculate_shipping_rate($shipping) {

    if(!empty($shipping['package_info']['location']['city_id'])&&!empty($shipping['salesbeat_id'])) {
        $data=array(
            'city_id'=>$shipping['package_info']['location']['city_id'],
            'postalcode'=>!empty($shipping['package_info']['location']['zipcode']) ? $shipping['package_info']['location']['zipcode'] : '',
            'ip'=>!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
            'delivery_method_id'=>!empty($shipping['salesbeat_id']) ? $shipping['salesbeat_id'] : '',
            'pvz_id'=> !empty($shipping['package_info']['location']['pvz_id']&&!empty($shipping['package_info']['location']['sb_id'])&&$shipping['package_info']['location']['sb_id']==$shipping['salesbeat_id']) ? $shipping['package_info']['location']['pvz_id'] : '',
            'weight'=>!empty($shipping['package_info']['W']) ? $shipping['package_info']['W']*Registry::get('settings.General.weight_symbol_grams') : '',
            'price_to_pay'=>!empty($shipping['package_info']['C']) ? $shipping['package_info']['C'] : '',
            'price_insurance'=>!empty($shipping['package_info']['C']) ?$shipping['package_info']['C'] : '',
        );

        $params=http_build_query( $data);
        $result=fn_salesbeat_request('https://app.salesbeat.pro/api/v1/get_delivery_price?'.$params,array(),'GET');
        $result=json_decode($result);

        if(!empty($result->success)) {
            return $result->delivery_price;
        } elseif(!empty($result->error_list)||!empty($result->error_message)||!empty($result->error)) {
            /*
            if(!empty($result->error_message)){
                fn_set_notification('E',__('error'),__('salesbeat').' '.$result->error_message);
            }

            if(!empty($result->error)){
                fn_set_notification('E',__('error'),__('salesbeat').' '.$result->error);
            }

            if(!empty($result->error_list)) {
                foreach($result->error_list as $error) {
                    fn_set_notification('E',__('error'),__('salesbeat').' '.$error->message);
                }
            } 
            */
            return false;
        }   
    }  
}

function fn_salesbeat_update_profile_field(&$cart,$field,$value,$auth) {
    if($cart['ship_to_another']==false) {
        $cart['user_data']['s_'.$field]=$cart['user_data']['b_'.$field]=$value;
        $b_field_id=db_get_field('SELECT field_id FROM ?:profile_fields WHERE field_name=?s','b_'.$field);
        $s_field_id=db_get_field('SELECT field_id FROM ?:profile_fields WHERE field_name=?s','s_'.$field);
        $cart['user_data']['fields'][$b_field_id]=$cart['user_data']['fields'][$s_field_id]=$value;
        if(!empty($auth['user_id'])) {
            if(!empty($b_field_id)) {
                $data=array(
                    'object_id'=>$auth['user_id'],
                    'object_type'=>'U',
                    'field_id'=>$b_field_id,
                    'value'=>$value
                );
                db_query('REPLACE INTO ?:profile_fields_data ?e',$data);
                $profiles_ids=db_get_fields('SELECT profile_id FROM ?:user_profiles WHERE user_id=?i',$auth['user_id']);
                if(!empty($profiles_ids)) {
                    foreach($profiles_ids as $profile_id) {
                        $data['object_type']='P';
                        $data['object_id']=$profile_id;
                        db_query('REPLACE INTO ?:profile_fields_data ?e',$data);
                    }
                }
            }
            if(!empty($s_field_id)) {
                $data=array(
                    'object_id'=>$auth['user_id'],
                    'object_type'=>'U',
                    'field_id'=>$s_field_id,
                    'value'=>$value
                );
                db_query('REPLACE INTO ?:profile_fields_data ?e',$data);
                $profiles_ids=db_get_fields('SELECT profile_id FROM ?:user_profiles WHERE user_id=?i',$auth['user_id']);
                if(!empty($profiles_ids)) {
                    foreach($profiles_ids as $profile_id) {
                        $data['object_type']='P';
                        $data['object_id']=$profile_id;
                        db_query('REPLACE INTO ?:profile_fields_data ?e',$data);
                    }
                }
            }
        }
    } else {
        $cart['user_data']['user_data'][Registry::get('addons.cp_salesbeat.street')]=$field;
        $cart['user_data']['fields'][$field_id]=$value;
        if(!empty($auth['user_id'])) {
            $field_id=db_get_field('SELECT field_id FROM ?:profile_fields WHERE field_name=?s','s_'.$field);
            if(!empty($field_id)) {
                $data=array(
                    'object_id'=>$auth['user_id'],
                    'object_type'=>'P',
                    'field_id'=>$field_id,
                    'value'=>$value
                );
                db_query('REPLACE INTO ?:profile_fields_data ?e',$data);
                $profiles_ids=db_get_fields('SELECT profile_id FROM ?:user_profiles WHERE user_id=?i',$auth['user_id']);
                if(!empty($profiles_ids)) {
                    foreach($profiles_ids as $profile_id) {
                        $data['object_type']='P';
                        $data['object_id']=$profile_id;
                        db_query('REPLACE INTO ?:profile_fields_data ?e',$data);
                    }
                }
            }
        }
    }
}

function fn_salesbeat_get_address_from_profile($user_data) {
   if(!empty($user_data['fields'])) {
        $profile_fields=array();
        foreach($user_data['fields'] as $id => $val) {
            $field_name=db_get_field('SELECT field_name FROM ?:profile_fields WHERE field_id=?i',$id);
            $profile_fields[$field_name]=$val;
        }
        return $profile_fields;
   }
   return false;
}

function fn_salesbeat_get_product_weight($product_id) {
    return db_get_field('SELECT weight FROM ?:products WHERE product_id=?i',$product_id)*Registry::get('settings.General.weight_symbol_grams');
}

function fn_salesbeat_get_shipping_params($product_id) {
    $params=db_get_field('SELECT shipping_params FROM ?:products WHERE product_id=?i',$product_id);
    if(!empty($params)) {
        return unserialize($params);
    } else {
        return false;
    }
}

function fn_cp_salesbeat_pre_place_order(&$cart, $allow, $product_groups) {
    if(empty($cart['city_id'])&&!empty($cart['user_data']['s_city_id'])) {
        $cart['city_id']=$cart['user_data']['s_city_id'];
    }
    if(empty($cart['pvz_id'])&&!empty($cart['user_data']['s_pvz_id'])) {
        $cart['pvz_id']=$cart['user_data']['s_pvz_id'];
    }

    $cart['s_pvz_id']=$cart['pvz_id'];
    $cart['s_city_id']=$cart['city_id'];
    if(AREA=='C') {
        if(!empty($cart['chosen_shipping'])) {
            $shipping_info=fn_get_shipping_info($cart['chosen_shipping']['0']);
            if(!empty($shipping_info['salesbeat_id'])) {
                $cart['s_sb_id']=$shipping_info['salesbeat_id'];
            }
        }
    }
}

function fn_cp_salesbeat_pre_get_cart_product_data($hash, $product, $skip_promotion, $cart, $auth, $promotion_amount, &$fields, $join) {
    $fields[]='?:products.price_insurance';
}


function fn_cp_check_sb_shipment($track_code='') {
    if(!empty($track_code)) {
        return db_get_field('SELECT shipment_id FROM ?:shipments WHERE tracking_number=?s',$track_code);
    } else {
        return false;
    }
}

function fn_cp_sb_allow_shipment_to_delete($shipment_id) {
    list($shipment, $search) = fn_get_shipments_info(array("shipment_id"=>$shipment_id));
    if (!empty($shipment)) {
        $shipment = array_pop($shipment);
        $courier_called=db_get_field('SELECT order_id FROM ?:orders WHERE order_id=?i AND courier_called=?s AND sb_id=?s',$shipment['order_id'],'Y',$shipment['tracking_number']);
        if(!empty($courier_called)) {
            return false;
        }
    }
    return true;
}

function fn_cp_salesbeat_calculate_cart_items(&$cart, $cart_products, $auth, $apply_cart_promotions) {

    unset($cart['stored_shipping']);

    if(!empty($cart['user_data']['city_id'])) {
        $cart['user_data']['s_city_id']=$cart['user_data']['city_id'];
    }
    if(!empty($cart['user_data']['pvz_id'])) {
        $cart['user_data']['s_pvz_id']=$cart['user_data']['pvz_id'];
    }
}
