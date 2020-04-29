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

namespace Tygh\Api\Entities\v40;

use Tygh\Addons\StorefrontRestApi\ASraEntity;
use Tygh\Api\Response;
use Tygh\Registry;

class SraCartContent extends ASraEntity
{
    protected $icon_size_small = [500, 500];

    protected $icon_size_big = [1000, 1000];

    /**
     * @var array $cart Shopping cart content
     */
    protected $cart;

    /**
     * @var string $cart_type Regular shopping cart type
     */
    protected $cart_type = 'C';

    /**
     * Gets cart content.
     *
     * @param string $lang_code Two-letter language code
     *
     * @return array
     */
    protected function get($lang_code = DEFAULT_LANGUAGE)
    {
        if (!$this->cart) {
            $this->cart = [];
            fn_extract_cart_content($this->cart, $this->auth['user_id'], $this->cart_type, 'R', $lang_code);
        }

        return $this->cart;
    }

    /**
     * Calculates cart content with promotions, taxes and shipping.
     *
     * @param array  $params
     * @param string $lang_code Two-letter language code
     *
     * @return array
     */
    protected function calculate($params = [], $lang_code = DEFAULT_LANGUAGE)
    {
        $cart = $this->get($lang_code);
        $cart['user_data'] = fn_get_user_info($this->auth['user_id']);

        $params = array_merge([
            'calculate_shipping' => 'S', // skip shipping calculation
            'coupon_codes'       => [],
            'shipping_ids'       => [],
        ], $params);

        if ($params['shipping_ids']) {
            $cart['chosen_shipping'] = $params['shipping_ids'];
            if ($params['calculate_shipping'] == 'S') {
                $params['calculate_shipping'] = 'E';
            }
        }

        if ($params['coupon_codes']) {
            $do_recalc = false;
            foreach ($params['coupon_codes'] as $code) {
                if ($do_recalc) {
                    fn_calculate_cart_content($cart, $this->auth, 'S', false, 'S', true, $lang_code);
                }
                $cart['pending_coupon'] = $code;
                $do_recalc = true;
            }
        }

        if ($params['calculate_shipping'] != 'S') {
            $cart['calculate_shipping'] = true;
        }

        list($products,) = fn_calculate_cart_content(
            $cart,
            $this->auth,
            $params['calculate_shipping'],
            true,
            'F',
            true,
            $lang_code
        );
        $cart['products'] = $this->mergeProductsDataIntoCart($cart['products'], $products);
        $cart['products'] = $this->getDetailedOptions($cart['products'], $products, $lang_code);

        $cart['default_location'] = $this->getDefaultLocation($lang_code);

        // add payment methods
        $cart['payments'] = $this->getPayments($lang_code);

        // remove sensitive and redundant information
        $cart = $this->stripServiceData($cart);

        return $cart;
    }

    /**
     * Gets payments list that doesn't contain any sensitive data (like config).
     *
     * @param string $lang_code Two-letter language code
     *
     * @return array
     */
    protected function getPayments($lang_code = DEFAULT_LANGUAGE)
    {
        return array_map(function ($payment) {
            $script = fn_get_processor_data($payment['payment_id']);

            return [
                'payment'         => $payment['payment'],
                'description'     => $payment['description'],
                'instructions'    => $payment['instructions'],
                'p_surcharge'     => $payment['p_surcharge'],
                'a_surcharge'     => $payment['a_surcharge'],
                'surcharge_title' => $payment['surcharge_title'],
                'script'          => !empty($script['processor_script']) ? $script['processor_script'] : null,
                'template'        => !empty($payment['template']) ? $payment['template'] : null,
            ];
        }, fn_get_payments([
            'area'          => 'C',
            'usergroup_ids' => $this->auth['usergroup_ids'],
            'lang_code'     => $lang_code,
        ]));
    }

    /**
     * Saves cart content.
     *
     * @return bool
     */
    protected function save()
    {
        $this->cart = $this->calculate();

        return fn_save_cart_content($this->cart, $this->auth['user_id'], $this->cart_type);
    }

    /**
     * Adds product to a cart.
     *
     * @param array $cart_products         Products data to add/update.
     *                                     Add:
     *                                     product_id: [
     *                                     'amount': product_amount,
     *                                     'product_options': [
     *                                     option_id => option_value
     *                                     ]
     *                                     ]
     *                                     Update:
     *                                     cart_id: [
     *                                     'amount': product_amount,
     *                                     'product_options': [
     *                                     option_id => option_value
     *                                     ]
     *                                     ]
     * @param bool  $update                Whether to update existing cart products or add the new one
     *
     * @return array Status and added products cart IDs as pairs of [cart_id => product_id].
     *               Status is Response::STATUS_CREATED when products are added
     *               and Response::STATUS_CONFLICT when unable to add.
     */
    protected function addProducts($cart_products = [], $update = false)
    {
        $this->get();

        $result = fn_add_product_to_cart($cart_products, $this->cart, $this->auth, $update);

        if ($result) {

            $this->save();

            return [Response::STATUS_CREATED, ['cart_ids' => $result]];
        }

        return [Response::STATUS_CONFLICT, []];
    }

