{assign var="bf" value=$product.header_features[$settings.abt__ut2.general.brand_feature_id] scope=parent}
{if empty($bf)}
    {$bf = fn_array_value_to_key($product.header_features, 'feature_id')}
    {assign var="bf" value=$bf[$settings.abt__ut2.general.brand_feature_id] scope=parent}
{/if}

{if empty($bf)}
    {assign var="bf" value=$product.product_features[$settings.abt__ut2.general.brand_feature_id] scope=parent}
{/if}

{if empty($bf)}
    {$bf = fn_array_value_to_key($product.product_features, 'feature_id')}
    {assign var="bf" value=$bf[$settings.abt__ut2.general.brand_feature_id] scope=parent}
{/if}

{if empty($bf)}
    {$bf = ['product_id' => $product.product_id, 'variants_selected_only' => true, 'feature_id' => $settings.abt__ut2.general.brand_feature_id, 'variants' => true, 'existent_only' => true]|fn_get_product_features}
    {if $bf}
        {assign var="bf" value=$bf[0][$settings.abt__ut2.general.brand_feature_id] scope=parent}
    {/if}
{/if}