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

namespace Tygh\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tygh\BlockManager\Layout;
use Tygh\Common\Robots;
use Tygh\Embedded;
use Tygh\Registry;
use Tygh\Storefront\RelationsManager;
use Tygh\Storefront\Factory;
use Tygh\Storefront\Normalizer;
use Tygh\Storefront\Repository;

class StorefrontProvider implements ServiceProviderInterface
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $url;

    /**
     * StorefrontProvider constructor.
     *
     * @param string $url
     * @param array  $request
     */
    public function __construct($url, array $request = [])
    {
        $this->url = $url;
        $this->params = $request;
    }

    /**
     * @inheritdoc
     */
    public function register(Container $app)
    {
        $app['storefront.repository'] = function (Container $app) {
            return new Repository(
                $app['db'],
                $app['storefront.factory'],
                $app['storefront.normalizer'],
                $app['storefront.relations_manager'],
                new Robots()
            );
        };

        $app['storefront.repository.init'] = function (Container $app) {
            return new Repository(
                $app['db'],
                $app['storefront.factory.init'],
                $app['storefront.normalizer'],
                $app['storefront.relations_manager.init'],
                new Robots()
            );
        };

        $app['storefront.factory'] = function (Container $app) {
            return new Factory(
                $app['db'],
                $app['storefront.relations_manager'],
                $app['storefront.normalizer']
            );
        };

        $app['storefront.factory.init'] = function (Container $app) {
            return new Factory(
                $app['db'],
                $app['storefront.relations_manager.init'],
                $app['storefront.normalizer']
            );
        };

        $app['storefront.relations_schema'] = function (Container $app) {
            $schema = fn_get_schema('storefronts', 'relations');

            return $schema;
        };

        $app['storefront.relations_schema.init'] = function (Container $app) {
            $schema = [
                'country_codes' => [
                    'table'               => '?:storefronts_countries',
                    'table_alias'         => 'countries',
                    'type'                => 'value',
                    'id_field'            => 'country_code',
                    'storefront_id_field' => 'storefront_id',
                ],
                'company_ids'   => [
                    'table'               => '?:storefronts_companies',
                    'table_alias'         => 'companies',
                    'type'                => 'value',
                    'id_field'            => 'company_id',
                    'storefront_id_field' => 'storefront_id',
                ],
            ];

            return $schema;
        };

        $app['storefront.relations_manager'] = function (Container $app) {
            return new RelationsManager(
                $app['db'],
                $app['storefront.relation_name_resolver'],
                $app['storefront.relations_schema']
            );
        };

        $app['storefront.relations_manager.init'] = function (Container $app) {
            return new RelationsManager(
                $app['db'],
                $app['storefront.relation_name_resolver'],
                $app['storefront.relations_schema.init']
            );
        };

        $app['storefront.normalizer'] = function (Container $app) {
            return new Normalizer();
        };

        $app['storefront.relation_name_resolver'] = function (Container $app) {
            return 'fn_uncamelize';
        };

        $app['storefront'] = function (Container $app) {
            /** @var \Tygh\Storefront\Repository $repository */
            $repository = $app['storefront.repository'];
            $storefront_id = $storefront = null;
            $is_storefront_stored = false;
            $runtime_company_id = fn_get_runtime_company_id();

            if (defined('CONSOLE') && isset($this->params['switch_storefront_id'])) {
                $storefront_id = (int) $this->params['switch_storefront_id'];
            } elseif (isset($this->params['s_storefront'])) {
                $storefront_id = (int) $this->params['s_storefront'];
                $is_storefront_stored = true;
            }

            $embedded_suffix = Embedded::isEnabled()
                ? '_embedded'
                : '';
            $key_name = 'stored_storefront' . $embedded_suffix;
            $storefront_data = fn_get_session_data($key_name);

            if (is_array($storefront_data)) {
                $storefront = $repository->findById($storefront_data['storefront_id']);
                if (!$storefront) {
                    fn_set_session_data($key_name, $storefront);
                }
            }

            if ($storefront_id) {
                $storefront = $repository->findById($storefront_id);
            }

            if ($runtime_company_id
                && (!$storefront
                    || $storefront->getCompanyIds()
                    && !in_array($runtime_company_id, $storefront->getCompanyIds())
                )
            ) {
                $storefront = $repository->findByCompanyId($runtime_company_id);
            }

            if (!$storefront) {
                $storefront = $repository->findByUrl($this->url);
            }
            if (!$storefront) {
                $storefront = $repository->findDefault();
            }

            if ($is_storefront_stored) {
                fn_set_session_data($key_name, $storefront->toArray());
            }

            return $storefront;
        };

        $app['storefront.switcher.selected_storefront_id'] = function (Container $app) {
            $is_storefront_stored = isset($this->params['s_storefront']);
            $runtime_company_id = fn_get_runtime_company_id();
            $storefront_id = 0;

            if ($runtime_company_id) {
                /** @var \Tygh\Storefront\Repository $repository */
                $repository = $app['storefront.repository'];

                $storefront = $repository->findByCompanyId($runtime_company_id);

                if ($storefront) {
                    $storefront_id = $storefront->storefront_id;
                }
            }

            if (isset($this->params['s_storefront'])) {
                $storefront_id = $this->params['s_storefront'];
            }

            return $storefront_id;
        };

        $app['storefront.switcher.dispatches_schema'] = function () {
            return fn_get_schema('storefronts', 'switcher_dispatches');
        };

        $app['storefront.switcher.is_available_for_dispatch'] = function (Container $app) {
            $controller = Registry::get('runtime.controller');
            $mode = Registry::get('runtime.mode');
            $action = Registry::get('runtime.action');

            $dispatches = [
                sprintf('%s.%s.%s', $controller, $mode, $action),
                sprintf('%s.%s', $controller, $mode),
                $controller
            ];

            $schema = $app['storefront.switcher.dispatches_schema'];

            foreach ($dispatches as $dispatch) {
                if (isset($schema[$dispatch])) {
                    $value = $schema[$dispatch];

                    if (is_callable($value)) {
                        $value = call_user_func($value, $this->params);
                    }

                    return $value;
                }
            }

            return true;
        };

        $app['storefront.switcher.is_enabled'] = function (Container $app) {
            return fn_check_change_storefront_permission()
                && (fn_allowed_for('ULTIMATE') || fn_allowed_for('MULTIVENDOR:ULTIMATE'));
        };

        $app['storefront.switcher.preset_data.factory'] = function (Container $app) {
            return function ($current_storefront_id, $storefronts_threshold = 3) use ($app) {
                $is_ultimate = fn_allowed_for('ULTIMATE');
                $result = [
                    'storefronts' => [],
                    'count'       => 0,
                    'threshold'   => $storefronts_threshold
                ];

                /** @var \Tygh\Storefront\Repository $repository */
                $repository = $app['storefront.repository'];

                /** @var \Tygh\Storefront\Storefront[] $storefronts */
                list($storefronts) = $repository->find();

                /** @var \Tygh\Storefront\Storefront[] $visible_storefronts */
                $visible_storefronts = array_slice($storefronts, 0, $storefronts_threshold, true);

                if (!isset($visible_storefronts[$current_storefront_id]) && isset($storefronts[$current_storefront_id])) {
                    array_pop($visible_storefronts);
                    $visible_storefronts[$current_storefront_id] = $storefronts[$current_storefront_id];
                }

                foreach ($visible_storefronts as $storefront) {
                    $storefront_id = $storefront->storefront_id;
                    $company_id = 0;

                    if ($is_ultimate) {
                        $storefront_company_ids = $storefront->getCompanyIds();
                        $company_id = reset($storefront_company_ids);
                    }

                    $layout = Layout::instance($company_id, [], $storefront->storefront_id)->getDefault($storefront->theme_name);
                    $layout_id = isset($layout['layout_id']) ? $layout['layout_id'] : null;
                    $style_id = isset($layout['style_id']) ? $layout['style_id'] : null;

                    $storefront_logos = fn_get_logos($company_id, $layout_id, $style_id, $storefront->storefront_id);

                    $result['storefronts'][] = [
                        'storefront_id' => $storefront_id,
                        'company_id'    => $company_id,
                        'name'          => $storefront->name,
                        'is_default'    => $storefront->is_default,
                        'is_selected'   => $storefront_id == $current_storefront_id,
                        'status'        => $storefront->status,
                        'images'        => !empty($storefront_logos['theme']['image']) ? $storefront_logos['theme']['image'] : ''
                    ];
                }

                $result['count'] = count($storefronts);

                return $result;
            };
        };
    }
}
