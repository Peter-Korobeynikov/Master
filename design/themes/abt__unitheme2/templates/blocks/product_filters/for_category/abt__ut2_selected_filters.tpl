{** block-description:abt__ut2_selected_filters **}
{strip}
{if $items}
    {capture name="abt__selected_filters"}
            {$ajax_div_ids = "product_filters_*,products_search_*,category_products_*,product_features_*,breadcrumbs_*,currencies_*,languages_*,selected_filters_*"}
            {$curl = $config.current_url}
            {$filter_base_url = $curl|fn_query_remove:"result_ids":"full_render":"filter_id":"view_all":"req_range_id":"features_hash":"subcats":"page":"total"}

            <div class="ut2-selected-product-filters cm-product-filters" id="selected_filters_{$block.block_id}">
                {$reset_url = $filter_base_url}
                {$abt__selected=false}
                {foreach from=$items item="filter"}
                    {if $filter.selected_variants || $filter.selected_range}
                        {$abt__selected=true}
                        {if $filter.selected_variants}
                            {foreach from=$filter.selected_variants item="v"}
                                {$fh = $smarty.request.features_hash|fn_delete_filter_from_hash:$filter.filter_id:$v.variant_id}
                                {if $fh}
                                    {$reset_url = $filter_base_url|fn_link_attach:"features_hash=$fh"}
                                {/if}
                                <a class="ut2-selected-filter-item cm-ajax cm-ajax-full-render cm-history" href="{$reset_url|fn_url}" data-ca-event="ce.filtersinit" data-ca-target-id="{$ajax_div_ids}"><span id="sw_elm_selected_filter_{$filter.filter_id}_{$v.variant_id}">{$v.variant}</span><i class="ty-icon-cancel-circle"></i></a>
                            {/foreach}
                        {elseif $filter.selected_range}
                            {$fh = $smarty.request.features_hash|fn_delete_filter_from_hash:$filter.filter_id}
                            {if $fh}
                                {$reset_url = $filter_base_url|fn_link_attach:"features_hash=$fh"}
                            {/if}
                            {$left = $filter.left|default:$min * $currencies.$secondary_currency.coefficient|default:1}
                            {$right = $filter.right|default:$max * $currencies.$secondary_currency.coefficient|default:1}
                            <a class="ut2-selected-filter-item cm-ajax cm-ajax-full-render cm-history" id="sw_elm_selected_filter_{$filter.filter_id}" href="{$reset_url|fn_url}" data-ca-event="ce.filtersinit" data-ca-target-id="{$ajax_div_ids}">{if $filter.field_type == 'P'}{include file="common/price.tpl" value=$left}{else}{$left}{/if} - {if $filter.field_type == 'P'}{include file="common/price.tpl" value=$right}{else}{$right}{/if}<i class="ty-icon-cancel-circle"></i></a>
                        {/if}
                    {/if}
                {/foreach}

                {if $abt__selected}
                    <a href="{$filter_base_url|fn_url}" rel="nofollow" class="ut2-selected-filter-item reset cm-ajax cm-ajax-full-render cm-history" data-ca-event="ce.filtersinit" data-ca-scroll=".ty-mainbox-title" data-ca-target-id="{$ajax_div_ids}"><i class="ty-icon-cw"></i> {__("reset")}</a>
                {/if}
                <!--selected_filters_{$block.block_id}--></div>

    {/capture}
{/if}{/strip}