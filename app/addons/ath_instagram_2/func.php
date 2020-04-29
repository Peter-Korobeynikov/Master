<?php
/***************************************************************************
*                                                                          *
*   (c) 2016 ThemeHills - Premium themes and addons					       *
*                                                                          *
****************************************************************************/

use Tygh\Http;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }





function fn_get_instagram_feed_2($params = array()) {

	// Init Settings
	$instagram_feed = array(		
		'access_token'  => Registry::get('addons.ath_instagram_2.access_token'),
		'fb_page_id'    => Registry::get('addons.ath_instagram_2.fb_page_id'),
		'in_moment'  => time(),
		'cashe_time' => Registry::get('addons.ath_instagram_2.cashe_time'),
		'limit'		 => $params['limit'],
		'filling'    => $params['filling']
	);
	
	$data['data'] = '';


	$url['url'] = 'https://graph.facebook.com/v5.0/'.$instagram_feed['fb_page_id'];
	$url['options']  = array(
		'fields' => 'instagram_business_account',
		'access_token' => $instagram_feed['access_token']
	);
	
	$data = fn_get_instagram2_json($instagram_feed, $url, 22118400); //cashe_time = 256 days		
	$instagram_feed['instagram_business_accountID'] = $data['instagram_business_account']['id'];
	
	if ( $instagram_feed['filling'] == 'self' ) {
		//#2 Вывести страницу привязанную к модулю
		$url['url'] = 'https://graph.facebook.com/v5.0/'.$instagram_feed['instagram_business_accountID'].'/media';
		$url['options']  = array(		
			'fields' => 'id,media_type,comments_count,like_count,media_url,permalink,caption,thumbnail_url,timestamp,children{media_url,media_type}',
			'limit' => $instagram_feed['limit'],
			'access_token' => $instagram_feed['access_token']
		);
		
		$data = fn_get_instagram2_json($instagram_feed, $url, '');	
			
	} else if ( $instagram_feed['filling'] == 'tags' || $instagram_feed['filling'] == 'tags_top' ) {
		
		//#2 Вывести тег		
		$url['url'] = 'https://graph.facebook.com/v5.0/ig_hashtag_search';
		$url['options']  = array(		
			'user_id' => $instagram_feed['instagram_business_accountID'],
			'q' => $params['tag_name'],
			'access_token' => $instagram_feed['access_token']
		);
		
		$data = fn_get_instagram2_json($instagram_feed, $url, 22118400);
		$instagram_feed['tag_id'] = $data['data'][0]['id'];
		
		 if ( $instagram_feed['filling'] == 'tags' ) {
		 	$url['url'] = 'https://graph.facebook.com/v5.0/'.$instagram_feed['tag_id'].'/recent_media';			 
		 } else {
			$url['url'] = 'https://graph.facebook.com/v5.0/'.$instagram_feed['tag_id'].'/top_media'; 
		 }
	
		$url['options']  = array(
			'user_id' => $instagram_feed['instagram_business_accountID'],	
			'fields' => 'id,media_type,comments_count,like_count,media_url,permalink,caption,children{media_url,media_type}',
			'limit' => $instagram_feed['limit']+1,
			'access_token' => $instagram_feed['access_token']
		);
			
		$data = fn_get_instagram2_json($instagram_feed, $url, '');

		
	} else if ( $instagram_feed['filling'] == 'username' || $instagram_feed['filling'] == 'company_insta_user' ) {
		
		if ( $instagram_feed['filling'] == 'company_insta_user' ) {
			$params['insta_name'] = fn_get_company_instagram2($params['company_id'], CART_LANGUAGE);
		}
		
		//#2 Вывести бизнесс акк 
		$url['url'] = 'https://graph.facebook.com/v5.0/'.$instagram_feed['instagram_business_accountID'];
		$url['options']  = array(		
			'fields' => 'business_discovery.username(' . $params['insta_name'] . '){followers_count,media_count,media{id,media_type,comments_count,like_count,media_url,permalink,caption,children{media_url,media_type}}}',
			'limit' => $instagram_feed['limit'],
			'access_token' => $instagram_feed['access_token']
		);
		
		$data = fn_get_instagram2_json($instagram_feed, $url, '');	
		
		$data['data'] = $data['business_discovery']['media']['data'];
		
	} else if ( $instagram_feed['filling'] == 'company_insta_user' ) {
		
	}

// 	fn_print_r($data);

	return array( $data['data'] );
};	


function fn_get_company_instagram2($company_id, $lang_code = CART_LANGUAGE) {
	
    $result = false;
    if (!empty($company_id)) {
        
		$result = db_get_row("SELECT instagram_2_name FROM ?:companies WHERE company_id = ?i", $company_id);

        if (isset($result['instagram_2_name'])) {
            $result = $result['instagram_2_name'];
        } else {
            $result = null;
        }
    }
    
    return $result;
}


