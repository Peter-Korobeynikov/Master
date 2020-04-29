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

use Tygh\Registry;
use Tygh\Languages\Languages;
use Tygh\Shippings\RusPickpoint;
use Tygh\Http;
use Tygh\Template\Document\Variables\PickpupPointVariable;

if ( !defined('AREA') ) { die('Access denied'); }

function fn_rus_pickpoint_install()
{
    $service = array(
        'status' => 'A',
        'module' => 'pickpoint',
        'code' => 'pickpoint',
        'sp_file' => '',
        'description' => 'Pickpoint'
    );

    $service['service_id'] = db_query('INSERT INTO ?:shipping_services ?e', $service);

    foreach (Languages::getAll() as $service['lang_code'] => $lang_data) {
        db_query('INSERT INTO ?:shipping_service_descriptions ?e', $service);
    }

    $http_url = fn_get_storefront_protocol();
    $url = $http_url . '://e-solution.pickpoint.ru/api/';
    RusPickpoint::postamatPickpoint($url . 'postamatlist');
}

function fn_rus_pickpoint_uninstall()
{
    $service_ids = db_get_fields('SELECT service_id FROM ?:shipping_services WHERE module = ?s', 'pickpoint');
    db_query('DELETE FROM ?:shipping_services WHERE service_id IN (?a)', $service_ids);
    db_query('DELETE FROM ?:shipping_service_descriptions WHERE service_id IN (?a)', $service_ids);
}

function fn_rus_pickpoint_calculate_cart_taxes_pre(&$cart, $cart_products, &$product_groups)
{
    if (!empty($cart['shippings_extra']['data'])) {
        if (!empty($cart['pickpoint_office'])) {
            $pickpoint_office = $cart['pickpoint_office'];
        } elseif (!empty($_REQUEST['pickpoint_office'])) {
            $pickpoint_office = $cart['pickpoint_office'] = $_REQUEST['pickpoint_office'];
        }

        if (!empty($pickpoint_office)) {
            foreach ($product_groups as $group_key => $group) {
                if (!empty($group['chosen_shippings'])) {
                    foreach ($group['chosen_shippings'] as $shipping_key => $shipping) {
                        if ($shipping['module'] != 'pickpoint') {
                            continue;
                        }

                        $shipping_id = $shipping['shipping_id'];

                        if (!empty($cart['shippings_extra']['data'][$group_key][$shipping_id])) {
                            $shippings_extra = $cart['shippings_extra']['data'][$group_key][$shipping_id];

                            $product_groups[$group_key]['chosen_shippings'][$shipping_key]['data'] = $shippings_extra;
                        }
                    }
                }
            }
        }

        foreach ($cart['shippings_extra']['data'] as $group_key => $shippings) {
            foreach ($shippings as $shipping_id => $shippings_extra) {
                if (!empty($product_groups[$group_key]['shippings'][$shipping_id]['module'])) {
                    $module = $product_groups[$group_key]['shippings'][$shipping_id]['module'];

                    if ($module == 'pickpoint' && !empty($shippings_extra)) {
                        $pickpoint_cost = $shippings_extra['pickpoint_postamat']['Cost'];
                        if (!empty($cart['pickpoint_office'][$group_key][$shipping_id])) {
                            $shippings_extra['pickpoint_postamat'] = $cart['pickpoint_office'][$group_key][$shipping_id];
                        }

                        if (!empty($pickpoint_cost)) {
                            $shippings_extra['pickpoint_postamat']['pickpoint_cost'] = $pickpoint_cost;
                        }

                        $product_groups[$group_key]['shippings'][$shipping_id]['data'] = $shippings_extra;
                    }
                }
            }
        }

        foreach ($product_groups as $group_key => $group) {
            if (!empty($group['chosen_shippings'])) {
                foreach ($group['chosen_shippings'] as $shipping_key => $shipping) {
                    $shipping_id = $shipping['shipping_id'];
                    $module = $shipping['module'];
                    if ($module == 'pickpoint' && !empty($cart['shippings_extra']['data'][$group_key][$shipping_id])) {
                        $shipping_extra = $cart['shippings_extra']['data'][$group_key][$shipping_id];
                        if (!empty($cart['pickpoint_office'][$group_key][$shipping_id])) {
                            $shipping_extra['pickpoint_postamat'] = $cart['pickpoint_office'][$group_key][$shipping_id];
                        }

                        if (!empty($pickpoint_cost)) {
                            $shipping_extra['pickpoint_postamat']['pickpoint_cost'] = $pickpoint_cost;
                        }

                        $product_groups[$group_key]['chosen_shippings'][$shipping_key]['data'] = $shipping_extra;
                    }
                }
            }
        }
    }
}

