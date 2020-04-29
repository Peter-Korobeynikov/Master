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

namespace Tygh\Addons\AdvancedImport\Presets;

use Tygh\Addons\AdvancedImport\SchemasManager;
use Tygh\Database\Connection;
use Tygh\Enum\Addons\AdvancedImport\RelatedObjectTypes;
use Tygh\Languages\Languages;

class Manager
{
    /** @var \Tygh\Database\Connection $db */
    protected $db;

    /** @var int $company_id */
    protected $company_id;

    /** @var int $default_items_per_page */
    protected $default_items_per_page;

    /** @var string $lang_code */
    protected $lang_code;

    /** @var SchemasManager $schemas_manager */
    protected $schemas_manager;

    /**
     * Manager constructor.
     *
     * @param \Tygh\Database\Connection $db              Database connection instance
     * @param int                       $company_id      Runtime company ID
     * @param int                       $default_limit   Default limit for the pagination
     * @param string                    $lang_code       Two-letter language code
     * @param SchemasManager            $schemas_manager Schemas manager instance
     */
    public function __construct(
        Connection $db,
        $company_id,
        $default_limit,
        $lang_code,
        SchemasManager $schemas_manager
    ) {
        $this->db = $db;
        $this->company_id = $company_id;
        $this->default_items_per_page = $default_limit;
        $this->lang_code = $lang_code;
        $this->schemas_manager = $schemas_manager;
    }

    /**
     * Adds import preset.
     *
     * @param array $data Preset data
     *
     * @return int Preset ID
     */
    public function add(array $data)
    {
        $data = $this->encodeOptions($data);
        unset($data['preset_id']);

        if ($this->company_id) {
            $data['company_id'] = $this->company_id;
        }

        $data = $this->correctFilePath($data);

        $id = $this->db->query('INSERT INTO ?:import_presets ?e', $data);
        $data['preset_id'] = $id;

        $this->updateDescription($id, $data, array_keys(Languages::getAll()));

        if (isset($data['fields'])) {
            $this->updateFieldsMapping($id, $data['fields']);
        }

        return $id;
    }

    /**
     * Encodes preset options to store in the database.
     *
     * @param array $data Preset data
     *
     * @return array Preset data with options encoded
     */
    public function encodeOptions(array $data)
    {
        if (!isset($data['options'])) {
            return $data;
        }

        $pattern = $this->getPattern($data['object_type']);
        if (isset($pattern['options'])) {
            foreach ($pattern['options'] as $option_id => $option_data) {
                if (isset($option_data['option_data_pre_modifier'])) {
                    foreach ($option_data['option_data_pre_modifier'] as $pre_save_modifier) {
                        $initial_value = isset($data['options'][$option_id])
                            ? $data['options'][$option_id]
                            : null;

                        $data['options'][$option_id] = call_user_func(
                            $pre_save_modifier,
                            $option_id,
                            $option_data,
                            $pattern['options'],
                            $data['options'],
                            $initial_value
                        );
                    }
                }
            }
        }

        $data['options'] = json_encode($data['options']);

        return $data;
    }

    /**
     * Updates preset description.
     *
     * @param int   $id              Preset ID
     * @param array $data            Preset data
     * @param array $lang_codes_list Languages to update for
     */
    protected function updateDescription($id, array $data, array $lang_codes_list)
    {
        foreach ($lang_codes_list as $lang_code) {
            $description = array_merge(array(
                'preset_id' => $id,
                'lang_code' => $lang_code,
            ), $data);
            $this->db->replaceInto('import_preset_descriptions', $description);
        }
    }

    /**
     * Updates preset fields mapping.
     *
     * @param int   $id          Preset ID
     * @param array $fields_list Fields list
     */
    protected function updateFieldsMapping($id, array $fields_list)
    {
        $this->db->query('DELETE FROM ?:import_preset_fields WHERE preset_id = ?i', $id);

        foreach ($fields_list as &$field) {
            $field['preset_id'] = $id;
        }
        unset($field);

        $this->db->query('INSERT INTO ?:import_preset_fields ?m', $fields_list);
    }

