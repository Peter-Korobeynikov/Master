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

use Tygh\BlockManager\Location;
use Tygh\BlockManager\SchemesManager;
use Tygh\Development;
use Tygh\Enum\StorefrontStatuses;
use Tygh\Enum\YesNo;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * Act on behalf functionality
 */
if (!empty($_REQUEST['skey'])) {
    $session_data = fn_get_storage_data('session_' . $_REQUEST['skey'] . '_data');
    fn_set_storage_data('session_' . $_REQUEST['skey'] . '_data', '');

    if (!empty($session_data)) {
        Tygh::$app['session']->start();

        Tygh::$app['session']->fromArray(unserialize($session_data));

        Tygh::$app['session']->save(
            Tygh::$app['session']->getID(),
            Tygh::$app['session']->toArray()
        );

        if (!fn_cart_is_empty(Tygh::$app['session']['cart'])) {
            fn_calculate_cart_content(Tygh::$app['session']['cart'], Tygh::$app['session']['auth'], 'S', true, 'F', true);
            fn_save_cart_content(Tygh::$app['session']['cart'], Tygh::$app['session']['auth']['user_id']);
        }
    }

    return array(CONTROLLER_STATUS_REDIRECT, fn_query_remove(REAL_URL, 'skey'));
}

if (Registry::get('config.demo_mode') && (
        !empty($_REQUEST['demo_customize_theme']) && $_REQUEST['demo_customize_theme'] == 'Y' ||
        !empty(Tygh::$app['session']['customize_theme'])
    )
    || ($own_id = fn_get_styles_owner()) && !empty(Tygh::$app['session']['customize_theme'])
) {
    Tygh::$app['session']['customize_theme'] = true;
    Registry::set('runtime.customization_mode.theme_editor', true);

    if (!empty($_REQUEST['demo_customize_theme'])) {
        $current_url = Registry::get('config.current_url');
        $current_url = fn_query_remove($current_url, 'demo_customize_theme');

        return array(CONTROLLER_STATUS_REDIRECT, $current_url);
    }
}

if (Registry::get('config.demo_mode') && (
        !empty($_REQUEST['demo_block_manager']) && $_REQUEST['demo_block_manager'] == 'Y' ||
        !empty(Tygh::$app['session']['customize_blocks'])
    )
) {
    Tygh::$app['session']['customize_blocks'] = true;
    Registry::set('runtime.customization_mode.block_manager', true);

    if (!empty($_REQUEST['demo_block_manager'])) {
        $current_url = Registry::get('config.current_url');
        $current_url = fn_query_remove($current_url, 'demo_block_manager');

        return array(CONTROLLER_STATUS_REDIRECT, $current_url);
    }
}

if (Registry::get('runtime.customization_mode.live_editor')) {
    Tygh::$app['view']->assign('live_editor_objects', fn_get_schema('customization', 'live_editor_objects'));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return;
}

//
// Check if store is closed
//
/** @var \Tygh\Storefront\Storefront $storefront */

$storefront = Tygh::$app['storefront'];
if ($storefront->status === StorefrontStatuses::CLOSED) {
    if (!empty($_REQUEST['store_access_key'])) {
        Tygh::$app['session']['store_access_key'] = $_GET['store_access_key'];
    }

    if (!fn_check_permissions(Registry::get('runtime.controller'), Registry::get('runtime.mode'), 'trusted_controllers')) {
        if (empty(Tygh::$app['session']['store_access_key']) || Tygh::$app['session']['store_access_key'] !== $storefront->access_key) {

            if (defined('AJAX_REQUEST')) {
                fn_set_notification('E', __('notice'), __('text_store_closed'));
                exit;
            }

            Development::showStub();
        }
    }
} elseif (empty($auth['user_id'])
    && $storefront->is_accessible_for_authorized_customers_only
    && !fn_check_permissions(Registry::get('runtime.controller'), Registry::get('runtime.mode'), 'trusted_controllers')
    && !fn_check_permissions(Registry::get('runtime.controller'), Registry::get('runtime.mode'), 'trusted_customer_controllers')
) {
    fn_set_notification('E', __('access_denied'), __('error_not_logged'));

    return [CONTROLLER_STATUS_REDIRECT, 'auth.login_form?return_url=' . urlencode(Registry::get('config.current_url'))];
}


if (empty($_REQUEST['product_id']) && empty($_REQUEST['category_id'])) {
    unset(Tygh::$app['session']['current_category_id']);
}

$dispatch = $_REQUEST['dispatch'];
$dynamic_object = array();
if (!empty($_REQUEST['dynamic_object'])) {
    $dynamic_object = $_REQUEST['dynamic_object'];
}

$dynamic_object_scheme = SchemesManager::getDynamicObject($dispatch, AREA, $_REQUEST);
if (!empty($dynamic_object_scheme) && !empty($_REQUEST[$dynamic_object_scheme['key']])) {
    $dynamic_object['object_type'] = $dynamic_object_scheme['object_type'];
    $dynamic_object['object_id'] = $_REQUEST[$dynamic_object_scheme['key']];
    $dispatch = $dynamic_object_scheme['customer_dispatch'];
}

Tygh::$app['view']->assign('location_data', Location::instance()->get($dispatch, $dynamic_object, CART_LANGUAGE));
Tygh::$app['view']->assign('layout_data', Registry::get('runtime.layout'));
Tygh::$app['view']->assign('current_mode', fn_get_current_mode($_REQUEST));

// Init cart if not set
if (empty(Tygh::$app['session']['cart'])) {
    fn_clear_cart(Tygh::$app['session']['cart']);
}

if (!empty(Tygh::$app['session']['continue_url'])) {
    Tygh::$app['session']['continue_url'] = fn_url_remove_service_params(Tygh::$app['session']['continue_url']);
}
