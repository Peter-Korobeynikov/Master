{** block-description:tmpl_scroller **}

{if $settings.Appearance.enable_quick_view == "Y" && $block.properties.enable_quick_view == "Y" && $settings.abt__device != "mobile"}
    {$quick_nav_ids = $items|fn_fields_from_multi_level:"product_id":"product_id"}
{/if}

{if $block.properties.hide_add_to_cart_button == "Y"}
        {assign var="_show_add_to_cart" value=false}
    {else}
        {assign var="_show_add_to_cart" value=true}
    {/if}
    {if $block.properties.show_price == "Y"}
        {assign var="_hide_price" value=false}
    {else}
        {assign var="_hide_price" value=true}
{/if}

{** Detecting grid item height **}

	{* Grid padding *}
	{assign var="pd" value=20}

	{* Thumb *}
	{assign var="t1" value=$block.properties.thumbnail_width|intval + 10}
	
	{* Show rating *}
	{if $settings.abt__ut2.product_list.show_rating == "Y" && $addons.discussion.status == "A" && $product.discussion_type != 'D'}
	    {assign var="t2" value=18}
	{/if}
	
	{* Show name *}
	{assign var="t3" value=32}
	
	{* Show price *}
	{assign var="t4" value=40}
	
	{* Show buttons *}
	{if $_show_add_to_cart}
	    {assign var="t5" value=56}
	{/if}
	
	{capture name="abt__ut2_scroller_items_height"}{$t1|default:80 + $t2|default:0 + $t3|default:0 + $t4|default:0 + $t5|default:0 + $pd}{/capture}
	
{** end **}

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

<div id="scroll_list_{$block.block_id}" class="owl-carousel ty-scroller-list" {if $settings.abt__device != "mobile"}style="height: {$smarty.capture.abt__ut2_scroller_items_height nofilter}px"{/if}>
    {foreach from=$items item="product" name="for_products"}
        {hook name="products:product_scroller_list"}
        <div class="ty-scroller-list__item">
            {assign var="obj_id" value="scr_`$block.block_id`000`$product.product_id`"}
            <div class="ty-scroller-list__img-block">
                {include file="common/image.tpl" assign="object_img" images=$product.main_pair image_width=$block.properties.thumbnail_width image_height=$block.properties.thumbnail_width no_ids=true lazy_load=true}
                <a href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$object_img nofilter}</a>
                {if $settings.Appearance.enable_quick_view == "Y" && $block.properties.enable_quick_view == "Y" && $settings.abt__device != "mobile"}
                    {include file="views/products/components/quick_view_link.tpl" quick_nav_ids=$quick_nav_ids}
                {/if}
            </div>
            <div class="ty-scroller-list__description">
                {strip}
                    {include file="blocks/list_templates/simple_list.tpl" product=$product show_name=true show_price=true show_add_to_cart=$_show_add_to_cart but_role="action" hide_price=$_hide_price hide_qty=true show_product_labels=true show_discount_label=true show_shipping_label=true}
                {/strip}
            </div>
        </div>
        {/hook}
    {/foreach}
</div>

{include file="common/scroller_init.tpl" prev_selector="#owl_prev_`$obj_prefix`" next_selector="#owl_next_`$obj_prefix`"}
