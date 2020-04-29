<meta property="og:type" content="website" />
<meta property="og:locale" content="{""|fn_abt__ut2_get_locale}" />
<meta property="og:title" content="{$smarty.capture.page_title|strip|trim nofilter}" />
<meta property="og:description" content="{$meta_description|html_entity_decode:$smarty.const.ENT_COMPAT:"UTF-8"|default:$location_data.meta_description}" />
<meta property="og:url" content="{$config.current_url|fn_url}" />
{if $runtime.controller == 'categories' && $category_data && $category_data.main_pair}
    <meta property="og:image" content="{$category_data.main_pair.detailed.image_path}" />
{elseif $runtime.controller == 'products' && $product && $product.main_pair}
    <meta property="og:image" content="{$product.main_pair.detailed.image_path}" />
{elseif $runtime.controller == 'pages' && $page && $page.main_pair}
    <meta property="og:image" content="{$page.main_pair.icon.image_path}" />
{elseif $runtime.controller == 'product_features' && $variant_data && $variant_data.image_pair}
    <meta property="og:image" content="{$variant_data.image_pair.icon.image_path}" />
{else}
    {hook name="abt__unitheme:og_image"}
        <meta property="og:image" content=" {$logos.theme.image.image_path}" />
    {/hook}
{/if}
