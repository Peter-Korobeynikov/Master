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

namespace Tygh\Addons\Warehouses;

use Tygh\Database\Connection;

class Manager
{
    const STORE_LOCATOR_TYPE_WAREHOUSE = 'W';
    const STORE_LOCATOR_TYPE_STORE = 'S';
    const STORE_LOCATOR_TYPE_PICKUP = 'P';

    /** @var Connection */
    protected $db;

    /** @var string */
    protected $language_code;

    public function __construct(Connection $db, $language_code)
    {
        $this->db = $db;
        $this->language_code = $language_code;
    }

    /**
     * Fetches available warehouses
     *
     * @param int|null $company_id Company identifier
     *
     * @return mixed
     */
    public function getWarehouses($company_id = null)
    {
        $params = ['store_types' => [self::STORE_LOCATOR_TYPE_WAREHOUSE, self::STORE_LOCATOR_TYPE_STORE]];
        if ($company_id !== null) {
            $params['company_id'] = $company_id;
        }

        list($warehouses) = fn_get_store_locations($params, 0, $this->language_code);
        foreach ($warehouses as &$warehouse) {
            $warehouse['warehouse_id'] = $warehouse['store_location_id'];
        }

        return $warehouses;
    }

    /**
     * Creates product store manager
     *
     * @param int   $product_id         Product identifier
     *
     * @return \Tygh\Addons\Warehouses\ProductStock
     */
    public function getProductWarehousesStock($product_id)
    {
        $product_warehouses = $this->getProductWarehousesData($product_id);
        return new ProductStock($product_id, $product_warehouses);
    }

    /**
     * Creates product store manager based on prowided warehouses stock data
     *
     * @param int   $product_id         Product identifier
     * @param array $warehouses_amounts Product warehouses amount values
     *
     * @return \Tygh\Addons\Warehouses\ProductStock
     */
    public function createProductStockFromWarehousesData($product_id, $warehouses_amounts)
    {
        $valid_warehouse_amounts = array_filter($warehouses_amounts, function ($warehouse) {
            return is_numeric($warehouse['amount']);
        });

        $warehouse_ids = array_column($valid_warehouse_amounts, 'warehouse_id');
        if ($warehouse_ids) {
            $warehouses = $this->db->getHash(
                'SELECT store_location_id AS warehouse_id, store_type, position, main_destination_id, pickup_destinations_ids, shipping_destinations_ids, status'
                . ' FROM ?:store_locations'
                . ' WHERE store_location_id IN (?n)',
                'warehouse_id',
                $warehouse_ids
            );

            $product_warehouses = [];
            foreach ($valid_warehouse_amounts as $warehouse) {
                $warehouse_id = $warehouse['warehouse_id'];
                $product_warehouses[] = array_merge($warehouses[$warehouse_id], $warehouse);
            }
        } else {
            $product_warehouses = [];
        }

        $product_warehouses = $this->initializeDestinations($product_warehouses);

        return new ProductStock($product_id, $product_warehouses);
    }

    /**
     * Fetches product warehouses amounts for group of products
     *
     * @param array $products Products
     *
     * @return array
     */
    public function fetchProductsWarehousesAmounts($products)
    {
        if (empty($products)) {
            return $products;
        }

        $product_ids = array_column($products, 'product_id');
        $products_warehouses_amounts = $this->db->getHash(
            'SELECT product_id, SUM(amount) as amount FROM ?:warehouses_products_amount WHERE product_id IN (?n) GROUP BY product_id',
            'product_id',
            $product_ids
        );

        foreach ($products as &$product) {
            $product_id = $product['product_id'];
            if (isset($products_warehouses_amounts[$product_id])) {
                $product['warehouse_amount'] = $products_warehouses_amounts[$product_id]['amount'];
            }
        }

        return $products;
    }

    /**
     * Saves product stock data
     *
     * @param \Tygh\Addons\Warehouses\ProductStock $product_stock Product stock
     *
     * @return mixed
     * @throws \Tygh\Exceptions\DatabaseException
     * @throws \Tygh\Exceptions\DeveloperException
     */
    public function saveProductStock(ProductStock $product_stock)
    {
        $product_id = $product_stock->getProductId();
        $this->db->query('DELETE FROM ?:warehouses_products_amount WHERE product_id = ?i', $product_id);

        $warehouses_amounts = $product_stock->getStockAsArray();
        if (empty($warehouses_amounts)) {
            return false;
        }

        return $this->db->query('INSERT INTO ?:warehouses_products_amount ?m', $warehouses_amounts);
    }

    /**
     * Fetches product warehouses amounts
     *
     * @param int $product_id Product identifier
     *
     * @return array
     */
    protected function getProductWarehousesData($product_id)
    {
        $product_warehouses = $this->db->getArray(
            'SELECT warehouse_id, amount, store_type, position, main_destination_id, pickup_destinations_ids, shipping_destinations_ids, status'
            . ' FROM ?:warehouses_products_amount AS wpa'
            . ' LEFT JOIN ?:store_locations AS sl ON wpa.warehouse_id = sl.store_location_id'
            . ' WHERE product_id = ?i'
            . ' ORDER BY sl.position ASC',
            $product_id
        );

        $product_warehouses = $this->initializeDestinations($product_warehouses);

        return $product_warehouses;
    }

    protected function initializeDestinations(array $product_warehouses)
    {
        if (!$product_warehouses) {
            return [];
        }

        $destinations = $this->db->getMultiHash(
            'SELECT destination_links.*, shipping_delays.*'
            . ' FROM ?:store_location_destination_links AS destination_links'
            . ' LEFT JOIN ?:store_location_shipping_delays AS shipping_delays'
            . ' ON shipping_delays.store_location_id = destination_links.store_location_id'
            . ' AND shipping_delays.destination_id = destination_links.destination_id'
            . ' AND shipping_delays.lang_code = ?s'
            . ' WHERE destination_links.store_location_id IN (?n)',
            ['store_location_id', 'destination_id'],
            $this->language_code,
            array_column($product_warehouses, 'warehouse_id')
        );

        foreach ($product_warehouses as &$warehouse) {
            $warehouse['destinations'] = [];
            if (!empty($destinations[$warehouse['warehouse_id']])) {
                $warehouse['destinations'] = $destinations[$warehouse['warehouse_id']];
            }
        }
        unset($warehouse);

        return $product_warehouses;
    }

    /**
     * @param int $warehouse_id
     *
     * @return \Tygh\Addons\Warehouses\Destination[]
     */
    public function initializeDestinationsByWarehouseId($warehouse_id)
    {
        $warehouses = $this->initializeDestinations(
            [
                [
                    'warehouse_id' => $warehouse_id
                ],
            ]
        );
        if (!$warehouses) {
            return [];
        }

        $warehouse = reset($warehouses);

        foreach ($warehouse['destinations'] as &$destination) {
            $destination = new Destination($destination);
        }
        unset($destination);

        return $warehouse['destinations'];
    }

    public function removeWarehouse($warehouse_id)
    {
        $this->db->query(
            'DELETE FROM ?:warehouses_products_amount WHERE ?w',
            [
                'warehouse_id' => $warehouse_id,
            ]
        );

        $this->db->query(
            'DELETE FROM ?:store_location_destination_links WHERE ?w',
            [
                'store_location_id' => $warehouse_id,
            ]
        );

        $this->db->query(
            'DELETE FROM ?:store_location_shipping_delays WHERE ?w',
            [
                'store_location_id' => $warehouse_id,
            ]
        );
    }
}
