{if $addons.ab__deal_of_the_day.ab__show_promos == 'Y' && $settings.abt__device == "desktop" && $product.promotions}
    <div class="ab-dotd-promos">
        {foreach $product.promotions as $promotion_id => $item}
            <div class="ab-dotd-category-promo" data-ca-promotion-id="{$promotion_id}"></div>
        {/foreach}
    </div>
{/if}