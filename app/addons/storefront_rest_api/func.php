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

use Tygh\Enum\ProductFilterProductFieldTypes;
use Tygh\Enum\ProductFilterStyles;
use Tygh\Registry;
use Tygh\Settings;
use Tygh\Tools\SecurityHelper;
use Tygh\Languages\Languages;
use Tygh\Enum\ProfileFieldAreas;
use Tygh\Enum\ProfileFieldSections;
use Tygh\Enum\ProfileFieldTypes;
use Tygh\Addons\StorefrontRestApi\ProfileFields\Manager as ProfileFieldsManager;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

/**
 * Formats the price for further usage in REST API.
 *
 * @param float  $price    Price
 * @param string $currency Currency code
 *
 * @return array
 */
function fn_storefront_rest_api_format_price($price, $currency = CART_PRIMARY_CURRENCY)
{
    /** @var \Tygh\Tools\Formatter $formatter */
    $formatter = Tygh::$app['formatter'];

    $price = $formatter->asPrice($price, $currency);
    // FIXME: Refactor space replacement
    $price = str_replace('&nbsp;', ' ', $price);

    return array(
        'price'  => $price,
        'symbol' => Registry::get('currencies.' . $currency . '.symbol'),
    );
}

/**
 * Formats the prices of a product for their further usage in REST API.
 *
 * @param array  $product  Product data
 * @param string $currency Currency code
 *
 * @return array
 */
function fn_storefront_rest_api_format_product_prices($product, $currency = CART_PRIMARY_CURRENCY)
{
    $fields = array(
        'list_price',
        'price',
        'base_price',
        'original_price',
        'display_price',
        'discount',
        'subtotal',
        'display_subtotal',
        'taxed_price',
        'clean_price'
    );

    foreach ($fields as $field) {
        if (isset($product[$field])) {
            $product[$field . '_formatted'] = fn_storefront_rest_api_format_price($product[$field], $currency);
        }
    }

    return $product;
}

/**
 * Formats the prices of a order for their further usage in REST API.
 *
 * @param array  $order    Order data
 * @param string $currency Currency code
 *
 * @return array
 */
function fn_storefront_rest_api_format_order_prices($order, $currency = CART_PRIMARY_CURRENCY)
{
    $fields = array(
        'total',
        'subtotal',
        'discount',
        'subtotal_discount',
        'payment_surcharge',
        'shipping_cost',
        'tax_subtotal',
        'display_subtotal',
        'display_shipping_cost',
    );

    foreach ($fields as $field) {
        if (isset($order[$field])) {
            $order[$field . '_formatted'] = fn_storefront_rest_api_format_price($order[$field], $currency);
        }
    }

    if (isset($order['tax_summary'])) {
        foreach ($order['tax_summary'] as $key => $value) {
            $order['tax_summary'][$key . '_formatted'] = fn_storefront_rest_api_format_price($value, $currency);
        }
    }

    if (!empty($order['products'])) {
        $order['products'] = fn_storefront_rest_api_format_products_prices($order['products']);
    }

    if (!empty($order['product_groups'])) {
        foreach ($order['product_groups'] as &$group) {
            $group['products'] = fn_storefront_rest_api_format_products_prices($group['products']);
            foreach ($group['shippings'] as &$shipping) {
                $shipping['rate_formatted'] = fn_storefront_rest_api_format_price($shipping['rate'], $currency);
            }
            if (isset($group['chosen_shippings'])) {
                foreach ($group['chosen_shippings'] as &$chosen_shipping) {
                    $chosen_shipping['rate_formatted'] = fn_storefront_rest_api_format_price($chosen_shipping['rate'], $currency);
                }
            }
        }
        unset($group);
    }

    return $order;
}

/**
 * Formats the prices of products for their further usage in REST API.
 *
 * @param array  $products List of the product data
 * @param string $currency Currency code
 *
 * @return array
 */
function fn_storefront_rest_api_format_products_prices($products, $currency = CART_PRIMARY_CURRENCY)
{
    foreach ($products as &$product) {
        $product = fn_storefront_rest_api_format_product_prices($product, $currency);
    }
    unset($product);

    return $products;
}

/**
 * Gets current request headers
 *
 * return array
 */
function fn_storefront_rest_api_get_request_headers()
{
    $result = array();

    if (function_exists('getallheaders')) {
        $headers = getallheaders();

        foreach ($headers as $name => $value) {
            $result[$name] = $value;
        }
    } else {
        foreach ($_SERVER as $name => $value) {
            if (strncmp($name, 'HTTP_', 5) === 0) {
                $name = strtolower(str_replace('_', '-', substr($name, 5)));
                $result[$name] = $value;
            }
        }
    }

    foreach ($result as $name => $value) {
        $valid_name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
        unset($result[$name]);
        $result[$valid_name] = $value;
    }

    return $result;
}