//get JSON DATA
function fn_get_instagram2_json($instagram_feed, $url, $cashe_time) {
	if (!empty($cashe_time)) {
		$speciallt_params = array(
	        'cashe_time' => $cashe_time,
	    );
	    $instagram_feed = array_merge($instagram_feed, $speciallt_params);
    }
    

    $json_request = $url['url'] . '?' . json_encode( $url['options'] );
    $json_hash = md5($json_request);
    	
	$json_data = db_get_row("SELECT json_hash, json_request, json_txt, timestamp_last_update FROM ?:ath_instagram_2_cache WHERE json_hash = ?s", $json_hash);
	
	if ( !empty($json_data) ) {
		$insta_request = json_decode($json_data['json_txt'], true);
	}
		    		
	if (empty($json_data) || !empty($insta_request['error']) || ($instagram_feed['in_moment'] > $json_data['timestamp_last_update']+$instagram_feed['cashe_time'])) {

		$json_data_bd['json_txt'] = Http::get($url['url'], $url['options']);
				
	    $json_data_bd = array(
	        'json_hash' 			=> $json_hash,
	        'json_request' 			=> $json_request,
	        'json_txt'       		=> $json_data_bd['json_txt'],
	        'timestamp_last_update' => $instagram_feed['in_moment']
	    );
	    
	    if (empty($json_data)) {		    
			db_query("INSERT INTO ?:ath_instagram_2_cache ?e", $json_data_bd);
	    } else {	    
			db_query("UPDATE ?:ath_instagram_2_cache SET ?u WHERE json_request = ?s", $json_data_bd, $url_json);
	    }
	    
	    $insta_request = json_decode( $json_data['json_txt'] = $json_data_bd['json_txt'], true);
	}
	
	return $insta_request;
}






function fn_activate_license_ath_instagram_2( $api_params, $license_server_url ) {
	$api_params['slm_action'] = 'slm_activate';
	return json_decode(Http::get($license_server_url, $api_params), true);
}

if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}
	
function fn_check_license_ath_instagram_2($type) {
	$name = 'ath_instagram_2';
		
	$api_params = array(
		'slm_action' => 'slm_check',
		'secret_key' => '588dcea47787b9.48200647',
		'license_key' => Registry::get('addons.'.$name.'.license'),
		'registered_domain' => Registry::get('config.http_host')
	);
	if (fn_allowed_for('ULTIMATE') && !Registry::get('runtime.simple_ultimate')) {
		$api_params['product_ref'] = 'lt';
	}
	if (fn_allowed_for('MULTIVENDOR')) {
		$api_params['product_ref'] = 'mv';
	}

	$license_server_url = 'http://licenses.themehills.com';

	$response = json_decode(Http::get($license_server_url, $api_params), true);

	if ($response == '') {
		$ativation = false;
		$message = 'license_error_not_available';
	} elseif ($response['result'] == 'success') {
		if ($response['status'] == 'active') {
			$check_host = array_search(Registry::get('config.http_host'), array_column($response['registered_domains'], 'registered_domain'));
			if ($check_host !== false) {
				$ativation = true;
			} elseif ($response['max_allowed_domains'] > count($response['registered_domains'])) {	
				$activate_response = fn_activate_license_ath_instagram_2($api_params, $license_server_url);
				if ($activate_response['result'] == 'success') 
					$ativation = true;
				else {			
					$ativation = false;
					$message = 'license_error_'.$name.'_already_activated';
				}
			} else {
				$ativation = false;
				$message = 'license_error_'.$name.'_domain_limit';
			}
			if ( ((fn_allowed_for('ULTIMATE') && !Registry::get('runtime.simple_ultimate')) || fn_allowed_for('MULTIVENDOR')) && ($response['product_ref'] != $api_params['product_ref']) ) {
				$ativation = false;
				$message = 'license_error_'.$name.'_wrong_prod_'.$api_params['product_ref'];
			}
		} elseif ( $response['status'] == 'pending' ) {
			if ( ((fn_allowed_for('ULTIMATE') && !Registry::get('runtime.simple_ultimate')) || fn_allowed_for('MULTIVENDOR')) && ($response['product_ref'] != $api_params['product_ref']) ) {
					$ativation = false;
					$message = 'license_error_'.$name.'_wrong_prod_'.$api_params['product_ref'];
			} else {
				$activate_response = fn_activate_license_ath_instagram_2($api_params, $license_server_url);
				if ($activate_response['result'] == 'success') 
					$ativation = true;
				else {						
					$ativation = false;
					$message = 'license_error_'.$name.'_domain_limit';
				}
			}
		} elseif ( $response['status'] == 'expired' ) {
			$ativation = false;
			$message = 'license_error_'.$name.'_expired';
		} elseif ( $response['status'] == 'blocked' ) {
			$ativation = false;
			$message = 'license_error_'.$name.'_blocked';
		} else {		
			$ativation = false;
			$message = 'license_error_'.$name.'_contect_us';
		}
	} else {
		$ativation = false;
		$message = 'license_error_'.$name.'_contect_us';
	}
	
	if (!$ativation) {		
		db_query("UPDATE ?:addons SET status = ?s WHERE addon = ?s", 'D', $name);
		fn_set_notification('E', __('error'), __($message));
		
		if ($type == 'return') {
			return array(CONTROLLER_STATUS_REDIRECT, 'addons.manage');
		} else {
			exit;
		}
	}
};

//check vendor plan
function fn_check_vendor_plan2($plan_id) {
	$result = db_get_row("SELECT instagram_2_allow FROM ?:vendor_plans WHERE plan_id = ?i", $plan_id);
	return $result['instagram_2_allow'];
}

// LOGIN
function init_fb()
{
    $access_token = Registry::get('addons.ath_instagram_2.access_token');
    $app_id = Registry::get('addons.ath_instagram_2.app_id');
    if (!empty($app_id))
    {
        Registry::get('class_loader')->add('Facebook', Registry::get('config.dir.addons') . 'ath_instagram_2/lib', true);
        return new \Facebook\Facebook(array(
            'app_id' => $app_id,
            'app_secret' => Registry::get('addons.ath_instagram_2.app_secret') ,
            'default_graph_version' => 'v5.0',
            'default_access_token' => Registry::get('addons.ath_instagram_2.access_token')
        ));
    }
}

