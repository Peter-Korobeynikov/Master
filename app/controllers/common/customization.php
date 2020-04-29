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

use Tygh\Enum\UserTypes;
use Tygh\Registry;

defined('BOOTSTRAP') or die('Access denied');

$request_method = $_SERVER['REQUEST_METHOD'];
$has_permissions = Registry::get('config.demo_mode')
    ? fn_check_permissions('customization', $mode, 'demo', $request_method, $_REQUEST, AREA, $auth['user_id'])
    : fn_check_permissions('customization', $mode, 'admin', $request_method, $_REQUEST, AREA, $auth['user_id']);

if (AREA === 'C' &&
    $mode === 'update_mode' && ($auth['user_type'] !== UserTypes::ADMIN || !$has_permissions)
) {
    return [CONTROLLER_STATUS_DENIED];
}

if ($mode === 'update_mode') {
    if (!empty($_REQUEST['status']) && !empty($_REQUEST['type'])) {
        $return_url = !empty($_REQUEST['return_url'])
            ? $_REQUEST['return_url']
            : '';

        if (fn_allowed_for('ULTIMATE') && !fn_get_runtime_company_id()) {
            fn_set_notification('W', __('warning'), __('text_select_vendor'));

            return [CONTROLLER_STATUS_REDIRECT, $return_url];
        }

        $c_mode = $_REQUEST['type'];
        $status = $_REQUEST['status'];
        $avail_modes = array_keys(fn_get_customization_modes());

        if (!in_array($c_mode, $avail_modes)) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $changed_modes = [];

        if ($status == 'enable') {
            // disable all other modes
            $changed_modes = array_fill_keys($avail_modes, 'disable');
        }

        $changed_modes[$c_mode] = $status;

        fn_update_customization_mode($changed_modes);

        if ($status === 'enable' && AREA !== 'C') {
            // redirect to frontend after enabling mode
            /** @var \Tygh\Storefront\Repository $repository */
            $repository = Tygh::$app['storefront.repository'];
            /** @var \Tygh\Storefront\Storefront $storefront */
            $storefront = Tygh::$app['storefront'];
            $storefront_id = $storefront->storefront_id;
            $extra_url = '&s_storefront=' . $storefront_id;

            if (!empty($_REQUEST['s_layout'])) {
                $redirect_url = 'index.index';
                if ($vendor_id = fn_get_styles_owner()) {
                    $redirect_url = fn_link_attach('companies.products', 'company_id=' . $vendor_id);
                }
                $redirect_url = fn_link_attach($redirect_url, 's_layout=' . $_REQUEST['s_layout']);
                $extra_url .= '&redirect_url=' . urlencode($redirect_url);
            } elseif (!empty($_REQUEST['frontend_url'])) {
                $extra_url .= '&redirect_url=' . urlencode($_REQUEST['frontend_url']);
            }

            if (fn_get_styles_owner() && $c_mode == 'theme_editor') {
                $extra_url .= '&customize_theme=Y';
            }

            $return_url = 'profiles.act_as_user?user_id=' . $auth['user_id'] . '&area=C' . $extra_url;
        }

        return [CONTROLLER_STATUS_REDIRECT, $return_url];
    }
}
