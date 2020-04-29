{if $ab__mb_bg_color_setting}
{if $ab__mb_bg_color_setting.update_for_all && $settings.Stores.default_state_update_for_all == 'not_active' && !$runtime.simple_ultimate}
{assign var="disable_input" value=true}
{/if}
<div id="container_addon_option_ab__motivation_block_bg_color" class="control-group setting-wide ab__motivation_block">
<label class="control-label cm-color" for="addon_option_ab__motivation_block_bg_color">{__('ab__mb_bg_color')}:</label>
<div class="controls">
{if !$disable_input}
{include file="views/theme_editor/components/colorpicker.tpl" cp_name="addon_data[options][{$ab__mb_bg_color_setting.object_id}]" cp_id="addon_option_ab__motivation_block_bg_color" cp_value=$addons.ab__motivation_block.bg_color|default:'#FFFFFF'}
{else}
<input id="addon_option_ab__motivation_block_bg_color" disabled="disabled" type="text" style="font-family: monospace;" value="{$addons.ab__motivation_block.bg_color|default:'#FFFFFF'}">
{/if}
<div class="right update-for-all">
{include file="buttons/update_for_all.tpl" display=$ab__mb_bg_color_setting.update_for_all object_id=$ab__mb_bg_color_setting.object_id name="update_all_vendors[`$ab__mb_bg_color_setting.object_id`]" hide_element="addon_option_ab__motivation_block_bg_color"}
</div>
</div>
</div>
{/if}