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

namespace Tygh\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tygh\Notifications\EventDispatcher;
use Tygh\Notifications\Settings\Factory;
use Tygh\Notifications\Transports\Internal\InternalTransport;
use Tygh\Notifications\Transports\Mail\MailTransport;
use Tygh\Notifications\Transports\TransportFactory;

class EventDispatcherProvider implements ServiceProviderInterface
{
    /** @inheritdoc */
    public function register(Container $app)
    {
        $app['event.events_schema'] = function (Container $app) {
            $events_schema = fn_get_schema('notifications', 'events');

            return $events_schema;
        };

        $app['event.notification_settings'] = function (Container $app) {
            $notification_settings = $app['event.events_schema'];

            $notification_settings_from_db = fn_get_notification_settings();

            foreach ($notification_settings as $event_id => &$event) {
                foreach ($event['receivers'] as $receiver_id => &$transports) {
                    foreach ($transports as $transport_id => &$callback) {
                        $callback = isset($notification_settings_from_db[$event_id][$receiver_id][$transport_id])
                            ? $notification_settings_from_db[$event_id][$receiver_id][$transport_id]
                            : true;
                    }
                }
            }

            return $notification_settings;
        };

        $app['event.transports_schema'] = function (Container $app) {
            $schema = $app['event.events_schema'];
            $transports = [];
            foreach ($schema as $event) {
                foreach ($event['receivers'] as $list_of_transports) {
                    $transports = array_merge($transports, array_keys($list_of_transports));
                }
            }
            return array_unique($transports);
        };

        $app['event.dispatcher'] = function (Container $app) {
            $dispatcher = new EventDispatcher(
                $app['event.events_schema'],
                $app['event.notification_settings'],
                $app['event.transport_factory']
            );

            return $dispatcher;
        };

        $app['event.transport_factory'] = function (Container $app) {
            $factory = new TransportFactory($app);

            return $factory;
        };

        $app['event.transports.mail'] = function (Container $app) {
            return new MailTransport($app['mailer']);
        };

        $app['event.transports.internal'] = function (Container $app) {
            return new InternalTransport(
                $app['notifications_center'],
                $app['db'],
                $app['notifications_center.factory']
            );
        };

        $app['event.receivers_schema'] = function (Container $app) {
            $receivers_schema = array_keys(fn_get_notification_rules(false, true));

            return $receivers_schema;
        };

        $app['event.notification_settings.factory'] = function (Container $app) {
            return new Factory($app['event.receivers_schema'], $app['event.transports_schema']);
        };
    }
}
