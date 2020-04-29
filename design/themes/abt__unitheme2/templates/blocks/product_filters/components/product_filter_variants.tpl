{hook name="blocks:ab__cf_variants"}
    <ul class="ty-product-filters {if $collapse}hidden{/if}" id="content_{$filter_uid}">

        {if $filter.display_count && $filter.variants|count > $filter.display_count}
            <li>
                {script src="js/tygh/filter_table.js"}

                <div class="ty-product-filters__search">
                    <input type="text" placeholder="{__("search")}" class="cm-autocomplete-off ty-input-text-medium" name="q" id="elm_search_{$filter_uid}" value="" />
                    <i class="ty-product-filters__search-icon ty-icon-cancel-circle hidden" id="elm_search_clear_{$filter_uid}" title="{__("clear")}"></i>
                </div>
            </li>
        {/if}

        {$white_color = "#ffffff"}

        {* Selected variants *}
        {if $filter.filter_style == "ProductFilterStyles::COLOR"|enum}
            <div class="ty-product-filters__color-filter">
                {foreach $filter.selected_variants as $variant_id => $variant}
                    <li class="ty-product-filters__color-list-item">
                        <label
                                id="elm_checkbox_{$filter_uid}_{$variant.variant_id}"
                                name="product_filters[{$filter.filter_id}]"
                                class="ty-product-filters__color-filter-item ty-product-filters__color-filter-item--selected"
                                data-cm-product-color-filter="true"
                                data-ca-product-color-filter-checkbox-id="elm_checkbox_{$filter_uid}_{$variant.variant_id}"
                                title="{$filter.prefix}{$variant.variant|fn_text_placeholders}{$filter.suffix}">
                            <input
                                    class="cm-product-filters-checkbox ty-product-filters__color-filter-checkbox"
                                    type="checkbox"
                                    data-ca-filter-id="{$filter.filter_id}"
                                    value="{$variant.variant_id}"
                                    checked />
                            <i class="ty-icon-ok ty-product-filters__color-filter-check ty-product-filters__color-filter-check--selected
                        {if $variant.color == $white_color}ty-product-filters__color-filter-check--invert{/if}"></i>
                            <div
                                    class="ty-product-filters__color-filter-swatch ty-product-filters__color-filter-swatch--selected"
                                    style="background-color:{$variant.color|default:$white_color}">
                            </div>
                        </label>
                    </li>
                {/foreach}
            </div>
        {else}
            {foreach $filter.selected_variants as $variant_id => $variant}
                <li class="cm-product-filters-checkbox-container ty-product-filters__group">
                    <input class="cm-product-filters-checkbox" type="checkbox" name="product_filters[{$filter.filter_id}]" data-ca-filter-id="{$filter.filter_id}" value="{$variant.variant_id}" id="elm_checkbox_{$filter_uid}_{$variant.variant_id}" checked="checked"><label for="elm_checkbox_{$filter_uid}_{$variant.variant_id}"><span>{$filter.prefix}{$variant.variant|fn_text_placeholders}{$filter.suffix}</span></label>
                </li>
            {/foreach}
        {/if}

        {if $filter.variants}
            {if $filter.filter_style == "ProductFilterStyles::COLOR"|enum}
                <div class="ty-product-filters__color-filter">
                    {foreach $filter.variants as $variant}
                        <li class="ty-product-filters__color-list-item">
                            <label
                                    id="elm_checkbox_{$filter_uid}_{$variant.variant_id}"
                                    name="product_filters[{$filter.filter_id}]"
                                    class="ty-product-filters__color-filter-item
                            {if $variant.disabled}ty-product-filters__color-filter-item--disabled{/if}"
                                    data-cm-product-color-filter="true"
                                    data-ca-product-color-filter-checkbox-id="elm_checkbox_{$filter_uid}_{$variant.variant_id}"
                                    title="{$filter.prefix}{$variant.variant|fn_text_placeholders}{$filter.suffix}">
                                <input
                                        class="cm-product-filters-checkbox ty-product-filters__color-filter-checkbox"
                                        type="checkbox"
                                        data-ca-filter-id="{$filter.filter_id}"
                                        value="{$variant.variant_id}"
                                        {if $variant.disabled}disabled="disabled"{/if} />
                                <i class="ty-icon-ok ty-product-filters__color-filter-check
                            {if $variant.color == $white_color}ty-product-filters__color-filter-check--invert{/if}"></i>
                                <div
                                        class="ty-product-filters__color-filter-swatch"
                                        style="background-color:{$variant.color|default:$white_color}">
                                </div>
                            </label>
                        </li>
                    {/foreach}
                </div>
            {else}
                <li class="ty-product-filters__item-more">
                    <ul id="ranges_{$filter_uid}" {if $filter.display_count}style="max-height: {$filter.display_count * 31}px;"{/if} class="ty-product-filters__variants {if $filter.variants|@count > $filter.display_count}items-more {/if}cm-filter-table" data-ca-input-id="elm_search_{$filter_uid}" data-ca-clear-id="elm_search_clear_{$filter_uid}" data-ca-empty-id="elm_search_empty_{$filter_uid}">

                        {foreach $filter.variants as $variant}
                            <li class="cm-product-filters-checkbox-container ty-product-filters__group">
                                <input class="cm-product-filters-checkbox" type="checkbox" name="product_filters[{$filter.filter_id}]" data-ca-filter-id="{$filter.filter_id}" value="{$variant.variant_id}" id="elm_checkbox_{$filter_uid}_{$variant.variant_id}" {if $variant.disabled}disabled="disabled"{/if}><label {if $variant.disabled}class="disabled"{/if} for="elm_checkbox_{$filter_uid}_{$variant.variant_id}"><span>{$filter.prefix}{$variant.variant|fn_text_placeholders}{$filter.suffix}</span></label>
                            </li>
                        {/foreach}
                    </ul>
                    {if $filter.variants|@count > $filter.display_count}
                        <a class="ut2-more-btn" onclick="$(this).prev().toggleClass('none-overflow'); $(this).toggleClass('open');"><i class="ut2-icon-outline-expand_more"></i><span class="more">{__('more')} ({$filter.variants|@count - $filter.display_count})</span><span class="hidden">{__('close')}</span></a>
                    {/if}
                </li>
            {/if}
            <p id="elm_search_empty_{$filter_uid}" class="ty-product-filters__no-items-found hidden">{__("no_items_found")}</p>
        {/if}
    </ul>
{/hook}
