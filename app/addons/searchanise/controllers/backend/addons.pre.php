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

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_REQUEST['addon'] == 'seo' && in_array($mode, array('update', 'install', 'uninstall'))) {
        fn_se_show_seo_notice();
    }

    return;
}

if ($mode == 'update') {
    if ($_REQUEST['addon'] == 'searchanise') {
        fn_se_check_connect();
        fn_se_check_queue();
    }

} elseif ($mode == 'update_status') {
    if ($_REQUEST['id'] == 'seo') {
        fn_se_show_seo_notice();
    }
}
