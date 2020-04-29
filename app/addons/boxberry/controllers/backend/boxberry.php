<?php
/**
 * Created by PhpStorm.
 * User: aleksandr.tsioma
 * Date: 13.04.2017
 * Time: 17:04
 */
if ($mode == 'save_token') {
    if (isset($_REQUEST['token'])&& !empty($_REQUEST['token'])){
        $shipping_data['service_params']['password']=$_REQUEST['token'];
        $shipping_id =  $_REQUEST['shipping_id'];
        fn_update_shipping($shipping_data, $shipping_id, DEFAULT_LANGUAGE);
    }
}