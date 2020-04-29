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

use Tygh\Enum\NotificationSeverity;
use Tygh\Enum\RecipientSearchMethods;
use Tygh\Enum\UserTypes;
use Tygh\Notifications\DataValue;
use Tygh\Notifications\Transports\Internal\InternalMessageSchema;
use Tygh\Notifications\Transports\Internal\InternalTransport;
use Tygh\Notifications\Transports\Mail\MailMessageSchema;
use Tygh\Notifications\Transports\Mail\MailTransport;
use Tygh\NotificationsCenter\NotificationsCenter;

defined('BOOTSTRAP') or die('Access denied');

$schema['vendor_communication.message_received']['receivers'][UserTypes::VENDOR] = [
    MailTransport::getId() => MailMessageSchema::create([
        'area' => 'A',
        'from' => 'default_company_users_department',
        'template_code' => 'vendor_communication.notify_admin',
        'language_code' => DataValue::create('lang_code', CART_LANGUAGE),
        'to' => DataValue::create('to.vendor'),
        'data_modifier' => function (array $data) {
            $thread_url = fn_url("vendor_communication.view?thread_id={$data['thread_id']}", 'V');
            if (!empty($data['last_message_user_id'])) {
                $message_from = fn_vendor_communication_get_user_name($data['last_message_user_id']);
            }
            $message_from = !empty($message_from) ? $message_from : __('customer');

            return array_merge($data, [
                'thread_url' => $thread_url,
                'message_from' => $message_from,
            ]);
        }
    ]),
    InternalTransport::getId() => InternalMessageSchema::create([
        'tag'                       => 'vendor_communication',
        'area'                      => 'V',
        'section'                   => NotificationsCenter::SECTION_COMMUNICATION,
        'recipient_search_method'   => RecipientSearchMethods::USER_ID,
        'recipient_search_criteria' => DataValue::create('admin_user_id'),
        'language_code'             => DataValue::create('lang_code', CART_LANGUAGE),
        'action_url'                => DataValue::create('vendor_action_url'),
        'severity'                  => NotificationSeverity::NOTICE,
        'template_code'             => 'vendor_communication_message_received',
    ]),

];

return $schema;
