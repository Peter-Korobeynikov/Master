<div class="sidebar-field">
<label>{__("category")}</label>
<div class="break clear correct-picker-but">
{if "categories"|fn_show_picker:$smarty.const.CATEGORY_THRESHOLD}
{if $search.category_ids}
{assign var="s_cid" value=$search.category_ids[0]}
{else}
{assign var="s_cid" value="0"}
{/if}
{include file="pickers/categories/picker.tpl" data_id="location_category" input_name="category_ids[]" item_ids=$s_cid hide_link=true hide_delete_button=true default_name=__("all_categories") extra=""}
{else}
{include file="common/select_category.tpl" name="category_ids[]" id=$search.category_ids[0]|default:0}
{/if}
</div>
</div>
<div class="sidebar-field">
<label>{__("ab__mb.destinations")}</label>
<div class="break clear correct-picker-but">
{if $search.destination_id}
{assign var="s_did" value=$search.destination_id}
{else}
{assign var="s_did" value="0"}
{/if}
{include
file="addons/ab__motivation_block/pickers/destinations/picker.tpl"
input_name="destination_id"
item_ids=$s_did
data_id="location_destinations"
default_name=__("ab__mb_all_destinations")
prepend=true
}
</div>
</div>
<div class="sidebar-field">
<label for="ab__mb_name">{__("name")}:</label>
<div class="break">
<input type="text" name="name" id="ab__mb_name" value="{$search.name}" size="30" class="search-input-text" />
</div>
</div>
{if !isset($search.ab__mb_template)}
{$serach_ab__mb_template = 'ignore'}
{else}
{$serach_ab__mb_template = $search.ab__mb_template}
{/if}
<div class="sidebar-field">
<label for="ab__mb_name">{__("template")}:</label>
<div class="break">
<select name="ab__mb_template" id="ab__mb_template">
{hook name="ab__mb:search_templates_select"}
<option value="ignore" {if $serach_ab__mb_template == 'ignore'} selected{/if} title="{__('ab__mb.template_path.search.ignore_this_field.tooltip')}">
{__('ab__mb.template_path.search.ignore_this_field')}
</option>
<option value="" {if $serach_ab__mb_template == ''} selected{/if}>{__('ab__mb.template_path.variants.custom')}</option>
<optgroup label="{__('templates')}">
{foreach $ab__mb_templates as $template}
<option value="{$template.template_path}"
{if $serach_ab__mb_template == $template.template_path} selected{/if}
{if $template.disabled} disabled{/if}
{if $template.tooltip} title="{$template.tooltip|strip_tags}"{/if}
>
{$template.template_name|strip_tags}
</option>
{/foreach}
</optgroup>
{/hook}
</select>
</div>
</div>