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

namespace Tygh\Addons\ProductVariations;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tygh\Addons\ProductVariations\HookHandlers\AttachmentsHookHandler;
use Tygh\Addons\ProductVariations\HookHandlers\BlockManagerHookHandler;
use Tygh\Addons\ProductVariations\HookHandlers\DiscussionsHookHandler;
use Tygh\Addons\ProductVariations\HookHandlers\ProductsHookHandler;
use Tygh\Addons\ProductVariations\HookHandlers\CartsHookHandler;
use Tygh\Addons\ProductVariations\HookHandlers\SeoHookHandler;
use Tygh\Addons\ProductVariations\Product\ProductIdMap;
use Tygh\Addons\ProductVariations\Product\Repository;
use Tygh\Addons\ProductVariations\Product\Sync\ProductDataIdentityMapRepository;
use Tygh\Addons\ProductVariations\Product\Group\GroupCodeGenerator;
use Tygh\Addons\ProductVariations\Product\Group\Repository as ProductGroupRepository;
use Tygh\Addons\ProductVariations\Product\Repository as ProductRepository;
use Tygh\Addons\ProductVariations\Product\Type\TypeCollection;
use Tygh\Addons\ProductVariations\Tools\QueryFactory;
use Tygh\Tools\SecurityHelper;
use Tygh\Registry;
use Tygh\Tygh;

