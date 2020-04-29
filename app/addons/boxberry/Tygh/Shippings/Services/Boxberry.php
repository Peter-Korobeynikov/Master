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

namespace Tygh\Shippings\Services;

use Boxberry\Models\DeliveryCosts;
use Tygh\Payments\Processors\YandexMoneyMWS\Exception;
use Tygh\Shippings\IService;
use Tygh\Registry;
use Boxberry\Client\Client;
use Tygh\Tygh;

class Boxberry implements IService
{
    protected $client = null;
    protected $weight = null;
    protected $shipping_info;
    /**
     * Boxberry constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    public function prepareData($shipping_info)
    {

        $group_key = $shipping_info['keys']['group_key'];
        $shipping_id = $shipping_info['keys']['shipping_id'];
        $key = trim($shipping_info['service_params']['password']);

	//$boxberry_target_start = $shipping_info['service_params']['boxberry_target_start'];

        $default_weight = ($shipping_info['service_params']['default_weight'] == "" || $shipping_info['service_params']['default_weight'] == "0") ? "1000" : $shipping_info['service_params']['default_weight'];
        $min_weight = $shipping_info['service_params']['min_weight'];
        $max_weight = $shipping_info['service_params']['max_weight'];
        $payment_sum = round($shipping_info['package_info']['C']);


        $this->client->setApiUrl($shipping_info['service_params']['api_url']);
        $this->client->setKey($key);
        $widgetKeyMethod = $this->client->getKeyIntegration();
        $widgetKeyMethod->setToken($key);
        $widgetResponse = $this->client->execute($widgetKeyMethod);
        $widget_key = $widgetResponse->getWidgetKey();

        $weight_data = fn_expand_weight($shipping_info['package_info']['W']);
        $weight = $weight_data['plain'] * Registry::get('settings.General.weight_symbol_grams') / 1000;
        $weight = sprintf('%.3f', round((double) $weight + 0.00000000001, 3)) * 1000;

		if (empty($weight) || $weight === (float)1 && !empty($default_weight)){
			$weight = $default_weight;
		}

		if ($weight < $min_weight || $weight > $max_weight){
		    return false;
        }

		$this->weight = $weight;

        $this->shipping_info = $shipping_info;
		
		Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['apiKey'] = $key;
        Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['apiKeyWidget'] = $widget_key;
       // Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['boxberry_target_start'] = $boxberry_target_start;
        Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['boxberry_weight'] = (float)$weight;
        Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['boxberry_ordersum'] = $payment_sum;
        Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['widget_url'] = $shipping_info['service_params']['widget_url'];
        Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['api_url'] = $shipping_info['service_params']['api_url'];
        Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['sucrh'] = $shipping_info['service_params']['sucrh'];
        if ($shipping_info['service_code'] == 'boxberry_courier_prepaid' || $shipping_info['service_code'] == 'boxberry_self_prepaid') {
            Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['boxberry_paymentsum'] = 0;
        } else {
            Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['boxberry_paymentsum'] = $payment_sum;
        }
    }

    public function processResponse($response)
    {
        if (isset($this->shipping_info['service_params']['margin_percent']) && is_numeric($this->shipping_info['service_params']['margin_percent'])) {
            $response['cost'] += ($response['cost'] * $this->shipping_info['service_params']['margin_percent']) / 100;
        }
        if (isset($this->shipping_info['service_params']['margin_plus']) && is_numeric($this->shipping_info['service_params']['margin_plus'])) {
            $response['cost'] += $this->shipping_info['service_params']['margin_plus'];
        }

        return $response;
    }

    public function processErrors($response)
    {

        if (!is_array($response) || (isset($response['error']) && $response['error'])) {
            return $response['error'];
        }
        return false;
    }

    public function allowMultithreading()
    {
        return false;
    }

    public function getRequestData()
    {

        return array(
            'method' => 'GET',
            'url' => $this->client->getApiUrl(),
            'data' => array(),
        );
    }

    public function getSimpleRates()
    {

     	$deliveryCosts = $this->client->getDeliveryCosts();
		$deliveryCosts->setWeight($this->weight);
      /*  if ($this->shipping_info['service_params']['boxberry_target_start']) {
            $deliveryCosts->setTargetstart($this->shipping_info['service_params']['boxberry_target_start']);
        }*/

