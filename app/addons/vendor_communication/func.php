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
use Tygh\Navigation\LastView;
use Tygh\Tools\SecurityHelper;
use Tygh\Common\OperationResult;
use Tygh\Enum\UserTypes;
use Tygh\Enum\YesNo;
use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * Creates/updates thread based on passed data
 *
 * @param array $thread_data Thread data
 *
 * @return Tygh\Common\OperationResult
 */
function fn_vendor_communication_update_thread(array $thread_data)
{
    /** @var Tygh\Common\OperationResult $result */
    $result = new OperationResult();
    $result->setSuccess(true);
    $thread_id = !empty($thread_data['thread_id']) ? (int) $thread_data['thread_id'] : null;
    $required_fields = array();

    if (!$thread_id) {
        $required_fields[] = 'company_id';
        $required_fields[] = 'user_id';
    }

    foreach ($required_fields as $field) {

        if (empty($thread_data[$field])) {
            $result->setSuccess(false);
            $result->addError(
                'thread_id_field_missing',
                __('vendor_communication.required_field_is_missing', array('[field_name]' => $field))
            );
        }
    }

    if (!empty($thread_data['object_type'])
        && !fn_vendor_communication_validate_object_type($thread_data['object_type'])
    ) {
        $result->setSuccess(false);
        $result->addError(
            'invalid_thread_object_type',
            __('vendor_communication.invalid_thread_object_type')
        );
    }

    if ($result->isSuccess()) {
        $thread_data['last_updated'] = TIME;

        if (isset($thread_data['last_message'])) {
            // strip all tags for short message
            $thread_data['last_message'] = fn_vendor_communication_sanitize_data($thread_data['last_message']);
            $thread_data['last_message'] = fn_vendor_communication_truncate_message($thread_data['last_message']);
        }

        if (!$thread_id) {
            $thread_data['created_at'] = TIME;
            $thread_id = db_query('INSERT INTO ?:vendor_communications ?e', $thread_data);

            if (!$thread_id) {
                $result->setSuccess(false);
                $result->addError('cannot_create_thread', __('vendor_communication.cannot_create_thread'));
            }
        } else {
            $update_data = array(
                'status' => isset($thread_data['status']) ? $thread_data['status'] : VC_THREAD_STATUS_HAS_NEW_MESSAGE,
                'last_updated' => $thread_data['last_updated'],
            );

            if (isset($thread_data['last_message'])) {
                $update_data['last_message'] = isset($thread_data['last_message']) ? $thread_data['last_message'] : '';
            }

            if (isset($thread_data['last_message_user_id'])) {
                $update_data['last_message_user_id'] = isset($thread_data['last_message_user_id']) ? $thread_data['last_message_user_id'] : '';
            }

            if (isset($thread_data['last_message_user_type'])) {
                $update_data['last_message_user_type'] = isset($thread_data['last_message_user_type']) ? $thread_data['last_message_user_type'] : '';
            }

            $updated = db_query(
                'UPDATE ?:vendor_communications SET ?u WHERE thread_id = ?i',
                $update_data,
                $thread_id
            );

            if (!$updated) {
                $result->setSuccess(false);
                $result->addError('cannot_update_thread', __('vendor_communication.cannot_update_thread'));
            }
        }
    }

    $result->setData($thread_id);

    return $result;
}

/**
 * Checks if thread object type is valid
 *
 * @param $object_type Object type code
 *
 * @return string
 */
function fn_vendor_communication_validate_object_type($object_type)
{
    $available_object_types = array(
        VC_OBJECT_TYPE_PRODUCT,
        VC_OBJECT_TYPE_COMPANY,
    );

    return in_array($object_type, $available_object_types);
}

/**
 * Add a new message to a thread
 *
 * @param array $message_data Message data array
 *
 * @return Tygh\Common\OperationResult
 */