function fn_pickpoint_cost_by_shipment(&$cart, $shipping_info, $service_data, $city) 
{
    if (!empty($service_data['module']) && ($service_data['module'] == 'pickpoint')) {
        $pickpoint_info = Registry::get('addons.rus_pickpoint');
        $url = RusPickpoint::Url();
        $data_url = RusPickpoint::$extra_data;
        $login = RusPickpoint::Login();

        if ($login) {
            $shipping_settings = $service_data['service_params'];
            $weight_data = fn_expand_weight($shipping_info['package_info']['W']);
            $weight = round($weight_data['plain'] * Registry::get('settings.General.weight_symbol_grams') / 1000, 3);

            $origination = empty($shipping_info['package_info']['origination'])
                ? ['state' => '', 'country' => '', 'city' => '']
                : $shipping_info['package_info']['origination'];

            $from_state = fn_get_state_name($origination['state'], $origination['country'], 'RU');

            $length = !empty($shipping_settings['pickpoint_length']) ? $shipping_settings['pickpoint_length'] : 10;
            $width = !empty($shipping_settings['pickpoint_width']) ? $shipping_settings['pickpoint_width'] : 10;
            $height = !empty($shipping_settings['pickpoint_height']) ? $shipping_settings['pickpoint_height'] : 10;

            if (!empty($shipping_info['package_info']['packages'])) {
                $packages = $shipping_info['package_info']['packages'];
                $packages_count = count($packages);
                $pickpoint_weight = $pickpoint_length = $pickpoint_width = $pickpoint_height = 0;
                if ($packages_count > 0) {
                    foreach ($packages as $id => $package) {
                        $package_length = empty($package['shipping_params']['box_length']) ? $length : $package['shipping_params']['box_length'];
                        $package_width = empty($package['shipping_params']['box_width']) ? $width : $package['shipping_params']['box_width'];
                        $package_height = empty($package['shipping_params']['box_height']) ? $height : $package['shipping_params']['box_height'];
                        $weight_ar = fn_expand_weight($package['weight']);
                        $package_weight = round($weight_ar['plain'] * Registry::get('settings.General.weight_symbol_grams') / 1000, 3);

                        $pickpoint_weight = $pickpoint_weight + $package_weight;
                        $pickpoint_length = $pickpoint_length + $package_length;
                        $pickpoint_width = $pickpoint_width + $package_width;
                        $pickpoint_height = $pickpoint_height + $package_height;
                    }

                    $length = $pickpoint_length;
                    $width = $pickpoint_width;
                    $height = $pickpoint_height;
                    $weight = $pickpoint_weight;
                }
            } else {
                $packages_count = 1;
                $weight = round($weight_data['plain'] * Registry::get('settings.General.weight_symbol_grams') / 1000, 3);
            }

            $sid = RusPickpoint::$sid;
            $data_zone = array(
                'SessionId' => $sid,
                'FromCity' => $origination['city']
            );
            $url_zone = $url . 'getzone';

            $pickpoint_id = '';
            $address_pickpoint = RusPickpoint::findPostamatPickpoint($pickpoint_id, $city);
            $data_zone['ToPT'] = $pickpoint_id;
            $pickpoint_zone = RusPickpoint::zonesPickpoint($url_zone, $data_zone, $data_url);
            if (!empty($pickpoint_zone)) {
                $pickpoint_id = (!empty($pickpoint_zone['to_pt'])) ? $pickpoint_zone['to_pt'] : '';
                if ($pickpoint_zone['delivery_min'] == $pickpoint_zone['delivery_max']) {
                    $date_zone = $pickpoint_zone['delivery_max'] . ' ' . __('days');
                } else {
                    $date_zone = $pickpoint_zone['delivery_min'] . '-' . $pickpoint_zone['delivery_max'] . ' ' . __('days');
                }
            }

            if (!empty($pickpoint_id) && !empty($address_pickpoint)) {
                if (!empty($shipping_info['keys']['group_key']) && !empty($shipping_info['keys']['shipping_id'])) {
                    $group_key = $shipping_info['keys']['group_key'];
                    $shipping_id = $shipping_info['keys']['shipping_id'];
                    $cart['pickpoint_office'][$group_key][$shipping_id]['pickpoint_id'] = $pickpoint_id;
                    $cart['pickpoint_office'][$group_key][$shipping_id]['address_pickpoint'] = $address_pickpoint;
                    $cart['pickpoint_office'][$group_key][$shipping_id]['pickup_data'] = RusPickpoint::getPickpointPostamatById($pickpoint_id);

                } elseif (!empty($shipping_info['shippings'])) {
                    foreach ($shipping_info['shippings'] as $shipping) {
                        if ($shipping['module'] == 'pickpoint') {
                            $group_key = $shipping['group_key'];
                            $shipping_id = $shipping['shipping_id'];
                            $cart['pickpoint_office'][$group_key][$shipping_id]['pickpoint_id'] = $pickpoint_id;
                            $cart['pickpoint_office'][$group_key][$shipping_id]['address_pickpoint'] = $address_pickpoint;
                            $cart['pickpoint_office'][$group_key][$shipping_id]['pickup_data'] = RusPickpoint::getPickpointPostamatById($pickpoint_id);
                        }
                    }
                }
            }

            $data = array(
                'SessionId' => $sid,
                'IKN' => $pickpoint_info['ikn'],
                'FromCity' => $origination['city'],
                'FromRegion' => $from_state,
                'PTNumber' => $pickpoint_id,
                'EncloseCount' => $packages_count,
                'Length' => $length,
                'Depth' => $height,
                'Width' => $width,
                'Weight' => $weight
            );

            $response = Http::post($url . 'calctariff', json_encode($data), $data_url);
            $result = json_decode($response);
            $data_services = json_decode(json_encode($result), true);
            $cost = 0;
            if (isset($data_services['Error']) && ($data_services['Error'] == 1) && !empty($data_services['ErrorMessage'])){
                fn_set_notification('E', __('notice'), $data_services['ErrorMessage']);

            } elseif (isset($data_services['Error']) && !empty($data_services['Error'])) {
                fn_set_notification('E', __('notice'), $data_services['Error']);

            } elseif (isset($data_services['Services'])) {
                $shipment = array_shift($data_services['Services']);
                $cost = $shipment['Tariff'] + $shipment['NDS'];
            }
            foreach ($cart['shipping'] as &$shipping) {
                if ($shipping['module'] == 'pickpoint') {
                    $shipping['rate'] = $cost;
                    $shipping['delivery_time'] = $date_zone;
                }
            }

            RusPickpoint::Logout();
        } else {
            fn_set_notification('E', __('notice'), RusPickpoint::$last_error);
        }
    }
}

