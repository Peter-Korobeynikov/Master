{if "ab__hpd.view"|fn_check_view_permissions}
{if $addons.ab__hide_product_description.hide_in_product == 'Y'}
{strip}
<div id="content_ab__smc_{$html_id}"{if !"ab__hpd.manage"|fn_check_view_permissions} class="cm-hide-inputs"{/if}>
<fieldset>
<div class="control-group">
<label class="control-label" for="elm_ab__smc_hide_content_{$html_id}">{__("ab__smc.product_tabs.hide_content")}:</label>
<div class="controls">
<input type="hidden" name="tab_data[ab__smc_hide_content]" value="N">
<input type="checkbox" name="tab_data[ab__smc_hide_content]" id="elm_ab__smc_hide_content_{$html_id}"{if $tab_data.ab__smc_hide_content == "Y"} checked="checked"{/if} value="Y">
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_ab__smc_show_more_{$html_id}">{__("ab__smc.product_tabs.hide_content.more")}:</label>
<div class="controls">
<input type="text" name="tab_data[ab__smc_show_more]" id="elm_ab__smc_show_more_{$html_id}" value="{$tab_data.ab__smc_show_more}">
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_ab__smc_show_less_{$html_id}">{__("ab__smc.product_tabs.hide_content.less")}:</label>
<div class="controls">
<input type="text" name="tab_data[ab__smc_show_less]" id="elm_ab__smc_show_less_{$html_id}" value="{$tab_data.ab__smc_show_less}">
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_ab__smc_override_height_{$html_id}">{__("ab__smc.product_tabs.override")}
{include file="common/tooltip.tpl" tooltip=__("ab__smc.product_tabs.override.tooltip")}:</label>
<div class="controls">
<input type="hidden" name="tab_data[ab__smc_override]" value="N">
<input type="checkbox" name="tab_data[ab__smc_override]" id="elm_ab__smc_override_height_{$html_id}"{if $tab_data.ab__smc_override == "Y"} checked="checked"{/if} value="Y">
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_ab__smc_tab_height_{$html_id}">{__("ab__smc.product_tabs.height")}
{include file="common/tooltip.tpl" tooltip=__("ab__smc.product_tabs.height.tooltip")}:</label>
<div class="controls">
<input type="text" class="cm-value-integer" name="tab_data[ab__smc_height]" id="elm_ab__smc_tab_height_{$html_id}" value="{$tab_data.ab__smc_height|default:$addons.ab__hide_product_description.max_height}">
</div>
</div>
</fieldset>
</div>
{/strip}
{/if}
{/if}