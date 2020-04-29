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

use Tygh\Notifications\DataValue;
use Tygh\Notifications\Transports\Mail\MailTransport;
use Tygh\Enum\YesNo;
use Tygh\Enum\UserTypes;


if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode = 'm_update') {
        if (!isset($_REQUEST['notification_settings']) || !is_array($_REQUEST['notification_settings'])) {
            return array(CONTROLLER_STATUS_OK, 'notification_settings.manage');
        }

        foreach ($_REQUEST['notification_settings'] as $event_id => $event) {
            foreach ($event as $receiver => $transports) {
                foreach ($transports as $transport_name => $is_allowed) {
                    $is_allowed = (int) YesNo::toBool($is_allowed);
                    fn_set_notification_settings($event_id, $transport_name, $receiver, $is_allowed);
                }
            }
        }
        return array(CONTROLLER_STATUS_OK, 'notification_settings.manage?receiver_type=' . $_REQUEST['receiver_type']);
    }

}

if ($mode == 'manage') {
    $selected_section = (empty($_REQUEST['selected_section']) ? 'detailed' : $_REQUEST['selected_section']);

    if (isset($_REQUEST['receiver_type'])) {
        $receiver_type = $_REQUEST['receiver_type'];
    } else {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    /** @var \Tygh\Template\Mail\Repository $mail_template_repository */
    $mail_template_repository = Tygh::$app['template.mail.repository'];
    $notification_settings = Tygh::$app['event.notification_settings'];
    $events_schema = Tygh::$app['event.events_schema'];

    $events = [];
    $transports = [];

    foreach ($notification_settings as $event_name => $event) {
        //Grouping events by group identifier
        $events[$event['group']][$event_name] = $event;
        foreach ($event['receivers'] as $receiver => $avaliable_transports) {
            if (isset($avaliable_transports[MailTransport::getId()])) {
                $event_schema = empty($events_schema[$event_name]['receivers'][$receiver][MailTransport::getId()])
                    ? null
                    : $events_schema[$event_name]['receivers'][$receiver][MailTransport::getId()];

                if ($event_schema) {
                    $template_code = $event_schema->template_code;
                    if (!empty($template_code) && !($template_code instanceof DataValue)) {
                        $events[$event['group']][$event_name]['receivers'][$receiver]['template_code'] = $template_code;
                        $events[$event['group']][$event_name]['receivers'][$receiver]['template_area'] = ($receiver == UserTypes::CUSTOMER)
                            ? UserTypes::CUSTOMER
                            : UserTypes::ADMIN;
                    }
                }
            }
            foreach ($avaliable_transports as $transport => $callback) {
                //Marking transports that is using by certain receiver
                $transports[$receiver][$transport] = true;
            }
        }
    }

    Tygh::$app['view']->assign('receiver_type', $receiver_type);
    Tygh::$app['view']->assign('event_groups', $events);
    Tygh::$app['view']->assign('transports', $transports);

    if ($receiver_type == UserTypes::CUSTOMER) {
        $active_section = 'customer_notifications';
    } elseif ($receiver_type == UserTypes::ADMIN) {
        $active_section = 'admin_notifications';
    } elseif ($receiver_type == UserTypes::VENDOR) {
        $active_section = 'vendor_notifications';
    } else {
        $active_section = '';
    }

    Tygh::$app['view']->assign('active_section', $active_section);
}