function fn_vendor_communication_add_thread_message(array $message_data, $notify_by_email = false)
{
    $result = new OperationResult();
    $result->setSuccess(true);
    $message_id = null;

    $required_fields = array('thread_id', 'message', 'user_id', 'user_type');

    foreach ($required_fields as $field) {

        if (empty($message_data[$field])) {
            $result->setSuccess(false);
            $result->addError(
                'thread_id_field_missing',
                __('vendor_communication.required_field_is_missing', array('[field_name]' => $field))
            );
        }
    }

    if ($result->isSuccess()) {
        $message_data['timestamp'] = TIME;
        $message_data['message'] = fn_vendor_communication_sanitize_data($message_data['message'], '<a>');

        $message_id = db_query('INSERT INTO ?:vendor_communication_messages ?e', $message_data);
        $result->setData($message_id);

        if ($message_id) {

            $thread_data = array(
                'thread_id'              => (int) $message_data['thread_id'],
                'last_message'           => $message_data['message'],
                'last_message_user_id'   => $message_data['user_id'],
                'last_message_user_type' => $message_data['user_type'],
                'last_updated'           => $message_data['timestamp'],
            );

            fn_vendor_communication_update_thread($thread_data);

            if ($notify_by_email && Registry::get('settings.Appearance.email_templates') == 'new') {
                $thread_full_data = fn_vendor_communication_get_thread(['thread_id' => $message_data['thread_id']]);

                /** @var \Tygh\Notifications\EventDispatcher $event_dispatcher */
                $event_dispatcher = Tygh::$app['event.dispatcher'];
                if (AREA === 'C') {
                    $force_notification['C'] = false;
                    $force_notification['A'] = ($message_data['user_type'] !== 'A');
                } else {
                    $force_notification[$message_data['user_type']] = false;
                }

                /** @var \Tygh\Notifications\Settings\Factory $notification_settings_factory */
                $notification_settings_factory = Tygh::$app['event.notification_settings.factory'];
                $notification_rules = $notification_settings_factory->create($force_notification);

                $event_dispatcher->dispatch('vendor_communication.message_received', $thread_full_data, $notification_rules);
            }
        } else {
            $result->setSuccess(false);
        }
    }

    return $result;
}

/**
 * Sanitize and strip tags from message (except the <a> tag)
 *
 * @param string $message Message
 *
 * @return string
 */
function fn_vendor_communication_sanitize_data($message, $allowable_tags = '')
{
    $message = strip_tags($message, $allowable_tags);
    $message = SecurityHelper::sanitizeHtml($message);

    return $message;
}

/**
 * Truncates message
 *
 * @param string $message Message
 *
 * @return string
 */
function fn_vendor_communication_truncate_message($message)
{
    return fn_truncate_chars($message, VC_LAST_MESSAGE_MAX_LENGTH);
}

/**
 * Fetches user name by user_id
 *
 * @param int $user_id
 *
 * @return string
 */
function fn_vendor_communication_get_user_name($user_id)
{
    $user_name = '';

    if (!empty($user_id)) {
        $user_data = fn_get_user_short_info($user_id);

        if (!empty($user_data['firstname'])) {
            $user_name .= $user_data['firstname'] . ' ';
        }

        if ($user_data['lastname']) {
            $user_name .= $user_data['lastname'];
        }
    }

    return $user_name;
}

/**
 * Sends new message email notifications to admin
 *
 * @param array $thread_data  Thread data
 *
 * @return boolean
 */
function fn_vendor_communication_send_admin_email_notification(array $thread_data)
{
    $result = false;

    if (!empty($thread_data['thread_id'])
        && Registry::get('addons.vendor_communication.notify_admin') == YesNo::YES
    ) {
        $root_admin_email = db_get_field(
            'SELECT email FROM ?:users WHERE user_type = ?s AND is_root = ?s LIMIT 1',
            UserTypes::ADMIN,
            YesNo::YES
        );
        $thread_url = fn_url("vendor_communication.view&thread_id={$thread_data['thread_id']}", 'A');

        // cannot generate url for admin from vendor area
        $thread_url = str_replace(Registry::get('config.vendor_index'), Registry::get('config.admin_index'), $thread_url);

        if (!empty($thread_data['last_message_user_id'])) {
            $message_from = fn_vendor_communication_get_user_name($thread_data['last_message_user_id']);
        }

        $email_data = array(
            'area' => 'A',
            'email' => $root_admin_email,
            'email_data' => array(
                'thread_url' => $thread_url,
                'message_from' => !empty($message_from) ? $message_from : fn_get_company_name($thread_data['company_id']),
            ),
            'template_code' => 'vendor_communication.notify_admin',
        );

        $result = fn_vendor_communication_send_email_notification($email_data);
    }

    return $result;
}

