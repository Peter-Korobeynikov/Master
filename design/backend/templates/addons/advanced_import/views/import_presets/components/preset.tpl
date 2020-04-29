<tr class="import-preset" id="preset_{$preset.preset_id}">
    <td class="left import-preset__checker mobile-hide">
        <input type="checkbox"
               name="preset_ids[]"
               value="{$preset.preset_id}"
               class="cm-item"
        />
    </td>

    <td class="import-preset__preset" data-th="{__("name")}">
        <a href="{"import_presets.update?preset_id=`$preset.preset_id`"|fn_url}">{$preset.preset}</a>
        {include file="views/companies/components/company_name.tpl" object=$preset}
    </td>

    <td class="import-preset__run">
        {if $preset.file_type == "Addons\\AdvancedImport\\PresetFileTypes::SERVER"|enum && $preset.file_path
            || $preset.file_type == "Addons\\AdvancedImport\\PresetFileTypes::URL"|enum
        }
            <a href="{"advanced_import.import?preset_id=`$preset.preset_id`"|fn_url}"
               class="btn cm-ajax cm-comet cm-post"
            >{__("import")}</a>
        {elseif $preset.file_type == "Addons\\AdvancedImport\\PresetFileTypes::LOCAL"|enum}
            {btn type="dialog"
                 text=__("import")
                 class="btn"
                 target_id="import_preset_file_upload_{$preset.preset_id}"
            }

            {capture name="popups"}
                {$smarty.capture.popups nofilter}

                <div class="hidden" title="{__("advanced_import.uploading_file", ["[preset]" => $preset.preset])}" id="import_preset_file_upload_{$preset.preset_id}">
                    <form action="{""|fn_url}"
                          method="post"
                          enctype="multipart/form-data">

                        <input type="hidden" name="preset_id" value="{$preset.preset_id}">
                        <div class="form-horizontal form-edit import-preset__fileuploader-form">

                            <div class="control-group">
                                <label class="control-label">{__("select_file")}:</label>
                                <div class="controls">
                                    {include file="addons/advanced_import/views/import_presets/components/fileuploader.tpl"
                                        var_name="upload[{$preset.preset_id}]"
                                        prefix=$preset.preset_id
                                        allowed_ext=["csv", "xml"]
                                    }
                                </div>
                            </div>
                            <div class="buttons-container">
                                {include file="buttons/save_cancel.tpl"
                                    cancel_action="close"
                                    but_text=__("upload")
                                    but_meta="cm-ajax cm-comet cm-post"
                                    but_name="dispatch[import_presets.upload]"
                                }
                            </div>
                        </div>
                    </form>
                <!--import_preset_file_upload_{$preset.preset_id}--></div>
            {/capture}
        {/if}

        {if $preview_preset_id == $preset.preset_id}
            {btn type="dialog"
                text=__("preview")
                class="cm-dialog-auto-width hidden import-preset__preview-fields-mapping"
                href="{"import_presets.get_fields.import?preset_id=`$preset.preset_id`"|fn_url}"
                target_id="import_preset_fields_mapping_{$preset.preset_id}"
                id="import_preset_preview_fields_mapping_{$preset.preset_id}"
            }

            <div class="hidden form-horizontal form-edit import-preset__fields-mapping"
                 title="{__("advanced_import.previewing_fields_mapping", ["[preset]" => $preset.preset])}"
                 id="import_preset_fields_mapping_{$preset.preset_id}"
            >
            <!--import_preset_fields_mapping_{$preset.preset_id}--></div>
        {/if}
    </td>

    <td class="import-preset__last-launch" data-th="{__("advanced_import.last_launch")}">
        {if $preset.last_launch}
            {$preset.last_launch|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
        {else}
            {__("advanced_import.never")}
        {/if}
    </td>

    <td class="import-preset__last-status" data-th="{__("advanced_import.last_status")}">
        <span class="status--{$preset.last_status|lower}">
            {__("advanced_import.last_status.`$preset.last_status`")}
            {if $preset.last_status == "Addons\\AdvancedImport\\ImportStatuses::SUCCESS"|enum}
                {include file="common/tooltip.tpl"
                         tooltip=__("text_exim_data_imported", [
                             "[new]" => $preset.last_result.N,
                             "[exist]" => $preset.last_result.E,
                             "[skipped]" => $preset.last_result.S,
                             "[total]" => $preset.last_result.N + $preset.last_result.E + $preset.last_result.S
                         ])
                }
            {elseif $preset.last_status == "Addons\\AdvancedImport\\ImportStatuses::FAIL"|enum && is_array($preset.last_result.msg)}
                {include file="common/tooltip.tpl"
                         tooltip=$preset.last_result.msg|implode:"<br>"
                }
            {/if}
        </span>
    </td>

    <td class="import-preset__file" data-th="{__("advanced_import.file")}">
        {if $preset.file_type == "Addons\\AdvancedImport\\PresetFileTypes::URL"|enum}
            <a href="{$preset.file}" target="_blank">{$preset.file}</a>
        {elseif $preset.file_type == "Addons\\AdvancedImport\\PresetFileTypes::SERVER"|enum}
            {if $preset.file_path}
                {$preset.file}
            {else}
                <span class="type-error">{__("error_file_not_found", ["[file]" => $preset.file])}</span>
            {/if}
        {elseif $preset.file}
            {$preset.file}
        {else}
            {__("advanced_import.user_upload")}
        {/if}
    </td>

    <td class="import-preset__has-modifiers" data-th="{__("advanced_import.has_modifiers")}">
        {if $preset.has_modifiers|default:0}
            {__("yes")}
        {else}
            {__("no")}
        {/if}
    </td>

    <td class="import-preset__tools">
        <div class="hidden-tools">
            {capture name="tools_list"}
                {hook name="advanced_import:preset_list_extra_links"}
                    <li>{btn type="list" text=__("edit") href="import_presets.update?preset_id=`$preset.preset_id`"}</li>
                    <li>{btn type="list" text=__("delete") class="cm-confirm" href="import_presets.delete?preset_id=`$preset.preset_id`" method="POST"}</li>
                {/hook}
            {/capture}
            {dropdown content=$smarty.capture.tools_list}
        </div>
    </td>
<!--preset_{$preset.preset_id}--></tr>