<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if($_SERVER['REQUEST_METHOD']=='POST') {
    if($mode=='m_export_to_salesbeat') {
        if(!empty($_REQUEST['order_ids'])) {
            $result=fn_export_to_salesbeat_bulk($_REQUEST['order_ids']);

            if(!empty($result)) {
                Tygh::$app['view']->assign('info',$result);
                Tygh::$app['view']->display('addons/cp_salesbeat/components/info_message.tpl');
                exit;
             } else {
                exit;
            }
        }

    }elseif($mode=='m_call_courier') {
        if(!empty($_REQUEST['order_ids'])) {
            foreach($_REQUEST['order_ids'] as $k =>$v) {

                $res=fn_salesbeat_call_courier($v,$_REQUEST['date'],$_REQUEST['time']);
                if(!empty($res)) {
                    fn_set_notification('N', __('notice'), __('courier_called').' #'.$v);
                }       
            }  
        }
        return array(CONTROLLER_STATUS_REDIRECT,fn_url('orders.manage'));
    }
}

if($mode=='export_to_salesbeat') {

    if(!empty($_REQUEST['order_id'])) {
        $res=fn_export_to_salesbeat($_REQUEST['order_id']);
        if(!empty($res)) {
            fn_set_notification('N', __('notice'), __('order').' #'.$_REQUEST['order_id'].' '.__('exported_to_salesbeat'));
        }

        return array(CONTROLLER_STATUS_REDIRECT,fn_url('orders.details?order_id='.$_REQUEST['order_id']));
    }

} elseif($mode=='call_courier') {
    if(!empty($_REQUEST['order_id'])) {
        $res=fn_salesbeat_call_courier($_REQUEST['order_id'],$_REQUEST['date'],$_REQUEST['time']);
        if(!empty($res)) {
            fn_set_notification('N', __('notice'), __('courier_called').' #'.$_REQUEST['order_id']);
        }
        
        return array(CONTROLLER_STATUS_REDIRECT,fn_url('orders.details?order_id='.$_REQUEST['order_id']));
    }
} elseif($mode=='get_cities') {
    if(!empty($_REQUEST['q'])) {

        $result=fn_salesbeat_request('https://app.salesbeat.pro/api/v1/get_cities?city='.rtrim($_REQUEST['q']),array(),'GET');

        if(!empty(json_decode($result))) {
            echo $result;
        } else {
            echo "{}";
        }
    }
    exit;
}elseif($mode=='change_pvz') {
    $cart = & Tygh::$app['session']['cart'];
    if(!empty($_REQUEST['delivery_method_id'])) {
        if(!empty($_REQUEST['pvz_id'])) {
            if(!empty($auth['user_id'])) {
                db_query('UPDATE ?:user_profiles SET s_pvz_id=?s WHERE user_id=?i',$_REQUEST['pvz_id'],$auth['user_id']);
            }
            $cart['user_data']['s_pvz_id']=$_REQUEST['pvz_id'];
            $cart['pvz_id']=$_REQUEST['pvz_id'];

            $cart['user_data']['s_sb_id']=$_REQUEST['delivery_method_id'];
            $cart['s_sb_id']=$_REQUEST['delivery_method_id'];

        } else {
            if(!empty($auth['user_id'])) {
                db_query('UPDATE ?:user_profiles SET s_pvz_id=?s WHERE user_id=?i','',$auth['user_id']);
            }
            $cart['user_data']['s_pvz_id']='';
            $cart['pvz_id']="";

            $cart['user_data']['s_sb_id']=$_REQUEST['delivery_method_id'];
            $cart['s_sb_id']=$_REQUEST['delivery_method_id'];
        }
        fn_set_notification('N', __('notice'), __('pvz_changed'));
    }    
}


