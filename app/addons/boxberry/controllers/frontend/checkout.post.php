<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_REQUEST['boxberry_selected_point']) && is_array($_REQUEST['boxberry_selected_point'])) {
        foreach ($_REQUEST['boxberry_selected_point'] as $group_id => $shippings) {
            if (!is_array($shippings)) {
                continue;
            }
            foreach ($shippings as $shipping_id => $boxberry_point) {
				if (strlen($boxberry_point) > 0) {
                    Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_id][$shipping_id]['point_id'] = $boxberry_point;
                }
            }
        }
    }
}