{** block-description:abt__ut2__scroller_advanced **}

{$tmpl='products_multicolumns'}

{if $block.properties.enable_quick_view == "Y" && $settings.abt__device != "mobile"}
    {$quick_nav_ids = $items|fn_fields_from_multi_level:"product_id":"product_id"}
{/if}

{if $block.properties.hide_add_to_cart_button == "Y"}
    {assign var="show_add_to_cart" value=false}
{else}
    {assign var="show_add_to_cart" value=true}
{/if}

{assign var="show_name" value=true}
{assign var="show_rating" value=true}
{assign var="show_price" value=true}
{assign var="show_old_price" value=true}
{assign var="show_clean_price" value=true}
{assign var="show_list_discount" value=$settings.abt__ut2.product_list.$tmpl.show_you_save[$settings.abt__device] == 'Y'}
{assign var="show_sku" value=$settings.abt__ut2.product_list.$tmpl.show_sku[$settings.abt__device] == 'Y'}
{assign var="show_qty" value=$settings.abt__ut2.product_list.$tmpl.show_qty[$settings.abt__device] == 'Y'}
{assign var="show_brand_logo" value=$settings.abt__ut2.product_list.$tmpl.show_brand_logo[$settings.abt__device] == 'Y'}
{assign var="show_product_amount" value=false}
{assign var="hide_qty_label" value=true}
{assign var="show_amount_label" value=false}
{assign scope='parent' var='show_product_labels' value=true}
{assign scope='parent' var='show_discount_label' value=true}
{assign scope='parent' var='show_shipping_label' value=true}
{assign var="show_list_buttons" value=false}
{assign var="but_role" value="action"}

{** Detecting price block height **}

{* Show price *}
{assign var="t1" value=41}
	    
{* Show your save *}
{if $settings.abt__ut2.product_list.$tmpl.show_you_save[$settings.abt__device] == 'Y'}
	{assign var="t2" value=15}
{/if}

{* Show clean price *}
{if $settings.Appearance.show_prices_taxed_clean == "Y"}
	{assign var="t3" value=15}
{/if}

{$pth = $t1|default:0 + $t2|default:0 + $t3|default:0}
{capture name="abt__ut2_pr_block_height"}{$pth}{/capture}

{** end **}

