{if !$block_id}
    {$block_id = $block.block_id}
{/if}

{if !$block_type}
    {$block_type = $block.type}
{/if}

{$auto_load = $addons.sd_shipping_estimation.auto_load}

{$filter_crawlers = $addons.sd_shipping_estimation.filter_crawlers}

{foreach $tabs as $tab}
    {if $block_id == $tab.block_id}
        {$show_in_popup = $tab.show_in_popup}
        {break}
    {/if}
{/foreach}

{if !$show_in_popup}
    {$show_in_popup = "N"}
{/if}

{if $block_type == "shipping_estimation"}
    {include "addons/sd_shipping_estimation/views/shipping_estimation/components/shipping_methods_inscription.tpl"
        block_id = $block_id
    }
{/if}

{if $block_type == "shipping_estimation"
    && $show_in_popup == "N"
    && $auto_load == "N"
}
    {$show_loading = "N"}

    <div class="ty-buttons-container">
        <a
            class="hidden ty-btn__primary ty-btn"
            id="get_shipping_methods_list_{$block_id}"
        >
            {__("addons.sd_shipping_estimation.calculate_shipping")}
        </a>
    </div>
{else}
    {$show_loading = "Y"}
{/if}

{if !$quick_view}
    <div
        class="cm-ajax"
        data-ca-sd-shipping-estimation="Y"
        data-ca-block-id="{$block_id}"
        data-ca-block-type="{$block_type}"
        data-ca-product-id="{$product.product_id}"
        data-ca-show-in-popup="{$show_in_popup}"
        data-ca-auto-load="{$auto_load}"
        data-ca-filter-crawlers="{$filter_crawlers}"
        id="shipping_methods_list_{$block_id}">
        <div
            class="
                {if $shipping_methods_list || $show_loading == "N" || $is_ajax}
                    hidden
                {else}
                    shipping_loading
                {/if}
            "
        ></div>

        {if empty($location)}
            <p
                class="
                    ty-no-items
                    {if !$is_ajax || $block_type != "shipping_estimation"} hidden {/if}
                "
            >
                {__("text_no_shipping_methods")}
            </p>
        {elseif $shipping_methods_list}
            {if $block_type == "shipping_estimation"}
                {include "addons/sd_shipping_estimation/views/shipping_estimation/components/shipping_methods_block_table.tpl"}
            {else}
                {include "addons/sd_shipping_estimation/views/shipping_estimation/components/shipping_methods_popup.tpl"}
            {/if}
        {/if}
    <!--shipping_methods_list_{$block_id}--></div>
{/if}