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
'general' => [
'position' => 100,
'items' => [
'brand_feature_id' => [
'type' => 'input',
'class' => 'input-small cm-value-integer',
'position' => 100,
'value' => 18,
'is_for_all_devices' => 'Y',
],
'blog_page_id' => [
'type' => 'input',
'class' => 'input-small cm-value-integer',
'position' => 150,
'value' => '',
'is_for_all_devices' => 'Y',
],
'menu_min_height' => [
'type' => 'input',
'class' => 'input-small cm-value-integer',
'position' => 200,
'value' => 476,
'suffix' => 'px',
'is_for_all_devices' => 'Y',
],
'enable_fixed_header_panel' => [
'type' => 'checkbox',
'position' => 300,
'value' => 'Y',
'is_for_all_devices' => 'Y',
],
],
],
'category' => [
'position' => 150,
'items' => [
'show_subcategories' => [
'type' => 'checkbox',
'position' => 100,
'value' => 'N',
'is_for_all_devices' => 'Y',
],
'description_position' => [
'type' => 'selectbox',
'position' => 200,
'class' => 'input-large',
'value' => 'bottom',
'variants' => [
'bottom',
'top',
'none',
],
'variants_as_language_variable' => 'Y',
'is_for_all_devices' => 'Y',
],
],
],
'features' => [
'position' => 175,
'items' => [
'description_position' => [
'type' => 'selectbox',
'position' => 200,
'class' => 'input-large',
'value' => 'bottom',
'variants' => [
'bottom',
'top',
'none',
],
'variants_as_language_variable' => 'Y',
'is_for_all_devices' => 'Y',
],
],
],
'product_list' => [
'position' => 200,
'items' => [
'show_gallery' => [
'type' => 'checkbox',
'position' => 10,
'value' => 'N',
'is_for_all_devices' => 'Y',
],
'lazy_load' => [
'type' => 'checkbox',
'position' => 20,
'value' => 'Y',
'is_for_all_devices' => 'Y',
],
'limit_product_variations' => [
'type' => 'input',
'class' => 'input-small cm-value-integer',
'position' => 30,
'value' => '10',
'is_for_all_devices' => 'Y',
],
'decolorate_out_of_stock_products' => [
'type' => 'checkbox',
'position' => 40,
'value' => 'N',
'is_for_all_devices' => 'Y',
],
'show_fixed_filters_button' => [
'type' => 'checkbox',
'position' => 50,
'value' => [
'desktop' => 'N',
'tablet' => 'N',
'mobile' => 'Y',
],
'disabled' => [
'desktop' => 'Y',
'tablet' => 'Y',
],
],
'max_features' => [
'type' => 'input',
'position' => 60,
'class' => 'input-small cm-value-integer',
'value' => [
'desktop' => 5,
'tablet' => 5,
'mobile' => 5,
],
],
'price_display_format' => [
'type' => 'selectbox',
'class' => 'input-large',
'position' => 70,
'value' => 'col',
'variants' => [
'col',
'row',
'mix',
],
'is_for_all_devices' => 'Y',
],
'show_rating' => [
'type' => 'checkbox',
'position' => 80,
'value' => 'Y',
'is_for_all_devices' => 'Y',
],
'products_multicolumns' => [
'is_group' => 'Y',
'position' => 40,
'items' => [
'grid_item_height' => [
'type' => 'input',
'class' => 'input-small cm-value-integer',
'position' => 100,
'value' => [
'desktop' => '',
'tablet' => '',
'mobile' => '',
],
],
'show_sku' => [
'type' => 'checkbox',
'position' => 110,
'value' => [
'desktop' => 'N',
'tablet' => 'N',
'mobile' => 'N',
],
],
'show_qty' => [
'type' => 'checkbox',
'position' => 120,
'value' => [
'desktop' => 'Y',
'tablet' => 'N',
'mobile' => 'N',
],
],
'show_buttons' => [
'type' => 'checkbox',
'position' => 130,
'value' => [
'desktop' => 'Y',
'tablet' => 'N',
'mobile' => 'N',
],
],
'show_buttons_on_hover' => [
'type' => 'checkbox',
'position' => 140,
'disabled' => [
'desktop' => 'N',
'tablet' => 'Y',
'mobile' => 'Y',
],
'value' => [
'desktop' => 'Y',
'tablet' => 'N',
'mobile' => 'N',
],
],
'grid_item_bottom_content' => [
'type' => 'selectbox',
'class' => 'input-large',
'position' => 150,
'disabled' => [
'desktop' => 'N',
'tablet' => 'Y',
'mobile' => 'Y',
],
'value' => [
'desktop' => 'features_and_variations',
'tablet' => 'none',
'mobile' => 'none',
],
'variants' => [
'none',
'description',
'features',
'variations',
'features_and_variations',
],
'variants_as_language_variable' => 'Y',
],
'show_brand_logo' => [
'type' => 'checkbox',
'position' => 160,
'disabled' => [
'desktop' => 'N',
'tablet' => 'N',
'mobile' => 'Y',
],
'value' => [
'desktop' => 'N',
'tablet' => 'N',
'mobile' => 'N',
],
],
'show_you_save' => [
'type' => 'checkbox',
'position' => 170,
'disabled' => [
'desktop' => 'N',
'tablet' => 'Y',
'mobile' => 'Y',
],
'value' => [
'desktop' => 'N',
'tablet' => 'N',
'mobile' => 'N',
],
],
],
],
'products_without_options' => [
'is_group' => 'Y',
'position' => 40,
'items' => [
'show_sku' => [
'type' => 'checkbox',
'position' => 170,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
'show_amount' => [
'type' => 'checkbox',
'position' => 180,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
'show_qty' => [
'type' => 'checkbox',
'position' => 190,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
'grid_item_bottom_content' => [
'type' => 'selectbox',
'class' => 'input-large',
'position' => 200,
'value' => [
'desktop' => 'features_and_variations',
'tablet' => 'none',
'mobile' => 'none',
],
'variants' => [
'none',
'features',
'variations',
'features_and_variations',
],
'variants_as_language_variable' => 'Y',
],
'show_options' => [
'type' => 'checkbox',
'position' => 210,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
'show_brand_logo' => [
'type' => 'checkbox',
'position' => 220,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
],
],
'short_list' => [
'is_group' => 'Y',
'position' => 40,
'items' => [
'show_sku' => [
'type' => 'checkbox',
'position' => 230,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'N',
],
],
'show_qty' => [
'type' => 'checkbox',
'position' => 240,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
'show_button' => [
'type' => 'checkbox',
'position' => 250,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
'show_button_quick_view' => [
'type' => 'checkbox',
'position' => 260,
'value' => [
'desktop' => 'N',
'tablet' => 'N',
'mobile' => 'N',
],
'disabled' => [
'desktop' => 'N',
'tablet' => 'N',
'mobile' => 'Y',
],
],
'show_button_wishlist' => [
'type' => 'checkbox',
'position' => 270,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
'is_addon_dependent' => 'Y',
],
'show_button_compare' => [
'type' => 'checkbox',
'position' => 280,
'value' => [
'desktop' => 'Y',
'tablet' => 'N',
'mobile' => 'N',
],
],
],
],
],
],
'products' => [
'position' => 300,
'items' => [
'custom_block_id' => [
'type' => 'input',
'class' => 'input-small cm-value-integer',
'position' => 100,
'value' => '',
'is_for_all_devices' => 'Y',
],
'view' => [
'is_group' => 'Y',
'position' => 40,
'items' => [
'show_qty' => [
'type' => 'checkbox',
'position' => 100,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
'show_sku' => [
'type' => 'checkbox',
'position' => 200,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
'show_features' => [
'type' => 'checkbox',
'position' => 300,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'Y',
],
],
'show_short_description' => [
'type' => 'checkbox',
'position' => 400,
'value' => [
'desktop' => 'N',
'tablet' => 'N',
'mobile' => 'N',
],
],
'show_sticky_add_to_cart' => [
'type' => 'checkbox',
'position' => 200,
'value' => [
'desktop' => 'N',
'tablet' => 'N',
'mobile' => 'Y',
],
'disabled' => [
'desktop' => 'Y',
'tablet' => 'N',
'mobile' => 'N',
],
],
'show_brand_logo' => [
'type' => 'checkbox',
'position' => 500,
'value' => [
'desktop' => 'Y',
'tablet' => 'Y',
'mobile' => 'N',
],
],
'brand_link_behavior' => [
'type' => 'selectbox',
'postion' => 501,
'class' => 'input-large',
'value' => 'to_category_with_filter',
'variants' => [
'to_brand_page',
'to_category_with_filter',
],
'is_for_all_devices' => 'Y',
],
],
],
'addon_buy_together' => [
'is_group' => 'Y',
'position' => 200,
'items' => [
'view' => [
'type' => 'selectbox',
'class' => 'input-large',
'position' => 150,
'value' => 'as_block_above_tabs',
'variants' => [
'as_block_above_tabs',
'as_tab_in_tabs',
],
'variants_as_language_variable' => 'Y',
'is_addon_dependent' => 'Y',
'is_for_all_devices' => 'Y',
],
],
],
'addon_required_products' => [
'is_group' => 'Y',
'position' => 200,
'items' => [
'list_type' => [
'type' => 'selectbox',
'class' => 'input-large',
'position' => 150,
'disabled' => [
'desktop' => 'N',
'tablet' => 'Y',
'mobile' => 'Y',
],
'value' => [
'desktop' => 'grid_list',
'tablet' => 'grid_list',
'mobile' => 'grid_list',
],
'variants' => [
'grid_list',
'compact_list',
'product_list',
],
'variants_as_language_variable' => 'Y',
'is_addon_dependent' => 'Y',
],
'item_quantity' => [
'type' => 'input',
'class' => 'input-small cm-value-integer',
'position' => 100,
'value' => [
'desktop' => 4,
'tablet' => 2,
'mobile' => 2,
],
'disabled' => [
'desktop' => 'N',
'tablet' => 'Y',
'mobile' => 'Y',
],
'is_addon_dependent' => 'Y',
],
],
],
'addon_social_buttons' => [
'is_group' => 'Y',
'position' => 300,
'items' => [
'view' => [
'type' => 'checkbox',
'position' => 100,
'value' => [
'desktop' => 'Y',
'tablet' => 'N',
'mobile' => 'N',
],
'is_addon_dependent' => 'Y',
],
],
],
],
],
'load_more' => [
'position' => 400,
'items' => [
'product_list' => [
'type' => 'checkbox',
'position' => 100,
'value' => 'Y',
'is_for_all_devices' => 'Y',
],
'blog' => [
'type' => 'checkbox',
'position' => 200,
'value' => 'Y',
'is_for_all_devices' => 'Y',
],
'mode' => [
'type' => 'selectbox',
'class' => 'input-large',
'position' => 300,
'value' => 'on_button_click',
'variants' => [
'auto',
'on_button_click',
],
'is_for_all_devices' => 'Y',
],
'before_end' => [
'type' => 'input',
'class' => 'input-small cm-value-integer',
'position' => 400,
'value' => 300,
'suffix' => 'px',
'is_for_all_devices' => 'Y',
],
],
],
'addons' => [
'position' => 10000,
'items' => [
'wishlist_products' => [
'is_group' => 'Y',
'position' => 100,
'items' => [
'item_quantity' => [
'type' => 'input',
'class' => 'input-small cm-value-integer',
'position' => 100,
'value' => [
'desktop' => 4,
'tablet' => 2,
'mobile' => 2,
],
'disabled' => [
'desktop' => 'N',
'tablet' => 'Y',
'mobile' => 'Y',
],
'is_addon_dependent' => 'Y',
],
],
],
],
],
];
return $schema;
