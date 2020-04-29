{if $clone && $abt__ut2_bt_generator}
<td>
<input type="text" class="hidden" id="item_price_bt_abt__ut2_generator_{$ldelim}bt_id{$rdelim}" value="{$ldelim}price{$rdelim}">
{include file="common/price.tpl" span_id="item_display_price_bt_abt__ut2_generator_`$ldelim`bt_id`$rdelim`_"}
</td>
<td>
<select name="{$input_name}[modifier_type]" class="input-slarge" id="item_modifier_type_bt_abt__ut2_generator_{$ldelim}bt_id{$rdelim}">
<option value="by_fixed">{__("by_fixed")}</option>
<option value="to_fixed">{__("to_fixed")}</option>
<option value="by_percentage">{__("by_percentage")}</option>
<option value="to_percentage">{__("to_percentage")}</option>
</select>
</td>
<td>
<input type="text" class="cm-chain-abt__ut2_generator hidden" value="{$ldelim}bt_id{$rdelim}" />
<input type="text" class="hidden" id="{$ldelim}bt_id{$rdelim}" value="abt__ut2_generator" />
<input type="text" name="{$input_name}[modifier]" id="item_modifier_bt_abt__ut2_generator_{$ldelim}bt_id{$rdelim}" size="4" value="0" class="input-mini">
</td>
<td>
{include file="common/price.tpl" span_id="item_discounted_price_bt_abt__ut2_generator_`$ldelim`bt_id`$rdelim`_"}
</td>
{/if}