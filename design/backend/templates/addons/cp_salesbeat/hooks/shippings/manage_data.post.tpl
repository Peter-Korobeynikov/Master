<td class="nowrap" data-th="{__("cp_salesbeat_delivery")}">
    <select name="shipping_data[{$shipping.shipping_id}][salesbeat_id]" >
        <option value="">{__('none')}</option>
        {foreach from=$salesbeat_list  item="delivery"}
            <option value="{$delivery->id}"  {if $shipping.salesbeat_id == $delivery->id}selected="selected"{/if}>{$delivery->name}</option>
        {/foreach}
    </select>
</td>
