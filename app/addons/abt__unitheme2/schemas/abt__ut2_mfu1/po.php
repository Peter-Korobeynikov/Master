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
$schema = [
'tmpl_payment_icons_rus',
'tmpl_subscription_advanced',
'my_account_links_advanced',
'add_to_cart',
'cart_is_empty',
'select_options',
'add_to_comparison_list',
'call_requests.request_call',
'continue_shopping',
'clear_wishlist',
'view_wishlist',
'share',
'sort_by_bestsellers_asc',
'sort_by_bestsellers_desc',
'sort_by_on_sale_asc',
'sort_by_on_sale_desc',
'sort_by_popularity_asc',
'sort_by_popularity_desc',
'sort_by_position_asc',
'sort_by_position_desc',
'sort_by_price_asc',
'sort_by_price_desc',
'sort_by_product_asc',
'sort_by_product_desc',
'sort_by_rating_asc',
'sort_by_rating_desc',
'sort_by_timestamp_asc',
'sort_by_timestamp_desc',
];
$schema = array_map(function ($val) {
return 'Languages::' . $val;
}, $schema);
return $schema;
