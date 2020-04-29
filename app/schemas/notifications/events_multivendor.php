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
use Tygh\Notifications\DataProviders\ProfileDataProvider;
use Tygh\Notifications\DataValue;
use Tygh\Notifications\Transports\Internal\InternalMessageSchema;
use Tygh\Notifications\Transports\Internal\InternalTransport;
use Tygh\Notifications\Transports\Mail\MailMessageSchema;
use Tygh\Notifications\Transports\Mail\MailTransport;
use Tygh\NotificationsCenter\NotificationsCenter;
use Tygh\Registry;

require_once Registry::get('config.dir.schemas') . 'notifications/events.functions.php';

defined('BOOTSTRAP') or die('Access denied');

/** @var array $schema */

$schema['profile.activated.v'] = [
    'group'     => 'profile',
    'name'      => [
        'template' => 'event.profile.activated.name',
        'params'   => [],
    ],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId() => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'company_users_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'profile_activated',
                'legacy_template' => 'profiles/profile_activated.tpl',
                'company_id'      => 0,
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE)
            ]),
        ],
    ],
];

$schema['profile.deactivated.v'] = [
    'group'     => 'profile',
    'name'      => [
        'template' => 'event.profile.deactivated.name',
        'params'   => [],
    ],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId() => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'company_users_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'profile_deactivated',
                'legacy_template' => 'profiles/profile_deactivated.tpl',
                'company_id'      => 0,
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE)
            ]),
        ],
    ],
];


$schema['profile.updated.v'] = [
    'group'     => 'profile',
    'name'      => [
        'template' => 'event.profile.updated.name',
        'params'   => [],
    ],
    'data_provider' => [ProfileDataProvider::class, 'factory'],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId() => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'default_company_users_department',
                'template_code'   => 'update_profile',
                'legacy_template' => 'profiles/update_profile.tpl',
                'company_id'      => 0,
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE),
            ]),
        ],
    ],
];

$schema['profile.created.v'] = [
    'group'     => 'profile',
    'name'      => [
        'template' => 'event.profile.created.name',
        'params'   => [],
    ],
    'data_provider' => [ProfileDataProvider::class, 'factory'],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId() => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'company_users_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'create_profile',
                'legacy_template' => 'profiles/create_profile.tpl',
                'company_id'      => 0,
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE)
            ]),
        ],
    ],
];

$schema['profile.usergroup_activation.v'] = [
    'group'     => 'profile',
    'name'      => [
        'template' => 'event.profile.usergroup_activation.name',
        'params'   => [],
    ],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId() => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'company_users_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'usergroup_activation',
                'legacy_template' => 'profiles/usergroup_activation.tpl',
                'company_id'      => DataValue::create('user_data.company_id'),
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE),
                'data_modifier'   => 'fn_event_profile_usergroup_state_updated_data_modifer'
            ]),
        ],
    ],
];
$schema['profile.usergroup_disactivation.v'] = [
    'group'     => 'profile',
    'name'      => [
        'template' => 'event.profile.usergroup_disactivation.name',
        'params'   => [],
    ],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId() => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'company_users_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'usergroup_disactivation',
                'legacy_template' => 'profiles/usergroup_disactivation.tpl',
                'company_id'      => DataValue::create('user_data.company_id'),
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE),
                'data_modifier'   => 'fn_event_profile_usergroup_state_updated_data_modifer'
            ]),
        ],
    ],
];

$schema['profile.password_recover.v'] = [
    'group'     => 'profile',
    'name'      => [
        'template' => 'event.profile.password_recovery.name',
        'params'   => [],
    ],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId() => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'default_company_users_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'recover_password',
                'legacy_template' => 'profiles/recover_password.tpl',
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE),
                'data_modifier'   => function (array $data) {
                    return array_merge($data, [
                        'url' => fn_url('auth.recover_password?ekey=' . $data['ekey'], 'V'),
                    ]);
                }
            ]),
        ],
    ],
];

