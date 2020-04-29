{$columns=$settings.abt__ut2.addons.wishlist_products.item_quantity[$settings.abt__device]}

{if !$wishlist_is_empty}
    {script src="js/tygh/exceptions.js"}
    {assign var="show_hr" value=false}
    {assign var="location" value="cart"}
{/if}

{$tmpl='products_multicolumns'}

{if $products}
    {include file="blocks/list_templates/grid_list.tpl" 
        columns=$columns
        show_empty=true
		show_trunc_name=true
		show_rating=true
		show_old_price=true
		show_price=true
		show_clean_price=true
		show_list_discount=$settings.abt__ut2.product_list.$tmpl.show_you_save[$settings.abt__device] == 'Y'
		show_discount_label=true
		hide_qty_label=true
		show_sku_label=true
		show_amount_label=false
		show_add_to_cart=$settings.abt__ut2.product_list.$tmpl.show_buttons[$settings.abt__device] == 'Y'
		show_sku=$settings.abt__ut2.product_list.$tmpl.show_sku[$settings.abt__device] == 'Y'
		show_qty=$settings.abt__ut2.product_list.$tmpl.show_qty[$settings.abt__device] == 'Y'
		show_features=$settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'features'
		show_descr=$settings.abt__ut2.product_list.$tmpl.grid_item_bottom_content[$settings.abt__device] == 'description'
        show_brand_logo=$settings.abt__ut2.product_list.$tmpl.show_brand_logo[$settings.abt__device] == 'Y'
		show_list_buttons=false
		show_custom_class="ut2-wl__grid"
		but_role="action"
        no_pagination=true
        no_sorting=true
        is_wishlist=true
        }
{else}
    {math equation="100 / x" x=$columns|default:"2" assign="cell_width"}
    <div class="ty-grid-list{if $wishlist_is_empty} ty-wish-list-empty{/if}">
        {assign var="iteration" value=0}
        {capture name="iteration"}{$iteration}{/capture}
            {hook name="wishlist:view"}
            {/hook}
        {assign var="iteration" value=$smarty.capture.iteration}
        {if $iteration == 0 || $iteration % $columns != 0}
            {math assign="empty_count" equation="c - it%c" it=$iteration c=$columns}
            {section loop=$empty_count name="empty_rows"}
                <div class="ty-column{$columns}">
                    <div class="ty-product-empty">
                        <span class="ty-product-empty__text">{__("empty")}</span>
                    </div>
                </div>
            {/section}
        {/if}
    </div>
{/if}

{if !$wishlist_is_empty}
    <div class="ty-wish-list__buttons">
        {include file="buttons/button.tpl" but_text=__("clear_wishlist") but_href="wishlist.clear" but_meta="ty-btn__tertiary"}
        {include file="buttons/continue_shopping.tpl" but_href=$continue_url|fn_url but_role="text"}
    </div>
{else}
    <div class="buttons-container ty-wish-list__buttons ty-wish-list__continue">
        {include file="buttons/continue_shopping.tpl" but_href=$continue_url|fn_url but_role="text"}
    </div>
{/if}

{capture name="mainbox_title"}{__("wishlist_content")}{/capture}