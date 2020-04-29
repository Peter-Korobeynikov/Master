{$is_form_readonly = ($product.product_id && $runtime.company_id && (fn_allowed_for("MULTIVENDOR") || $product.shared_product == "Y") && $product.company_id != $runtime.company_id)}

{if $is_form_readonly}
    {$hide_inputs_if_shared_product = "cm-hide-inputs"}
    {$no_hide_input_if_shared_product = "cm-no-hide-input"}
{else}
    {$hide_inputs_if_shared_product = ""}
    {$no_hide_input_if_shared_product = ""}
{/if}

{$redirect_url="products.update?product_id={$product_id}&selected_section=variations"}

<div id="content_variations">
    <form action="{""|fn_url}" method="post" name="manage_variation_products_form" id="manage_variation_products_form" data-ca-main-content-selector="[data-ca-main-content]" class="js-manage-variation-products-form">
        <input type="hidden" value="{$redirect_url|fn_url}" name="redirect_url" class="{$no_hide_input_if_shared_product}">

        <div class="product-variations__toolbar">
            <div class="product-variations__toolbar-left">
                {if $group}
                    {include file="addons/product_variations/views/product_variations/components/group_code.tpl" group=$group}
                {elseif !$is_form_readonly}
                    {include file="addons/product_variations/views/product_variations/components/link_to_group.tpl"}
                {/if}
            </div>
            <div class="product-variations__toolbar-right cm-hide-with-inputs">
                {if $group}
                    {capture name="tools_list"}
                        {if $group}
                            <li>{btn type="list" id="manage_variations" text=__("product_variations.manage") href="products.manage?variation_group_id={$group->getId()}&is_search=Y"}</li>
                            <li>{btn type="list" id="edit_variations_features" text=__("product_variations.edit_features") href="product_features.manage?{http_build_query(["feature_id" => $selected_features|array_keys])}"}</li>

                            {if !$is_form_readonly}
                                <li>{btn type="list" id="delete_variations" class="cm-confirm" text=__("product_variations.delete") href="product_variations.delete?product_id=`$product_id`" method="POST"}</li>
                            {/if}
                        {/if}
                    {/capture}
                    {dropdown content=$smarty.capture.tools_list icon=" " text=__("actions")}
                {/if}
                {if !$is_form_readonly}
                    {include file="common/popupbox.tpl" id="update_product_group" text=__("product_variations.add_variations") href="product_variations.update?product_id=`$product_id`" link_text=__("product_variations.add_variations") act="general" icon="icon-plus" meta="shift-left"}
                {/if}
            </div>
        </div>

        {if $products}
            <div class="object-container product-variations__container">
                <table width="100%" class="table table-middle table--relative" data-ca-main-content>
                    <thead>
                    <tr>
                        <th width="2%">&nbsp;</th>
                        <th width="5%" class="product-variations__th-img">&nbsp;</th>
                        <th width="25%" class="nowrap"><span>{__("name")}</span></th>
                        <th width="13%" class="nowrap">{__("sku")}</th>
                        {foreach $selected_features as $feature}
                            <th><span>{$feature.description}</span></th>
                        {/foreach}
                        <th width="13%" class="nowrap">{__("price")} ({$currencies.$primary_currency.symbol nofilter})</th>
                        <th width="9%" class="nowrap">{__("quantity")}</th>
                        <th width="6%" class="mobile-hide">&nbsp;</th>
                    </tr>
                    </thead>
                    {foreach $products as $product}
                        {if !$product.parent_product_id}
                            {if !$product@first}
                                </tbody>
                            {/if}

                            <tbody>
                                {include file="addons/product_variations/views/product_variations/components/product_item.tpl"}
                            </tbody>
                            <tbody data-ca-switch-id="product_variations_group_{$product.product_id}">
                        {else}
                            {include file="addons/product_variations/views/product_variations/components/product_item.tpl"}
                        {/if}
                    {/foreach}
                    </tbody>
                </table>
            </div>

            <div class="hidden">
                {foreach $selected_features as $feature}
                    <select class="js-product-variation-feature" data-ca-feature-id="{$feature.feature_id}">
                        {foreach $feature.variants as $variant}
                            <option value="{$variant.variant_id}">{$variant.variant}</option>
                        {/foreach}
                    </select>
                {/foreach}
            </div>
        {else}
            <p class="no-items">{__("product_variations.add_variations_description")}</p>
        {/if}
    </form>
<!--content_variations--></div>
