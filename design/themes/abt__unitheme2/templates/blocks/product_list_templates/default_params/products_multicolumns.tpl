{assign scope='parent' var='show_trunc_name' value=true}
{assign scope='parent' var='show_rating' value=true}
{assign scope='parent' var='show_old_price' value=true}
{assign scope='parent' var='show_price' value=true}
{assign scope='parent' var='show_clean_price' value=true}
{assign scope='parent' var='show_list_discount' value=$settings.abt__ut2.product_list.$tmpl.show_you_save[$settings.abt__device] == 'Y'}
{assign scope='parent' var='hide_qty_label' value=true}
{assign scope='parent' var='show_sku_label' value=true}
{assign scope='parent' var='show_amount_label' value=false}
{assign scope='parent' var='show_add_to_cart' value=$settings.abt__ut2.product_list.$tmpl.show_buttons[$settings.abt__device] == 'Y'}
{assign scope='parent' var='show_sku' value=$settings.abt__ut2.product_list.$tmpl.show_sku[$settings.abt__device] == 'Y'}
{assign scope='parent' var='show_qty' value=$settings.abt__ut2.product_list.$tmpl.show_qty[$settings.abt__device] == 'Y'}
{assign scope='parent' var='show_features' value=$settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'features'}
{assign scope='parent' var='show_descr' value=$settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'description'}
{assign scope='parent' var='show_brand_logo' value=$settings.abt__ut2.product_list.$tmpl.show_brand_logo[$settings.abt__device] == 'Y'}
{assign scope='parent' var='show_list_buttons' value=false}
{assign scope='parent' var='but_role' value="action"}
{assign scope='parent' var='is_category' value=true}
{assign scope='parent' var='show_product_labels' value=true}
{assign scope='parent' var='show_discount_label' value=true}
{assign scope='parent' var='show_shipping_label' value=true}
{assign scope='parent' var='ut2_load_more' value=$settings.abt__ut2.load_more.product_list == 'Y'}