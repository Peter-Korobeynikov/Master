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
$schema['ab__motivation_block'] = [
'templates' => 'addons/ab__motivation_block/blocks/ab__motivation_block.tpl',
'wrappers' => 'blocks/wrappers',
'show_on_locations' => ['products.view'],
'cache' => [
'update_handlers' => [
'ab__mb_motivation_items',
'ab__mb_motivation_item_descriptions',
'ab__mb_motivation_item_objects',
'product_categories',
'destinations',
'tags',
],
'request_handlers' => ['company_id', 'category_id'],
],
];
$schema['main']['cache_overrides_by_dispatch']['products.view']['update_handlers'][] = 'ab__mb_motivation_items';
$schema['main']['cache_overrides_by_dispatch']['products.view']['update_handlers'][] = 'ab__mb_motivation_item_descriptions';
$schema['main']['cache_overrides_by_dispatch']['products.view']['update_handlers'][] = 'ab__mb_motivation_item_objects';
if (fn_allowed_for('MULTIVENDOR')) {
$schema['ab__motivation_block']['cache']['update_handlers'][] = 'ab__mb_vendors_descriptions';
$schema['main']['cache_overrides_by_dispatch']['products.view']['update_handlers'][] = 'ab__mb_vendors_descriptions';
}
return $schema;
