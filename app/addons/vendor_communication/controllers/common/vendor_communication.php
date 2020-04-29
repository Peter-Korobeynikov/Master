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

if (empty($auth['user_id'])) {
    return array(CONTROLLER_STATUS_REDIRECT, 'auth.login_form?return_url=' . urlencode(Registry::get('config.current_url')));
} else {
    /** @var Tygh\SmartyEngine\Core $view */
    $view = Tygh::$app['view'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'post_message') {
        $thread_id = !empty($_REQUEST['message']['thread_id']) ? (int) $_REQUEST['message']['thread_id'] : null;

        if ($thread_id
            && fn_vendor_communication_can_user_access_thread($thread_id, $auth)
        ) {
            fn_trusted_vars('message');

            if (!empty($_REQUEST['message']['message'])) {
                $message = array(
                    'user_id'   => $auth['user_id'],
                    'user_type' => $auth['user_type'],
                    'message'   => $_REQUEST['message']['message'],
                    'thread_id' => $thread_id,
                );

                $result = fn_vendor_communication_add_thread_message($message, true);

                if (!$result->isSuccess()) {
                    fn_set_notification('E', __('error'), __('vendor_communication.cannot_post_message'));
                }
            }

            return array(CONTROLLER_STATUS_REDIRECT, "vendor_communication.view&thread_id=$thread_id");
        }

        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    if ($mode == 'create_thread') {
        $object_id = !empty($_REQUEST['thread']['object_id']) ? (int) $_REQUEST['thread']['object_id'] : 0;

        if (!empty($_REQUEST['thread']['company_id'])
            && !empty($_REQUEST['thread']['message'])
            && fn_vendor_communication_is_company_exists($_REQUEST['thread']['company_id'])
        ) {
            fn_trusted_vars('thread');

            $thread_data = array(
                'user_id'                => $auth['user_id'],
                'last_message_user_id'   => $auth['user_id'],
                'last_message_user_type' => $auth['user_type'],
                'company_id'             => (int) $_REQUEST['thread']['company_id'],
                'last_message'           => $_REQUEST['thread']['message'],
                'object_id'              => $object_id,
                'object_type'            => !empty($_REQUEST['thread']['object_type']) ? $_REQUEST['thread']['object_type'] : '',
            );

            $result = fn_vendor_communication_update_thread($thread_data);

            if ($result->isSuccess()) {
                $thread_id = $result->getData();

                if ($thread_id) {
                    $message = array(
                        'user_id'   => $auth['user_id'],
                        'user_type' => $auth['user_type'],
                        'message'   => $_REQUEST['thread']['message'],
                        'thread_id' => $thread_id,
                    );

                    fn_vendor_communication_add_thread_message($message, true);

                    $success_message = __(
                        'vendor_communication.message_sent',
                        array(
                            '[vendor_name]' => fn_get_company_name($thread_data['company_id']),
                            '[thread_url]' => fn_url("vendor_communication.view&thread_id={$thread_id}"),
                        )
                    );

                    fn_set_notification('N', __('notice'), $success_message, 'K');
                }
            } else {
                fn_set_notification('E', __('error'), __('vendor_communication.cannot_create_thread'));
            }
        }

        $redirect_url = !empty($_REQUEST['redirect_url']) ? $_REQUEST['redirect_url'] : 'vendor_communication.threads';
        return array(CONTROLLER_STATUS_REDIRECT, $redirect_url);
    }

    return;
}

if ($mode == 'threads') {
    fn_add_breadcrumb(__('vendor_communication.messages'), 'vendor_communication.threads');
    $threads = $search = array();
    $params = $_REQUEST;

    if (AREA == 'C') {
        $params['user_id'] = $auth['user_id'];
    } elseif (AREA == 'A') {
        $params['get_object'] = true;
        $company_id = Registry::get('runtime.company_id');

        if (!empty($company_id)) {
            $params['company_id'] = $company_id;
        }
    }

    list($threads, $search) = fn_vendor_communication_get_threads(
        $params,
        Registry::get('settings.Appearance.admin_elements_per_page')
    );

    $threads = fn_vendor_communication_get_threads_user_status($threads, $auth);

    $view->assign(array(
        'threads' => $threads,
        'search' => $search,
        'company_id' => Registry::get('runtime.company_id'),
    ));

    if (AREA == 'C' && !empty($_REQUEST['active_thread'])) {
        $view->assign('active_thread', $_REQUEST['active_thread']);
    }


} elseif ($mode == 'view') {

    if (!empty($_REQUEST['thread_id'])
        && fn_vendor_communication_can_user_access_thread($_REQUEST['thread_id'], $auth)
    ) {

        if (AREA == 'C') {

            if (!defined('AJAX_REQUEST')) {
                return array(
                    CONTROLLER_STATUS_REDIRECT,
                    "vendor_communication.threads&active_thread={$_REQUEST['thread_id']}"
                );
            } else {
                $params['user_id'] = $auth['user_id'];
            }
        } elseif (AREA == 'A') {
            $params['get_object'] = true;
            $company_id = Registry::get('runtime.company_id');

            if (!empty($company_id)) {
                $params['company_id'] = $company_id;
            }
        }

        $params['thread_id'] = (int) $_REQUEST['thread_id'];
        $thread = fn_vendor_communication_get_thread($params);

        if (!empty($thread)) {

            $thread_user_status = fn_vendor_communication_get_thread_user_status($thread, $auth);

            if ($thread_user_status == VC_THREAD_STATUS_HAS_NEW_MESSAGE) {
                fn_vendor_communication_mark_thread_as_viewed($thread);
            }

            $messages = fn_vendor_communication_get_thread_messages($params);

            $view->assign(array(
                'messages' => $messages,
                'thread_id' => $_REQUEST['thread_id'],
                'thread' => $thread,
            ));
        } else {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }
    } else {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }
}
