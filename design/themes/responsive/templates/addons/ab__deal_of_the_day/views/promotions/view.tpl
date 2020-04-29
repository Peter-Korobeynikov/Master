{if $promotion.hide_products_block != 'Y'}
{if $categories && $promotion.filter == 'Y'}
{$ajax_div_ids = "promotion_filter,promotion_products"}
{$filter_base_url = $config.current_url|fn_query_remove:"result_ids":"full_render":"category_id"}
<div class="ab__dotd_promotions-filter" data-ca-target-id="{$ajax_div_ids}" data-ca-base-url="{$filter_base_url|fn_url}" id="promotion_filter">
<div class="ab__dotd_promotions-filter_item{if !$selected_category_id} active{/if}">{__('ab__dotd.clear_filter')}</div>
{foreach $categories as $category_id => $category_name}
<div class="ab__dotd_promotions-filter_item{if $selected_category_id == $category_id} active{/if}" data-ca-category-id="{$category_id}">{$category_name nofilter}</div>
{/foreach}
<!--promotion_filter--></div>
{/if}
<div id="category_products_{$block.block_id}">
<div class="ab__dotd_promotions-products" id="promotion_products">
{if $products}
{assign var="layouts" value=""|fn_get_products_views:false:0}
{if $layouts.$selected_layout.template}
{include file="`$layouts.$selected_layout.template`" columns=$settings.Appearance.columns_in_products_list}
{/if}
{/if}
<!--promotion_products--></div>
<!--category_products_{$block.block_id}--></div>
{/if}