    /**
     * Provides list of presets
     *
     * @param array|false $limit     Array with 'page' and 'items_per_page' elements or false to skip limit at all
     * @param array       $condition Search conditions.
     *                               See \Tygh\Addons\AdvancedImport\Presets\Manager::buildConditionStatement for
     *                               reference
     * @param array|false $join      Additional tables to join.
     *                               See \Tygh\Addons\AdvancedImport\Presets\Manager::buildJoinStatement for reference
     * @param array       $fields    Fields to fetch.
     *                               See \Tygh\Addons\AdvancedImport\Presets\Manager::buildFieldsStatement for reference
     * @param array       $sorting   Sorting params.
     *                               See \Tygh\Addons\AdvancedImport\Presets\Manager::buildOrderStatement for reference
     *
     * @return array Array of items and array of pagination parameters
     */
    public function find(
        $limit = null,
        array $condition = array(),
        $join = null,
        array $fields = array('*'),
        array $sorting = array()
    ) {
        if ($join === null) {
            $join = array(
                array(
                    'table'     => array('?:import_preset_descriptions' => 'ipd'),
                    'condition' => array(
                        $this->db->quote('ip.preset_id = ipd.preset_id AND ipd.lang_code = ?s', $this->lang_code),
                    ),
                ),
            );
        }

        if ($limit === null) {
            $limit = array(
                'page'           => 1,
                'items_per_page' => $this->default_items_per_page,
            );
        }

        $fields[] = 'ip.preset_id';

        $fields_statement = $this->buildFieldsStatement($fields);

        $join_statement = '';
        if ($join !== false) {
            $join_statement = $this->buildJoinStatement($join);
        }

        $limit_statement = '';
        if ($limit !== false) {
            $limit_statement = $this->buildLimitStatement($limit);
        }

        $condition_statement = '1 = 1';
        if ($this->company_id) {
            $condition['ip.company_id'] = $this->company_id;
        }
        if ($condition) {
            $condition_statement = $this->buildConditionStatement($condition);
        }

        $group_condition = '';
        if ($join_statement) {
            $group_condition = 'GROUP BY ip.preset_id';
        }

        if (isset($sorting['sort_by'])) {
            list($order_statement, $sorting) = $this->buildOrderStatement($sorting);
        } else {
            $order_statement = '';
        }

        $presets_list = $this->db->getHash(
            'SELECT ?p FROM ?:import_presets AS ip ?p WHERE ?p ?p ?p ?p',
            'preset_id',
            $fields_statement,
            $join_statement,
            $condition_statement,
            $group_condition,
            $order_statement,
            $limit_statement
        );

        $total_items = $this->db->getField(
            'SELECT COUNT(*) FROM ?:import_presets AS ip ?p WHERE ?p',
            $join_statement,
            $condition_statement
        );

        $search = array(
            'page'           => (int) $limit['page'],
            'items_per_page' => (int) $limit['items_per_page'],
            'total_items'    => (int) $total_items,
        );

        if (!empty($sorting['sort_order_rev'])) {
            $search['sort_by']        = $sorting['sort_by'];
            $search['sort_order']     = $sorting['sort_order'];
            $search['sort_order_rev'] = $sorting['sort_order_rev'];
        }

        foreach ($presets_list as &$preset) {
            $preset = $this->decodeOptions($preset);
            $preset = $this->decodeResult($preset);
        }
        unset($preset);

        return array($presets_list, $search);
    }

    /**
     * Builds ORDER BY statement for SQL query.
     *
     * @param  array  $sorting Can contain 'sort_by' and/or 'sort_order' key/value pairs.
     *                         E.g.:
     *                         [
     *                              'sort_by' => 'last_import'|'name'|'status',
     *                              'sort_order' => 'asc'|'desc',
     *                         ]
     *                Default sorting is by name ascending.
     *
     * @return array (string, array)
     */
    public function buildOrderStatement(array $sorting = array())
    {
        $sortings = array(
            'last_import' => 'last_launch',
            'name'        => 'preset',
            'status'      => 'last_status',
        );

        $order_statement = db_sort($sorting, $sortings, 'name', 'asc');

        return array($order_statement, $sorting);
    }

    /**
     * Builds fields selection statement for SQL query.
     *
     * @param array $fields Must contain strings or arrays of 'field' => 'alias' values.
     *                      E.g.:
     *                      [
     *                          'ip.preset_id',
     *                          array('ipd.preset' => 'preset_name'),
     *                      ]
     *
     * @return string
     */
    protected function buildFieldsStatement(array $fields)
    {
        $fields_list = array();

        foreach ($fields as $name => $alias) {
            if (substr($alias, -1) == '*' || is_numeric($name)) {
                $fields_list[] = $alias;
            } else {
                $fields_list[] = $this->db->quote('?p AS ?f', $name, $alias);
            }
        }

        return implode(',', $fields_list);
    }

