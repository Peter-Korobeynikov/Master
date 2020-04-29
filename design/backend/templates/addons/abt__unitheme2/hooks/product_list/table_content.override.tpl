{if $smarty.request.data_id|strpos:'abt__ut2_bt' !== false}
{hook name="product_list:table_content"}
{assign var="time" value=$smarty.now}
<script>
(function(_, $) {
var row = $('#checkbox_id_{$time}{$product.product_id}').closest('tr#picker_product_row_{$product.product_id}');
if (row.length) {
row.attr('id', 'picker_product_row_{$time}{$product.product_id}');
}
}(Tygh, Tygh.$));
</script>
{if $hide_amount}
<td class="center" width="1%" data-th=""><input type="{if $show_radio}radio{else}checkbox{/if}" name="{$checkbox_name}[]" value="{$product.product_id}" class="cm-item mrg-check" id="checkbox_id_{$time}{$product.product_id}" /></td>
{/if}
<td data-th="{__("product_name")}">
{hook name="product_list:product_data"}
<input type="hidden" id="product_{$product.product_id}" value="{$product.product}" />
{if $hide_amount}
<label for="checkbox_id_{$time}{$product.product_id}">{$product.product nofilter}</label>
{else}
<span>{$product.product nofilter}</span>
{/if}
{if $smarty.request.data_id|strpos:'abt__ut2_bt_base_products_' === false}
{include file="views/products/components/select_product_options.tpl" id=$product.product_id product_options=$product.product_options name="product_data" show_aoc=$show_aoc additional_class=$additional_class}
{/if}
{/hook}
</td>
{if $show_price}
<td class="cm-picker-product-options right" data-th="{__("price")}">{if !$product.price|floatval && $product.zero_price_action == "A"}<input class="input-medium" id="product_price_{$product.product_id}" type="text" size="3" name="product_data[{$product.product_id}][price]" value="" />{else}{include file="common/price.tpl" value=$product.price}{/if}</td>
{/if}
{if !$is_order_management}
{if !$hide_amount}
<td class="center nowrap cm-value-changer" width="5%">
<div class="input-prepend input-append">
<a class="btn no-underline strong increase-font cm-decrease"><i class="icon-minus"></i></a>
<input id="product_id_{$product.product_id}" type="text" value="0" name="product_data[{$product.product_id}][amount]" size="3" class="input-micro cm-amount"{if $product.qty_step > 1} data-ca-step="{$product.qty_step}"{/if} />
<a class="btn no-underline strong increase-font cm-increase"><i class="icon-plus"></i></a>
</div>
</td>
{/if}
{else}
{if !$hide_amount}
<td class="center nowrap cm-value-changer" width="5%">
<div class="input-prepend input-append">
<a class="btn no-underline strong increase-font cm-decrease"><i class="icon-minus"></i></a>
<input id="product_id_{$product.product_id}" type="text" value="1" name="product_data[{$product.product_id}][amount]" size="3" class="input-micro cm-amount"{if $product.qty_step > 1} data-ca-step="{$product.qty_step}"{/if} />
<a class="btn no-underline strong increase-font cm-increase"><i class="icon-plus"></i></a>
</div>
</td>
{/if}
<td class="center nowrap" width="5%">
<div>
<a class="btn cm-process-items cm-submit cm-ajax cm-add-product" id="{$product.product_id}" title="{__("add_product")}" data-ca-dispatch="dispatch[order_management.add]" data-ca-check-filter="#picker_product_row_{$product.product_id}" data-ca-target-form="add_products"><i class="icon-arrow-right" data-ca-check-filter="#picker_product_row_{$product.product_id}"></i></a>
</div>
</td>
{/if}
{/hook}
{/if}