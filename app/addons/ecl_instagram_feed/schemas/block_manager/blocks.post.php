<?php
/*****************************************************************************
*                                                                            *
*                   All rights reserved! eCom Labs LLC                       *
* http://www.ecom-labs.com/about-us/ecom-labs-modules-license-agreement.html *
*                                                                            *
*****************************************************************************/

$schema['instagram_feed'] = array(
    'templates' => array(
        'addons/ecl_instagram_feed/blocks/instagram_feed_multicolumns.tpl' => array(
            'settings' => array(
                'number_of_columns' => array(
                    'type' => 'input',
                    'default_value' => '4'
                ),
            ),
        ),
        'addons/ecl_instagram_feed/blocks/instagram_feed_scroller.tpl' => array(
            'settings' => array(
                'not_scroll_automatically' => array (
                    'type' => 'checkbox',
                    'default_value' => 'N'
                ),
                'scroll_per_page' =>  array (
                    'type' => 'checkbox',
                    'default_value' => 'N'
                ),
                'speed' =>  array (
                    'type' => 'input',
                    'default_value' => 400
                ),
                'pause_delay' =>  array (
                    'type' => 'input',
                    'default_value' => 3
                ),
                'item_quantity' =>  array (
                    'type' => 'input',
                    'default_value' => 5
                ),
                'thumbnail_width' =>  array (
                    'type' => 'input',
                    'default_value' => 80
                ),
                'outside_navigation' => array (
                    'type' => 'checkbox',
                    'default_value' => 'Y'
                )                    
            ),
        ),
    ),    
    'settings' => array(
        'username' => array(
            'type' => 'input',
            'tooltip' => __('addons.ecl_instagram_feed.username_setting_tooltip'),
        ),
        'display_likes_count' => array(
            'type' => 'checkbox',
            'default_value' => 'Y'
        ),
        'display_comments_count' => array(
            'type' => 'checkbox',
            'default_value' => 'Y'
        ),
        'display_photo_description' => array(
            'type' => 'checkbox',
            'default_value' => 'Y'
        ),
        'theme_editor.background' => array(
            'type' => 'input',
            'default_value' => '#369ff3'
        ),
        'posts_amount' => array(
            'type' => 'input',
            'default_value' => '12'
        ),
    ),
    'wrappers' => 'blocks/wrappers'
);

return $schema;