/**
 * Class ServiceProvider is intended to register services and components of the "Product variations" add-on to the application
 * container.
 *
 * @package Tygh\Addons\ProductVariations
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app['addons.product_variations.product.group.repository'] = function (Container $app) {
            return new ProductGroupRepository(self::getQueryFactory(), DEFAULT_LANGUAGE);
        };

        $app['addons.product_variations.product.group.code_generator'] = function (Container $app) {
            return new GroupCodeGenerator(
                self::getGroupRepository(),
                new SecurityHelper()
            );
        };

        $app['addons.product_variations.product.repository'] = function (Container $app) {
            return new ProductRepository(
                self::getGroupRepository(),
                self::getQueryFactory(),
                AREA,
                CART_LANGUAGE,
                array_keys($app['languages'])
            );
        };

        $app['addons.product_variations.sync_service'] = function (Container $app) {
            return new SyncService(
                self::getProductIdMap(),
                function () { return (array) fn_get_schema('product_variations', 'product_data_sync'); },
                function () { return (array) fn_get_schema('product_variations', 'product_data_copy'); }
            );
        };

        $app['addons.product_variations.service'] = function (Container $app) {
            return new Service(
                self::getGroupRepository(),
                self::getGroupCodeGenerator(),
                self::getProductRepository(),
                self::getDataIdentityMapRepository(),
                self::getSyncService(),
                self::getProductIdMap(),
                fn_allowed_for('MULTIVENDOR'),
                Registry::get('settings.General.inventory_tracking') === 'Y',
                Registry::get('addons.product_variations.variations_allow_auto_change_default_variation') === 'Y'
            );
        };

        $app['addons.product_variations.product.sync.product_data_identity_map_repository'] = function (Container $app) {
            return new ProductDataIdentityMapRepository(self::getQueryFactory());
        };

        $app['addons.product_variations.tools.query_factory'] = function (Container $app) {
            return new QueryFactory($app['db']);
        };

        $app['addons.product_variations.product.product_id_map'] = function (Container $app) {
            return new ProductIdMap(self::getGroupRepository());
        };

        $app['addons.product_variations.product.type.type_collection'] = function(Container $app) {
            return new TypeCollection((array) fn_get_schema('product_variations', 'product_types'));
        };

        $app['addons.product_variations.hook_handlers.products'] = function (Container $app) {
            return new ProductsHookHandler($app);
        };

        $app['addons.product_variations.hook_handlers.carts'] = function (Container $app) {
            return new CartsHookHandler($app);
        };

        $app['addons.product_variations.hook_handlers.seo'] = function (Container $app) {
            return new SeoHookHandler($app);
        };

        $app['addons.product_variations.hook_handlers.block_manager'] = function (Container $app) {
            return new BlockManagerHookHandler($app);
        };

        $app['addons.product_variations.hook_handlers.attachments'] = function (Container $app) {
            return new AttachmentsHookHandler($app);
        };

        $app['addons.product_variations.hook_handlers.discussions'] = function (Container $app) {
            return new DiscussionsHookHandler($app);
        };
    }

    /**
     * @return Service
     */
    public static function getService()
    {
        return Tygh::$app['addons.product_variations.service'];
    }

    /**
     * @return \Tygh\Addons\ProductVariations\SyncService
     */
    public static function getSyncService()
    {
        return Tygh::$app['addons.product_variations.sync_service'];
    }

    /**
     * @return \Tygh\Addons\ProductVariations\Product\Repository
     */
    public static function getProductRepository()
    {
        return Tygh::$app['addons.product_variations.product.repository'];
    }

    /**
     * @return ProductGroupRepository
     */
    public static function getGroupRepository()
    {
        return Tygh::$app['addons.product_variations.product.group.repository'];
    }

    /**
     * @return GroupCodeGenerator
     */
    public static function getGroupCodeGenerator()
    {
        return Tygh::$app['addons.product_variations.product.group.code_generator'];
    }

    /**
     * @return ProductDataIdentityMapRepository
     */
    public static function getDataIdentityMapRepository()
    {
        return Tygh::$app['addons.product_variations.product.sync.product_data_identity_map_repository'];
    }

    /**
     * @return QueryFactory
     */
    public static function getQueryFactory()
    {
        return Tygh::$app['addons.product_variations.tools.query_factory'];
    }

    /**
     * @return \Tygh\Addons\ProductVariations\Product\ProductIdMap
     */
    public static function getProductIdMap()
    {
        return Tygh::$app['addons.product_variations.product.product_id_map'];
    }

    /**
     * @return TypeCollection
     */
    public static function getTypeCollection()
    {
        return Tygh::$app['addons.product_variations.product.type.type_collection'];
    }

    /**
     * @return bool
     */
    public static function isAllowOwnImages()
    {
        return Registry::get('addons.product_variations.variations_allow_own_images') === 'Y';
    }

    /**
     * @return bool
     */
    public static function isAllowOwnFeatures()
    {
        return Registry::get('addons.product_variations.variations_allow_own_features') === 'Y';
    }

    /**
     * @return bool
     * @internal
     */
    public static function areOldProductVariationsExists()
    {
        $product_columns = fn_get_table_fields('products');
        $product_columns = array_combine($product_columns, $product_columns);
        $required_columns = ['__variation_code', '__variation_options', '__is_default_variation'];

        foreach ($required_columns as $column) {
            if (!isset($product_columns[$column])) {
                return false;
            }
        }

        $query = self::getQueryFactory()->createQuery(Repository::TABLE_PRODUCTS, [
            'product_type' => 'C'
        ]);

        $query
            ->addCondition('__variation_options IS NOT NULL')
            ->setLimit(1)
            ->setFields(['product_id']);

        return (bool) $query->scalar();
    }

    /**
     * @internal
     */
    public static function notifyIfOldProductVariationsExists()
    {
        static $exists;

        if ($exists === null) {
            $exists = self::areOldProductVariationsExists();
        }

        if (!$exists || !fn_check_permissions('product_variations_converter', 'process', 'admin', 'POST')) {
            return;
        }

        fn_set_notification(
            'W',
            __('warning'),
            __('product_variations.notice.old_product_variations_exists', [
                '[convert_url]' => fn_url('product_variations_converter.process?by_combinations=0&by_variations=1&switch_company_id=0'),
            ]),
            'S',
            'old_product_variations_exists'
        );
    }

    /**
     * @param array $product_data
     *
     * @internal
     */
    public static function notifyIfProductIsOldProductVariation(array $product_data)
    {
        $product_type = isset($product_data['product_type']) ? $product_data['product_type'] : null;

        if (empty($product_data['__variation_options']) || !in_array($product_type, ['C', 'V'])) {
            return;
        }

        if (!fn_check_permissions('product_variations_converter', 'process', 'admin', 'POST')) {
            return;
        }

        fn_set_notification(
            'W',
            __('warning'),
            __('product_variations.notice.is_old_product_variation', [
                '[convert_url]' => fn_url('product_variations_converter.process?by_combinations=0&by_variations=1&switch_company_id=0'),
            ]),
            'S',
            'is_old_product_variation'
        );
    }
}
