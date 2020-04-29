{strip}

{if $ab__mb_item.template_settings && $ab__mb_item.template_settings.brand_feature_id}
    {$mb__header_feature = fn_ab__mb_get_brand_feature(['product' => $product, 'feature_id' => $ab__mb_item.template_settings.brand_feature_id])}
{/if}

{hook name="ab__mb:tmpl_product_categories_list"}
<ul class="ab-mb-prod-categories-list">
{foreach fn_get_category_name($product.category_ids, $smarty.const.CART_LANGUAGE, true) as $mb_cat}
    <li>
        <a href="{"categories.view&category_id=`$mb_cat@key`"|fn_url}" target="_blank">{$mb_cat}</a>
        {if $mb__header_feature.features_hash} / <a href="{"categories.view&category_id=`$mb_cat@key`&features_hash=`$mb__header_feature.features_hash`"|fn_url}" target="_blank">{$mb__header_feature.variant}</a>{/if}
    </li>
{/foreach}
</ul>
{/hook}
{/strip}