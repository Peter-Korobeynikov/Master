{if $menu_id == "0"}
{assign var="menu" value=$default_name}
{else}
{assign var="menu" value=$menu_id|fn_get_menu_name|default:"`$ldelim`menu`$rdelim`"}
{/if}
<tr {if !$clone}id="{$holder}_{$menu_id}" {/if}class="cm-js-item{if $clone} cm-clone hidden{/if}">
{if $position_field}
<td>
<input type="text" name="{$input_name}[{$menu_id}][position]" value="{if $menu_obj.position && !$clone}{$menu_obj.position}{else}0{/if}" size="3" class="input-micro" {if $clone}disabled="disabled"{/if} />
</td>
{/if}
<td><a href="{"menus.update?menu_id=`$menu_id`"|fn_url}">{$menu}</a></td>
<td>
<input type="text" class="input-small" name="{$input_name}[{$menu_id}][abt__ut2_custom_class]" value="{$menu_obj.abt__ut2_custom_class}">
</td>
<td>
<select name="{$input_name}[{$menu_id}][abt__ut2_menu_state]">
<option{if $menu_obj.abt__ut2_menu_state == 'show_items'} selected="selected"{/if} value="show_items">{__("abt__ut2.menus.pickers.states.show_items")}</option>
<option{if $menu_obj.abt__ut2_menu_state == 'hide_items'} selected="selected"{/if} value="hide_items">{__("abt__ut2.menus.pickers.states.hide_items")}</option>
</select>
</td>
<td>
{capture name="tools_list"}
{if !$hide_delete_button && !$view_only}
<li><a onclick="Tygh.$.cePicker('delete_js_item', '{$holder}', '{$menu_id}', 'm'); return false;">{__("delete")}</a></li>
{/if}
{/capture}
<div class="hidden-tools">
{dropdown content=$smarty.capture.tools_list}
</div>
</td>
</tr>