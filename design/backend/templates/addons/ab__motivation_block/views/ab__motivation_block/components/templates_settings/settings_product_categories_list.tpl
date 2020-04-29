<div class="control-group">
<label class="control-label{if $motivation_item_data.template_path == $tmpl_name} cm-required{else}" data-required="{/if}" for="{$id_pref}_bfi">{__("ab__mb.templates_settings.brand_feature_id")}{include file="common/tooltip.tpl" tooltip=__('ab__mb.templates_settings.brand_feature_id.tooltip')}:</label>
<div class="controls">
<input type="text" name="{$name_prefix}[brand_feature_id]" id="{$id_pref}_bfi" value="{$t_settings.brand_feature_id|default:fn_ab__mb_get_default_brand_setting_id()}" size="25" class="input-small cm-value-integer" />
</div>
</div>