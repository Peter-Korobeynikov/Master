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

use Tygh\Addons\AdvancedImport\Exceptions\DownloadException;
use Tygh\Addons\AdvancedImport\Exceptions\FileNotFoundException;
use Tygh\Addons\AdvancedImport\Exceptions\ReaderNotFoundException;
use Tygh\Enum\Addons\AdvancedImport\ImportStatuses;
use Tygh\Exceptions\PermissionsException;
use Tygh\Registry;
use Tygh\Http;

defined('BOOTSTRAP') or die('Access denied');

/** @var string $mode */

/** @var \Tygh\Addons\AdvancedImport\Presets\Manager $presets_manager */
$presets_manager = Tygh::$app['addons.advanced_import.presets.manager'];
/** @var \Tygh\Addons\AdvancedImport\Presets\Importer $presets_importer */
$presets_importer = Tygh::$app['addons.advanced_import.presets.importer'];

ini_set('auto_detect_line_endings', true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_REQUEST = array_merge(array(
        'preset_id' => 0,
        'fields'    => array(),
    ), $_REQUEST);

    $redirect_url = 'import_presets.manage';

    if ($mode == 'import') {

        list($presets,) = $presets_manager->find(false, array('ip.preset_id' => $_REQUEST['preset_id']), false);

        if ($presets) {
            Registry::set('runtime.advanced_import.in_progress', true, true);

            $preset = reset($presets);

            /** @var \Tygh\Addons\AdvancedImport\Readers\Factory $reader_factory */
            $reader_factory = Tygh::$app['addons.advanced_import.readers.factory'];

            $is_success = false;
            try {
                $reader = $reader_factory->get($preset);
                if (!empty($_REQUEST['fields'])) {
                    $fields_mapping = array_combine(
                        array_column($_REQUEST['fields'], 'name'),
                        $_REQUEST['fields']
                    );
                } else {
                    $fields_mapping = $presets_manager->getFieldsMapping($preset['preset_id']);
                }

                $pattern = $presets_manager->getPattern($preset['object_type']);
                $schema = $reader->getSchema();
                $schema->showNotifications();
                $schema = $schema->getData();

                $remapping_schema = $presets_importer->getEximSchema(
                    $schema,
                    $fields_mapping,
                    $pattern
                );

                if ($remapping_schema) {
                    $presets_importer->setPattern($pattern);
                    $result = $reader->getContents(null, $schema);
                    $result->showNotifications();

                    $import_items = $presets_importer->prepareImportItems(
                        $result->getData(),
                        $fields_mapping,
                        $preset['object_type'],
                        true,
                        $remapping_schema
                    );

                    $presets_manager->update($preset['preset_id'], array(
                        'last_launch' => TIME,
                        'last_status' => ImportStatuses::IN_PROGRESS,
                    ));

                    $preset['options']['preset'] = $preset;
                    unset($preset['options']['preset']['options']);

                    // Sets execution timeout for files getting from remote server
                    Http::setDefaultTimeout(ADVANCED_IMPORT_HTTP_EXECUTION_TIMEOUT);

                    $is_success = fn_import($pattern, $import_items, $preset['options']);
                }
            } catch (ReaderNotFoundException $e) {
                fn_set_notification('E', __('error'), __('error_exim_cant_read_file'));
            } catch (PermissionsException $e) {
                fn_set_notification('E', __('error'), __('advanced_import.cant_load_file_for_company'));
            } catch (FileNotFoundException $e) {
                fn_set_notification('E', __('error'), __('advanced_import.cant_load_file_for_company'));
            } catch (DownloadException $e) {
                fn_set_notification('E', __('error'), __('advanced_import.cant_load_file'));
            }

            $presets_manager->update($preset['preset_id'], array(
                'last_status' => $is_success
                    ? ImportStatuses::SUCCESS
                    : ImportStatuses::FAIL,
                'last_result' => Registry::get('runtime.advanced_import.result'),
            ));

            Registry::set('runtime.advanced_import.in_progress', false, true);

            $redirect_url = 'import_presets.manage?object_type=' . $preset['object_type'];

            if (!empty($_REQUEST['return_url'])) {
                $redirect_url = $_REQUEST['return_url'];
            }
        } else {
            fn_set_notification('E', __('error'), __('advanced_import.preset_not_found'));
        }
    }

    if (defined('AJAX_REQUEST')) {
        Tygh::$app['ajax']->assign('non_ajax_notifications', true);
        Tygh::$app['ajax']->assign('force_redirection', fn_url($redirect_url));
        exit;
    }

    return array(CONTROLLER_STATUS_OK, $redirect_url);
}

if ($mode == 'modifiers_list') {
    /** @var \Tygh\Addons\AdvancedImport\SchemasManager $schema_manager */
    $schema_manager = Tygh::$app['addons.advanced_import.schemas_manager'];
    $modifiers = $schema_manager->getModifiers();

    Tygh::$app['view']->assign(array(
        'modifiers' => $modifiers['operations'],
    ));
}
