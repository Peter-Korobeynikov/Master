<?php
/**
 * @author DevIK
 *
 * */
if ($mode == 'update') {
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


    if(isset($_POST['shipping_data']['service_params']) && $_POST['shipping_data']['service_params']['password']==''){
        fn_set_notification('E', fn_get_lang_var('Error'), 'Укажите токен!');
    } elseif (isset($_POST['shipping_data']['service_params']) && $_POST['shipping_data']['service_params']['api_url']==''){
        fn_set_notification('E', fn_get_lang_var('Error'), 'Укажите Url для API!');
    }elseif (isset($_POST['shipping_data']['service_params']) && $_POST['shipping_data']['service_params']['widget_url']==''){
        fn_set_notification('E', fn_get_lang_var('Error'), 'Укажите Url для виджета!');
    }else {
        if (isset($_POST['shipping_data']['service_params']) && $_POST['shipping_data']['service_params']['api_url'] != '' && $_POST['shipping_data']['service_params']['widget_url'] != '') {
            $timeout=3;
            $ch = curl_init();


            curl_setopt ($ch, CURLOPT_URL, $_POST['shipping_data']['service_params']['api_url'] . '?token=' . $_POST['shipping_data']['service_params']['password'] . '&method=GetKeyIntegration');
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt ($ch, CURLOPT_LOW_SPEED_TIME, $timeout);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            $file_contents = curl_exec($ch);


            $statusCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

            if ( $statusCode !== 200 && $statusCode !== 401 ) {
                fn_set_notification('E', fn_get_lang_var('Error'), 'Неверный Url для Api');
            } else if ($statusCode == 401) {
                fn_set_notification('E', fn_get_lang_var('Error'), 'Невалидный API token');
            } else {

                $res = json_decode($file_contents, true);
                if (isset($res[0]['err']) && !empty($res[0]['err'])) {
                    fn_set_notification('E', fn_get_lang_var('Error'), $res[0]['err']);
                } else {


                    $update_values = null;
                    $boxberry_name = 'boxberry';
                    $boxberry_services = db_get_array('
                            SELECT `service_params`,`shipping_id`
                            FROM ?:shipping_services
                            INNER JOIN ?:shippings
                            ON ?:shipping_services.service_id = ?:shippings.service_id
                            WHERE module = ?s ORDER BY `service_params` DESC', $boxberry_name
                    );
                    foreach ($boxberry_services as $params) {

                        $shipping_id = $params["shipping_id"];
                        $service_params = unserialize($params["service_params"]);

                        if (isset($service_params['password']) && !empty($service_params['password'])) {
                            $update_values['service_params'] = $service_params;
                        } else {
                            if (!empty($update_values)) {
                                fn_update_shipping($update_values, $shipping_id);
                            }
                        }
                    }
                }
            }

        }
    }
}

