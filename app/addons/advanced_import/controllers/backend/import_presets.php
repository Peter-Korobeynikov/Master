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

defined('BOOTSTRAP') or die('Access denied');

use Tygh\Addons\AdvancedImport\Exceptions\DownloadException;
use Tygh\Addons\AdvancedImport\Exceptions\FileNotFoundException;
use Tygh\Addons\AdvancedImport\Exceptions\ReaderNotFoundException;
use Tygh\Enum\Addons\AdvancedImport\PresetFileTypes;
use Tygh\Exceptions\PermissionsException;
use Tygh\Registry;
use Tygh\Tools\Url;

/** @var string $mode */

/** @var \Tygh\Addons\AdvancedImport\Presets\Manager $presets_manager */
$presets_manager = Tygh::$app['addons.advanced_import.presets.manager'];
/** @var \Tygh\Addons\AdvancedImport\Presets\Importer $presets_importer */
$presets_importer = Tygh::$app['addons.advanced_import.presets.importer'];

ini_set('auto_detect_line_endings', true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /** @var \Tygh\Addons\AdvancedImport\Readers\Factory $file_uploader */
    $file_uploader = Tygh::$app['addons.advanced_import.readers.factory'];

    if ($mode == 'upload') {
        $file_types = !empty($_REQUEST['type_' . $file_uploader::UPLOADED_FILE_NAME]) ? $_REQUEST['type_' . $file_uploader::UPLOADED_FILE_NAME] : [];
        $files = !empty($_REQUEST['file_' . $file_uploader::UPLOADED_FILE_NAME]) ? $_REQUEST['file_' . $file_uploader::UPLOADED_FILE_NAME] : [];
        $preset_id = isset($_REQUEST['preset_id']) ? (int) $_REQUEST['preset_id'] : 0;

        $preset = [
            'preset_id' => $preset_id,
            'file_type' => isset($file_types[$preset_id]) ? $file_types[$preset_id] : PresetFileTypes::LOCAL,
            'file'      => isset($files[$preset_id]) ? $files[$preset_id] : ''
        ];

        if ($preset['preset_id']) {
            list($old_preset,) = $presets_manager->find(false, ['ip.preset_id' => $preset['preset_id']], false);

            $old_preset = $old_preset ? reset($old_preset) : null;
        } else {
            $old_preset = null;
        }

        if (empty($old_preset)) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }

        $file = $file_uploader->uploadPresetFile($preset);

        if ($file) {
            $file = reset($file);

            if ($preset['file_type'] !== PresetFileTypes::URL) {
                $preset['file'] = $file['name'];
            }
            $preset['file_extension'] = fn_advanced_import_get_file_extension_by_mimetype($file['name'], $file['type']);
        } else {
            fn_set_notification('E', __('error'), __('error_exim_no_file_uploaded'));
            exit;
        }

        if ($preset['file_type'] == PresetFileTypes::LOCAL) {
            $preset['file'] = $file_uploader->moveUpload($file['name'], $file['path'], $old_preset['company_id']);
        }

        $presets_manager->update($preset['preset_id'], $preset);

        if (!empty($old_preset['file']) && $old_preset['file_type'] === PresetFileTypes::LOCAL && !empty($file)) {
            $file_uploader->removeFile($old_preset['file'], $old_preset['company_id']);
        }

        $redirect_url = Url::buildUrn(array('import_presets', 'manage'), array(
            'object_type'       => $old_preset['object_type'],
            'preview_preset_id' => $old_preset['preset_id'],
        ));

        Tygh::$app['ajax']->assign('force_redirection', fn_url($redirect_url));

        exit;
    } elseif ($mode == 'update') {

        fn_trusted_vars('fields');
        $preset = array_merge(array(
            'preset_id'      => 0,
            'file_type'      => PresetFileTypes::LOCAL,
            'file'           => '',
        ), $_REQUEST);

        if ($preset['preset_id']) {
            list($old_preset,) = $presets_manager->find(false, ['ip.preset_id' => $preset['preset_id']], false);

            if (empty($old_preset)) {
                return [CONTROLLER_STATUS_NO_PAGE];
            }
            $old_preset = reset($old_preset);
        } else {
            $old_preset = null;
        }

        $file = $file_uploader->uploadPresetFile($preset);

        if ($file) {
            $file = reset($file);
            $preset['file_extension'] = fn_advanced_import_get_file_extension_by_mimetype($file['name'], $file['type']);
            unset($preset['type_upload'], $preset['file_upload']);
        }

        if (isset($preset['options']['images_path'])) {
            $images_directories = fn_advanced_import_get_import_images_directory($preset['company_id'], $preset['options']['images_path']);
            $preset['options']['images_path'] = $images_directories['exim_path'];
        }

        if ($file && $preset['file_type'] == PresetFileTypes::LOCAL) {
            // rename temporary file for a preset if exists
            $preset['file'] = $file_uploader->moveUpload($file['name'], $file['path'], $preset['company_id']);
        }

        if ($preset['preset_id']) {
            $presets_manager->update($preset['preset_id'], $preset);
        } else {
            $preset['preset_id'] = $presets_manager->add($preset);
        }

        if (!empty($old_preset['file']) && $old_preset['file_type'] === PresetFileTypes::LOCAL && !empty($file)) {
            $file_uploader->removeFile($old_preset['file'], $old_preset['company_id']);
        }

        $redirect_url = 'import_presets.update?preset_id=' . $preset['preset_id'];

        if ($action == 'import') {
            $redirect_url .= '&start_import=1';
        }

        return array(CONTROLLER_STATUS_OK, $redirect_url);
    } elseif ($mode == 'm_delete') {

        $_REQUEST = array_merge(array(
            'preset_ids'   => array(),
            'object_type'  => 'products',
            'redirect_url' => 'import_presets.manage',
        ), $_REQUEST);

        foreach ($_REQUEST['preset_ids'] as $preset_id) {
            $presets_manager->delete($preset_id);
        }

        return array(CONTROLLER_STATUS_OK, $_REQUEST['redirect_url']);
    } elseif ($mode == 'delete') {

        $_REQUEST = array_merge(array(
            'preset_id' => 0,
        ), $_REQUEST);

        list($presets,) = $presets_manager->find(
            false,
            array('ip.preset_id' => $_REQUEST['preset_id'])
        );

        if (!$presets) {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }

        $preset = reset($presets);

        $presets_manager->delete($_REQUEST['preset_id']);

        return array(CONTROLLER_STATUS_OK, 'import_presets.manage?object_type=' . $preset['object_type']);
    } elseif ($mode == 'validate_modifier') {

        $params = array_merge(array(
            'modifier' => '',
            'value'    => '',
            'notify'   => 'Y',
        ), $_REQUEST);

        $presets_importer->applyModifier($params['value'], $params['modifier'], array());

        Tygh::$app['ajax']->assign('is_valid', !fn_notification_exists('type', 'E'));

        if ($params['notify'] === 'N') {
            fn_get_notifications();
        }

        exit;
    }
}

