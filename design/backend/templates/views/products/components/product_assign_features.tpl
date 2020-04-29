{script src="js/tygh/backend/select2_color.js"}

{$features_used = []}
{foreach from=$product_features item=feature key="feature_id"}
    {$allow_enter_variant = $feature|fn_allow_save_object:"product_features"}

    {if $feature.feature_style == "ProductFeatureStyles::COLOR"|enum || $feature.filter_style == "ProductFilterStyles::COLOR"|enum}
        {$template_type = "color"}
        {$enable_images = false}
        {$template_result_selector = "#template_result_feature_color"}
        {$template_selection_selector = "#template_selection_feature_color"}
        {$template_result_add_selector = "#template_result_add_feature_color"}
        {$features_used[] = "color"}

    {elseif $feature.feature_style == "ProductFeatureStyles::BRAND"|enum}
        {$template_type = "image"}
        {$enable_images = true}
        {$template_result_selector = "#template_result_feature_image"}
        {$template_selection_selector = ""}
        {$template_result_add_selector = "#template_result_add_feature"}
        {$features_used[] = "image"}
    {else}
        {$template_type = ""}
        {$enable_images = false}
        {$template_result_selector = "#template_result_feature"}
        {$template_selection_selector = "#template_selection_feature"}
        {$template_result_add_selector = "#template_result_add_feature"}
    {/if}

    {if $feature.feature_type != "ProductFeatures::GROUP"|enum}
        {hook name="products:update_product_feature"}
        <div class="control-group">
            <label class="control-label" for="feature_{$feature_id}">{$feature.description}</label>
            <div class="controls">
            {if $feature.prefix}<span>{$feature.prefix}</span>{/if}

            {if $feature.feature_type == "ProductFeatures::TEXT_SELECTBOX"|enum
                || $feature.feature_type == "ProductFeatures::NUMBER_SELECTBOX"|enum
                || $feature.feature_type == "ProductFeatures::EXTENDED"|enum}
                {assign var="value_selected" value=false}
                <input type="hidden"
                       name="product_data[product_features][{$feature_id}]"
                       id="feature_{$feature_id}"
                       value="{$selected|default:$feature.variant_id}"
                />
                <input type="hidden"
                       name="product_data[add_new_variant][{$feature_id}][variant]"
                       id="product_feature_{$feature_id}_add_new_variant"
                       value=""
                />
                <div class="object-selector object-selector--mobile-full-width">
                    <select id="feature_{$feature_id}"
                            class="cm-object-selector object-selector--mobile-full-width"
                            name="product_data[product_features][{$feature_id}]"
                            data-ca-enable-images="{$enable_images|default:false}"
                            data-ca-image-width="30"
                            data-ca-image-height="30"
                            data-ca-enable-search="true"
                            data-ca-escape-html="false"
                            data-ca-load-via-ajax="{$feature.use_variant_picker|default:false}"
                            data-ca-page-size="10"
                            data-ca-data-url="{"product_features.get_variants_list?include_empty=Y&feature_id=`$feature_id`&product_id=`$product_id`&lang_code=`$descr_sl`"|fn_url nofilter}"
                            data-ca-placeholder="-{__("none")}-"
                            data-ca-allow-clear="true"
                            data-ca-enable-add="{$select2_enable_add|default:$allow_enter_variant}"
                            data-ca-template-type="{$select2_template_type|default:$template_type}"
                            data-ca-template-result-selector="{$template_result_selector}"
                            data-ca-template-selection-selector="{$template_selection_selector}"
                            data-ca-template-result-add-selector="{$template_result_add_selector}"
                            data-ca-new-value-holder-selector="#product_feature_{$feature_id}_add_new_variant"
                            >
                        <option value="">-{__("none")}-</option>
                        {foreach from=$feature.variants item="variant"}
                            {if $feature.feature_style == "ProductFeatureStyles::COLOR"|enum || $feature.filter_style == "ProductFilterStyles::COLOR"|enum}
                                <option
                                    value="{$variant.variant_id}"
                                    {if $variant.selected} selected="selected"{/if}
                                    data-ca-feature-color="{$variant.color}"
                                >{$variant.variant}</option>
                            {else}
                                <option
                                    value="{$variant.variant_id}"
                                    {if $variant.selected} selected="selected"{/if}
                                >{$variant.variant}</option>
                            {/if}
                        {/foreach}
                        <option value="">-{__("none")}-</option>
                    </select>
                </div>
            {elseif $feature.feature_type == "ProductFeatures::MULTIPLE_CHECKBOX"|enum}
                <input type="hidden"
                       name="product_data[product_features][{$feature_id}]"
                       value=""
                />
                <input type="hidden"
                       name="product_data[add_new_variant][{$feature_id}][variant]"
                       id="product_feature_{$feature_id}_add_new_variant"
                       value=""
                />
                <div class="object-selector">
                    <select id="feature_{$feature_id}"
                            class="cm-object-selector"
                            name="product_data[product_features][{$feature_id}][]"
                            multiple
                            data-ca-load-via-ajax="{$feature.use_variant_picker|default:false}"
                            data-ca-placeholder="{__("search")}"
                            data-ca-enable-search="true"
                            data-ca-escape-html="false"
                            data-ca-enable-images="true"
                            data-ca-image-width="30"
                            data-ca-image-height="30"
                            data-ca-close-on-select="false"
                            data-ca-page-size="10"
                            data-ca-data-url="{"product_features.get_variants_list?feature_id=`$feature_id`&product_id=`$product_id`&lang_code=`$descr_sl`"|fn_url nofilter}"
                            data-ca-enable-add="{$select2_enable_add|default:$allow_enter_variant}"
                            data-ca-template-type="{$select2_template_type|default:$template_type}"
                            data-ca-template-result-selector="{$template_result_selector}"
                            data-ca-template-selection-selector="{$template_selection_selector}"
                            data-ca-template-result-add-selector="{$template_result_add_selector}"
                            data-ca-new-value-holder-selector="#product_feature_{$feature_id}_add_new_variant"
                            >
                        {foreach from=$feature.variants item="variant"}
                            <option value="{$variant.variant_id}"{if $variant.selected} selected="selected"{/if}>{$variant.variant}</option>
                        {/foreach}
                    </select>
                </div>
            {elseif $feature.feature_type == "ProductFeatures::SINGLE_CHECKBOX"|enum}
                <label class="checkbox">
                <input type="hidden" name="product_data[product_features][{$feature_id}]" value="N" />
                <input type="checkbox" name="product_data[product_features][{$feature_id}]" value="Y" id="feature_{$feature_id}" {if $feature.value == "Y"}checked="checked"{/if} /></label>
            {elseif $feature.feature_type == "ProductFeatures::DATE"|enum}
                {include file="common/calendar.tpl" date_id="date_`$feature_id`" date_name="product_data[product_features][$feature_id]" date_val=$feature.value_int|default:""}
            {else}
                <input type="text" name="product_data[product_features][{$feature_id}]" value="{if $feature.feature_type == "ProductFeatures::NUMBER_FIELD"|enum}{if $feature.value_int != ""}{$feature.value_int|floatval}{/if}{else}{$feature.value}{/if}" id="feature_{$feature_id}" class="{if $feature.feature_type == "ProductFeatures::NUMBER_FIELD"|enum} cm-value-decimal{/if}" />
            {/if}
            {if $feature.suffix}<span>{$feature.suffix}</span>{/if}
            </div>
        </div>
        {/hook}
    {/if}
{/foreach}

