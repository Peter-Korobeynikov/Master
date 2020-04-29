{** Request features **}
{if $show_features}
    {** request all available product features **}
    {$products=$products|fn_abt__ut2_add_products_features_list:0:true}
	
	{** required display only features to product variants **}
	{elseif $settings.abt__ut2.product_list.limit_product_variations > 0 && $settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'variations'}
		{assign scope='parent' var='show_features' value=true}
		
	{elseif $settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'features_and_variations'}
		{assign scope='parent' var='show_features' value=true}
		{$products=$products|fn_abt__ut2_add_products_features_list:0:true}

	{** request the BRAND feature for the products **}	
	{elseif $settings.abt__ut2.general.brand_feature_id > 0}
        {$products=$products|fn_abt__ut2_add_products_features_list:$settings.abt__ut2.general.brand_feature_id:true}
{/if}