        if ($this->shipping_info['service_code'] == 'boxberry_courier' || $this->shipping_info['service_code'] == 'boxberry_courier_prepaid') {
            $zip_delivery = $this->shipping_info['package_info']['location']['zipcode'];
				try{
					$zipcheck = $this->client->getZipCheck();
					$zipcheck->setZip($zip_delivery);
					
					$responseObject = $this->client->execute($zipcheck);
					$zipResult = $responseObject->getExpressDelivery();
					if (!empty($zipResult)) {
						$deliveryCosts->setZip($zip_delivery);
					} else {
						$response = array(
							'cost' => false,
							'error' => false,
							'delivery_time' => false,
						);
						return $response;
					}
				}catch(\Exception $e) {
					$error = $e->getMessage(); 
					$response = array(
						'cost' => false,
						'error' => false,
						'delivery_time' => false,
					);
					return $response;
				}
		} elseif ($this->shipping_info['service_code'] == 'boxberry_self' || $this->shipping_info['service_code'] == 'boxberry_self_prepaid') {
            $group_key = $this->shipping_info['keys']['group_key'];
            $shipping_id = $this->shipping_info['keys']['shipping_id'];

                $listCities = $this->client->getListCities();
                $boxberry_cities = $this->client->execute($listCities);
                $city_code = null;
                $findCityCode = false;
                $selected_city = trim(mb_strtoupper($this->shipping_info['package_info']['location']['city']));
                $selected_state = trim(mb_strtoupper($this->shipping_info['package_info']['location']['state_descr']));


                foreach ($boxberry_cities as $city)
                {
                    /** var /Boxberry/Models/City $city */
                    if ($city->getName() == $selected_city && strpos($selected_state,mb_strtoupper($city->getRegion())) !== false ) {
                        $city_code = $city->getCode();
                        $findCityCode = true;
                        break;
                    }
                }
                if (is_null($city_code) && !$findCityCode){
                    foreach ($boxberry_cities as $city)
                    {
                        /** var /Boxberry/Models/City $city */
                        if ($city->getName() == $selected_city) {
                            $city_code = $city->getCode();
                            break;
                        }
                    }
                }

                $SurchagesSettingsMethod = $this->client->getWidgetSettings();

                $SurchagesSettings = $this->client->execute($SurchagesSettingsMethod);

                $blockedCityCodes = $SurchagesSettings->getCityCode();

                if (in_array($city_code,$blockedCityCodes)){
                    $response = array(
                        'cost' => false,
                        'error' => false,
                        'delivery_time' => false,
                    );
                    return $response;
                }



			if (isset(Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['point_id'])){
                if ($city_code !== null) {
                    $target = Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['point_id'];
                }else {
                    unset(Tygh::$app['session']['cart']['shippings_extra']['boxberry'][$group_key][$shipping_id]['point_id']);
                }
			}else{

				    if ($city_code !== null) {

                        $listPoints = $this->client->getListPoints();
                        if ($this->shipping_info['service_code'] == 'boxberry_self' ) {
                            $listPoints->setPrepaid(0);
                        }elseif ($this->shipping_info['service_code'] == 'boxberry_self_prepaid' ){
                            $listPoints->setPrepaid(1);
                        }

                        $listPoints->setCityCode($city_code);

                        try{
                            $listPointsCollection = $this->client->execute($listPoints);



                            if (is_null($listPointsCollection) || empty($listPointsCollection) || !isset($listPointsCollection[0])){
                                $response = array(
                                    'cost' => false,
                                    'error' => false,
                                    'delivery_time' => false,
                                );
                                return $response;
                            }

                            $target = $listPointsCollection[0]->getCode();

                        }catch (\Exception $ex){
                            $response = array(
                                'cost' => false,
                                'error' => false,
                                'delivery_time' => false,
                            );
                            return $response;
                        }
                    }
                    unset($city_code, $listCities, $listPointsCollection);

            }
            $deliveryCosts->setTarget($target);
        }

        $deliveryCosts->setWidth(0);
        $deliveryCosts->setHeight(0);
        $deliveryCosts->setDepth(0);
        $deliveryCosts->setOrdersum(round($this->shipping_info['package_info']['C']));
        $deliveryCosts->setDeliverysum(0);
        $deliveryCosts->setCms('cscart');
        $deliveryCosts->setVersion('1.1');

		$deliveryCosts->setsucrh($this->shipping_info['service_params']['sucrh']);


        if ($this->shipping_info['service_code'] == 'boxberry_self_prepaid' || $this->shipping_info['service_code'] == 'boxberry_courier_prepaid') {
            $deliveryCosts->setPaysum(0);
        } else {
            $deliveryCosts->setPaysum(round($this->shipping_info['package_info']['C']));
        }

        $deliveryCosts->setUrl($_SERVER['HTTP_HOST']);
        $cost = false;
        $delivery = false;
        $error = false;
		
        try {
            /** @var DeliveryCosts $responseObject */
            $responseObject = $this->client->execute($deliveryCosts);

			$costReceived = $responseObject->getPrice();

			if (is_null($costReceived)){
                $response = array(
                    'cost' => false,
                    'delivery_time' => false,
                    'error' => false
                );
                return $response;
            }

            $cost = fn_format_price_by_currency(round($costReceived), 'RUB', CART_PRIMARY_CURRENCY);

			$SurchagesSettingsMethod = $this->client->getWidgetSettings();

			$SurchagesSettings = $this->client->execute($SurchagesSettingsMethod);

			$delivery = $responseObject->getDeliveryPeriod();
			$delivery_html = false;
			
			if ($SurchagesSettings->getHide_delivery_day() == 0){
				$delivery_html =  $delivery.' '.$this->plural($delivery,'рабочий день','рабочих дня','рабочих дней');
			}

			$response = array(
					'cost' => $cost,
					'delivery_time' => $delivery_html,
					'error' => null
				);


        } catch (\Exception $e) {

			$response = array(
				'cost' => false,
				'delivery_time' => false,
				'error' => false
			);
        }

		return $response;
    }

    private function  plural($n, $form1, $form2, $form3) {
        $n  = abs($n) % 100;
        $n1 = $n % 10;

        if ($n > 10 && $n < 20) return $form3;
        if ($n1 > 1 && $n1 < 5) return $form2;
        if ($n1 == 1) return $form1;

        return $form3;
    }

}