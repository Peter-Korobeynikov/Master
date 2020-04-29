<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Boxberry\Models\Parsel;
use Boxberry\Models\Customer;
use Boxberry\Client\ParselCreateResponse;
use Tygh\Registry;

if ($mode == 'add') {
    $order_info = fn_get_order_info($_REQUEST['shipment_data']['order_id'], false, true, true, false);

    foreach ($order_info['product_groups'] as $product_group)
    {
        $chosen_shipping = current($product_group['chosen_shippings']);

        if ($chosen_shipping['module'] == 'boxberry') {
            $client = new \Boxberry\Client\Client();
            $service_params =  db_get_field('SELECT service_params FROM ?:shippings WHERE shipping_id = ?i', $chosen_shipping['shipping_id']);
            $serviceParamsArray = unserialize($service_params);
			
            $key = trim($serviceParamsArray['password']);
            $apiUrl = trim($serviceParamsArray['api_url']);

            $client->setKey($key);
            $client->setApiUrl($apiUrl);

            /** @var \Boxberry\Requests\ParselCreateRequest $parselCreate */

            $parselCreate = $client->getParselCreate();
            $parsel = new Parsel();
            $parsel->setOrderId('cscart_'.$order_info['order_id']);
            $parsel->setPrice($order_info['total']);
            if ($chosen_shipping['service_code'] == 'boxberry_self_prepaid' || $chosen_shipping['service_code'] == 'boxberry_courier_prepaid') {
                $parsel->setPaymentSum(0);
            } else {
                $parsel->setPaymentSum($order_info['total']);
            }

            $parsel->setDeliverySum($order_info['shipping_cost']);

            $customer = new Customer();
            $customer->setPhone($order_info['phone']);
            $customer->setFio($order_info['firstname'] . ' ' . $order_info['lastname']);
            $customer->setEmail($order_info['email']);
            $customer->setAddress($order_info['b_address']);
            $parsel->setCustomer($customer);

            $items = new \Boxberry\Collections\Items();
			
            foreach ($product_group['products'] as $product)
            {
                $item = new \Boxberry\Models\Item();
				$item->setId($product['product_id']);
                $item->setName($product['product']);
                $item->setPrice($product['display_price']);
                $item->setQuantity($product['amount']);
                $weight =  db_get_field('SELECT weight FROM ?:products WHERE product_id = ?i', $product['product_id']);
                $weight_data = fn_expand_weight($weight);
                $weight = $weight_data['plain'] * Registry::get('settings.General.weight_symbol_grams') / 1000;
                $weight = sprintf('%.3f', round((double) $weight + 0.00000000001, 3));
                $default_weight = $serviceParamsArray['default_weight'];
                if (empty($weight) || $weight === "0.000" && !empty($default_weight)){
                    $item->setWeight($default_weight * $product['amount']);
                }else{
                    $item->setWeight($weight * 1000 * $product['amount']);
                }
			    $items[] = $item;
			}
			
			$parsel->setItems($items);
			$shop = array(
				'name' => '',
				'name1' => ''
			);
            if ($chosen_shipping['service_code'] == 'boxberry_courier' || $chosen_shipping['service_code'] == 'boxberry_courier_prepaid') {
                $parsel->setVid(2);
                $courierDost = new \Boxberry\Models\CourierDelivery();
                $courierDost->setIndex($product_group['package_info_full']['location']['zipcode']);
                $courierDost->setCity($product_group['package_info_full']['location']['city']);
                $courierDost->setAddressp($product_group['package_info_full']['location']['address']);
                $parsel->setCourierDelivery($courierDost);
            } else {
                $parsel->setVid(1);
                $shop['name'] = $chosen_shipping['point_id'];
            }
            $parsel->setShop($shop);
            $parselCreate->setParsel($parsel);
			
            /** @var ParselCreateResponse $answer */
            try {
                $answer = $client->execute($parselCreate);

                if (strlen($answer->getTrack())) {
                    $_REQUEST['shipment_data']['tracking_number'] = $answer->getTrack();
                    $parselSend = $client->getParselSend();
                    $imIdsList = new \Boxberry\Collections\ImgIdsCollection(array(
                        $answer->getTrack()
                    ));

                    $parselSend->setImgIdsList($imIdsList);
                    /** @var \Boxberry\Client\ParselCheckResponse $parselCheck */
                    //$parselCheck = $client->execute($parselSend);
                }
            } catch (Exception $e) {
                $_REQUEST['shipment_data']['shipping_id'] = false;
                fn_set_notification('E', fn_get_lang_var('Error'), $e->getMessage());
            }
        }
    }
}
if ($mode == 'manage') {
    list($shipments, $search) = fn_get_shipments_info($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'));

    foreach ($shipments as $shipment)
    {
        $array = db_get_array('SELECT * FROM ?:shipments WHERE shipment_id = ?i', $shipment['shipment_id'][0]);
		$array = current($array);
		
        if ($array['carrier'] == 'boxberry') {

            $client = new \Boxberry\Client\Client();
            $service_params =  db_get_field('SELECT service_params FROM ?:shippings WHERE shipping_id = ?i', $array['shipping_id']);
			
            $settings = unserialize($service_params);
			$client->setKey( $settings['password']);
			$client->setApiUrl( $settings['api_url']);
			/** @var \Boxberry\Requests\ParselCheckRequest $parselCheck */
            $parselCheck = $client->getParselCheck();
            $parselCheck->setImId($array['tracking_number']);

            try {
                /** @var \Boxberry\Client\ParselCheckResponse $answer */
                $answer = $client->execute($parselCheck);
                $label = $answer->getLabel();
				
                Tygh::$app['view']->assign('label', $label);
            } catch (Exception $e) {
                fn_set_notification('W', fn_get_lang_var('Warning'), $e->getMessage());
            }
        }
    }
}
if ($mode == 'details') {
    list($shipments, $search) = fn_get_shipments_info($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'));

    foreach ($shipments as $shipment)
    {
        $array = db_get_array('SELECT * FROM ?:shipments WHERE shipment_id = ?i', $shipment['shipment_id'][0]);

        if ($array['carrier'] == 'boxberry' && strlen($array['tracking_number']) > 0) {

            $client = new \Boxberry\Client\Client();

            $service_params =  db_get_field('SELECT service_params FROM ?:shippings WHERE shipping_id = ?i', $array['shipping_id']);

            $key = unserialize($service_params['password']);
            $apiUrl = unserialize($service_params['api_url']);
            $client->setKey($key);
            $client->setApiUrl($apiUrl);

            /** @var \Boxberry\Requests\ListStatusesRequest $listStatuses */
            $listStatuses = $client->getListStatuses();
            $listStatuses->setImId($array['tracking_number']);

            try {
                /** @var \Boxberry\Collections\ListStatusesCollection $answer */
                $answer = $client->execute($listStatuses);
                $status = current($answer);
                Tygh::$app['view']->assign('boxberry_status', $status);
            } catch (Exception $e) {
                fn_set_notification('W', fn_get_lang_var('Warning'), $e->getMessage());
            }
        }
    }
}
