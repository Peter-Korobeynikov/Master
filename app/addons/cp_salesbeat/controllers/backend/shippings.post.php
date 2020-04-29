<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }



if($mode=='manage') {

    $salesbeat_list=fn_cp_get_salesbeat_deliveries_list();
    if(!empty($salesbeat_list)) {
        Tygh::$app['view']->assign('salesbeat_list',$salesbeat_list);
    }

}
