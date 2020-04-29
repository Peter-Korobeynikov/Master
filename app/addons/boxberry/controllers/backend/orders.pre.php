<?php
	/**
	 * Created by PhpStorm.
	 * User: aleksandr.tsioma
	 * Date: 13.04.2017
	 * Time: 17:04
	 */
	if ($mode == 'details') {
		$order_info = fn_get_order_info($_REQUEST['order_id'], false, true, true, false);
		if ($order_info["shipping"][0]["module"] == "boxberry"){
			if(isset( $order_info["shipping"][0]["point_id"] ) && !empty( $order_info["shipping"][0]["point_id"] )){

			    $client = new \Boxberry\Client\Client();
			    $pointDescription  = $client::getPointsDescription();
			    $pointDescription->setCode((string)$order_info["shipping"][0]["point_id"]);

                $key = trim($order_info["shipping"][0]['service_params']['password']);
                $apiUrl = trim($order_info["shipping"][0]['service_params']['api_url']);

                $client->setKey($key);
                $client->setApiUrl($apiUrl);
                $listPoint = $client->execute($pointDescription);

                $address = $listPoint->getAddress();

				Tygh::$app['view']->assign('boxberry_point', $address.' ( '.$order_info["shipping"][0]["point_id"].' )');
                Tygh::$app['view']->assign('s_city', $order_info['s_city']);

			}else{
				Tygh::$app['view']->assign('boxberry_point', false);
				Tygh::$app['view']->assign('s_city', $order_info['s_city']);
			}
		}
		
		
	}

	if ($mode == 'update_details'){
		if (isset($_REQUEST['point_id'])&& !empty($_REQUEST['point_id'])){
			$new_point_id = $_REQUEST['point_id'];
			$order_id = $_REQUEST['order_id'];
			$order_info = fn_get_order_info($order_id);
			foreach ($order_info['product_groups'] as &$product_group)
			{
				$product_group['chosen_shippings'][0]['point_id'] = $new_point_id;
			}
			fn_update_order_data($order_id, $order_info, DEFAULT_LANGUAGE);
		}	
	}