/**
 * Sends new message email notifications to vendor
 *
 * @param array $thread_data  Thread data
 *
 * @return boolean
 */
function fn_vendor_communication_send_vendor_email_notification(array $thread_data)
{
    $result = false;

    if (!empty($thread_data['thread_id'])
        && !empty($thread_data['company_id'])
        && Registry::get('addons.vendor_communication.notify_vendor') == YesNo::YES
    ) {
        $vendor_email = db_get_field('SELECT email FROM ?:companies WHERE company_id = ?i', $thread_data['company_id']);

        if (!empty($thread_data['last_message_user_id'])) {
            $message_from = fn_vendor_communication_get_user_name($thread_data['last_message_user_id']);
        }


        $email_data = array(
            'area' => 'A',
            'email' => $vendor_email,
            'email_data' => array(
                'thread_url' => fn_url("vendor_communication.view&thread_id={$thread_data['thread_id']}", 'V'),
                'message_from' => !empty($message_from) ? $message_from : fn_get_company_name($thread_data['company_id']),
            ),
            'template_code' => 'vendor_communication.notify_admin',
        );

        $result = fn_vendor_communication_send_email_notification($email_data);
    }

    return $result;
}

/**
 * Sends new message email notifications to customer
 *
 * @param array $thread_data  Thread data
 *
 * @return boolean
 */
function fn_vendor_communication_send_customer_email_notification(array $thread_data)
{
    $result = false;

    if (!empty($thread_data['thread_id'])
        && !empty($thread_data['user_id'])
        && Registry::get('addons.vendor_communication.notify_customer') == YesNo::YES
    ) {
        $user_data = fn_get_user_short_info($thread_data['user_id']);

        $email_data = array(
            'area' => 'C',
            'email' => !empty($user_data['email']) ? $user_data['email'] : '',
            'email_data' => array(
                'thread_url' => fn_url("vendor_communication.view&thread_id={$thread_data['thread_id']}", 'C'),
                'message_from' => fn_get_company_name($thread_data['company_id']),
            ),
            'template_code' => 'vendor_communication.notify_customer',
        );

        $result = fn_vendor_communication_send_email_notification($email_data);
    }

    return $result;
}

/**
 * Sends email based on provided data
 *
 * @param array $email_data Email data
 *
 * @return bool
 */
function fn_vendor_communication_send_email_notification(array $email_data)
{
    $result = false;

    if (!empty($email_data['email'])
        && !empty($email_data['template_code'])
        && !empty($email_data['area'])
    ) {
        /** @var \Tygh\Mailer\Mailer $mailer */
        $mailer = Tygh::$app['mailer'];

        $result = (bool) $mailer->send(array(
            'to' => $email_data['email'],
            'from' => 'default_company_users_department',
            'data' => !empty($email_data['email_data']) ? $email_data['email_data'] : array(),
            'template_code' => $email_data['template_code'],
        ), $email_data['area']);
    }

    return $result;
}

/**
 * Fetches available threads
 *
 * @param array $params         Request parameters
 * @param int   $items_per_page Items per page
 *
 * @return array
 */
