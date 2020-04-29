{assign scope='parent' var='show_name' value=true}
{assign scope='parent' var='show_sku' value=$settings.abt__ut2.product_list.$tmpl.show_sku[$settings.abt__device] == 'Y'}
{assign scope='parent' var='show_rating' value=true}
{assign scope='parent' var='show_price' value=true}
{assign scope='parent' var='show_old_price' value=true}
{assign scope='parent' var='show_clean_price' value=true}
{assign scope='parent' var='show_qty' value=$settings.abt__ut2.product_list.$tmpl.show_qty[$settings.abt__device] == 'Y'}
{assign scope='parent' var='show_add_to_cart' value=$settings.abt__ut2.product_list.$tmpl.show_button[$settings.abt__device] == 'Y'}
{assign scope='parent' var='but_role' value="action"}
{assign scope='parent' var='hide_form' value=true}
{assign scope='parent' var='hide_qty_label' value=true}
{assign scope='parent' var='show_product_labels' value=true}
{assign scope='parent' var='show_discount_label' value=true}
{assign scope='parent' var='show_shipping_label' value=true}
{assign scope='parent' var='ut2_load_more' value=$settings.abt__ut2.load_more.product_list == 'Y'}
