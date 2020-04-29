<fieldset>
<div class="control-group">
    <label class="control-label" for="pickpoint_width">{__("ship_width")}</label>
    <div class="controls">
        <input id="pickpoint_width" type="text" name="shipping_data[service_params][pickpoint_width]" size="30" value="{$shipping.service_params.pickpoint_width}" />
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="pickpoint_height">{__("ship_height")}</label>
    <div class="controls">
        <input id="pickpoint_height" type="text" name="shipping_data[service_params][pickpoint_height]" size="30" value="{$shipping.service_params.pickpoint_height}" />
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="pickpoint_length">{__("ship_length")}</label>
    <div class="controls">
        <input id="pickpoint_length" type="text" name="shipping_data[service_params][pickpoint_length]" size="30" value="{$shipping.service_params.pickpoint_length}" />
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="delivery_mode">{__("addons.rus_pickpoint.delivery_mode")}</label>
    <div class="controls">
        <select name="shipping_data[service_params][delivery_mode]" id="delivery_mode">
            <option value="Standard" {if $shipping.service_params.delivery_mode == "Standard"}selected="selected"{/if}>{__("addons.rus_pickpoint.standard")}</option>
            <option value="Priority" {if $shipping.service_params.delivery_mode == "Priority"}selected="selected"{/if}>{__("addons.rus_pickpoint.priority")}</option>
        </select>
    </div>
</div>
</fieldset>