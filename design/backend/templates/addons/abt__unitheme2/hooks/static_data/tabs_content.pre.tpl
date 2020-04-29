<div id="content_abt__ut2_{$id}">
<div class="abt-ut2-doc">{__('abt__ut2.menu.tab_description')}</div>
<fieldset>
<div class="control-group">
<label for="abt__ut2_mwi__status_{$id}" class="control-label">{__("abt__ut2.menu_with_icons.status")}:</label>
<div class="controls">
<input type="hidden" name="static_data[abt__ut2_mwi__status]" value="N">
<input type="checkbox" id="abt__ut2_mwi__status_{$id}" name="static_data[abt__ut2_mwi__status]" value="Y" {if $static_data.abt__ut2_mwi__status == 'Y'}checked="checked"{/if}>
</div>
</div>
{** Icon **}
<div class="control-group">
<label class="control-label">{__("abt__ut2.menu_with_icons.icon")}:</label>
<div class="controls">
{include file="common/attach_images.tpl"
image_name="abt__ut2_mwi__icon"
image_object_type="abt__ut2_mwi__icon"
image_key=$id
hide_titles=true
no_detailed=true
hide_alt=true
image_pair=$static_data.abt__ut2_mwi__icon}
</div>
</div>
{** Desc of a item **}
<div class="control-group">
<label for="abt__ut2_mwi__desc_{$id}" class="control-label">{__("abt__ut2.menu_with_icons.desc")}:</label>
<div class="controls">
<textarea name="static_data[abt__ut2_mwi__desc]" id="abt__ut2_mwi__desc_{$id}" class="input-xxlarge">{$static_data.abt__ut2_mwi__desc}</textarea>
</div>
</div>
{** Type of a item **}
<div class="control-group">
<label for="abt__ut2_mwi__dropdown_{$id}" class="control-label">{__("abt__ut2.menu_with_icons.dropdown")}:</label>
<div class="controls">
<input type="hidden" id="abt__ut2_mwi__dropdown_{$id}" name="static_data[abt__ut2_mwi__dropdown]" value="N">
<input type="checkbox" id="abt__ut2_mwi__dropdown_{$id}" name="static_data[abt__ut2_mwi__dropdown]" value="Y" {if $static_data.abt__ut2_mwi__dropdown == 'Y'}checked="checked"{/if}>
</div>
</div>
{** text **}
<div class="control-group">
<label for="abt__ut2_mwi__text_{$id}" class="control-label">{__("abt__ut2.menu_with_icons.text")}:</label>
<div class="controls">
<textarea name="static_data[abt__ut2_mwi__text]" id="abt__ut2_mwi__text_{$id}" class="input-xxlarge cm-wysiwyg">{$static_data.abt__ut2_mwi__text}</textarea>
</div>
</div>
{** text-position **}
<div class="control-group">
<label for="abt__ut2_mwi__text_position_{$id}" class="control-label">{__("abt__ut2.menu_with_icons.text_position")}:</label>
<div class="controls">
<select name="static_data[abt__ut2_mwi__text_position]" id="abt__ut2_mwi__text_position_{$id}">
<option value="bottom" {if $static_data.abt__ut2_mwi__text_position == 'bottom'}selected="selected"{/if}>{__("abt__ut2.menu_with_icons.text_position.bottom")}</option>
<option value="right_bottom" {if $static_data.abt__ut2_mwi__text_position == 'right_bottom'}selected="selected"{/if}>{__("abt__ut2.menu_with_icons.text_position.right_bottom")}</option>
<option value="right_top" {if $static_data.abt__ut2_mwi__text_position == 'right_top'}selected="selected"{/if}>{__("abt__ut2.menu_with_icons.text_position.right_top")}</option>
</select>
</div>
</div>
{** label **}
<div class="control-group">
<label for="abt__ut2_mwi__label_{$id}" class="control-label">{__("abt__ut2.menu_with_icons.label")}:</label>
<div class="controls">
<input type="text" id="abt__ut2_mwi__label_{$id}" name="static_data[abt__ut2_mwi__label]" value="{$static_data.abt__ut2_mwi__label}" class="input-large main-input">
</div>
</div>
{** label color **}
<div class="control-group">
<label for="abt__ut2_mwi__label_color_{$id}" class="control-label cm-color">{__("abt__ut2.menu_with_icons.label_color")}:</label>
<div class="controls">
<input class="cm-abt-ut2-colorpicker" style="font-family: monospace;" type="text" name="static_data[abt__ut2_mwi__label_color]" id="abt__ut2_mwi__label_color_{$id}" value="{$static_data.abt__ut2_mwi__label_color|replace:"transparent":""|default:"#ffffff"}"/>
</div>
</div>
{** label background **}
<div class="control-group">
<label for="abt__ut2_mwi__label_background_{$id}" class="control-label cm-color">{__("abt__ut2.menu_with_icons.label_background")}:</label>
<div class="controls">
<input class="cm-abt-ut2-colorpicker" style="font-family: monospace;" type="text" name="static_data[abt__ut2_mwi__label_background]" id="abt__ut2_mwi__label_background_{$id}" value="{$static_data.abt__ut2_mwi__label_background|replace:"transparent":""|default:"#ffffff"}"/>
</div>
</div>
</fieldset>
<!--content_abt__ut2_{$id}--></div>