{* FIXME: Don't move this file *}
{script src="js/tygh/product_image_gallery.js"}

{assign var="obj_prefix" value="`$block.block_id`000"}

{if $block.properties.outside_navigation == "Y"}
    <div class="owl-theme ty-owl-controls">
        <div class="owl-controls clickable owl-controls-outside"  id="owl_outside_nav_{$block.block_id}">
            <div class="owl-buttons">
                <div id="owl_prev_{$obj_prefix}" class="owl-prev"><i class="ty-icon-left-open-thin"></i></div>
                <div id="owl_next_{$obj_prefix}" class="owl-next"><i class="ty-icon-right-open-thin"></i></div>
            </div>
        </div>
    </div>
{/if}

<div id="scroll_list_{$block.block_id}" class="owl-carousel ty-scroller-list grid-list {if !$show_add_to_cart}no-buttons{/if} ut2-scroller-advanced">
    {strip}
        {foreach from=$items item="product" name="for_products"}
            {hook name="products:product_scroller_advanced_list"}
                <div class="ut2-gl__item {if $settings.abt__ut2.product_list.decolorate_out_of_stock_products == "Y" && $product.amount <= 0}out-of-stock{/if}">

                    {* Start ut2-gl *}

                    {if $product}
                        {assign var="obj_id" value=$product.product_id}
                        {assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}

                        {include file="common/product_data.tpl" product=$product}

                        {assign var="form_open" value="form_open_`$obj_id`"}
                        {$smarty.capture.$form_open nofilter}

                        {hook name="products:product_multicolumns_list"}

                            <div class="ut2-gl__body" {if $settings.abt__ut2.product_list.$tmpl.grid_item_height[$settings.abt__device]}style="min-height: {$settings.abt__ut2.product_list.$tmpl.grid_item_height[$settings.abt__device]|intval}px"{/if}>
                                <div class="ut2-gl__image" style="height: {$settings.Thumbnails.product_lists_thumbnail_height|intval}px">
                                    {capture name="main_icon"}
                                        <a href="{"products.view?product_id=`$product.product_id`"|fn_url}">
                                            {include file="common/image.tpl" lazy_load=true images=$product.main_pair image_height=$block.properties.abt__ut2_thumbnail_height|default:$settings.Thumbnails.product_lists_thumbnail_height image_width=$block.properties.thumbnail_width|default:$settings.Thumbnails.product_lists_thumbnail_width}
                                        </a>
                                    {/capture}

                                    {$smarty.capture.main_icon nofilter}

                                    {assign var="product_labels" value="product_labels_`$obj_prefix``$obj_id`"}
                                    {$smarty.capture.$product_labels nofilter}

                                    <div class="ut2-gl__buttons">
                                        {if $block.properties.enable_quick_view == "Y" && $settings.abt__device != "mobile"}
                                            {include file="views/products/components/quick_view_link.tpl" quick_nav_ids=$quick_nav_ids}
                                        {/if}
                                        {if $addons.wishlist.status == "A" && !$hide_wishlist_button}
                                            {include file="addons/wishlist/views/wishlist/components/add_to_wishlist.tpl" but_id="button_wishlist_`$obj_prefix``$product.product_id`" but_name="dispatch[wishlist.add..`$product.product_id`]" but_role="text"}
                                        {/if}
                                        {if $settings.General.enable_compare_products == "Y" && !$hide_compare_list_button || $product.feature_comparison == "Y"}
                                            {include file="buttons/add_to_compare_list.tpl" product_id=$product.product_id}
                                        {/if}
                                    </div>

                                    {if $show_brand_logo && $settings.abt__ut2.general.brand_feature_id > 0}
                                        {$b_feature=$product.abt__ut2_features[$settings.abt__ut2.general.brand_feature_id]}
                                        {if $b_feature.variants[$b_feature.variant_id].image_pairs}
                                            <div class="brand-img">
                                                {include file="common/image.tpl" image_height=20 images=$b_feature.variants[$b_feature.variant_id].image_pairs no_ids=true}
                                            </div>
                                        {/if}
                                    {/if}
                                </div>
                                
								{assign var="rating" value="rating_$obj_id"}
								{hook name="products:product_rating"}
                                
                                	<div class="ut2-gl__rating{if $addons.discussion.status == "A"} no-rating{/if}">
                                    	
									{hook name="products:dotd_product_label"}{/hook}
									{hook name="products:video_gallery"}{/hook}
									
									{if $settings.abt__ut2.product_list.show_rating == "Y"}
	                                    {if $smarty.capture.$rating|strlen > 40 && $product.discussion_type && $product.discussion_type != 'D'}
	                                        {$smarty.capture.$rating nofilter}
	                                    {else}
	                                    	{if $addons.discussion.status == "A"}
	                                        	<span class="ty-nowrap ty-stars"><i class="ty-icon-star-empty"></i><i class="ty-icon-star-empty"></i><i class="ty-icon-star-empty"></i><i class="ty-icon-star-empty"></i><i class="ty-icon-star-empty"></i></span>
	                                        {/if}
	                                    {/if}
                                    {/if}

                                    </div>
								{/hook}

                                {assign var="sku" value="sku_$obj_id"}
                                {$smarty.capture.$sku nofilter}

                                <div class="ut2-gl__name">
                                    {if $item_number == "Y"}
                                        <span class="item-number">{$cur_number}.&nbsp;</span>
                                        {math equation="num + 1" num=$cur_number assign="cur_number"}
                                    {/if}
                                    
                                    {if $settings.abt__ut2.product_list.show_rating !="Y"}{hook name="products:video_gallery"}{/hook}{/if}

                                    {assign var="name" value="name_$obj_id"}
                                    {$smarty.capture.$name nofilter}
                                </div>

                                <div class="ut2-gl__price {if $product.price == 0}ut2-gl__no-price{/if}	pr-{$settings.abt__ut2.product_list.price_display_format}{if $product.list_discount || $product.discount} pr-color{/if}" style="min-height: {$smarty.capture.abt__ut2_pr_block_height nofilter}px;">
                                    <div>
	                                    {assign var="old_price" value="old_price_`$obj_id`"}
                                        {if $smarty.capture.$old_price|trim}{$smarty.capture.$old_price nofilter}{/if}
                                        
                                        {assign var="price" value="price_`$obj_id`"}
                                        {$smarty.capture.$price nofilter}
                                    </div>
                                    <div>
                                        {assign var="list_discount" value="list_discount_`$obj_id`"}
                                        {$smarty.capture.$list_discount nofilter}

                                        {assign var="clean_price" value="clean_price_`$obj_id`"}
                                        {$smarty.capture.$clean_price nofilter}
									</div>
                                </div>

                                {if $show_add_to_cart}
                                    <div class="ut2-gl__control {if !$hide_form && $addons.call_requests.buy_now_with_one_click == "Y" && ($auth.user_id || $settings.General.allow_anonymous_shopping == "allow_shopping")}bt-2x{/if} {if $settings.abt__ut2.product_list.$tmpl.show_qty[$settings.abt__device] == 'Y' && $settings.General.allow_anonymous_shopping == "allow_shopping" && !$product.has_options}ut2-view-qty{/if}">

                                        {if $show_qty}
                                            <div class="ut2-gl__qty">
                                                {assign var="qty" value="qty_`$obj_id`"}
                                                {$smarty.capture.$qty nofilter}
                                            </div>
                                        {/if}

                                        <div class="button-container">
                                            {assign var="add_to_cart" value="add_to_cart_`$obj_id`"}
                                            {$smarty.capture.$add_to_cart nofilter}
                                        </div>

                                    </div>
                                {/if}
                            </div>

                        {/hook}
                        {assign var="form_close" value="form_close_`$obj_id`"}
                        {$smarty.capture.$form_close nofilter}

                    {/if}
                    {* End ut2-gl *}

                </div>
            {/hook}
        {/foreach}
    {/strip}
</div>

{include file="common/scroller_init.tpl" prev_selector="#owl_prev_`$obj_prefix`" next_selector="#owl_next_`$obj_prefix`"}