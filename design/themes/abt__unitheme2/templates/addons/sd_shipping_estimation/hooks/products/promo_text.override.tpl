<!--overridden by sd_shipping_estimation-->

{hook name="products:promo_text"}
    <div class="ut2-pb__note">
        {if $product.promo_text}
            {$product.promo_text nofilter}
        {/if}

        {if $addons.sd_shipping_estimation.display_in_product_page == "promo"
            && ""|sd_ZTY0NTQwMTcxZDZhN2UwZGY2ZjBhNWI0
        }
            {include "addons/sd_shipping_estimation/views/shipping_estimation/shipping_methods_list.tpl"}
        {/if}

        {hook name = "sd_shipping_estimation:promo_text"}{/hook}
    </div>
{/hook}