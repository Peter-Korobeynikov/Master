{if $destination_id}
{assign var="destination" value=$destination_id|fn_get_destination_name|default:"`$ldelim`destination`$rdelim`"}
{else}
{assign var="destination" value=$default_name}
{/if}
{if $multiple}
<tr {if !$clone}id="{$holder}_{$destination_id}" {/if}class="cm-js-item{if $clone} cm-clone hidden{/if}">
{if $position_field}<td><input type="text" name="{$input_name}[{$destination_id}]" value="{math equation="a*b" a=$position b=10}" size="3" class="input-micro"{if $clone} disabled="disabled"{/if} /></td>{/if}
<td><a href="{"destinations.update?destination_id=`$destination_id`"|fn_url}">{$destination}</a></td>
<td>
<div class="hidden-tools">
{if !$hide_delete_button && !$view_only}
{capture name="tools_list"}
<li>{btn type="list" text=__("remove") onclick="Tygh.$.cePicker('delete_js_item', '{$holder}', '{$destination_id}', 'd'); return false;"}</li>
{/capture}
{dropdown content=$smarty.capture.tools_list}
{/if}
</div>
</td>
{if !$hide_input}
<input {if $input_id}id="{$input_id}"{/if} type="hidden" name="{$input_name}" value="{$destination_id}" />
{/if}
</tr>
{else}
<span {if !$clone}id="{$holder}_{$destination_id}" {/if}class="cm-js-item no-margin{if $clone} cm-clone hidden{/if}">
{if !$first_item && $single_line}<span class="cm-comma{if $clone} hidden{/if}">,&nbsp;&nbsp;</span>{/if}
<input class="cm-picker-value-description {$extra_class}" type="text" value="{$destination}" {if $display_input_id}id="{$display_input_id}"{/if} size="10" name="destination_name" readonly="readonly" {$extra}>&nbsp;
</span>
{/if}