{foreach from=$product_features item=feature key="feature_id"}
    {if $feature.feature_type == "ProductFeatures::GROUP"|enum && $feature.subfeatures}
        {include file="common/subheader.tpl" title=$feature.description additional_id=$feature.feature_id}
        {include file="views/products/components/product_assign_features.tpl" product_features=$feature.subfeatures}
    {/if}
{/foreach}

<template id="template_result_feature">
    {include file="common/select2/components/object_result.tpl"}
</template>
<template id="template_result_add_feature">
    {include file="common/select2/components/object_result.tpl"
        prefix=__("add")
        icon="icon-plus-sign"
    }
</template>
<template id="template_selection_feature">
    {include file="common/select2/components/object_selection.tpl"}
</template>

{if in_array("color", $features_used)}
    <template id="template_result_feature_color">
        {include file="common/select2/components/color_result.tpl"}
    </template>
    <template id="template_result_add_feature_color">
        {include file="common/select2/components/color_result.tpl"
            prefix=__("add")
            help=true
        }
    </template>
    <template id="template_selection_feature_color">
        {include file="common/select2/components/color_selection.tpl"}
    </template>
{/if}

{if in_array("image", $features_used)}
    <template id="template_result_feature_image">
        {include file="common/select2/components/image_result.tpl"}
    </template>
{/if}
