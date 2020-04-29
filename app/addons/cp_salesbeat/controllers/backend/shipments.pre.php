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

use Tygh\Pdf;
use Tygh\Registry;
use Tygh\Shippings\Shippings;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'delete'||$mode == 'm_delete') {
    if(!empty($_REQUEST['shipment_ids'])) {
        foreach($_REQUEST['shipment_ids'] as $k => $shipment_id) {
            if(!fn_cp_sb_allow_shipment_to_delete($shipment_id)) {
                unset($_REQUEST['shipment_ids'][$k]);
                fn_set_notification('E',__('error'),__('sb_courier_already_called').','.__('shipment').' #'.$shipment_id);
            }
        }
    }
}
