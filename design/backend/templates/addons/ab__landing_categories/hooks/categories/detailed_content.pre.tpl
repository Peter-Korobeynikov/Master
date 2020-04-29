{if 'ab__lc.data.view'|fn_check_view_permissions:"GET"}
{include file="common/subheader.tpl" title=__("ab__lc_catalog") target="#ab__lc_catalog"}
<div id="ab__lc_catalog" class="in collapse{if !'ab__lc.data.manage'|fn_check_view_permissions:'POST'} cm-hide-inputs{/if}">
<div class="control-group">
<label class="control-label" for="ab__lc_catalog_image_control">{__("ab__lc.catalog.image_control")}:</label>
<div class="controls">
<select name="category_data[ab__lc_catalog_image_control]" id="ab__lc_catalog_image_control" class="input-large">
<option {if $category_data.ab__lc_catalog_image_control == 'none'}selected="selected"{/if} value="none">{__("ab__lc.catalog.image_control.none")}</option>
<option {if $category_data.ab__lc_catalog_image_control == 'top'}selected="selected"{/if} value="top">{__("ab__lc.catalog.image_control.top")}</option>
<option {if $category_data.ab__lc_catalog_image_control == 'left'}selected="selected"{/if} value="left">{__("ab__lc.catalog.image_control.left")}</option>
</select>
</div>
</div>
<div class="control-group">
<label class="control-label">{__("ab__lc.catalog.icon")}:</label>
<div class="controls">
{include file="common/attach_images.tpl"
image_name="ab__lc_catalog_icon"
image_object_type="ab__lc_catalog_icon"
image_key=$id
hide_titles=true
no_detailed=true
hide_alt=true
image_pair=$category_data.ab__lc_catalog_icon}
</div>
</div>
</div>
{include file="common/subheader.tpl" title=__("ab__lc_landing_category") target="#ab__lc_landing_category"}
<div id="ab__lc_landing_category" class="in collapse{if !'ab__lc.data.manage'|fn_check_view_permissions:'POST'} cm-hide-inputs{/if}">
<div class="control-group">
<label class="control-label" for="ab__lc_landing">{__("ab__lc.landing_category.landing")}:</label>
<div class="controls">
<input type="hidden" value="N" name="category_data[ab__lc_landing]">
<input type="checkbox" value="Y" id="ab__lc_landing" name="category_data[ab__lc_landing]" {if $category_data.ab__lc_landing == 'Y'}checked="checked"{/if}>
</div>
</div>
<div class="control-group">
<label class="control-label" for="ab__lc_subsubcategories">{__("ab__lc.landing_category.subsubcategories")}:</label>
<div class="controls">
<input type="text" name="category_data[ab__lc_subsubcategories]" id="ab__lc_subsubcategories" value="{$category_data.ab__lc_subsubcategories|default:0}" class="input-text-short" />
</div>
</div>
</div>
{include file="common/subheader.tpl" title=__("ab__lc.control_subcategory_structure") target="#ab__lc_control_subcategory_structure"}
<div id="ab__lc_control_subcategory_structure" class="in collapse{if !'ab__lc.data.manage'|fn_check_view_permissions:'POST'} cm-hide-inputs{/if}">
<div class="control-group">
<label class="control-label" for="ab__lc_menu_id">{__("ab__lc.control_subcategory_structure.menu_id")}:</label>
<div class="controls">
<input type="text" name="category_data[ab__lc_menu_id]" id="ab__lc_menu_id" value="{$category_data.ab__lc_menu_id|default:0}" class="input-text-short cm-trim" />
{if $category_data.ab__lc_menu_id|default:0 == 0}
{if !$runtime.simple_ultimate and !$runtime.company_id}
{__('ab__lc.control_subcategory_structure.add_menu.info.multiplestore')}
{else}
{btn type="text" text="{__('ab__lc.control_subcategory_structure.add_menu')}" title="{__('ab__lc.control_subcategory_structure.add_menu.info')}" class="cm-tooltip btn" href="{"categories.add__ab__lc_menu&category_id={$category_data.category_id}"|fn_url}"}
{/if}
{else}
<a target="_blank" href="{"static_data.manage&section=A&menu_id={$category_data.ab__lc_menu_id}"|fn_url}">{__('manage_items')}: {$category_data.ab__lc_menu_id|fn_ab__lc_get_menu_name}</a>
{/if}
</div>
</div>
<div class="control-group">
<label class="control-label" for="ab__lc_how_to_use_menu">{__("ab__lc.control_subcategory_structure.how_to_use_menu")}:</label>
<div class="controls">
<select name="category_data[ab__lc_how_to_use_menu]" id="ab__lc_how_to_use_menu" class="input-large">
<option value="N"{if $category_data.ab__lc_how_to_use_menu == 'N'} selected="selected"{/if}>{__('ab__lc.control_subcategory_structure.how_to_use_menu.not_to_use')}</option>
<option value="P"{if $category_data.ab__lc_how_to_use_menu == 'P'} selected="selected"{/if}>{__('ab__lc.control_subcategory_structure.how_to_use_menu.prepend')}</option>
<option value="A"{if $category_data.ab__lc_how_to_use_menu == 'A'} selected="selected"{/if}>{__('ab__lc.control_subcategory_structure.how_to_use_menu.append')}</option>
<option value="R"{if $category_data.ab__lc_how_to_use_menu == 'R'} selected="selected"{/if}>{__('ab__lc.control_subcategory_structure.how_to_use_menu.replace')}</option>
</select>
</div>
</div>
<div class="control-group">
<label class="control-label" for="ab__lc_control_subcategory_structure_inherit_control">{__("ab__lc.control_subcategory_structure.inherit_control")}:</label>
<div class="controls">
<input type="hidden" value="N" name="category_data[ab__lc_inherit_control]">
<input type="checkbox" value="Y" id="ab__lc_control_subcategory_structure_inherit_control" name="category_data[ab__lc_inherit_control]" {if $category_data.ab__lc_inherit_control == 'Y'}checked="checked"{/if}>
</div>
</div>
</div>
{/if}