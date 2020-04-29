<?php

use Tygh\Registry;
use Tygh\BlockManager\ProductTabs;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'view') {

    $product = Registry::get('view')->getTemplateVars('product');
    if(!empty($product['sb_show_in_tab'])&&$product['sb_show_in_tab']=='Y') {

        $tabs = Registry::get('view')->getTemplateVars('tabs');
        $salesbeat_tab = array(
            'tab_id'=>count($tabs)+1,
            'tab_type'=>'T',
            'template' => 'addons/cp_salesbeat/blocks/product_tabs/salesbeat.tpl',
            'addon' => 'cp_salesbeat',
            'position' => count($tabs)+1,
            'show_in_popup' => 'N',
            'name' => __('salesbeat_info'),
            'status'=>'A',
            'type' => '',
            'properties' => '',
            'html_id' => 'salesbeat_info',
        );

        Registry::set('navigation.tabs.salesbeat', array (
            'title' => __('salesbeat_info'),
            'js' => true
        ));

        $tabs[] = $salesbeat_tab;

        Registry::get('view')->assign('tabs', $tabs);
    }
}