    /**
     * Builds JOIN statement for SQL query.
     *
     * @param array $join Must contain arrays with 'type', 'table' and 'condition' fields.
     *                    E.g.:
     *                    [
     *                        'type' => 'LEFT',
     *                        'table' => ('?:descriptions' => 'ipd'),
     *                        'conditions' => ('ipd.preset_id' => 42)
     *                    ]
     *
     * @return string
     */
    protected function buildJoinStatement(array $join)
    {
        $joins_list = array();

        foreach ($join as $table_join) {
            if (!isset($table_join['type'])) {
                $table_join['type'] = 'LEFT';
            }

            $alias = reset($table_join['table']);

            $table = key($table_join['table']);

            $condition = $this->buildConditionStatement($table_join['condition']);

            $joins_list[] = $this->db->quote(
                '?p JOIN ?p AS ?f ON ?p',
                $table_join['type'],
                $table,
                $alias,
                $condition
            );
        }

        return implode(' ', $joins_list);
    }

    /**
     * Builds WHERE statement for SQL query.
     *
     * @param array $condition Must contain a single string with the whole condition
     *                         or to be an array of values for ?w database placeholder.
     *
     * @return string
     */
    protected function buildConditionStatement(array $condition)
    {
        $first_condition = reset($condition);

        if (is_numeric(key($condition)) && is_string($first_condition)) {
            return $first_condition;
        }

        if (!$condition) {
            return '1 = 1';
        }

        $condition = $this->db->quote('?w', $condition);

        return $condition;
    }

    /**
     * Builds LIMIT statement for SQL query.
     *
     * @param array $limit Must contain 'items_per_page' and 'page' elements
     *
     * @return string
     */
    protected function buildLimitStatement(array $limit)
    {
        if ((int) $limit['items_per_page'] === 0) {
            return '';
        }

        return db_paginate($limit['page'], $limit['items_per_page']);
    }

    /**
     * Decodes preset options stored in the database.
     *
     * @param array $data Preset data
     *
     * @return array Preset data with options decoded
     */
    public function decodeOptions(array $data)
    {
        if (isset($data['options'])) {
            $data['options'] = json_decode($data['options'], true) ?: array();
        }

        return $data;
    }

    /**
     * Updates preset.
     *
     * @param int   $id   Preset ID
     * @param array $data Preset data
     *
     * @return bool
     */
    public function update($id, array $data)
    {
        $result = true;

        $data = $this->encodeOptions($data);
        $data = $this->encodeResult($data);
        unset($data['preset_id']);

        if ($this->company_id) {
            $data['company_id'] = $this->company_id;
        }

        $data = $this->correctFilePath($data);

        $this->db->query('UPDATE ?:import_presets SET ?u WHERE preset_id = ?i', $data, $id);

        $this->updateDescription($id, $data, array($this->lang_code));

        if (isset($data['fields'])) {
            $this->updateFieldsMapping($id, $data['fields']);
        }

        return $result;
    }

    /**
     * Deletes preset.
     *
     * @param int $id Preset ID
     *
     * @return bool
     */
    public function delete($id)
    {
        $result = true;

        $this->db->query('DELETE FROM ?:import_presets WHERE preset_id = ?i', $id);
        $this->db->query('DELETE FROM ?:import_preset_descriptions WHERE preset_id = ?i', $id);
        $this->db->query('DELETE FROM ?:import_preset_fields WHERE preset_id = ?i', $id);

        return $result;
    }

    /**
     * Merges import preset with default exim pattern.
     *
     * @param array $preset  Preset data
     * @param array $pattern Exim pattern
     *
     * @return array
     */
    public function mergePattern(array $preset, array $pattern)
    {
        if (empty($preset['options'])) {
            $preset['options'] = array();
        }

        foreach ($pattern['options'] as $option_id => $option_definition) {
            $option_definition['selected_value'] = isset($preset['options'][$option_id])
                ? $preset['options'][$option_id]
                : null;

            if (isset($option_definition['option_data_post_modifier']) && is_callable($option_definition['option_data_post_modifier'])) {
                $option_definition = $option_definition['option_data_post_modifier']($option_definition, $preset);
            }

            $preset['options'][$option_id] = $option_definition;
        }

        $preset['options'] = $this->sortByPosition($preset['options']);

        return $preset;
    }

