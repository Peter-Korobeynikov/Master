{if $has_inventory}
    {include file="buttons/button.tpl" but_text=__("option_combinations") but_href="product_options.inventory?product_id=`$product_data.product_id`" but_meta="btn"  but_role="text"}
{else}
    {capture name="notes_picker"}
        {__("text_options_no_inventory")}
    {/capture}
    {include file="common/popupbox.tpl" act="button" id="content_option_combinations" text=__("note") content=$smarty.capture.notes_picker link_text=__("option_combinations") but_href="product_options.inventory?product_id=`$product_data.product_id`" but_role="text" extra_act="notes"}
{/if}