/**
 * Handler: on add-on install
 */
function fn_storefront_rest_api_install()
{
    Settings::instance()->updateValue(
        'access_key',
        SecurityHelper::generateRandomString(),
        'storefront_rest_api'
    );
}

/**
 * Hook handler: on before api request handled
 *
 * @param \Tygh\Api $api
 * @param bool      $authorized
 */
function fn_storefront_rest_api_api_handle_request($api, &$authorized)
{
    if (!$authorized) {
        $headers = fn_storefront_rest_api_get_request_headers();

        $key = isset($headers['Storefront-Api-Access-Key']) ? $headers['Storefront-Api-Access-Key'] : null;

        if ($key === Registry::get('addons.storefront_rest_api.access_key')) {
            Registry::set('runtime.api.is_guest_access', true);
            $authorized = true;
        }
    }
}

/**
 * Hook handler: enables the token auth when the customer API access is disabled.
 *
 * @param \Tygh\Api $api  API instance
 * @param string[]  $auth Authetication data from request headers
 */
function fn_storefront_rest_api_api_get_user_data($api, &$auth)
{
    if (!empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW'])) {
        $auth['token'] = $_SERVER['PHP_AUTH_USER'];
        $auth['is_token_auth'] = true;
    }
}

/**
 * Hook handler: on after api checking access
 *
 * @param \Tygh\Api         $api
 * @param \Tygh\Api\AEntity $entity
 * @param string            $method_name
 * @param bool              $can_access
 */
function fn_storefront_rest_api_api_check_access($api, $entity, $method_name, &$can_access)
{
    if (!$can_access && Registry::get('runtime.api.is_guest_access')) {
        $reflection = new ReflectionClass($entity);
        $resource = fn_uncamelize($reflection->getShortName());
        $schema = fn_get_schema('storefront_rest_api', 'guest_access');

        if (isset($schema[$resource][$method_name])) {
            $can_access = $schema[$resource][$method_name];
        }
    }
}

/**
 * Filters out unnecessary profile fields from API response.
 *
 * @param array $field Field data
 *
 * @return array
 */
function fn_storefront_rest_api_filter_profile_fields($field)
{
    $fields = ['field_id', 'field_type', 'field_name', 'description', 'required', 'is_default', 'values', 'value'];

    $field = array_intersect_key($field, array_combine($fields, $fields));

    return $field;
}

/**
 * Adds icons for detailed and additional products images.
 *
 * @param array $products Products data to inject icons into
 * @param array $sizes    Icon sizes
 *
 * @return array Products data with image icons
 */
function fn_storefront_rest_api_set_products_icons(array $products, array $sizes)
{
    foreach ($products as &$product) {
        $product = fn_storefront_rest_api_set_product_icons($product, $sizes);
    }
    unset($product);

    return $products;
}

/**
 * Adds icons for detailed and additional product images.
 *
 * @param array $product Product data to inject icons into
 * @param array $sizes   Icon sizes
 *
 * @return array Product data with image icons
 */
function fn_storefront_rest_api_set_product_icons(array $product, array $sizes)
{
    // main image
    if (!empty($product['main_pair'])) {
        $product['main_pair']['icons'] = fn_storefront_rest_api_generate_icons(
            $product['main_pair']['detailed'],
            $sizes['main_pair']
        );
    }

    // additional images
    if (!empty($product['image_pairs'])) {
        foreach ($product['image_pairs'] as &$pair) {
            $pair['icons'] = fn_storefront_rest_api_generate_icons($pair['detailed'], $sizes['image_pairs']);
        }
        unset($pair);
    }

    // option combintaions images
    if (!empty($product['product_options'])) {
        foreach ($product['product_options'] as &$option) {
            if (!empty($option['variants'])) {
                foreach ($option['variants'] as &$variant) {
                    if (!empty($variant['image_pair'])) {
                        $variant['image_pair']['icons'] = fn_storefront_rest_api_generate_icons(
                            $variant['image_pair']['icon'],
                            $sizes['image_pairs']
                        );
                    }
                }
                unset($variant);
            }
        }
        unset($option);
    }

    // features images
    if (!empty($product['product_features'])) {
        foreach ($product['product_features'] as &$feature) {
            if (!empty($feature['variants'])) {
                foreach ($feature['variants'] as &$variant) {
                    if (!empty($variant['image_pairs'])) {
                        $variant['image_pairs']['icons'] = fn_storefront_rest_api_generate_icons(
                            $variant['image_pairs']['icon'],
                            $sizes['image_pairs']
                        );
                    }
                }
                unset($variant);
            }
        }
        unset($feature);
    }

    return $product;
}

