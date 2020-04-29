{if $min_shipping_method}
    <table class="ty-shipping-methods-list ty-table">
    <thead>
        <tr>
            <th colspan="2" class="ty-shipping-methods__title">{__('transport_company')}</th>
            <th class="ty-shipping-methods__title">{__('information')}</th>
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
                <td class="ty-shipping-methods__description ty-shipping-methods__information">{$shipping_method.description nofilter}</td>
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
                    <div class="ty-shipping-methods__estimate-text">{$shipping_method.delivery_time nofilter}</div>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}