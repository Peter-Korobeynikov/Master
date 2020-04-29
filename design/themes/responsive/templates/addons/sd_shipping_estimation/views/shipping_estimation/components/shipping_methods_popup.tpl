<div class="ty-shipping-methods-list__link">
    {capture name="shipping_link"}
        {if !empty($shipping_methods_list)}
            {include file="addons/sd_shipping_estimation/views/shipping_estimation/components/shipping_methods_inscription.tpl"}
            {if isset($min_shipping_method)}
                {foreach from=$min_shipping_method item=shipping key=shipping_key}
                    <p>
                        {if $shipping.rate}
                            {$shipping.shipping nofilter}
                            <span class="ty-shipping-methods-list__link-price">
                                {include file="common/price.tpl" value=$shipping.rate}
                            </span>
                        {else}
                            <span class="ty-shipping-methods-list__link-price">
                                {__("free_shipping")}
                            </span>
                            {$shipping.shipping nofilter}
                        {/if}
                        {if $addons.sd_shipping_estimation.show_delivery_time == 'Y'}
                            <span class="ty-shipping-methods-list__link-data">{$shipping.delivery_time nofilter}</span>
                        {/if}
                    </p>
                {/foreach}
                {if !empty($shipping_methods_list) && $min_shipping_method|count != $shipping_methods_list|count}
                    <a class="cm-dialog-opener cm-dialog-auto-size" data-ca-target-id="shipping_methods_table" data-ca-dialog-class="shipping-method-list-popup">{__("addons.sd_shipping_estimation.show_all_shipping_methods")}</a>
                {/if}
            {/if}
        {/if}
    {/capture}
    {$smarty.capture.shipping_link nofilter}
</div>
<div class="hidden" id="shipping_methods_table">
    {include file="addons/sd_shipping_estimation/views/shipping_estimation/components/shipping_methods_inscription.tpl"}
    <table class="ty-shipping-methods-list ty-table">
        <thead>
            <tr>
                <th colspan="2" class="ty-shipping-methods__title">{__('transport_company')}</th>
                <th class="ty-shipping-methods__title">{__('cost_of_delivery')}</th>
                <th class="ty-shipping-methods__title">{__('estimated_delivery_time')}</th>
            </tr>
        </thead>
        <tbody>
        {foreach from=$shipping_methods_list item="shipping_method"}
            <tr>
                <td width="12%" class="ty-shipping-methods__description ty-shipping-methods__image ty-center">
                    <div>{include file="common/image.tpl" images=$shipping_method.icon image_width=100 obj_id="shipping_icon_`$shipping_method.shipping_id`"}</div>
                    <div class="ty-shipping-methods__transport-company-text">{$shipping_method.shipping nofilter}</div>
                </td>
                <td width="14%" class="ty-shipping-methods__description ty-shipping-methods__transport-company">
                    {$shipping_method.shipping nofilter}
                </td>
                <td width="17%" class="ty-shipping-methods__description ty-shipping-methods__cost">
                    <div class="ty-table__responsive-header-mobile">{__('cost_of_delivery')}</div>
                    <div class="ty-shipping-methods__cost-text">
                        {if $shipping_method.rate}
                            {include file="common/price.tpl" value=$shipping_method.rate}
                        {else}
                            {__("free_shipping")}
                        {/if}
                    </div>
                </td>
                <td width="17%" class="ty-shipping-methods__description ty-shipping-methods__estimate">
                    <div class="ty-table__responsive-header-mobile">{__('estimated_delivery_time')}</div>
                    <div class="ty-shipping-methods__estimate-text">
                        {$shipping_method.delivery_time nofilter}
                    </div>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>