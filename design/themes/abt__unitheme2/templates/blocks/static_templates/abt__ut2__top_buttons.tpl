{** block-description:tmpl_abt__ut2__top_buttons **}

{if $settings.General.enable_compare_products == "Y" && !$hide_compare_list_button || $product.feature_comparison == "Y"}
    {assign var="compared_products" value=""|fn_get_comparison_products}
    <div class="ut2-compared-products" id="abt__ut2_compared_products">
        <a class="{if !$runtime.customization_mode.live_editor}cm-tooltip{/if} ty-compare__a {if $compared_products|count > 0}active{/if}" href="{"product_features.compare"|fn_url}" rel="nofollow" title="{__("tmpl_abt__ut2__top_buttons.compare_list.tooltip")}"><i class="ut2-icon-baseline-equalizer"></i>{if $compared_products} <span class="count">{$compared_products|count}</span>{/if}</a>
        <!--abt__ut2_compared_products--></div>
{/if}

{if $addons.wishlist.status == "A" && !$hide_wishlist_button}
    {$wishlist_count = "fn_wishlist_get_count"|call_user_func}
    <div class="ut2-wishlist-count" id="abt__ut2_wishlist_count">
        <a class="{if !$runtime.customization_mode.live_editor}cm-tooltip{/if} ty-wishlist__a {if $wishlist_count > 0}active{/if}" href="{"wishlist.view"|fn_url}" rel="nofollow" title="{__("view_wishlist")}"><i class="ut2-icon-baseline-favorite-border"></i>{if $wishlist_count > 0}<span class="count">{$wishlist_count}</span>{/if}</a>
        <!--abt__ut2_wishlist_count--></div>
{/if}