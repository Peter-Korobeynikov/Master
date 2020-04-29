{** block-description:tmpl_grid **}

{if $block.properties.hide_add_to_cart_button == "Y"}
    {assign var="_show_add_to_cart" value=false}
{else}
    {assign var="_show_add_to_cart" value=true}
{/if}

{$tmpl='products_multicolumns'}

{include file="blocks/list_templates/grid_list.tpl"
products=$items
columns=$block.properties.number_of_columns
form_prefix="block_manager"
no_sorting="Y"
no_pagination="Y"
no_ids="Y"
obj_prefix="`$block.block_id`000"
item_number=$block.properties.item_number
show_name=true
show_old_price=true
show_price=true
show_rating=true
show_clean_price=true
show_list_discount=$settings.abt__ut2.product_list.$tmpl.show_you_save[$settings.abt__device] == 'Y'
hide_qty_label=true
show_sku_label=true
show_amount_label=false
show_sku=$settings.abt__ut2.product_list.$tmpl.show_sku[$settings.abt__device] == 'Y'
show_qty=$settings.abt__ut2.product_list.$tmpl.show_qty[$settings.abt__device] == 'Y'
show_features=$settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'features'
show_descr=$settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'description'
show_brand_logo=$settings.abt__ut2.product_list.$tmpl.show_brand_logo[$settings.abt__device] == 'Y'
show_list_buttons=false
show_add_to_cart=$_show_add_to_cart
but_role="action"
show_product_labels=true
show_discount_label=true
show_shipping_label=true}
