{math equation="rand()" assign="rnd"}
{assign var="data_id" value="`$data_id`_`$rnd`"}
{assign var="view_mode" value=$view_mode|default:"mixed"}
{script src="js/tygh/picker.js"}
{if $view_mode != "list"}
{if $extra_var}
{assign var="extra_var" value=$extra_var|escape:url}
{/if}
{if !$no_container}<div class="buttons-container">{/if}{if $picker_view}[{/if}
{include file="buttons/button.tpl" but_id="opener_picker_`$data_id`" but_href="menus.abt__ut2_picker?display=`$display`&picker_for=`$picker_for`&extra=`$extra_var`&checkbox_name=`$checkbox_name`&aoc=`$aoc`&data_id=`$data_id`"|fn_url but_text=$but_text|default:__("abt__ut2.menus.pickers.add_menus") but_role="add" but_target_id="content_`$data_id`" but_meta="cm-dialog-opener btn pull-right" but_icon="icon-plus"}
{if $picker_view}]{/if}{if !$no_container}</div>{/if}
<div class="hidden" id="content_{$data_id}" title="{$but_text|default:__("abt__ut2.menus.pickers.add_menus")}">
</div>
{/if}
{if $view_mode != "button"}
{if !$positions}
<input id="b{$data_id}_ids" type="hidden" name="{$input_name}" value="{if $item_ids}{","|implode:$item_ids}{/if}" class="input-micro" />
{/if}
<div class="table-wrapper" id="table-wrapper_{$data_id}">
<table width="100%" class="table table-middle">
<thead>
<tr>
{if $positions}<th>{__("position_short")}</th>{/if}
<th width="40%">{__("name")}</th>
<th width="30%">{__("user_class")}</th>
<th width="30%">{__("abt__ut2.menus.pickers.states")}{include file="common/tooltip.tpl" tooltip=__("abt__ut2.menus.pickers.states.tooltip")}</th>
<th>&nbsp;</th>
</tr>
</thead>
<tbody id="{$data_id}"{if !$item_ids} class="hidden"{/if}>
{include file="addons/abt__unitheme2/pickers/menus/js.tpl" menu_id="`$ldelim`menu_id`$rdelim`" holder=$data_id input_name=$input_name clone=true hide_link=$hide_link hide_delete_button=$hide_delete_button position_field=$positions position="0"}
{if $item_ids}
{$sorted = $item_ids|uasort:'abt_ut2_sort_item_positions'}
{foreach name="items" from=$item_ids item="p_id"}
{include file="addons/abt__unitheme2/pickers/menus/js.tpl" menu_obj=$p_id menu_id=$p_id@key holder=$data_id input_name=$input_name clone=false hide_link=$hide_link hide_delete_button=$hide_delete_button first_item=$smarty.foreach.items.first position_field=$positions position=$smarty.foreach.items.iteration}
{/foreach}
{/if}
</tbody>
<tbody id="{$data_id}_no_item"{if $item_ids} class="hidden"{/if}>
<tr class="no-items">
<td colspan="{if $positions}5{else}4{/if}"><p>{$no_item_text|default:__("no_items")}</p></td>
</tr>
</tbody>
</table>
</div>
{/if}
<script>
$("#table-wrapper_{$data_id}").parents("form").on("submit", function(){
$(this).find("tr.cm-js-item.cm-clone.hidden").remove();
});
</script>