<?

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

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD']	== 'POST') {

    fn_trusted_vars('banners', 'banner_data');
	//fn_print_die($_REQUEST);
	
	if($mode == "update") {
		if($_REQUEST["banner_data"]["type"] == "I") {
			//fn_print_die("OK");
			$_REQUEST['banner_id'] = fn_banners_update_banner($_REQUEST['banner_data'], $_REQUEST['banner_id'], DESCR_SL);
			$pair_data = fn_attach_image_pairs('banners_background_image', 'background_image', $_REQUEST['banner_id']);
		}
	}
}
if($mode == "update") {
	//$background_image_pair = fn_get_image_pairs($_REQUEST['banner_id'], 'background_image', 'M', true, false);
	
	//Tygh::$app['view']->assign('background_image_pair', $background_image_pair);
}