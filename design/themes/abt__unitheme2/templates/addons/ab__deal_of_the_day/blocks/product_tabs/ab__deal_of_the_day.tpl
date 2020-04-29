{** block-description:ab__deal_of_the_day_title_product **}

{if $product.promotions && $product.promotions|count > 1}
    {$items = ['promotion_id' => $product.promotions|array_keys]|fn_ab__dotd_get_promotions}
    {include file="views/promotions/list.tpl" promotions=$items.0 show_chains=false}
{/if}