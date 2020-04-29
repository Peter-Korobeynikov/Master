{if "ab__fast_navigation.view"|fn_check_view_permissions}
{if $static_data.ab__fn_menu_status == 'Y'}
<div id="content_ab__fn_{$id}"{if !"ab__hpd.manage"|fn_check_view_permissions} class="cm-hide-inputs"{/if}>
<fieldset>
{** Icon **}
<div class="control-group">
<label class="control-label">{__("ab__fn.menu.icon")}:</label>
<div class="controls">
{include file="common/attach_images.tpl"
image_name="ab__fn_menu_icon"
image_object_type="ab__fn_menu_icon"
image_key=$id
hide_titles=true
no_detailed=true
hide_alt=true
image_pair=$static_data.ab__fn_menu_icon}
</div>
</div>
{** should we use clipped images **}
<div class="control-group">
<label class="control-label" for="ab__fn_use_origin_image_{$id}">{__("ab__fn.menu.use_origin_image")}
{include file="common/tooltip.tpl" tooltip=__("ab__fn.menu.use_origin_image.tooltip")}:
</label>
<div class="controls">
<input type="hidden" name="static_data[ab__fn_use_origin_image]" value="N" />
<input type="checkbox" id="ab__fn_use_origin_image_{$id}" name="static_data[ab__fn_use_origin_image]" value="Y"{if $static_data['ab__fn_use_origin_image'] == "Y"} checked="checked"{/if} class="checkbox" />
</div>
</div>
{** show_label **}
<div class="control-group">
<label for="ab__fn_label_text_{$id}" class="control-label">{__("ab__fn.menu.label.show")}
{include file="common/tooltip.tpl" tooltip=__("ab__fn.menu.label.show.tooltip")}:
</label>
<div class="controls">
<input type="hidden" name="static_data[ab__fn_label_show]" value="N" />
<input type="checkbox" id="ab__fn_use_origin_image_{$id}" name="static_data[ab__fn_label_show]" value="Y"{if $static_data['ab__fn_label_show'] == "Y"} checked="checked"{/if} class="checkbox" />
</div>
</div>
{** label **}
<div class="control-group">
<label for="ab__fn_label_text_{$id}" class="control-label">{__("ab__fn.menu.label")}:</label>
<div class="controls">
<input type="text" id="ab__fn_label_text_{$id}" name="static_data[ab__fn_label_text]" value="{$static_data.ab__fn_label_text}" class="input-large main-input">
</div>
</div>
{** label color **}
<div class="control-group">
<label for="ab__fn_label_color_{$id}" class="control-label cm-color">{__("ab__fn.menu.label_color")}:</label>
<div class="controls">
{include file="views/theme_editor/components/colorpicker.tpl" cp_name="static_data[ab__fn_label_color]" cp_id="ab__fn_label_color_{$id}" cp_value=$static_data.ab__fn_label_color|replace:"transparent":"#ffffff"|default:"#ffffff"}
</div>
</div>
{** label background **}
<div class="control-group">
<label for="ab__fn_label_background_{$id}" class="control-label cm-color">{__("ab__fn.menu.label_background")}:</label>
<div class="controls">
{include file="views/theme_editor/components/colorpicker.tpl" cp_name="static_data[ab__fn_label_background]" cp_id="ab__fn_label_background_{$id}" cp_value=$static_data.ab__fn_label_background|replace:"transparent":"#222222"|default:"#222222"}
</div>
</div>
</fieldset>
<!--content_ab__fn_{$id}--></div>
{/if}
{/if}