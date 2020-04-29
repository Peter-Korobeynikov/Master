{if $cart.chosen_shipping.$group_key}
    {assign var="shipping_info" value=$cart.chosen_shipping.$group_key|fn_get_shipping_info}
    {if $shipping_info.show_pvz=='Y'}
        <div class="hidden">
                <input type="hidden" name="token" value="{$addons.cp_salesbeat.api_key}">
                {if $cart.user_data.s_city_id}
                    <input type="hidden" name="city_by" value="city_code">
                {else}
                    <input type="hidden" name="city_by" value="ip">
                {/if}
                <input type="hidden" name="params_by" value="params">
                <input type="hidden" name="city_id" value="{$cart.user_data.s_city_id}">
                {foreach from=$cart_products item="product" name="foo"}
                    {assign var="shipping_params" value=$product.product_id|fn_salesbeat_get_shipping_params}
                    <input type="hidden" name="products[{$product.product_id}][price_to_pay]" value="{$product.price|round}">
                    {if !$product.price_insurance}
                        <input type="hidden" name="products[{$product.product_id}][price_insurance]" value="{$product.price|round}">
                    {else}
                        <input type="hidden" name="products[{$product.product_id}][price_insurance]" value="{$product.price_insurance|round}">
                    {/if}
                    <input type="hidden" name="products[{$product.product_id}][weight]" value="{$product.product_id|fn_salesbeat_get_product_weight|round}">
                    <input type="hidden" name="products[{$product.product_id}][quantity]" value="{$product.amount}">

                    {if $shipping_params.box_length}
                        <input type="hidden" name="products[{$product.product_id}][x]" value="{$shipping_params.box_length|round}">
                    {/if}
                    {if $shipping_params.box_width}
                        <input type="hidden" name="products[{$product.product_id}][y]" value="{$shipping_params.box_width|round}">
                    {/if}
                    {if $shipping_params.box_height}
                        <input type="hidden" name="products[{$product.product_id}][z]" value="{$shipping_params.box_height|round}">
                    {/if}
                {/foreach}
                <input type="hidden" name="delivery_method_id" value="{$cart.product_groups.0.chosen_shippings.0.salesbeat_id}">

                {if $cart.user_data.s_pvz_id&&$shipping_info.salesbeat_id==$cart.user_data.s_sb_id}
                    <input type="hidden" name="pvz_id" value="{$cart.user_data.s_pvz_id}">
                {/if}
        </div>
        <div id="sb-cart-pvz-map" class="sb-fullscreen-map"></div>     
    {/if}
{/if}