function fn_vendor_communication_get_threads(array $params = array(), $items_per_page = 10)
{
    $params = LastView::instance()->update('vc_threads', $params);
    $conditions = $joins = array();

    $fields = array(
        'thread_id' => 'vendor_communications.thread_id',
        'status' => 'vendor_communications.status',
        'user_id' => 'vendor_communications.user_id',
        'company_id' => 'vendor_communications.company_id',
        'object_id' => 'vendor_communications.object_id',
        'object_type' => 'vendor_communications.object_type',
        'last_message' => 'vendor_communications.last_message',
        'last_message_user_id' => 'vendor_communications.last_message_user_id',
        'last_message_user_type' => 'vendor_communications.last_message_user_type',
        'last_updated' => 'vendor_communications.last_updated',
        'created_at' => 'vendor_communications.created_at',
    );

    $default_params = array(
        'get_company_data' => true,
        'get_user_data' => true,
        'page' => 1,
        'items_per_page' => $items_per_page,
        'exclude_statuses' => array(VC_THREAD_STATUS_DELETED),
    );

    $params = array_merge($default_params, $params);

    $sortings = array(
        'last_updated' => array('vendor_communications.last_updated', 'vendor_communications.thread_id'),
        'created_at' => array('vendor_communications.created_at', 'vendor_communications.thread_id'),
        'thread' => 'vendor_communications.thread_id',
    );

    if (!empty($params['user_id'])) {
        $conditions['user_id'] = db_quote(' AND vendor_communications.user_id = ?i', $params['user_id']);
    }

    if (!empty($params['company_id'])) {
        $conditions['company_id'] = db_quote(' AND vendor_communications.company_id = ?i', $params['company_id']);
    }

    if ($params['get_company_data']) {
        $joins['companies'] = db_quote(' LEFT JOIN ?:companies AS companies ON companies.company_id = vendor_communications.company_id');
        $sortings['company'] = 'companies.company';
        $fields['company'] = 'companies.company';

        if (!empty($params['company'])) {
            $conditions['company'] = db_quote(' AND companies.company LIKE ?l', '%' . $params['company'] . '%');
        }
    }

    $period = !empty($params['period']) ? $params['period'] : null;
    $time_from = !empty($params['time_from']) ? $params['time_from'] : null;
    $time_to = !empty($params['time_to']) ? $params['time_to'] : null;

    if ($period || $time_from || $time_to) {
        list($time_from, $time_to) = fn_create_periods(array(
            'period' => $period,
            'time_from' => $time_from,
            'time_to' => $time_to,
        ));

        if ($time_from) {
            $conditions['time_from'] = db_quote(' AND vendor_communications.created_at >= ?i', $time_from);
        }

        if ($time_to) {
            $conditions['time_to'] = db_quote(' AND vendor_communications.created_at < ?i', $time_to);
        }
    }

    if (!empty($params['thread_id'])) {
        $conditions['thread_id'] = db_quote(' AND vendor_communications.thread_id = ?i', $params['thread_id']);
    }

    if ($params['get_user_data']) {
        $fields['firstname'] = 'users.firstname';
        $fields['lastname'] = 'users.lastname';
        $fields['customer_email'] = 'users.email AS customer_email';

        $sortings['name'] = array('users.firstname', 'users.lastname');

        $joins['users'] = db_quote(' LEFT JOIN ?:users AS users ON users.user_id = vendor_communications.user_id');

        if (!empty($params['customer_name'])) {
            $name = trim($params['customer_name']);
            $name_parts = array_filter(fn_explode(' ', $name));

            if (count($name_parts) == 2) {
                $conditions['customer_name'] = db_quote(
                    ' AND (users.firstname LIKE ?l AND users.lastname LIKE ?l)',
                    '%' . array_shift($name_parts) . '%',
                    '%' . array_shift($name_parts) . '%'
                );
            } else {
                $name = "%{$name}%";
                $conditions['customer_name'] = db_quote(
                    ' AND (users.firstname LIKE ?l OR users.lastname LIKE ?l)',
                    $name,
                    $name
                );
            }
        }
    }

    if (!empty($params['statuses']) && is_array($params['statuses'])) {
        $conditions['statuses'] = db_quote(' AND vendor_communications.status IN (?a)', $params['statuses']);
    } elseif (!empty($params['exclude_statuses']) && is_array($params['exclude_statuses'])) {
        $conditions['exclude_statuses'] = db_quote(' AND vendor_communications.status NOT IN (?a)', $params['exclude_statuses']);
    }

    $conditions = implode(' ', $conditions);
    $fields = implode(', ', $fields);
    $joins = implode(' ', $joins);
    $sorting = db_sort($params, $sortings, 'last_updated', 'desc');
    $limit = '';

    if (!empty($params['items_per_page'])) {

        $params['total_items'] = db_get_field(
            'SELECT COUNT(DISTINCT(vendor_communications.thread_id)) FROM ?:vendor_communications AS vendor_communications ?p WHERE 1=1 ?p',
            $joins,
            $conditions
        );

        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }

    $threads = db_get_hash_array(
        'SELECT ?p FROM ?:vendor_communications AS vendor_communications ?p WHERE 1=1 ?p ?p ?p',
        'thread_id',
        $fields,
        $joins,
        $conditions,
        $sorting,
        $limit
    );

    LastView::instance()->processResults('vc_threads', $threads, $params);

    return array($threads, $params);
}

