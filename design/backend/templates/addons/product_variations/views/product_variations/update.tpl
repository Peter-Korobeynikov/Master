<div class="tabs cm-j-tabs">
    <ul class="nav nav-tabs">
        <li id="tab_link_existing_{$product_data.product_id}" class="cm-js active"><a>{__("product_variations.link_existing")}</a></li>
        {if $is_allow_generate_variations}
            <li id="tab_create_new_{$product_data.product_id}" class="cm-js"><a>{__("product_variations.create_new")}</a></li>
        {/if}
    </ul>
</div>
<div class="cm-tabs-content" id="tabs_content_{$product_data.product_id}">
    <div id="content_tab_link_existing_{$product_data.product_id}">
        {include file="addons/product_variations/views/product_variations/components/search_product_list.tpl"}
    </div>
    {if $is_allow_generate_variations}
        <div id="content_tab_create_new_{$product_data.product_id}">
            {include file="addons/product_variations/views/product_variations/components/feature_combinations.tpl"}
        </div>
    {/if}
<div class="buttons-container product-variations__add-variations-buttons-container">
    <div>
        <a class="cm-dialog-closer cm-cancel tool-link btn">{__("cancel")}</a>
    </div>
    <div class="cm-tab-tools" id="tools_tab_link_existing_{$product_data.product_id}">
        {if $products}
            {include file="buttons/button.tpl" but_text=__("product_variations.add_variations") but_role="submit-link" but_name="dispatch[product_variations.update]" but_meta="btn-primary" but_target_form="add_product_to_group_form"}
        {/if}
    <!--tools_tab_link_existing_{$product_data.product_id}--></div>
    {if $is_allow_generate_variations}
        <div class="cm-tab-tools" id="tools_tab_create_new_{$product_data.product_id}">
            {if $count_available_combinations}
                {include file="buttons/button.tpl" but_text=__("product_variations.add_variations") but_role="submit-link" but_name="dispatch[product_variations.generate]" but_meta="btn-primary" but_target_form="generate_product_to_group_form"}
            {/if}
        <!--tools_tab_create_new_{$product_data.product_id}--></div>
    {/if}
</div>