    /**
     * Removes product from cart.
     *
     * @param int $cart_id Product cart ID or 0 to clear cart
     */
    protected function removeProduct($cart_id = 0)
    {
        $this->get();

        if ($cart_id) {
            fn_delete_cart_product($this->cart, $cart_id);
        }

        if (!$cart_id || fn_cart_is_empty($this->cart) == true) {
            fn_clear_cart($this->cart);
        }

        $this->save();
    }

    /**
     * Lists cart content.
     *
     * @param string $id
     * @param array  $params
     *
     * @return array
     */
    public function index($id = '', $params = [])
    {
        if ($user_relation_error = $this->getUserRelationError()) {
            return $user_relation_error;
        }

        if ($id) {
            return [
                'status' => Response::STATUS_METHOD_NOT_ALLOWED,
                'data'   => [
                    'message' => __('api_not_need_id'),
                ],
            ];
        }

        $params['icon_sizes'] = $this->safeGet($params, 'icon_sizes', [
            'main_pair'   => [$this->icon_size_big, $this->icon_size_small],
            'image_pairs' => [$this->icon_size_small],
        ]);

        // normalize coupon codes
        if ($coupon_codes = $this->safeGet($params, 'coupon_codes', [])) {
            $params['coupon_codes'] = array_map(function ($code) {
                return fn_strtolower(trim($code));
            }, array_unique((array) $coupon_codes));
        }

        // normalize shipping ids
        $params['shipping_ids'] = array_filter((array) $this->safeGet($params, 'shipping_ids', []), 'is_numeric');

        // normalize shipping calculation policy
        $calculate_shipping = $this->safeGet($params, 'calculate_shipping', 'S');
        $params['calculate_shipping'] = in_array($calculate_shipping, ['A', 'E', 'S']) ? $calculate_shipping : 'S';

        $lang_code = $this->getLanguageCode($params);

        $cart = $this->calculate($params, $lang_code);

        $data = fn_storefront_rest_api_format_order_prices(
            $cart,
            $this->safeGet($params, 'currency', CART_PRIMARY_CURRENCY)
        );

        $data['products'] = fn_storefront_rest_api_set_products_icons($data['products'], $params['icon_sizes']);
        foreach ($data['product_groups'] as &$product_group) {
            $product_group['products'] = fn_storefront_rest_api_set_products_icons(
                $product_group['products'],
                $params['icon_sizes']
            );
        }

        if ($this->safeGet($params, 'get_checkout_fields', false)) {
            $data['checkout_fields'] = fn_storefront_rest_api_get_checkout_fields(
                $cart,
                $this->auth,
                $lang_code
            );
        }

        return [
            'status' => Response::STATUS_OK,
            'data'   => $data,
        ];
    }

    /**
     * Entry point for cart management.
     *
     * @param array $params
     *
     * @return array
     */
    public function create($params)
    {
        if ($user_relation_error = $this->getUserRelationError()) {
            return $user_relation_error;
        }

        $status = Response::STATUS_BAD_REQUEST;
        $data = [];

        $cart_products = $this->safeGet($params, 'products', []);

        // add to cart
        if ($cart_products) {
            if (!$this->auth['user_id']) {
                return [
                    'status' => Response::STATUS_FORBIDDEN,
                    'data'   => [
                        'message' => __('storefront_rest_api.guests_cant_add_products_to_cart')
                    ]
                ];
            }

            list($status, $data) = $this->addProducts($cart_products);
        } else {
            $data['message'] = __('api_required_field', [
                '[field]' => 'products',
            ]);
        }

        return [
            'status' => $status,
            'data'   => $data,
        ];
    }

