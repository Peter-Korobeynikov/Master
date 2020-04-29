{if $runtime.layout.theme_name == 'abt__unitheme2'}
{include file="common/subheader.tpl" title=__("abt__ut2.grid_settings") target="#abt__ut2_grid_settings_{$elm_id}"}
<div id="abt__ut2_grid_settings_{$elm_id}">
<div class="abt-ut2-doc">{__('abt__ut2.grid.tab_description')}</div>
<fieldset>
{** -------------------------------------------------------------------- **}
{** abt__ut2_padding **}
{** -------------------------------------------------------------------- **}
<div class="control-group cm-no-hide-input">
<label class="control-label" for="ext_grid_abt__ut2_padding_{$elm_id}">{__("abt__ut2.grid.padding")}</label>
<div class="controls">
<select id="ext_grid_abt__ut2_padding_{$elm_id}" name="abt__ut2_padding">
<option value="ut2-top"{if $grid.abt__ut2_padding == "ut2-top"} selected="selected"{/if}>{__("abt__ut2.grid.padding.variants.top")}</option>
<option value="ut2-bottom"{if $grid.abt__ut2_padding == "ut2-bottom"} selected="selected"{/if}>{__("abt__ut2.grid.padding.variants.bottom")}</option>
<option value="ut2-top-bottom"{if $grid.abt__ut2_padding == "ut2-top-bottom"} selected="selected"{/if}>{__("abt__ut2.grid.padding.variants.top_bottom")}</option>
<option value=""{if $grid.abt__ut2_padding == ""} selected="selected"{/if}>{__("abt__ut2.grid.padding.variants.none")}</option>
</select>
</div>
</div>
{** -------------------------------------------------------------------- **}
{** full_width **}
{** -------------------------------------------------------------------- **}
{if !$grid.parent_id && ( $grid.width + $grid.offset >= $runtime.layout.width ) }
<div class="control-group cm-no-hide-input">
<label class="control-label" for="ext_grid_full_width_{$elm_id}">{__("full_width")}</label>
<div class="controls">
<select id="ext_grid_full_width_{$elm_id}" name="abt__ut2_extended">
<option value="O" {if $grid.abt__ut2_extended == "O"}selected="selected"{/if}>{__("abt__ut2.extended.o")}</option>
<option value="E" {if $grid.abt__ut2_extended == "E"}selected="selected"{/if}>{__("abt__ut2.extended.e")}</option>
<option value="F" {if $grid.abt__ut2_extended == "F"}selected="selected"{/if}>{__("abt__ut2.extended.f")}</option>
</select>
</div>
</div>
{/if}
{** -------------------------------------------------------------------- **}
{** abt__ut2_grid_tabs **}
{** -------------------------------------------------------------------- **}
<div class="control-group cm-no-hide-input">
<label class="control-label" for="elm_grid_abt__ut2_show_in_tabs_{$elm_id}">{__("abt__ut2.grid_tabs.abt__ut2_show_in_tabs")}</label>
<div class="controls">
<input type="hidden" class="cm-no-hide-input" name="abt__ut2_show_in_tabs" value="N" />
<input id="elm_grid_abt__ut2_show_in_tabs_{$elm_id}"
type="checkbox"
class="cm-no-hide-input"
name="abt__ut2_show_in_tabs"
onclick="$(this).prop('checked') ? $('#grid_abt__ut2_use_ajax_{$elm_id}').removeClass('hidden') : $('#grid_abt__ut2_use_ajax_{$elm_id}').addClass('hidden')"
value="Y" {if $grid.abt__ut2_show_in_tabs == 'Y'}checked{/if} />
</div>
</div>
<div class="control-group cm-no-hide-input{if $grid.abt__ut2_show_in_tabs == 'N'} hidden{/if}" id="grid_abt__ut2_use_ajax_{$elm_id}">
<label class="control-label" for="elm_grid_abt__ut2_use_ajax_{$elm_id}">{__("abt__ut2.grid_tabs.abt__ut2_use_ajax")}</label>
<div class="controls">
<input type="hidden" class="cm-no-hide-input" name="abt__ut2_use_ajax" value="N" />
<input id="elm_grid_abt__ut2_use_ajax_{$elm_id}" type="checkbox" class="cm-no-hide-input" name="abt__ut2_use_ajax" value="Y" {if $grid.abt__ut2_use_ajax == 'Y'}checked{/if} />
</div>
</div>
</fieldset>
</div>
{/if}