/**
 * Generates icons in selected sizes.
 *
 * @param array $image_data Image to generate icons for
 * @param array $sizes      Icon sizes
 *
 * @return array
 */
function fn_storefront_rest_api_generate_icons($image_data, $sizes)
{
    $icons = [];

    foreach ($sizes as list($width, $height)) {
        $icons["{$width}x{$height}"] = fn_image_to_display($image_data, $width, $height);
        unset(
            $icons["{$width}x{$height}"]['absolute_path'],
            $icons["{$width}x{$height}"]['generate_image']
        );
    }

    return $icons;
}

/**
 * Adds icons for detailed categories images.
 *
 * @param array $categories Categories data to inject icons into
 * @param array $sizes      Icon sizes
 *
 * @return array Categories data with image icons
 */
function fn_storefront_rest_api_set_categories_icons(array $categories, array $sizes)
{
    foreach ($categories as &$category) {
        $category = fn_storefront_rest_api_set_category_icons($category, $sizes);
    }
    unset($category);

    return $categories;
}

/**
 * Adds icons for detailed category images.
 *
 * @param array $category Category data to inject icons into
 * @param array $sizes    Icon sizes
 *
 * @return array Category data with image icons
 */
function fn_storefront_rest_api_set_category_icons(array $category, array $sizes)
{
    if (!empty($category['main_pair'])) {
        $category['main_pair']['icons'] = fn_storefront_rest_api_generate_icons(
            $category['main_pair']['detailed'],
            $sizes['main_pair']
        );
    }

    if (!empty($category['subcategories'])) {
        foreach ($category['subcategories'] as &$subcategory) {
            $subcategory = fn_storefront_rest_api_set_category_icons($subcategory, $sizes);
        }
    }

    return $category;
}

/**
 * Adds icons for banners images.
 *
 * @param array $banners Banners data to inject icons into
 * @param array $sizes   Icon sizes
 *
 * @return array Banners data with image icons
 */
function fn_storefront_rest_api_set_banners_icons(array $banners, array $sizes)
{
    foreach ($banners as &$banner) {
        $banner = fn_storefront_rest_api_set_banner_icons($banner, $sizes);
    }
    unset($banner);

    return $banners;
}

/**
 * Adds icons for banner images.
 *
 * @param array $banner Banner data to inject icons into
 * @param array $sizes  Icon sizes
 *
 * @return array Banner data with image icons
 */
function fn_storefront_rest_api_set_banner_icons(array $banner, array $sizes)
{
    if (!empty($banner['main_pair'])) {
        $banner['main_pair']['icons'] = fn_storefront_rest_api_generate_icons(
            $banner['main_pair']['icon'],
            $sizes['main_pair']
        );
    }

    return $banner;
}

/**
 * Gets storefront information.
 *
 * @param int $storefront_id Storefront identifier
 *
 * @return array return empty array if storefront doesn't exist
 */
function fn_storefront_rest_api_get_storefront($storefront_id = 0)
{
    $storefront = Tygh::$app['storefront.repository']->findById($storefront_id);

    if (!$storefront) {
        return [];
    }

    $languages_params = [
        'area'           => 'C',
        'include_hidden' => false
    ];

    $currencies_params = [
        'status' => ['A']
    ];

    $language_ids = $storefront->getLanguageIds();
    $currency_ids = $storefront->getCurrencyIds();

    if ($language_ids) {
        $languages_params['language_ids'] = $language_ids;
    }

    if ($currency_ids) {
        $currencies_params['currency_id'] = $currency_ids;
    }

    $storefront = [
        'url'                       => $storefront->url,
        'status'                    => $storefront->status,
        'settings'                  => Settings::instance()->getValues('Company', Settings::CORE_SECTION, true, $storefront_id),
        'languages'                 => array_values(Languages::getAvailable($languages_params)),
        'currencies'                => array_values(fn_get_currencies_list($currencies_params)),
        STOREFRONT_FIELD_PROPERTIES => []
    ];

    /**
     * Executes after gets storefront information; allows modifying storefront information.
     *
     * @param int   $storefront_id Storefront identifier
     * @param array $storefront    Storefront information
     */
    fn_set_hook('storefront_rest_api_get_storefront', $storefront_id, $storefront);

    return $storefront;
}

