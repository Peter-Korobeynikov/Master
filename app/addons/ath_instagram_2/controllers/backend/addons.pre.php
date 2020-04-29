<?php
/***************************************************************************
*                                                                          *
*   (c) 2016 ThemeHills - Premium themes and addons					       *
*                                                                          *
****************************************************************************/

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'update_status' && $_REQUEST['addon'] == 'ath_instagram_2' ) {
	    fn_check_license_ath_instagram_2('return');
	}
	if ($mode == 'update' && $_REQUEST['addon'] == 'ath_instagram_2' ) {
		fn_check_license_ath_instagram_2('return');
	}
}
