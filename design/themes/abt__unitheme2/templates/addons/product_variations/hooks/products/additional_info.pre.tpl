{$limit = $settings.abt__ut2.product_list.limit_product_variations}

{if $settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] != 'variations' && $settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] != 'features_and_variations'}
	{$limit = 0}
{/if}

{if $show_features && $product.variation_features_variants}
    {capture name="variation_features_variants"}
        {$product.variation_features_variants=fn_abt__ut2_prepare_variation_features_variants($product.variation_features_variants, $product.abt__ut2_features)}

        {foreach $product.variation_features_variants as $variation_feature}
            {if $variation_feature.display_on_catalog === "Y"}
                {if $limit > 0}
                <div class="ut2-lv__features-item">
                    <p class="ut2-lv__features-description">
                        {$variation_feature.description}:
                    </p>

                    {foreach $variation_feature.variants as $variant name=variants}
						{if $smarty.foreach.variants.iteration <= $limit}
                        {* Color varian not work *}
                        {if $variation_feature.filter_style == 'color'}
                            <span class="ut2-lv__color-variant{if $variant.active} active{/if}" style="background-color: {$variant.color}">&nbsp;</span>
                        {else}
                            <span class="ut2-lv__features-variant{if $variant.active} active{/if}">
	                       {$variant.variant}
	                    </span>
                        {/if}
						{/if}
                    {/foreach}
                    {if $variation_feature.variants|count > $limit}<span class="ut2-lv__more">({__("more")} +{$variation_feature.variants|count - $limit})</span>{/if}
                </div>
                {/if}
            {/if}
        {/foreach}
    {/capture}
    {if $smarty.capture.variation_features_variants|trim}
        <div class="ut2-lv__item-features">
            {$smarty.capture.variation_features_variants nofilter}
        </div>
    {/if}
{/if}