    /**
     * Gets possible relations for a preset.
     *
     * @param string $object_type Preset type
     *
     * @return array
     */
    public function getRelations($object_type)
    {
        $pattern = $this->getPattern($object_type);

        foreach ($pattern['export_fields'] as $field_id => &$field) {
            $field['show_description'] = $field['show_name'] = true;
            $field['description'] = fn_exim_get_field_label($field_id);
        }
        unset($field);

        $relations = array(
            RelatedObjectTypes::PROPERTY => array(
                'description' => __('advanced_import.properties'),
                'fields'      => $pattern['export_fields'],
            ),
        );

        $relations_schema = $this->schemas_manager->getRelations();

        if (isset($relations_schema[$object_type])) {
            foreach ($relations_schema[$object_type] as $related_object_type => $relation) {
                $relations[$related_object_type] = array(
                    'description' => __($relation['description']),
                    'fields'      => call_user_func($relation['items_function'], $this, $relation),
                );
            }
        }

        return $relations;
    }

    /**
     * Gets exim pattern.
     *
     * @param string $object_type Preset type
     *
     * @return array|bool
     */
    public function getPattern($object_type)
    {
        $pattern = (array) fn_exim_get_pattern_definition($object_type, 'import', array('advanced_import' => true));
        $advanced_import = (array) $this->schemas_manager->getProducts();
        $pattern = array_replace_recursive($pattern, $advanced_import);
        unset($pattern['options']['lang_code']);

        return $pattern;
    }

    /**
     * Sorts array by position (if position is not set (NULL) then the element must be at the bottom)
     *
     * @param array $array Array to sort
     *
     * @return array
     */
    protected function sortByPosition(array $array)
    {
        uasort($array, function ($a, $b) {
            $a_pos = isset($a['position']) ? (int) $a['position'] : null;
            $b_pos = isset($b['position']) ? (int) $b['position'] : null;

            if ($a_pos === $b_pos) {
                return 0;
            } elseif ($a_pos !== null && $b_pos === null) {
                return -1;
            } elseif ($a_pos === null && $b_pos !== null) {
                return 1;
            }

            return ($a_pos < $b_pos) ? -1 : 1;
        });

        return $array;
    }

    /**
     * Gets preset manager language code.
     *
     * @return string
     */
    public function getLangCode()
    {
        return $this->lang_code;
    }

    /**
     * Gets fields mapping for a preset.
     *
     * @param int $id Preset ID
     *
     * @return array
     */
    public function getFieldsMapping($id)
    {
        $fields = $this->db->getHash(
            'SELECT * FROM ?:import_preset_fields WHERE preset_id = ?i',
            'name',
            $id
        );

        return $fields;
    }

    /**
     * Gets import preset name from database.
     *
     * @param  int    $id        Preset ID
     *
     * @return string            The preset field from ?:import_preset_descriptions or empty string
     */
    public function getName($id)
    {
        $result = '';
        $id = (int) $id;
        if (empty($id)) {
            return $result;
        }
        $result = $this->db->getField(
            'SELECT preset FROM ?:import_preset_descriptions WHERE preset_id = ?i AND lang_code = ?s',
            $id,
            $this->lang_code
        );

        return strval($result);
    }

    /**
     * Decodes import results stored in the database.
     *
     * @param array $data Preset data
     *
     * @return array Preset data with result decoded
     */
    protected function decodeResult(array $data)
    {
        if (isset($data['last_result'])) {
            $data['last_result'] = json_decode($data['last_result'], true) ?: array();
        }

        return $data;
    }

    /**
     * Encodes import result to store in the database.
     *
     * @param array $data Preset data
     *
     * @return array Preset data with result encoded
     */
    protected function encodeResult(array $data)
    {
        if (!isset($data['last_result'])) {
            return $data;
        }

        $data['last_result'] = json_encode($data['last_result']);

        return $data;
    }

    /**
     * Corrects path to an imported file for a company.
     *
     * @param array $data Preset data
     *
     * @return array Preset data with file path corrected
     */
    protected function correctFilePath(array $data)
    {
        if (!isset($data['file'])
            || !isset($data['company_id'])
            || !fn_string_not_empty($data['file'])
            || $this->company_id
        ) {
            return $data;
        }

        $data['file'] = preg_replace("!^{$data['company_id']}/!", '', $data['file']);

        return $data;
    }
}
