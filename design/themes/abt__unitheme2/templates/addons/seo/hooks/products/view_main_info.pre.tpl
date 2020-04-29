<div itemscope itemtype="http://schema.org/Product">
    <meta itemprop="sku" content="{$product.seo_snippet.sku}" />
    <meta itemprop="name" content="{$product.seo_snippet.name}" />
    <meta itemprop="description" content="{$product.seo_snippet.description}" />
    {foreach $product.seo_snippet.images as $image}
        <meta itemprop="image" content="{$image}" />
    {/foreach}

    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <link itemprop="url" href="{"products.view?product_id=`$product.product_id`"|fn_url}">
        <link itemprop="availability" href="http://schema.org/{$product.seo_snippet.availability}" />

        {if $product.seo_snippet.show_price}
            <meta itemprop="priceCurrency" content="{$product.seo_snippet.price_currency}"/>
            <meta itemprop="price" content="{$product.seo_snippet.price}"/>
        {/if}
    </div>

    {$brand = $product.product_id|fn_abt__ut2_get_product_brand}
    {if $brand}
        <div itemprop="brand" itemscope itemtype="http://schema.org/Thing">
            <meta itemprop="name" content="{$brand}" />
        </div>
    {/if}

    {hook name="products:seo_snippet_attributes"}
    {/hook}
</div>