/**
 * Gets list of fields for checkout location and action.
 *
 * @param array  $cart      Cart contents and user information necessary for purchase
 * @param array  $auth      Current user authentication data
 * @param string $lang_code Two-letter language code
 *
 * @return array
 */
function fn_storefront_rest_api_get_checkout_fields(array $cart, $auth, $lang_code)
{
    $fields = Tygh::$app['addons.storefront_rest_api.profile_fields.manager']->get(
        ProfileFieldAreas::CHECKOUT,
        ProfileFieldsManager::ACTION_UPDATE,
        $auth,
        $lang_code
    );

    unset($fields[ProfileFieldSections::ESSENTIALS]);

    $fields[CUSTOM_CHECKOUT_FIELDS]['accept_terms'] = [
        'description' => __('checkout_terms_n_conditions_name', $lang_code),
        'fields' => [
            [
                'field_id'    => 'accept_terms',
                'field_name'  => 'accept_terms',
                'field_type'  => ProfileFieldTypes::CHECKBOX,
                'is_default'  => true,
                'description' => __('terms_and_conditions_content', $lang_code),
                'required'    => true
            ]
        ]
    ];

    /**
     * Executes after gets checkout fields; allows modifying checkout fields.
     *
     * @param array  $cart      Cart contents and user information necessary for purchase
     * @param array  $auth      Current user authentication data
     * @param string $lang_code Two-letter language code
     * @param array  $fields    Checkout fields
     */
    fn_set_hook('storefront_rest_api_get_checkout_fields', $cart, $auth, $lang_code, $fields);

    return $fields;
}

/**
 * Determines filter style.
 *
 * @param array $filter Filter data
 *
 * @return string|null Filter style
 */
function fn_storefront_rest_api_get_filter_style($filter)
{
    if (!empty($filter['filter_style'])) {
        return $filter['filter_style'];
    }

    $filter_style = null;
    if (!empty($filter['slider'])) {
        $filter_style = ProductFilterStyles::SLIDER;
    } elseif (!empty($filter['variants'])) {
        $filter_style = ProductFilterStyles::CHECKBOX;
    }

    $field_type = null;
    if (!empty($filter['field_type'])) {
        $field_type = $filter['field_type'];
    }
    switch ($field_type) {
        case ProductFilterProductFieldTypes::PRICE:
            $filter_style = ProductFilterStyles::SLIDER;
            break;
        case ProductFilterProductFieldTypes::VENDOR:
        case ProductFilterProductFieldTypes::FREE_SHIPPING:
        case ProductFilterProductFieldTypes::IN_STOCK:
            $filter_style = ProductFilterStyles::CHECKBOX;
            break;
    }

    /**
     * Executes after filter style is determined by the filter data for Storefront REST API, allows you to modify
     * the detected filter style
     *
     * @param array  $filter       Filter data
     * @param string $filter_style Filter style
     * @param string $field_type   Product field type
     */
    fn_set_hook('storefront_rest_api_get_filter_style_post', $filter, $filter_style, $field_type);

    return $filter_style;
}

/**
 * Adds icons for pages images.
 *
 * @param array $pages Pages data to inject icons into
 * @param array $sizes Icon sizes
 *
 * @return array Pages data with image icons
 */
function fn_storefront_rest_api_set_pages_icons(array $pages, array $sizes)
{
    foreach ($pages as &$page) {
        $page = fn_storefront_rest_api_set_page_icons($page, $sizes);
    }
    unset($page);

    return $pages;
}

/**
 * Adds icons for page images.
 *
 * @param array $page  Page data to inject icons into
 * @param array $sizes Icon sizes
 *
 * @return array Page data with image icons
 */
function fn_storefront_rest_api_set_page_icons(array $page, array $sizes)
{
    /**
     * Executes after gets page information; allows sets page icons.
     *
     * @param array $page  Page data
     * @param array $sizes Icon sizes
     */
    fn_set_hook('storefront_rest_api_set_page_icons', $page, $sizes);

    return $page;
}

/**
 * The "fill_auth" hook handler.
 *
 * Actions performed:
 *  - Sets ip address from headers.
 *
 * @see fn_fill_auth
 */
function fn_storefront_rest_api_fill_auth(&$auth, $user_data, $area, $original_auth)
{
    if (defined('API')) {
        $headers = fn_storefront_rest_api_get_request_headers();

        if (isset($headers['Storefront-Api-User-Ip'])) {
            $auth['ip'] = $headers['Storefront-Api-User-Ip'];
        }
    }
}
