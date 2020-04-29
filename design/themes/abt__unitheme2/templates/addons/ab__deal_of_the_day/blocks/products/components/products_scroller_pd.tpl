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
{assign var="show_price" value=$block.properties.show_price == "Y"}
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
{assign var="show_buy_now" value=false}
{assign var="but_role" value="action"}

{* FIXME: Don't move this file *}
{script src="js/tygh/product_image_gallery.js"}
{assign var="obj_prefix" value="`$block.block_id`000"}
{$block.properties.outside_navigation = "N"}

<div id="scroll_list_{$block.block_id}"  class="owl-carousel ty-scroller-list grid-list {if !$show_add_to_cart}no-buttons{/if} ty-scroller-pd">
		
    {foreach from=$items item="product" name="for_products"}
    
        {hook name="products:product_scroller_pd"}
        	
            {if $product}
                {assign var="obj_id" value=$product.product_id}
                {assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}
                {include file="common/product_data.tpl" product=$product}

                <div class="ut2-pdb ut2-gl__item {if $settings.abt__ut2.product_list.decolorate_out_of_stock_products == "Y" && $product.amount <= 0}out-of-stock{/if}">
	                
                    {assign var="form_open" value="form_open_`$obj_id`"}
                    {$smarty.capture.$form_open nofilter}
                    
						{hook name="products:product_multicolumns_list"}

                            <div class="ut2-gl__body" {if $settings.abt__ut2.product_list.$tmpl.grid_item_height[$settings.abt__device]}style="min-height: {$settings.abt__ut2.product_list.$tmpl.grid_item_height[$settings.abt__device]|intval}px"{/if}>
                                <div class="ut2-gl__image">
                                    
                                    {include file="views/products/components/product_icon.tpl" product=$product show_gallery=false lazy_load=$settings.abt__ut2.product_list.lazy_load == 'Y'}

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

                                    {assign var="name" value="name_$obj_id"}
                                    {$smarty.capture.$name nofilter}
                                </div>
								
								{if $show_price}
                                <div class="ut2-gl__price {if $product.price == 0}ut2-gl__no-price{/if}	pr-{$settings.abt__ut2.product_list.price_display_format}{if $product.list_discount || $product.discount} pr-color{/if}">
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
                                {/if}

                                {if $show_add_to_cart}
                                    <div class="ut2-gl__control {if !$hide_form && $addons.call_requests.buy_now_with_one_click == "Y" && ($auth.user_id || $settings.General.allow_anonymous_shopping == "allow_shopping") && $show_buy_now}bt-2x{/if} {if $show_qty}ut2-view-qty{/if}">

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
                </div>
            {/if}

        {/hook}
        
    {/foreach}
</div>

{include file="common/scroller_init.tpl" itemsDesktop=1 itemsDesktopSmall=2 itemsTablet=2 itemsTabletSmall=1}