function fn_rus_pickpoint_init_user_session_data(&$sess_data, $user_id)
{
    $sess_data['cart']['pickpoint_office'] = array();
}

/**
 * Hook handler: injects pickup point into order data.
 */
function fn_rus_pickpoint_pickup_point_variable_init(
    PickpupPointVariable $instance,
    $order,
    $lang_code,
    &$is_selected,
    &$name,
    &$phone,
    &$full_address,
    &$open_hours_raw,
    &$open_hours,
    &$description_raw,
    &$description
) {
    if (!empty($order['shipping'])) {
        if (is_array($order['shipping'])) {
            $shipping = reset($order['shipping']);
        } else {
            $shipping = $order['shipping'];
        }

        if (!isset($shipping['module']) || $shipping['module'] !== 'pickpoint') {
            return;
        }

        if (isset($shipping['data']['pickpoint_postamat']['pickup_data'])) {
            $pickup_data = $shipping['data']['pickpoint_postamat']['pickup_data'];

            $is_selected = true;
            $name = $pickup_data['name'];
            $full_address = fn_rus_pickpoint_format_pickpoint_format_pickup_point_address($pickup_data);
            $open_hours_raw = fn_rus_pickpoint_format_pickup_point_open_hours($pickup_data['work_time'], $lang_code);
            $open_hours = implode('<br/>', $open_hours_raw);
        }
    }

    return;
}

/**
 * Formats Pickpoint pickup point address.
 *
 * @param string[] $pickup_point Pickup point data from API.
 *
 * @return string Address
 */
function fn_rus_pickpoint_format_pickpoint_format_pickup_point_address($pickup_point)
{
    $address_parts = array_filter([
        $pickup_point['post_code'],
        $pickup_point['region_name'],
        $pickup_point['city_name'],
        $pickup_point['address']
    ], 'fn_string_not_empty');

    $address = implode(', ', $address_parts);

    return $address;
}

/**
 * Formats Pickpoint pickup point open hours.
 *
 * @param string $work_time Pickup point work time from API response.
 * @param string $lang_code Two-letter language code
 *
 * @return string[] Open hours
 */
function fn_rus_pickpoint_format_pickup_point_open_hours($work_time, $lang_code)
{
    $open_hours = [];
    $work_days = explode(',', $work_time);
    $intervals = [];
    $interval = ['[first_day]' => null, '[last_day]' => null, '[schedule]' => null];
    foreach ($work_days as $day => $time) {
        $day = ++$day=== 7 ? 0 : $day;
        if ($interval['[schedule]'] === null) {
            $interval['[first_day]'] = __("weekday_{$day}", [], $lang_code);
            $interval['[schedule]'] = $time;
        } elseif ($time === $interval['[schedule]']) {
            $interval['[last_day]'] = __("weekday_{$day}", [], $lang_code);
            continue;
        } else {
            $intervals[] = $interval;
            $interval = ['[first_day]' => __("weekday_{$day}", [], $lang_code), '[last_day]' => null, '[schedule]' => $time];
            continue;
        }
    }
    $intervals[] = $interval;

    foreach ($intervals as $interval) {
        $schedule_type = 'interval';
        if ($interval['[schedule]'] === 'NODAY') {
            $schedule_type = 'closed';
        }

        $day_type = 'interval';
        if (count($intervals) === 1) {
            $day_type = 'every';
        } elseif ($interval['[last_day]'] === null) {
            $day_type = 'single';
        }

        $open_hours[] = __("rus_pickpoint.day_{$day_type}.schedule_{$schedule_type}", $interval, $lang_code);
    }

    return $open_hours;
}
