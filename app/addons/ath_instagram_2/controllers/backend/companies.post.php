<?php
/***************************************************************************
*                                                                          *
*   (c) 2018 ThemeHills - Premium themes and addons					       *
*                                                                          *
****************************************************************************/

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'update') {
    if (!fn_allowed_for('ULTIMATE')) {
        Registry::set('navigation.tabs.instagram', array (
            'title' => __('vendor_instagram'),
            'js' => true
        ));
    }
}
