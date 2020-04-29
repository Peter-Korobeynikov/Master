<div class="control-group">
<label class="control-label{if $motivation_item_data.template_path == $tmpl_name} cm-required{else}" data-required="{/if}" for="{$id_pref}_bfi">{__("ab__mb.templates_settings.max_tags_count")}:</label>
<div class="controls">
<input type="text" name="{$name_prefix}[tags_items_per_page]" id="{$id_pref}_bfi" value="{$t_settings.tags_items_per_page|default:$settings.Appearance.elements_per_page}" size="25" class="input-small cm-value-integer" />
</div>
</div>