    // update amount/options
    public function update($id = '', $params = [])
    {
        if ($user_relation_error = $this->getUserRelationError()) {
            return $user_relation_error;
        }

        $status = Response::STATUS_BAD_REQUEST;
        $data = [];
        $user_data = [];

        $can_edit = true;
        if ($id) {
            $cart_products = [$id => $params];
            if (!$params) {
                $can_edit = false;
                $data['message'] = __('api_need_params');
            }
        } else {
            $cart_products = (array) $this->safeGet($params, 'products', []);
            $user_data = (array) $this->safeGet($params, 'user_data', []);
            if (!$cart_products && !$user_data) {
                $can_edit = false;
                $data['message'] = __('api_required_fields', [
                    '[fields]' => 'products / user_data',
                ]);
            }
        }

        if ($can_edit) {
            $this->get();

            foreach ($cart_products as $cart_id => $product) {
                // check if editing products that are not in cart
                if (!isset($this->cart['products'][$cart_id])) {
                    $can_edit = false;
                    $status = Response::STATUS_NOT_FOUND;
                    break;
                }

                // remove products with zero amount
                if (isset($products_data['amount']) && empty($product['amount']) && !isset($this->cart['products'][$cart_id]['extra']['parent'])) {
                    $this->removeProduct($cart_id);
                    continue;
                }

                // update existing product data
                $cart_products[$cart_id] = array_merge($this->cart['products'][$cart_id], $product);
            }
        }

        // update cart
        if ($can_edit) {
            if ($user_data && $this->updateUserData($user_data)) {
                $status = Response::STATUS_CREATED;
            }

            if ($cart_products) {
                list($status, $data) = $this->addProducts($cart_products, true);
            }
        }

        return [
            'status' => $status,
            'data'   => $data,
        ];
    }

    /**
     * Deletes a product from a cart or cleans up the whole cart.
     *
     * @param int $id Product cart ID
     *
     * @return array
     */
    public function delete($id = 0)
    {
        if ($user_relation_error = $this->getUserRelationError()) {
            return $user_relation_error;
        }

        $status = Response::STATUS_NO_CONTENT;
        $this->get();

        if ($id && !isset($this->cart['products'][$id])) {
            $status = Response::STATUS_NOT_FOUND;
        } else {
            $this->removeProduct($id);
        }

        return [
            'status' => $status,
            'data'   => [],
        ];
    }

    public function privilegesCustomer()
    {
        return [
            'index'  => $this->auth['is_token_auth'],
            'create' => true,
            'update' => $this->auth['is_token_auth'],
            'delete' => $this->auth['is_token_auth'],
        ];
    }

    public function privileges()
    {
        return [
            'index'  => true,
            'create' => true,
            'update' => true,
            'delete' => true,
        ];
    }

    /**
     * Strips configuration data and redundant information from cart data.
     *
     * @param array $cart
     *
     * @return array
     */
    protected function stripServiceData($cart)
    {
        foreach ($cart['product_groups'] as $group_id => $group) {
            // remove session product data
            foreach ($cart['products'] as $cart_id => $product) {
                unset(
                    $cart['products'][$cart_id]['user_id'],
                    $cart['products'][$cart_id]['timestamp'],
                    $cart['products'][$cart_id]['type'],
                    $cart['products'][$cart_id]['user_type'],
                    $cart['products'][$cart_id]['item_id'],
                    $cart['products'][$cart_id]['item_type'],
                    $cart['products'][$cart_id]['session_id'],
                    $cart['products'][$cart_id]['ip_address'],
                    $cart['products'][$cart_id]['order_id'],

                    $cart['product_groups'][$group_id]['products'][$cart_id]['user_id'],
                    $cart['product_groups'][$group_id]['products'][$cart_id]['timestamp'],
                    $cart['product_groups'][$group_id]['products'][$cart_id]['type'],
                    $cart['product_groups'][$group_id]['products'][$cart_id]['user_type'],
                    $cart['product_groups'][$group_id]['products'][$cart_id]['item_id'],
                    $cart['product_groups'][$group_id]['products'][$cart_id]['item_type'],
                    $cart['product_groups'][$group_id]['products'][$cart_id]['session_id'],
                    $cart['product_groups'][$group_id]['products'][$cart_id]['ip_address'],
                    $cart['product_groups'][$group_id]['products'][$cart_id]['order_id']
                );
            }

            // remove shipping config
            foreach ($group['shippings'] as $shipping_id => $shipping) {
                unset(
                    $cart['product_groups'][$group_id]['shippings'][$shipping_id]['service_params'],
                    $cart['product_groups'][$group_id]['shippings'][$shipping_id]['rate_info'],
                    $cart['shipping'][$shipping_id]['service_params'],
                    $cart['shipping'][$shipping_id]['rate_info']
                );
            }

            // all required data is stored in $cart['chosen_shipping']
            unset(
                $cart['product_groups'][$group_id]['chosen_shippings'],
                $cart['product_groups'][$group_id]['package_info'],
                $cart['product_groups'][$group_id]['package_info_full']
            );
        }

        // remove promotions config
        unset($cart['applied_promotions']);

        // remove passwords and access keys
        unset(
            $cart['user_data']['password'],
            $cart['user_data']['salt'],
            $cart['user_data']['last_passwords'],
            $cart['user_data']['password_change_timestamp'],
            $cart['user_data']['api_key']
        );

        return $cart;
    }

