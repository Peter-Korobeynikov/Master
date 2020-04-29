<?php
/***************************************************************************
*                                                                          *
*   (c) 2019 ThemeHills - Premium themes and addons					       *
*                                                                          *
****************************************************************************/

use Tygh\Http;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

	$instagram_feed = array(		
		'access_token'  => Registry::get('addons.ath_instagram_2.access_token'),
		'fb_page_id'    => Registry::get('addons.ath_instagram_2.fb_page_id')
	);
	
	if ( empty( $instagram_feed['access_token'] ) ) {
		echo 'Error: empty access_token</br>';
	};
	
	if ( empty( $instagram_feed['fb_page_id'] ) ) {
		echo 'Error: empty fb_page_id</br>';
	};


if ($mode == 'test') {
	
	echo "max_execution_time" . ini_get('max_execution_time') . PHP_EOL . '<br>';

	$wait = 1; // wait Timeout In Seconds
	$host = 'graph.facebook.com';
	$ports = [
	    'http'  => 80,
	    'https' => 443
	];
	
	foreach ($ports as $key => $port) {
	    $fp = @fsockopen($host, $port, $errCode, $errStr, $wait);
	    echo "Ping $host:$port ($key) ==> ";
	    if ($fp) {
	        echo 'SUCCESS</br>';
	        fclose($fp);
	    } else {
	        echo "ERROR: $errCode - $errStr";
	    }
	    echo PHP_EOL;
	}

	$url['url'] = 'https://graph.facebook.com/v4.0/'.$instagram_feed['fb_page_id'];
	$url['options']  = array(
		'fields' => 'instagram_business_account',
		'access_token' => $instagram_feed['access_token']
	);
	
	echo '<br>Test url: <br> https://graph.facebook.com/v4.0/'.$instagram_feed['fb_page_id'].'?fields=instagram_business_account&access_token=' . $instagram_feed['access_token'] . '<br><br>';
	
	
	$json_data = Http::get($url['url'], $url['options']);	
	fn_print_r( 'Http::get', $json_data );
	

			
	exit;
}
if ($mode == 'time_limit') {
	
	$handle = curl_init();
	 
	$url = 'https://graph.facebook.com/v4.0/'.$instagram_feed['fb_page_id'].'?fields=instagram_business_account&access_token=' . $instagram_feed['access_token'];
	 
	// Set the url
	curl_setopt($handle, CURLOPT_URL, $url);
	// Set the result output to be a string.
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	 
	$output = curl_exec($handle);
	 
	curl_close($handle);
	 
	fn_print_r( json_decode($output, true) );
			
	exit;
}

fn_print_die();