if ($mode == 'update'
    || $mode == 'add'
    || $mode == 'get_fields'
) {
    /** @var \Tygh\Addons\AdvancedImport\Readers\Factory $reader_factory */
    $file_uploader = Tygh::$app['addons.advanced_import.readers.factory'];
    foreach (fn_get_short_companies() as $company_id => $company_name) {
        $file_uploader->initFilesDirectories($company_id);
    }
}

if ($mode == 'manage') {
    $params = array_merge(array(
        'page'              => 1,
        'items_per_page'    => Registry::get('settings.Appearance.admin_elements_per_page'),
        'object_type'       => 'products',
        'preview_preset_id' => 0,
    ), $_REQUEST);

    list($presets, $search) = fn_get_import_presets($params);

    if ($presets) {
        list($modifiers_presense,) = $presets_manager->find(
            false,
            array(
                array('modifier', '<>', ''),
                'ipf.preset_id' => array_keys($presets),
            ),
            array(
                array(
                    'table'     => array('?:import_preset_fields' => 'ipf'),
                    'condition' => array('ip.preset_id = ipf.preset_id'),
                ),
                array(
                    'table'     => array('?:import_preset_descriptions' => 'ipd'),
                    'condition' => array('ip.preset_id = ipd.preset_id'),
                ),
            ),
            array(
                'COUNT(ipf.field_id)' => 'has_modifiers',
            )
        );

        $presets = fn_array_merge($presets, $modifiers_presense);

        /** @var \Tygh\Addons\AdvancedImport\Readers\Factory $reader_factory */
        $reader_factory = Tygh::$app['addons.advanced_import.readers.factory'];

        foreach ($presets as &$preset) {
            if ($preset['file_type'] == PresetFileTypes::SERVER) {
                $preset['file_path'] = $reader_factory->getFilePath($preset['file'], $preset['company_id']);
            }
        }
        unset($preset);
    }

    Tygh::$app['view']->assign(array(
        'presets'           => $presets,
        'search'            => $search,
        'object_type'       => $params['object_type'],
        'preview_preset_id' => $params['preview_preset_id'],
    ));
} elseif ($mode == 'add') {

    $preset = array_merge(array(
        'object_type' => 'products',
    ), $_REQUEST);

    $pattern = $presets_manager->getPattern($preset['object_type']);
    $preset = $presets_manager->mergePattern($preset, $pattern);

    Registry::set('navigation.tabs', array(
        'general' => array(
            'title' => __('file'),
            'js'    => true,
        ),
        'fields'  => array(
            'title'        => __('advanced_import.fields_mapping'),
            'href'         => 'import_presets.get_fields',
            'ajax'         => true,
            'ajax_onclick' => true
        ),
        'options' => array(
            'title' => __('settings'),
            'js'    => true,
        ),
    ));

    Tygh::$app['view']->assign(array(
        'pattern' => $pattern,
        'preset'  => $preset,
    ));
} elseif ($mode == 'update') {
    if (empty($_REQUEST['preset_id'])) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    list($presets) = fn_get_import_presets(array(
        'preset_id' => $_REQUEST['preset_id'],
    ));

    if (!$presets) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    $preset = reset($presets);
    $pattern = $presets_manager->getPattern($preset['object_type']);
    $preset = $presets_manager->mergePattern($preset, $pattern);

    Registry::set('navigation.tabs', array(
        'general' => array(
            'title' => __('file'),
            'js'    => true,
        ),
        'fields'  => array(
            'title'        => __('advanced_import.fields_mapping'),
            'href'         => 'import_presets.get_fields?preset_id=' . $_REQUEST['preset_id'],
            'ajax'         => true,
            'ajax_onclick' => true
        ),
        'options' => array(
            'title' => __('settings'),
            'js'    => true,
        ),
    ));

    Tygh::$app['view']->assign(array(
        'pattern'      => $pattern,
        'preset'       => $preset,
        'start_import' => !empty($_REQUEST['start_import']) ? $_REQUEST['start_import'] : false,
    ));
} elseif ($mode == 'get_fields') {

    if (!defined('AJAX_REQUEST')) {
        if (!empty($_REQUEST['preset_id'])) {
            $redirect_url = sprintf('import_presets.update?preset_id=%s', $_REQUEST['preset_id']);
        } else {
            $redirect_url = 'import_presets.manage';
        }

        return array(CONTROLLER_STATUS_REDIRECT, $redirect_url);
    }

    $preset = array_merge(array(
        'file'           => '',
        'file_type'      => PresetFileTypes::LOCAL,
        'preset_id'      => 0,
        'company_id'     => fn_get_runtime_company_id(),
        'object_type'    => 'products',
        'fields'         => array(),
        'options'        => array(),
    ), $_REQUEST);

    if ($preset['preset_id']) {
        list($presets,) = $presets_manager->find(false, array('ip.preset_id' => $preset['preset_id']), false);
        $preset = reset($presets);
        $preset['fields'] = $presets_manager->getFieldsMapping($preset['preset_id']);
        if (isset($_REQUEST['file'])) {
            $preset['file'] = $_REQUEST['file'];
        }
        if (isset($_REQUEST['file_type'])) {
            $preset['file_type'] = $_REQUEST['file_type'];
        }
        if (isset($_REQUEST['options'])) {
            $preset['options'] = array_merge($preset['options'], $_REQUEST['options']);
        }
        if (isset($_REQUEST['company_id'])) {
            $preset['company_id'] = $_REQUEST['company_id'];
        }
    }

    if ($preset['file']) {

        /** @var \Tygh\Addons\AdvancedImport\Readers\Factory $reader_factory */
        $reader_factory = Tygh::$app['addons.advanced_import.readers.factory'];

        /** @var Tygh\Addons\AdvancedImport\Readers\IReader $reader */
        try {
            $reader = $reader_factory->get($preset);

            $schema = $reader->getSchema();
            $schema->showNotifications();
            $fields = $schema->getData();

            $result = $reader->getContents(1, $fields);
            $result->showNotifications();

            if ($result->getData()) {
                $preview = $presets_importer->prepareImportItems(
                    $result->getData(),
                    $preset['fields'],
                    $preset['object_type']
                );
            }

            $pattern = $presets_manager->getPattern($preset['object_type']);
            $preset = $presets_manager->mergePattern($preset, $pattern);

            $relations = $presets_manager->getRelations($preset['object_type']);

            Tygh::$app['view']->assign(array(
                'preset'                 => $preset,
                'fields'                 => $fields,
                'preview'                => isset($preview) ? $preview : null,
                'relations'              => $relations,
                'show_buttons_container' => $action == 'import',
            ));

            Tygh::$app['ajax']->assign('has_fields', !empty($fields));
            Tygh::$app['ajax']->assign('file_extension', $reader->getExtension());
        } catch (ReaderNotFoundException $e) {
            fn_set_notification('E', __('error'), __('error_exim_cant_read_file'));
            exit;
        } catch (PermissionsException $e) {
            fn_set_notification('E', __('error'), __('advanced_import.cant_load_file_for_company'));
            exit;
        } catch (FileNotFoundException $e) {
            fn_set_notification('E', __('error'), __('advanced_import.cant_load_file_for_company'));
            exit;
        } catch (DownloadException $e) {
            fn_set_notification('E', __('error'), __('advanced_import.cant_load_file'));
            exit;
        }
    }

} elseif ($mode == 'get_file') {
    list($presets) = fn_get_import_presets(array(
        'preset_id' => $_REQUEST['preset_id'],
    ));

    if (!$presets) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    $preset = reset($presets);

    if (empty($preset['file'])) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    /** @var \Tygh\Addons\AdvancedImport\Readers\Factory $reader_factory */
    $reader_factory = Tygh::$app['addons.advanced_import.readers.factory'];

    if ($preset['file'] && $preset['file_type'] == PresetFileTypes::URL) {
        fn_redirect($preset['file'], true);
    }

    $file_path = $reader_factory->getFilePath($preset['file']);

    if ($file_path) {
        fn_get_file($file_path);
    }

} elseif ($mode == 'file_manager') {
    if (!isset($_REQUEST['path']) || !isset($_REQUEST['company_id'])) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    $selected_company_id = (int) Registry::get('runtime.company_id');
    $company_id = (int) $_REQUEST['company_id'];

    if ($selected_company_id && $company_id !== $selected_company_id) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    $images_directories = fn_advanced_import_get_import_images_directory($company_id, $_REQUEST['path']);

    if (!file_exists($images_directories['absolute_path'])) {
        fn_mkdir($images_directories['absolute_path']);
    }

    $path = $images_directories['filemanager_path'];

    return array(
        CONTROLLER_STATUS_REDIRECT,
        sprintf('file_editor.manage?in_popup=1&path=%s&container_id=%s', $path, md5(TIME))
    );
}
