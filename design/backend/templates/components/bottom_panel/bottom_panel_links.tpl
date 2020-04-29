{* DELETE ME *}
{if $runtime.controller == "categories" && $runtime.mode === "view"}
    {$admin_panel_link = "products.manage?cid=`$category_data.category_id`&subcats=Y" scope=parent}
{elseif $runtime.controller == "products" && $runtime.mode === "view"}
    {$admin_panel_link = "products.update?product_id=`$product.product_id`" scope=parent}
{elseif $runtime.controller == "checkout" && $runtime.mode === "cart"}
    {$admin_panel_link = "cart.cart_list" scope=parent}
{elseif $runtime.controller == "checkout" && $runtime.mode === "checkout"}
    {$admin_panel_link = "orders.manage" scope=parent}
{elseif $runtime.controller == "profiles" && $runtime.mode === "update"}
    {$admin_panel_link = "profiles.manage?user_type=C" scope=parent}
{elseif $runtime.controller == "orders" && $runtime.mode === "search"}
    {$admin_panel_link = "orders.manage" scope=parent}
{elseif $runtime.controller == "orders" && $runtime.mode === "details"}
    {$admin_panel_link = "orders.details?order_id=`$order_info.order_id`" scope=parent}
{elseif $runtime.controller == "pages" && $runtime.mode === "view" && $page.page_type === "B" && !$page.parent_id}
    {$admin_panel_link = "pages.manage?page_type=B" scope=parent}
{elseif $runtime.controller == "pages" && $runtime.mode === "view" && $page.page_type === "B"}
    {$admin_panel_link = "pages.update?page_id=`$page.page_id`&come_from=B" scope=parent}
{elseif $runtime.controller == "pages" && $runtime.mode === "view"}
    {$admin_panel_link = "pages.update?page_id=`$page.page_id`" scope=parent}
{elseif $runtime.controller == "gift_certificates" && $runtime.mode === "add"}
    {$admin_panel_link = "gift_certificates.manage" scope=parent}
{elseif $runtime.controller == "product_features" && $runtime.mode === "view_all"}
    {$admin_panel_link = "product_features.update?feature_id=18&selected_section=variants_18" scope=parent}
{elseif $runtime.controller == "discussion" && $runtime.mode === "view"}
    {$admin_panel_link = "discussion.update?discussion_type=E" scope=parent}
{elseif $runtime.controller == "sitemap" && $runtime.mode === "view"}
    {$admin_panel_link = "sitemap.manage" scope=parent}
{elseif $runtime.controller == "rma" && $runtime.mode === "returns"}
    {$admin_panel_link = "rma.returns" scope=parent}
{elseif $runtime.controller == "rma" && $runtime.mode === "details"}
    {$admin_panel_link = "rma.details?return_id=`$return_info.return_id`" scope=parent}
{elseif $runtime.controller == "product_features" && $runtime.mode === "compare"}
    {$admin_panel_link = "product_features.manage" scope=parent}
{elseif $runtime.controller == "auth" && $runtime.mode === "login_form"}
    {$admin_panel_link = "hybrid_auth.manage" scope=parent}
{elseif $runtime.controller == "products" && $runtime.mode === "search"}
    {$admin_panel_link = "products.manage" scope=parent}
{elseif $runtime.controller == "promotions" && $runtime.mode === "view"}
    {$admin_panel_link = "promotions.manage" scope=parent}
{elseif $runtime.controller == "tags" && $runtime.mode === "view"}
    {$admin_panel_link = "tags.manage" scope=parent}
{elseif $runtime.controller == "vendor_communication" && $runtime.mode === "threads"}
    {$admin_panel_link = "vendor_communication.threads" scope=parent}
{elseif $runtime.controller == "companies" && $runtime.mode === "vendor_plans"}
    {$admin_panel_link = "vendor_plans.manage" scope=parent}
{elseif $runtime.controller == "companies" && $runtime.mode === "apply_for_vendor"}
    {$admin_panel_link = "vendor_plans.manage" scope=parent}
{elseif $runtime.controller == "companies" && $runtime.mode === "catalog"}
    {$admin_panel_link = "profiles.manage?user_type=V" scope=parent}
{elseif $runtime.controller == "companies" && $runtime.mode === "products"}
    {$admin_panel_link = "products.manage?company_id=`$company_id`" scope=parent}
{elseif $runtime.controller == "companies" && $runtime.mode === "view"}
    {$admin_panel_link = "profiles.update?user_id=`$company_data.company_id`&user_type=V" scope=parent}
{elseif $runtime.controller == "store_locator" && $runtime.mode === "search"}
    {$admin_panel_link = "store_locator.manage" scope=parent}
{else}
    {$admin_panel_link = "" scope=parent}
{/if}
{* /DELETE ME *}
