{if $banner.type == $smarty.const.ABT__UT2_BANNER_TYPE or $smarty.request.type == $smarty.const.ABT__UT2_BANNER_TYPE}
<input type="hidden" class="" name="selected_section" id="selected_section">
{hook name="banners:general_content"}
<div class="control-group">
<label for="elm_banner_name" class="control-label cm-required">{__("name")}</label>
<div class="controls">
<input type="text" name="banner_data[banner]" id="elm_banner_name" value="{$banner.banner}" size="25" class="input-large" /></div>
</div>
{if "ULTIMATE"|fn_allowed_for}
{include file="views/companies/components/company_field.tpl"
name="banner_data[company_id]"
id="banner_data_company_id"
selected=$banner.company_id
}
{/if}
<div class="control-group">
<label for="elm_banner_position" class="control-label">{__("position_short")}</label>
<div class="controls">
<input type="text" name="banner_data[position]" id="elm_banner_position" value="{$banner.position|default:"0"}" size="3"/>
</div>
</div>
<div class="control-group">
<label for="elm_banner_type" class="control-label cm-required">{__("type")}</label>
<div class="controls">
<select name="banner_data[type]" id="elm_banner_type">
<option {if $banner.type == $smarty.const.ABT__UT2_BANNER_TYPE}selected="selected"{/if} value="{$smarty.const.ABT__UT2_BANNER_TYPE}">{__("banner_type.`$smarty.const.ABT__UT2_BANNER_TYPE`")}</option>
</select>
</div>
</div>
{include file="addons/abt__unitheme2/views/banners/components/abt__ut2_fields.tpl"}
<hr>
{foreach ['tablet', 'mobile'] as $device}
{$field="`$device`_use"}{$elm="abt__ut2_`$field`"}
<div class="control-group{if $disabled} hidden{/if}">
<label for="elm_banner_{$elm}" class="control-label">{__("abt__ut2.banner.params.{$field}")}{include file="common/tooltip.tpl" tooltip=__("abt__ut2.banner.params.{$field}.tooltip")}</label>
<div class="controls">
<input type="hidden" name="banner_data[{$elm}]" value="N"/>
<input type="checkbox" name="banner_data[{$elm}]" id="elm_banner_{$elm}" value="Y" {if $banner.$elm == "Y"}checked="checked"{/if}/>
</div>
</div>
{/foreach}
{include file="views/localizations/components/select.tpl" data_name="banner_data[localization]" data_from=$banner.localization}
{include file="common/select_status.tpl" input_name="banner_data[status]" id="elm_banner_status" obj_id=$id obj=$banner hidden=true}
{/hook}
{/if}