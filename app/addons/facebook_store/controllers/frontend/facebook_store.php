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

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'add_tab') {

        if (empty($_REQUEST['signed_request'])) {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }

        $app_id = Registry::get('addons.facebook_store.app_id');
        $redirect_url = fn_url('facebook_store.complete', 'A');
        $add_tab_url = sprintf('https://www.facebook.com/dialog/pagetab?app_id=%s&redirect_uri=%s', $app_id, urlencode($redirect_url));

        Tygh::$app['view']->assign('add_tab_url', $add_tab_url);
        Tygh::$app['view']->display('addons/facebook_store/view/facebook_store/add_tab.tpl');
        exit;
    }

    if ($mode == 'show_store') {
        $app_id = Registry::get('addons.facebook_store.app_id');
        $page_id = Registry::get('addons.facebook_store.page_id');
        $widget_layout_id = Registry::get('addons.facebook_store.layout_id');
        $facebook_script_url = Registry::get('config.current_location') . '/' . fn_link_attach('js/addons/facebook_store/facebook.js', 'ver=' . PRODUCT_VERSION);

        $http_url = fn_get_storefront_url('http');
        $https_url = fn_get_storefront_url('https');

        Tygh::$app['view']->assign(array(
            'app_id'              => $app_id,
            'page_id'             => $page_id,
            'widget_layout_id'    => $widget_layout_id,
            'facebook_script_url' => $facebook_script_url,
            'widget_http_url'     => urlencode($http_url),
            'widget_https_url'    => urlencode($https_url),
        ));

        Tygh::$app['view']->display('addons/facebook_store/view/facebook_store/show_store.tpl');
        exit;
    }

    return array(CONTROLLER_STATUS_OK);
}
