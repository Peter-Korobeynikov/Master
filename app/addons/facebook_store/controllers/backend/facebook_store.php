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
use Tygh\Settings;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return array(CONTROLLER_STATUS_OK);
}

if ($mode == 'complete') {

    if (!empty($_REQUEST['tabs_added'])
        && $tab_id = (int) key($_REQUEST['tabs_added'])
    ) {
        $company_id = Registry::get('runtime.company_id');

        $settings = Settings::instance($company_id);
        $setting_id = $settings->getId('page_id', 'facebook_store');

        if (empty($setting_id)) {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }

        $settings->updateValueById($setting_id, $tab_id);

        $current_value = Registry::get('addons.facebook_store.page_id');
        $app_id = Registry::get('addons.facebook_store.app_id');

        $redirect_url = sprintf('https://facebook.com/%s/app/%s', $tab_id, $app_id);
        fn_redirect($redirect_url, true);
    }

    return array(CONTROLLER_STATUS_DENIED);
}
