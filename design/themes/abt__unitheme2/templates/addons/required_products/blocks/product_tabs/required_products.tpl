{** block-description:required_products **}

{if $product.required_products}

	{if $settings.abt__ut2.products.addon_required_products.list_type[$settings.abt__device] == 'grid_list'}
	
		{$tmpl='products_multicolumns'}
		{$columns=$settings.abt__ut2.products.addon_required_products.item_quantity[$settings.abt__device]}

		{include file="blocks/list_templates/grid_list.tpl"
			products=$product.required_products
			columns=$columns
			details_page=false
			show_name=true
			show_sku=$settings.abt__ut2.product_list.$tmpl.show_sku[$settings.abt__device] == 'Y'
			show_rating=true
			show_old_price=true
			show_price=true
			show_clean_price=true
			show_list_discount=$settings.abt__ut2.product_list.$tmpl.show_you_save[$settings.abt__device] == 'Y'
			show_discount_label=false
        	show_shipping_label=false
			show_product_amount=false
			show_product_options=false
			show_qty=$settings.abt__ut2.product_list.$tmpl.show_qty[$settings.abt__device] == 'Y'
			hide_qty_label=true
			show_min_qty=false
			show_add_to_cart=$settings.abt__ut2.product_list.$tmpl.show_buttons[$settings.abt__device] == 'Y'
			show_features=$settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'features'
			show_descr=$settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'description'
            show_brand_logo=$settings.abt__ut2.product_list.$tmpl.show_brand_logo[$settings.abt__device] == 'Y'
			show_list_buttons=false
			show_custom_class="ut2-rp__grid"
			but_role="action"
			no_pagination=true
			no_sorting=true
			obj_prefix="required_products"
		}

	{elseif $settings.abt__ut2.products.addon_required_products.list_type[$settings.abt__device] == 'product_list'}
		{$tmpl='products_without_options'}
		{include file="blocks/list_templates/products_list.tpl"
			products=$product.required_products
			show_product_status="Y"
			details_page=false
			show_name=true
			show_sku=$settings.abt__ut2.product_list.$tmpl.show_sku[$settings.abt__device] == 'Y'
			show_rating=true 
			show_features=true 
			show_prod_descr=true 
			show_descr=true 
			show_old_price=true 
			show_price=true 
			show_clean_price=true 
			show_list_discount=false 
			show_discount_label=true
        	show_shipping_label=false
			show_product_amount=$settings.abt__ut2.product_list.$tmpl.show_amount[$settings.abt__device] == 'Y'
			show_product_options=$settings.abt__ut2.product_list.$tmpl.show_options[$settings.abt__device] == 'Y'
			show_qty=$settings.abt__ut2.product_list.$tmpl.show_options[$settings.abt__device] == 'Y'
			show_min_qty=true 
			show_product_edp=true 
			show_add_to_cart=true 
			show_list_buttons=false 
			show_custom_class="ut2-rp__products"
			but_role="action" 
			no_pagination=true 
			no_sorting=true 
			obj_prefix="required_products" 
		}

	{elseif $settings.abt__ut2.products.addon_required_products.list_type[$settings.abt__device] == 'compact_list'}
		{$tmpl='short_list'}
		{include file="blocks/list_templates/compact_list.tpl" 
			products=$product.required_products 
			show_product_status="Y" 
			details_page=false 
			show_name=true 
			show_sku=$settings.abt__ut2.product_list.$tmpl.show_sku[$settings.abt__device] == 'Y'
			show_rating=true 
			show_price=true 
			show_old_price=true 
			show_clean_price=false 
			show_qty=$settings.abt__ut2.product_list.$tmpl.show_qty[$settings.abt__device] == 'Y'
			hide_qty_label=true
        	show_shipping_label=false
			show_product_options=false
			show_add_to_cart=$settings.abt__ut2.product_list.$tmpl.show_button[$settings.abt__device] == 'Y'
			show_list_buttons=false
			but_role="action" 
			hide_form=true 
			show_custom_class="ut2-rp__compact"
			no_pagination=true 
			no_sorting=true 
			obj_prefix="required_products" 
		}	
	{/if}
{/if}
