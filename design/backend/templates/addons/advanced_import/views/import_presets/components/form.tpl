<form action="{""|fn_url}"
        method="post"
        name="manage_import_presets_form"
        enctype="multipart/form-data"
        class="cm-skip-check-items import-preset {if $preview_preset_id}cm-ajax cm-comet{/if}"
        data-ca-advanced-import-element="management_form"
        id="manage_import_presets_form{$wrapper_extra_id}"
>
    <input type="hidden" name="object_type" value="{$object_type}"/>
    {$wrapper_content nofilter}
</form>