    /**
     * Provides company identifier of a storefront.
     *
     * @return int Company ID
     */
    protected function getCompanyId()
    {
        if (!empty($this->parent['company_id'])) {
            $company_id = $this->parent['company_id'];
        } else {
            $company_id = parent::getCompanyId();
        }

        return $company_id;
    }

    /**
     * Checks whether users are shared between storefronts.
     *
     * @return bool
     */
    protected function areUsersShared()
    {
        return fn_allowed_for('ULTIMATE') && Registry::get('settings.Stores.share_users') == 'Y';
    }

    /**
     * Checks whether user belongs to a company.
     *
     * @param int $company_id Company identifier of a user
     *
     * @return array API response data to return if user doesn't belong to a company or an empty array if belongs
     */
    protected function getUserRelationError($company_id = null)
    {
        if ($company_id == null && isset($this->auth['company_id'])) {
            $company_id = $this->auth['company_id'];
        }

        if (fn_allowed_for('ULTIMATE')
            && $company_id
            && $company_id != $this->getCompanyId()
            && !$this->areUsersShared()
        ) {
            return [
                'status' => Response::STATUS_FORBIDDEN,
                'data'   => [
                    'message' => __('api_wrong_user_company_relation'),
                ],
            ];
        }

        return [];
    }

    /**
     * Gathers additional information for products' options.
     *
     * @param array  $cart_products $cart['products'] from calculated cart
     * @param array  $products      Products returned from \fn_calculate_cart_content()
     * @param string $lang_code     Two-letter language code
     *
     * @return array $cart['products'] with options attached
     */
    protected function getDetailedOptions(array $cart_products, array $products, $lang_code)
    {
        fn_gather_additional_products_data($products, [
            'get_options' => true,
        ], $lang_code);

        foreach ($products as $cart_id => $product_data) {
            if (empty($product_data['product_options'])) {
                continue;
            }

            foreach ($product_data['product_options'] as $option_data) {
                if (!isset($cart_products[$cart_id]['product_options_detailed'])) {
                    $cart_products[$cart_id]['product_options_detailed'] = [];
                }

                $cart_products[$cart_id]['product_options_detailed'][$option_data['option_id']] = $option_data;
            }
        }

        return $cart_products;
    }

    /**
     * Updates customer's profile.
     *
     * @param array $user_data User data
     *
     * @return int User identifier
     */
    private function updateUserData(array $user_data)
    {
        unset($user_data['profile_id'], $user_data['user_id']);

        list(,$profile_id) = fn_update_user($this->auth['user_id'], $user_data, $this->auth, false, false);

        return $profile_id ? $profile_id : false;
    }

    /**
     * Provides default location from the store settings.
     *
     * @param string $lang_code Two-letter language code
     *
     * @return array Default address
     */
    protected function getDefaultLocation($lang_code = DEFAULT_LANGUAGE)
    {
        $general_settings = Registry::get('settings.General');

        $default_location = [];
        foreach ($general_settings as $field => $value) {
            if (strpos($field, 'default_') === 0) {
                $default_location[str_replace('default_', '', $field)] = $value;
            }
        }

        if ($default_location['country']) {
            $default_location['country_descr'] = fn_get_country_name(
                $default_location['country'],
                $lang_code
            );
        }

        if ($default_location['state']) {
            $default_location['state_descr'] = fn_get_state_name(
                $default_location['state'],
                $default_location['country'],
                $lang_code
            );
            if (!$default_location['state_descr']) {
                $default_location['state_descr'] = $default_location['state'];
            }
        }

        return $default_location;
    }

    /**
     * Merges product properties into the products stored in the cart.
     *
     * @param array $cart_products
     * @param array $products
     *
     * @return array
     */
    protected function mergeProductsDataIntoCart(array $cart_products, array $products)
    {
        foreach ($products as $cart_id => $product) {
            if (isset($cart_products[$cart_id])) {
                /**
                 * @todo: FIXME: Options in products and cart products are stored differently and processed separately.
                 * @see \Tygh\Api\Entities\v40\SraCartContent::getDetailedOptions
                 */
                unset($product['product_options']);
                $cart_products[$cart_id] = fn_array_merge($cart_products[$cart_id], $product);
            }
        }

        return $cart_products;
    }
}
