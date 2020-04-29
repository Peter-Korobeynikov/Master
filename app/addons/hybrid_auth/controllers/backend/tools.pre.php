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

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * @var string $mode
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST'
    && $mode === 'update_status'
    && $_REQUEST['table'] === 'hybrid_auth_providers'
    && $_REQUEST['status'] === 'A'
) {
    $providers_schema = fn_get_schema('hybrid_auth', 'providers');
    $provider_id = (int) $_REQUEST['id'];

    /** @var string $provider */
    $provider = db_get_field('SELECT provider FROM ?:hybrid_auth_providers WHERE provider_id = ?i', $provider_id);

    if (!isset($providers_schema[$provider])) {
        fn_set_notification('E', __('error'), __('hybrid_auth.provider_not_found', array('[provider]' => $provider)));

        return array(CONTROLLER_STATUS_REDIRECT, 'hybrid_auth.manage');
    }
}