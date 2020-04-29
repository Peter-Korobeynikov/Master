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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'delete_thread') {

        if (!empty($_REQUEST['thread_id'])) {
            $result = fn_vendor_communication_mark_threads_as_deleted(array('thread_id' => $_REQUEST['thread_id']));

            if ($result) {
                fn_set_notification('N', __('notice'), __('vendor_communication.thread_deleted'));
            } else {
                fn_set_notification('E', __('error'), __('vendor_communication.cannot_delete_thread'));
            }

            return array(CONTROLLER_STATUS_REDIRECT, 'vendor_communication.threads');
        } else {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }
    }

    if ($mode == 'm_delete_thread') {

        if (!empty($_REQUEST['thread_ids'])) {
            $result = fn_vendor_communication_mark_threads_as_deleted_by_ids($_REQUEST['thread_ids']);

            if ($result) {
                fn_set_notification('N', __('notice'), __('vendor_communication.threads_deleted'));
            } else {
                fn_set_notification('E', __('error'), __('vendor_communication.cannot_delete_threads'));
            }

            return array(CONTROLLER_STATUS_REDIRECT, 'vendor_communication.threads');
        } else {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }
    }

    return;
}