/**
 * Fetches a single thread by id passed in params array
 *
 * @param array $params Array of parameters
 *
 * @return array
 */
function fn_vendor_communication_get_thread($params)
{
    $thread = array();

    if ($params['thread_id']) {
        list($threads) = fn_vendor_communication_get_threads($params);
        $thread = reset($threads);
    }

    if (!empty($params['get_object'])
        && !empty($thread['object_id'])
        && !empty($thread['object_type'])
    ) {

        if ($thread['object_type'] == VC_OBJECT_TYPE_PRODUCT) {
            list($object) = fn_get_products(array('pid' => $thread['object_id']));
            $thread['object'] = reset($object);
        } elseif ($thread['object_type'] == VC_OBJECT_TYPE_COMPANY) {
            $thread['object'] = fn_get_company_data($thread['object_id']);
        }
    }

    return $thread;
}

/**
 * Fetches thread's messages
 *
 * @param array $params Array of parameters
 *
 * @return array
 */
function fn_vendor_communication_get_thread_messages(array $params)
{
    $messages = array();

    if (isset($params['thread_id'])) {
        $fields = array(
            'message_id' => 'messages.message_id',
            'thread_id' => 'messages.thread_id',
            'user_id' => 'messages.user_id',
            'user_type' => 'messages.user_type',
            'timestamp' => 'messages.timestamp',
            'message' => 'messages.message',
            'firstname' => 'users.firstname',
            'lastname' => 'users.lastname',
            'company_id' => 'users.company_id',
        );

        $sortings = array(
            'message_id' => 'messages.message_id',
        );

        $joins = array(
            'users' => db_quote(' LEFT JOIN ?:users AS users ON users.user_id = messages.user_id'),
        );

        $conditions = array(
            'thread_id' => db_quote(' AND messages.thread_id = ?i', $params['thread_id']),
        );

        $fields = implode(', ', $fields);
        $joins = implode(' ', $joins);
        $conditions = implode(' ', $conditions);
        $sorting = db_sort($params, $sortings, 'message_id', 'asc');

        $messages = db_get_array(
            'SELECT ?p FROM ?:vendor_communication_messages AS messages ?p WHERE 1=1 ?p ?p',
            $fields,
            $joins,
            $conditions,
            $sorting
        );

        if ($messages) {

            foreach ($messages as $key => $message) {

                if ($message['user_type'] == UserTypes::VENDOR) {
                    $messages[$key]['vendor_info']['logos'] = fn_vendor_communication_get_vendor_logos($message['company_id']);
                }
            }
        }
    }

    return $messages;
}

/**
 * Fetches company's logos
 *
 * @param int $company_id Company id
 *
 * @return mixed
 */
function fn_vendor_communication_get_vendor_logos($company_id)
{
    static $company_logos = array();

    if (!isset($company_logos[$company_id])) {
        $company_logos[$company_id] = fn_get_logos($company_id);
    }

    return $company_logos[$company_id];
}

/**
 * Checks if user can access thread
 *
 * @param int   $thread_id Thread id
 * @param array $auth      Authorization data array
 *
 * @return bool
 */
function fn_vendor_communication_can_user_access_thread($thread_id, array $auth)
{
    $can_access = false;

    if (empty($auth['user_id'])) {
        return $can_access;
    }

    if ($auth['user_type'] == UserTypes::ADMIN) {
        $can_access = true;
    } elseif ($auth['user_type'] == UserTypes::VENDOR && !empty($auth['company_id']) && AREA == 'A') {
        $can_access = (bool) db_get_field(
            'SELECT thread_id FROM ?:vendor_communications WHERE company_id = ?i AND thread_id = ?i',
            $auth['company_id'],
            $thread_id
        );
    } elseif (AREA === 'C') {
        $can_access = (bool) db_get_field(
            'SELECT thread_id FROM ?:vendor_communications WHERE user_id = ?i AND thread_id = ?i',
            $auth['user_id'],
            $thread_id
        );
    }

    return $can_access;
}

