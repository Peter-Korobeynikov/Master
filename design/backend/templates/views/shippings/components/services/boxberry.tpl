{script type="text/javascript" src="//insales.boxberry.ru/registration/js/boxberry.reg.js"}
{script src="js/addons/boxberry/boxberry.js"}
<fieldset>
    <div class="control-group">
        <label class="control-label" for="password">{__("boxberry.api_password")}</label>
        <div class="controls">
            <input id="password" type="text" name="shipping_data[service_params][password]" size="30" value="{$shipping.service_params.password}"/>
        </div>
        {if empty($shipping.service_params.password) }
            <div id="reg_to_boxberry" class="controls">
                <a href="#" onclick="{literal}boxberry_registration.open('token_callback',{cms:'CS-CART',version:'1.1'});return false;{/literal}">{__("boxberry.take_api_password")}</a>
            </div>
        {/if}
    </div>
    <!-- <div class="control-group">
        <label class="control-label" for="boxberry_target_start">{__("boxberry.target_start")}</label>
        <div class="controls">
            <input id="margin_percent" type="text" name="shipping_data[service_params][boxberry_target_start]" size="30" value="{$shipping.service_params.boxberry_target_start}"/>
        </div>
    </div> -->
    <div class="control-group">
        <label class="control-label" for="api_url">{__("boxberry.api_url")}</label>
        <div class="controls">
            <input id="api_url" type="text" name="shipping_data[service_params][api_url]" size="30" value="{if $shipping.service_params.api_url}
{$shipping.service_params.api_url}{else}https://api.boxberry.ru/json.php{/if}"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="widget_url">{__("boxberry.widget_url")}</label>
        <div class="controls">
            <input id="widget_url" type="text" name="shipping_data[service_params][widget_url]" size="30" value="{if $shipping.service_params.widget_url}{$shipping.service_params.widget_url}{else}https://points.boxberry.de/js/boxberry.js {/if}"/>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="sucrh">{__("boxberry.sucrh")}</label>
        <div class="controls">
            <select id="sucrh" name="shipping_data[service_params][sucrh]">
                <option value="1" {if ($shipping.service_params.sucrh)}selected{/if}>Применять</option>
                <option value="0" {if (!$shipping.service_params.sucrh)}selected{/if}>Не применять</option>
            </select>
        </div>
    </div>
    <div class="control-group">
    <label class="control-label" for="min_weight">{__("boxberry.min_weight")}</label>
    <div class="controls">
        <input id="min_weight" type="text" name="shipping_data[service_params][min_weight]" size="30" value="{if $shipping.service_params.min_weight}{$shipping.service_params.min_weight}{else}0{/if}"/>
    </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="max_weight">{__("boxberry.max_weight")}</label>
        <div class="controls">
            <input id="max_weight" type="text" name="shipping_data[service_params][max_weight]" size="30" value="{if $shipping.service_params.max_weight}{$shipping.service_params.max_weight}{else}31000{/if}"/>
        </div>
    </div>



    <div class="control-group">
        <label class="control-label" for="default_weight">{__("boxberry.default_weight")}</label>
        <div class="controls">
            <input id="default_weight" type="text" name="shipping_data[service_params][default_weight]" size="30" value="{if $shipping.service_params.default_weight}{$shipping.service_params.default_weight}{else}1000{/if}"/>
        </div>
    </div>
</fieldset>