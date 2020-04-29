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

$schema = array(
    'openid' => array(
        'provider' => 'OpenID',
        'enabled'  => false,
    ),
    'aol' => array(
        'provider' => 'AOL',
        'enabled'  => false,
    ),
    'google' => array(
        'provider' => 'Google',
        'keys' => array(
            'id' => array(
                'db_field' => 'app_id',
                'label' => 'id',
                'required' => true
            ),
            'secret' => array(
                'db_field' => 'app_secret_key',
                'label' => 'secret_key',
                'required' => true
            ),
        ),
        'wrapper' => array(
            'class' => '\Tygh\HybridProvidersGoogle',
        ),
        'params' => array(
            'google_callback' => array(
                'type' => 'template',
                'template' => 'addons/hybrid_auth/components/callback_url.tpl',
            )
        ),
        'instruction' => 'hybrid_auth.instruction_google',
        'hauth_done_param_name' => 'hauth.done',
    ),
    'facebook' => array(
        'provider' => 'Facebook',
        'keys' => array(
            'id' => array(
                'db_field' => 'app_id',
                'label' => 'id',
                'required' => true
            ),
            'secret' => array(
                'db_field' => 'app_secret_key',
                'label' => 'secret_key',
                'required' => true
            ),
        ),
        'wrapper' => array(
            'class' => '\Tygh\HybridProvidersFacebookNewScope',
        ),
        'params' => array(
            'facebook_oauth_redirect_uris' => array(
                'type' => 'template',
                'template' => 'addons/hybrid_auth/components/callback_url.tpl',
                'label' => __('hybrid_auth.facebook_oauth_redirect_uris'),
            )
        ),
        'instruction' => 'hybrid_auth.instruction_facebook_login',
    ),
    'paypal' => array(
        'provider' => 'Paypal',
        'keys' => array(
            'id' => array(
                'db_field' => 'app_id',
                'label' => 'id',
                'required' => true
            ),
            'secret' => array(
                'db_field' => 'app_secret_key',
                'label' => 'secret_key',
                'required' => true
            ),
        ),
        'wrapper' => array(
            'class' => '\Tygh\HybridProvidersPaypal',
        ),
        'params' => array(
            'paypal_seamless' => array(
                'type' => 'checkbox',
                'label' => 'paypal_seamless',
                'default' => 'Y'
            ),
            'paypal_sandbox' => array(
                'type' => 'checkbox',
                'label' => 'paypal_sandbox',
            ),
            'paypal_callback' => array(
                'type' => 'template',
                'template' => 'addons/hybrid_auth/components/callback_url.tpl',
            )
        ),
        'instruction' => 'hybrid_auth.instruction_paypal'
    ),
    'twitter' => array(
        'provider' => 'Twitter',
        'keys' => array(
            'key' => array(
                'db_field' => 'app_id',
                'label' => 'id',
                'required' => true
            ),
            'secret' => array(
                'db_field' => 'app_secret_key',
                'label' => 'secret_key',
                'required' => true
            ),
        ),
        'params' => array(
            'twitter_callback' => array(
                'type' => 'template',
                'template' => 'addons/hybrid_auth/components/callback_url.tpl',
                'callback_url' => '/auth/twitter',
            )
        ),
        'instruction' => 'hybrid_auth.instruction_twitter'
    ),
    'yahoo' => array(
        'provider' => 'Yahoo',
        'keys' => array(
            'id' => array(
                'db_field' => 'app_id',
                'label' => 'id',
                'required' => true
            ),
            'secret' => array(
                'db_field' => 'app_secret_key',
                'label' => 'secret_key',
                'required' => true
            ),
        ),
        'instruction' => 'hybrid_auth.instruction_yahoo'
    ),
    'live' => array(
        'provider' => 'Live',
        'keys' => array(
            'id' => array(
                'db_field' => 'app_id',
                'label' => 'id',
                'required' => true
            ),
            'secret' => array(
                'db_field' => 'app_secret_key',
                'label' => 'secret_key',
                'required' => true
            )
        ),
        'params' => array(
            'redirect_url' => array(
                'type'     => 'template',
                'template' => 'addons/hybrid_auth/components/redirect_url.tpl',
            ),
        ),
        'instruction' => 'hybrid_auth.instruction_live'
    ),
    'linkedin' => [
        'provider' => 'LinkedIn',
        'keys' => [
            'id' => [
                'db_field' => 'app_id',
                'label'    => 'id',
                'required' => true
            ],
            'secret' => [
                'db_field' => 'app_secret_key',
                'label'    => 'secret_key',
                'required' => true
            ]
        ],
        'params' => [
            'linkedin_callback' => [
                'type'     => 'template',
                'template' => 'addons/hybrid_auth/components/callback_url.tpl',
            ],
            'version' => [
                'type'     => 'select',
                'required' => true,
                'label'    => 'hybrid_auth.linkedin_api_version',
                'tooltip'  => 'ttc_hybrid_auth.linkedin_api_version',
                'default'  => 'linkedin_api_v2',
                'options'  => [
                    'linkedin_api_v1' => 'hybrid_auth.linkedin_api_v1',
                    'linkedin_api_v2' => 'hybrid_auth.linkedin_api_v2'
                ]
            ]
        ],
        'versions' => [
            'linkedin_api_v1' => [
                'wrapper' => [
                    'path'  => 'Providers/LinkedInV1.php',
                    'class' => '\Tygh\HybridProvidersLinkedInV1'
                ]
            ],
            'linkedin_api_v2' => [
                'wrapper' => [
                    'class' => '\Tygh\HybridProvidersLinkedIn'
                ]
            ]
        ],
        'instruction' => 'hybrid_auth.instruction_linkedin'
    ],
    'foursquare' => array(
        'provider' => 'Foursquare',
        'keys' => array(
            'id' => array(
                'db_field' => 'app_id',
                'label' => 'id',
                'required' => true
            ),
            'secret' => array(
                'db_field' => 'app_secret_key',
                'label' => 'secret_key',
                'required' => true
            )
        ),
        'wrapper' => array(
            'class' => '\Tygh\HybridProvidersFoursquare',
        ),
        'instruction' => 'hybrid_auth.instruction_foursquare'
    ),
);

return $schema;
