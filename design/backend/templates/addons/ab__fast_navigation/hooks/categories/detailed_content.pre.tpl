{if "ab__fast_navigation.view"|fn_check_view_permissions}
{include file="common/subheader.tpl" title=__("ab__fast_navigation") target="#acc_addon_ab__fast_navigation"}
<div id="acc_addon_ab__fast_navigation" class="collapsed in{if !"ab__hpd.manage"|fn_check_view_permissions} cm-hide-inputs{/if}">
<div class="control-group">
<label class="control-label" for="ab__fn_category_status_{$id}">{__("ab__fn.category.status")}
{include file="common/tooltip.tpl" tooltip=__("ab__fn.category.status.tooltip")}:
</label>
<div class="controls">
<input type="hidden" name="category_data[ab__fn_category_status]" value="N" />
<input type="checkbox" id="ab__fn_category_status_{$id}" name="category_data[ab__fn_category_status]" value="Y" {if $category_data['ab__fn_category_status'] == "Y"}checked="checked"{/if} />
</div>
</div>
{** should we use clipped images **}
<div class="control-group">
<label class="control-label" for="ab__fn_use_origin_image_{$id}">{__("ab__fn.category.use_origin_image")}
{include file="common/tooltip.tpl" tooltip=__("ab__fn.category.use_origin_image.tooltip")}:
</label>
<div class="controls">
<input type="hidden" name="category_data[ab__fn_use_origin_image]" value="N" />
<input type="checkbox" id="ab__fn_use_origin_image_{$id}" name="category_data[ab__fn_use_origin_image]" value="Y" {if $category_data['ab__fn_use_origin_image'] == "Y"}checked="checked"{/if} class="checkbox" />
</div>
</div>
{** show_label **}
<div class="control-group">
<label for="ab__fn_label_text_{$id}" class="control-label">{__("ab__fn.category.label.show")}
{include file="common/tooltip.tpl" tooltip=__("ab__fn.category.label.show.tooltip")}:
</label>
<div class="controls">
<input type="hidden" name="category_data[ab__fn_label_show]" value="N" />
<input type="checkbox" id="ab__fn_use_origin_image_{$id}" name="category_data[ab__fn_label_show]" value="Y" {if $category_data['ab__fn_label_show'] == "Y"}checked="checked"{/if} class="checkbox" />
</div>
</div>
{** label **}
<div class="control-group">
<label for="ab__fn_label_text_{$id}" class="control-label">{__("ab__fn.category.label")}:</label>
<div class="controls">
<input type="text" id="ab__fn_label_text_{$id}" name="category_data[ab__fn_label_text]" value="{$category_data.ab__fn_label_text}" class="input-large main-input">
</div>
</div>
{** label color **}
<div class="control-group">
<label for="ab__fn_label_color_{$id}" class="control-label cm-color">{__("ab__fn.category.label_color")}:</label>
<div class="controls">
{include file="views/theme_editor/components/colorpicker.tpl" cp_name="category_data[ab__fn_label_color]" cp_id="ab__fn_label_color_{$id}" cp_value=$category_data.ab__fn_label_color|replace:"transparent":"#ffffff"|default:"#ffffff"}
</div>
</div>
{** label background **}
<div class="control-group">
<label for="ab__fn_label_background_{$id}" class="control-label cm-color">{__("ab__fn.category.label_background")}:</label>
<div class="controls">
{include file="views/theme_editor/components/colorpicker.tpl" cp_name="category_data[ab__fn_label_background]" cp_id="ab__fn_label_background_{$id}" cp_value=$category_data.ab__fn_label_background|replace:"transparent":"#222222"|default:"#222222"}
</div>
</div>
</div>
{/if}