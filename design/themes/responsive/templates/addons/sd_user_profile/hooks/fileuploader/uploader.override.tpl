<div class="ty-nowrap" id="file_uploader_{$id_var_name}">
    <div class="ty-fileuploader__file-section" id="message_{$id_var_name}" title="">
        <p class="cm-fu-file hidden"><i id="clean_selection_{$id_var_name}" title="{__("remove_this_item")}" onclick="Tygh.fileuploader.clean_selection(this.id); {if $multiupload != "Y"}Tygh.fileuploader.toggle_links(this.id, 'show');{/if} Tygh.fileuploader.check_required_field('{$id_var_name}', '{$label_id}');" class="ty-icon-cancel-circle ty-fileuploader__icon"></i><span class="ty-fileuploader__filename ty-filename-link"></span></p>
    </div>

    {strip}
    <div class="ty-fileuploader__file-link {if $multiupload != "Y" && $images}hidden{/if}" id="link_container_{$id_var_name}">
        <input type="hidden" name="file_{$var_name}" value="{if $image_name}{$image_name}{/if}" id="file_{$id_var_name}" />
        <input type="hidden" name="type_{$var_name}" value="{if $image_name}local{/if}" id="type_{$id_var_name}" />
        <div class="ty-fileuploader__file-local upload-file-local">
            <input type="file" class="ty-fileuploader__file-input" name="file_{$var_name}" id="local_{$id_var_name}" onchange="Tygh.fileuploader.show_loader(this.id); {if $multiupload == "Y"}Tygh.fileuploader.check_image(this.id);{else}Tygh.fileuploader.toggle_links(this.id, 'hide');{/if} Tygh.fileuploader.check_required_field('{$id_var_name}', '{$label_id}');" data-ca-empty-file="" onclick="Tygh.$(this).removeAttr('data-ca-empty-file');">
            <a data-ca-multi="Y" {if !$images}class="hidden"{/if}>{$upload_another_file_text|default:__("upload_another_file")}</a><a data-ca-target-id="local_{$id_var_name}" data-ca-multi="N" class="ty-btn ty-fileuploader__a{if $images} hidden{/if}">{$upload_file_text|default:__("upload_file")}</a>
        </div>
        {if $allow_url_uploading}
            &nbsp;{__("or")}&nbsp;
            <a onclick="Tygh.fileuploader.show_loader(this.id); {if $multiupload == "Y"}Tygh.fileuploader.check_image(this.id);{else}Tygh.fileuploader.toggle_links(this.id, 'hide');{/if} Tygh.fileuploader.check_required_field('{$id_var_name}', '{$label_id}');" id="url_{$id_var_name}">{__("specify_url")}</a>
        {/if}
        {if $hidden_name}
            <input type="hidden" name="{$hidden_name}" value="{$hidden_value}">
        {/if}
    </div>
    {/strip}
</div>