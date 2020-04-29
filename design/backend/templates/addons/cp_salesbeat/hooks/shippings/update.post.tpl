<div class="control-group">
    <label class="control-label">{__("cp_salesbeat_delivery")}:</label>
    <div class="controls">
        {if $addons.cp_salesbeat.api_key}
            {assign var="salesbeat_list" value=""|fn_cp_get_salesbeat_deliveries_list}
        {/if}
        <select name="shipping_data[salesbeat_id]" id="elm_salesbeat_id">
            <option value="">{__('none')}</option>
            {foreach from=$salesbeat_list  item="delivery"}
                <option value="{$delivery->id}"  {if $shipping.salesbeat_id == $delivery->id}selected="selected"{/if}>{$delivery->name}</option>
            {/foreach}
        </select>
    </div>
</div>
<div class="control-group">
    <label for="cp_salesbeat_show_pvz" class="control-label">{__("cp_salesbeat_show_pvz")}:</label>
    <div class="controls">
        <input type="hidden" name="shipping_data[show_pvz]" value="N">
        <input type="checkbox" name="shipping_data[show_pvz]" value="Y" {if $shipping.show_pvz=='Y'}checked{/if} id="cp_salesbeat_show_pvz">
    </div>
</div>