$schema['vendor_status_changed_active'] = [
    'group' => 'vendor',
    'name' => [
        'template' => 'event.vendor_status_changed.name',
        'params' => [
            '[status]' => __('active'),
        ],
    ],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId()     => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'default_company_support_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'company_status_notification',
                'legacy_template' => 'companies/status_notification.tpl',
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE),
            ]),
            InternalTransport::getId() => InternalMessageSchema::create([
                'tag'                       => 'vendor_status',
                'area'                      => 'V',
                'section'                   => NotificationsCenter::SECTION_ADMINISTRATION,
                'recipient_search_method'   => RecipientSearchMethods::USER_ID,
                'recipient_search_criteria' => DataValue::create('user_data.user_id'),
                'language_code'             => DataValue::create('user_data.lang_code', CART_LANGUAGE),
                'severity'                  => NotificationSeverity::NOTICE,
                'title'                     => [
                    'template' => 'event.vendor_status_changed.title',
                    'params' => [
                        '[status]' => DataValue::create('status'),
                    ]
                ],
                'message'                   => [
                    'template' => 'event.vendor_status_changed.active.message',
                ]
            ]),
        ],
    ]
];

$schema['vendor_status_changed_pending'] = [
    'group' => 'vendor',
    'name' => [
        'template' => 'event.vendor_status_changed.name',
        'params' => [
            '[status]' => __('pending'),
        ],
    ],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId()     => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'default_company_support_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'company_status_notification',
                'legacy_template' => 'companies/status_notification.tpl',
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE),
            ]),
            InternalTransport::getId() => InternalMessageSchema::create([
                'tag'                       => 'vendor_status',
                'area'                      => 'V',
                'section'                   => NotificationsCenter::SECTION_ADMINISTRATION,
                'recipient_search_method'   => RecipientSearchMethods::USER_ID,
                'recipient_search_criteria' => DataValue::create('user_data.user_id'),
                'language_code'             => DataValue::create('user_data.lang_code', CART_LANGUAGE),
                'severity'                  => NotificationSeverity::NOTICE,
                'title'                     => [
                    'template' => 'event.vendor_status_changed.title',
                    'params' => [
                        '[status]' => DataValue::create('status'),
                    ]
                ],
                'message'                   => [
                    'template' => 'event.vendor_status_changed.pending.message',
                ]
            ]),
        ],
    ]
];

$schema['vendor_status_changed_disabled'] = [
    'group' => 'vendor',
    'name' => [
        'template' => 'event.vendor_status_changed.name',
        'params' => [
            '[status]' => __('disabled'),
        ],
    ],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId()     => MailMessageSchema::create([
                'area'            => 'A',
                'from'            => 'default_company_support_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'company_status_notification',
                'legacy_template' => 'companies/status_notification.tpl',
                'language_code'   => DataValue::create('user_data.lang_code', CART_LANGUAGE),
            ]),
        ],
    ],
];

$schema['vendors_require_approval'] = [
    'group' => 'vendor',
    'name' => [
        'template' => 'event.vendors_require_approval.name',
        'params' => [],
    ],
    'receivers' => [
        UserTypes::ADMIN => [
            InternalTransport::getId() => InternalMessageSchema::create([
                'tag'                       => 'vendor_status',
                'area'                      => 'A',
                'section'                   => NotificationsCenter::SECTION_ADMINISTRATION,
                'severity'                  => NotificationSeverity::WARNING,
                'recipient_search_method'   => RecipientSearchMethods::USER_ID,
                'recipient_search_criteria' => DataValue::create('user_id', 1),
                'language_code'             => DataValue::create('lang_code', CART_LANGUAGE),
                'title'                     => [
                    'template' => 'event.vendors_require_approval.title',
                ],
                'message'                   => [
                    'template' => 'text_not_approved_vendors',
                    'params'   => [
                        '[link]' => fn_url('companies.manage?status[]=N&status[]=P')
                    ]
                ]
            ]),
        ],
    ],
];

return $schema;