<?php
/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2020   *
* / /_\ | | _____  _| |_/ /_ __ __ _ _ __   __| |_ _ __   __ _   | |_ ___  __ _ _ __ ___   *
* |  _  | |/ _ \ \/ / ___ \ '__/ _` | '_ \ / _` | | '_ \ / _` |  | __/ _ \/ _` | '_ ` _ \  *
* | | | | |  __/>  <| |_/ / | | (_| | | | | (_| | | | | | (_| |  | ||  __/ (_| | | | | | | *
* \_| |_/_|\___/_/\_\____/|_|  \__,_|_| |_|\__,_|_|_| |_|\__, |  \___\___|\__,_|_| |_| |_| *
*                                                         __/ |                            *
*                                                        |___/                             *
* ---------------------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license and accept    *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
function fn_abt__ut2_prepare_variation_features_variants($variation_features, $features)
{
$variation_features = fn_array_value_to_key($variation_features, 'feature_id');
$variation_feature_styles = db_get_hash_array('SELECT feature_id, filter_style
FROM ?:product_features
WHERE feature_id IN (?n)', 'feature_id', array_keys($variation_features));
$variation_features = fn_array_merge($variation_feature_styles, $variation_features);
foreach ($variation_features as $f_id => &$f) {
if (!empty($f['filter_style']) && $f['filter_style'] == 'color') {
$variant_color = db_get_hash_array('SELECT variant_id, color
FROM ?:product_feature_variants
WHERE feature_id = ?i AND variant_id IN (?n)', 'variant_id', $f_id, array_keys($f['variants']));
if (!empty($variant_color)) {
$f['variants'] = fn_array_merge($variant_color, $f['variants']);
}
}
foreach ($f['variants'] as $v_id => &$v) {
if (isset($features[$f_id]['variants'][$v_id])) {
$v['active'] = true;
}
}
}
return $variation_features;
}
