<?php
/*****************************************************************************
*                                                                            *
*                   All rights reserved! eCom Labs LLC                       *
* http://www.ecom-labs.com/about-us/ecom-labs-modules-license-agreement.html *
*                                                                            *
*****************************************************************************/

$schema['wishlist_content'] = array(
    'templates' => array(
        'addons/ecl_wishlist_content/blocks/wishlist_content.tpl' => array(),
    ),
    'settings' => array(
        'display_bottom_buttons' => array(
            'type' => 'checkbox',
            'default_value' => 'Y'
        ),
        'display_delete_icons' => array(
            'type' => 'checkbox',
            'default_value' => 'Y'
        ),
        'products_links_type' => array(
            'type' => 'selectbox',
            'values' => array(
                'thumb' => 'thumb',
                'text' => 'text',
            ),
            'default_value' => 'thumb'
        ),
    ),
    'wrappers' => 'blocks/wrappers',
    'cache' => array(
        'disable_cache_when' => array(
            'session_handlers' => array(
               'wishlist.amount' => array('gt', 0)
            )
        )
    )
);

return $schema;