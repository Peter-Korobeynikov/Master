<fieldset>
    {if $code == 'russian_pochta'}

        <div class="control-group">
            <label for="ship_russian_post_object_type" class="control-label">{__("shipping.russianpost.russian_post_sending_type")}:</label>
            <div class="controls">
                <select id="ship_russian_post_object_type" name="shipping_data[service_params][object_type]">
                    {foreach from=$sending_objects item="object_group"}
                        <optgroup label="{$object_group.title}">
                            {foreach from=$object_group.variants item="object_type" key="object_code"}
                                <option value={$object_code} {if $shipping.service_params.object_type == $object_code}selected="selected"{/if}>{$object_type}</option>
                            {/foreach}
                        </optgroup>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="control-group">
            <label for="ship_russian_post_sending_packages" class="control-label">{__("shipping.russianpost.russian_post_sending_packages")}:</label>
            <div class="controls">
                <select id="ship_russian_post_sending_package" name="shipping_data[service_params][sending_package]">
                    {foreach from=$sending_packages item="s_package" key="k_package"}
                        <option value={$k_package} {if $shipping.service_params.sending_package == $k_package}selected="selected"{/if}>{$s_package}</option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="control-group">
            <label for="ship_russian_post_shipping_option" class="control-label">{__("shipping.russianpost.russian_post_shipping_option")}:</label>
            <div class="controls">
                <select id="ship_russian_post_shipping_option" name="shipping_data[service_params][isavia]">
                    <option value="0" {if $shipping.service_params.isavia == "0"}selected="selected"{/if}>{__("addons.rus_russianpost.ground")}</option>
                    <option value="1" {if $shipping.service_params.isavia == "1"}selected="selected"{/if}>{__("addons.rus_russianpost.avia_possible")}</option>
                    <option value="2" {if $shipping.service_params.isavia == "2"}selected="selected"{/if}>{__("addons.rus_russianpost.avia")}</option>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="ship_russian_post_delivery">{__("shipping.russianpost.russian_post_cash_on_delivery")}:</label>
            <div class="controls">
                <input id="ship_russian_post_delivery" type="text" name="shipping_data[service_params][cash_on_delivery]" size="30" value="{$shipping.service_params.cash_on_delivery}" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="ship_russian_post_average_quantity_in_packet">{__("shipping.russianpost.average_quantity_in_packet")}:</label>
            <div class="controls">
                <input id="ship_russian_post_average_quantity_in_packet" type="text" name="shipping_data[service_params][average_quantity_in_packet]" size="30" value="{$shipping.service_params.average_quantity_in_packet}" />
            </div>
        </div>

        {include file="addons/rus_russianpost/views/shippings/components/services/russian_post_services.tpl" sending_services=$sending_services}

        {include file="common/subheader.tpl" title=__("shippings.russianpost.data_tracking")}

        <div class="control-group">
            <label class="control-label" for="ship_russian_post_login">{__("shipping.russianpost.russian_post_login")}:</label>
            <div class="controls">
                <input id="ship_russian_post_login" type="text" name="shipping_data[service_params][api_login]" size="30" value="{$shipping.service_params.api_login}" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="ship_russian_post_password">{__("shipping.russianpost.russian_post_password")}:</label>
            <div class="controls">
                <input id="ship_russian_post_password" type="text" name="shipping_data[service_params][api_password]" size="30" value="{$shipping.service_params.api_password}" />
            </div>
        </div>

    {elseif $code == 'russian_post_calc'}

        <div class="control-group">
            <label class="control-label" for="user_key">{__("authentication_key")}</label>
            <div class="controls">
                <input id="user_key" type="text" name="shipping_data[service_params][user_key]" size="30" value="{$shipping.service_params.user_key}"/>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="user_key_password">{__("authentication_password")}</label>
            <div class="controls">
                <input id="user_key_password" type="password" name="shipping_data[service_params][user_key_password]" size="30" value="{$shipping.service_params.user_key_password}" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="package_type">{__("russianpost_shipping_type")}</label>
            <div class="controls">
                <select id="package_type" name="shipping_data[service_params][shipping_type]">
                    <option value="rp_main" {if $shipping.service_params.shipping_type == "rp_main"}selected="selected"{/if}>{__("ship_russianpost_shipping_type_rp_main")}</option>
                    <option value="rp_1class" {if $shipping.service_params.shipping_type == "rp_1class"}selected="selected"{/if}>{__("ship_russianpost_shipping_type_rp_1class")}</option>
                </select>
            </div>
        </div>

        <span>{__("ship_russianpost_register_text")}</span>
    {/if}

</fieldset>

{if $code == 'russian_pochta'}
    {script src="js/addons/rus_russianpost/russian_pochta.js"}
{/if}
