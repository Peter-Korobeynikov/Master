<?php


/* registered hooks */
function fn_it_extended_banner_get_banner_data($banner_id, $lang_code, &$fields, $joins, $condition)
{
	$fields[] = "?:banner_descriptions.it_button_text";
	$fields[] = "?:banner_descriptions.it_description";
}

function fn_it_extended_banner_get_banner_data_post($banner_id, $lang_code, &$banner)
{
	$banner['background_image'] = fn_get_image_pairs($_REQUEST['banner_id'], 'background_image', 'M', true, false);
}

function fn_it_extended_banner_get_banners_post(&$banners, $params)
{
	$banners_ids = array();
	$map = array();
	
	foreach ($banners as $key => $banner) {
		$banner_id = $banner["banner_id"];
        $banners[$key]['background_image'] = fn_get_image_pairs($banner_id, 'background_image', 'M', true, false);
		$banners_ids[] = $banner_id;
		$map[$banner_id] = $key;
    }
	//fn_print_die(db_quote("SELECT it_button_text, it_description FROM ?:banner_descriptions WHERE banner_id in (?s) AND lang_code=?s", implode(",", $banners_ids), CART_LANGUAGE));
	$banner_it_fields = db_get_hash_array("SELECT banner_id, it_button_text, it_description FROM ?:banner_descriptions WHERE banner_id in (?n) AND lang_code=?s", 'banner_id', $banners_ids, CART_LANGUAGE);

	foreach($banner_it_fields as $banner_id => $banner_data)
	{
		$banners[$map[$banner_id]]["it_button_text"] = $banner_data["it_button_text"];
		$banners[$map[$banner_id]]["it_description"] = $banner_data["it_description"];
	}
	//fn_print_r($banners, $map, $banner_ids);
}