/**
 * Checks if company exists
 *
 * @param int $company_id Company id
 *
 * @return bool
 */
function fn_vendor_communication_is_company_exists($company_id)
{
    $result = false;

    if (!empty($company_id)) {
        $result = (bool) db_get_field('SELECT company_id FROM ?:companies WHERE company_id = ?i', $company_id);
    }

    return $result;
}

/**
 * Fetches thread status (New or Viewed) based on user authorization data
 *
 * @param array $thread Thread data
 * @param array $auth   User authorization data
 *
 * @return mixed
 */
function fn_vendor_communication_get_thread_user_status(array $thread, array $auth)
{
    // it is always "viewed" for admin
    $status = VC_THREAD_STATUS_VIEWED;

    if (!empty($thread['status'])
        && !empty($thread['last_message_user_id'])
        && $thread['status'] == VC_THREAD_STATUS_HAS_NEW_MESSAGE
        && !empty($auth['user_type'])
        && !empty($auth['user_id'])
    ) {
        if ($auth['user_type'] == UserTypes::CUSTOMER
            && $auth['user_id'] != $thread['last_message_user_id']
        ) {
            $status = VC_THREAD_STATUS_HAS_NEW_MESSAGE;
        } elseif ($auth['user_type'] == UserTypes::VENDOR
            && $thread['user_id'] == $thread['last_message_user_id']
        ) {
            $status = VC_THREAD_STATUS_HAS_NEW_MESSAGE;
        }
    }

    return $status;
}

/**
 * Fetches threads statuses based on authorized user data
 *
 * @param array $threads Array of threads
 * @param array $auth    User authorization data
 *
 * @return array
 */
function fn_vendor_communication_get_threads_user_status(array $threads, array $auth)
{
    foreach ($threads as $key => $thread) {
        $threads[$key]['user_status'] = fn_vendor_communication_get_thread_user_status($thread, $auth);
    }

    return $threads;
}

/**
 * Changes thread status to "VIEWED"
 *
 * @param array $thread
 *
 * @return mixed
 */
function fn_vendor_communication_mark_thread_as_viewed(array $thread)
{
    $result = false;

    if (!empty($thread['thread_id'])) {
        $result = fn_vendor_communication_update_thread(array(
            'thread_id' => $thread['thread_id'],
            'status' => VC_THREAD_STATUS_VIEWED,
        ));

        $result = $result->isSuccess();
    }

    return $result;
}

/**
 * Changes status of thread that fit provided conditions to "DELETED"
 *
 * @param array|string $conditions Array or string of conditions
 *
 * @return mixed
 */
function fn_vendor_communication_mark_threads_as_deleted($conditions)
{
    $result = false;

    if (!empty($conditions)) {
        $data = array('status' => VC_THREAD_STATUS_DELETED);

        if (is_array($conditions)) {
            $result = db_query('UPDATE ?:vendor_communications SET ?u WHERE ?w', $data, $conditions);
        } else {
            $result = db_query('UPDATE ?:vendor_communications SET ?u WHERE ?p', $data, $conditions);
        }
    }

    return $result;
}

/**
 * Marks multiple threads as "DELETED"
 *
 * @param array $thread_ids Arrays with thread ids
 *
 * @return bool
 */
function fn_vendor_communication_mark_threads_as_deleted_by_ids(array $thread_ids)
{
    $result = false;

    if (!empty($thread_ids)) {
        $condition = db_quote(' thread_id IN (?n)', $thread_ids);
        $result = fn_vendor_communication_mark_threads_as_deleted($condition);
    }

    return $result;
}

/**
 * Hook handler for "delete_company"
 */
function fn_vendor_communication_delete_company($company_id, $result)
{
    if ($result && $company_id) {
        fn_vendor_communication_mark_threads_as_deleted(array('company_id' => $company_id));
    }
}

/**
* Hook handler for "post_delete_user"
*/
function fn_vendor_communication_post_delete_user($user_id, $user_data, $result)
{
    if ($result && $user_id) {
        fn_vendor_communication_mark_threads_as_deleted(array('user_id' => $user_id));
    }
}

