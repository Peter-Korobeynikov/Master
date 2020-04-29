{if !$smarty.request.extra}
<script>
(function(_, $) {
_.tr('text_items_added', '{__("text_items_added")|escape:"javascript"}');
var display_type = '{$smarty.request.display|escape:javascript nofilter}';
$.ceEvent('on', 'ce.formpost_promotions_form', function(frm, elm) {
var promotions = {};
if ($('input.cm-item:checked', frm).length > 0) {
$('input.cm-item:checked', frm).each( function() {
var id = $(this).val();
promotions[id] = $('#promotion_title_' + id).text();
});
{literal}
$.cePicker('add_js_item', frm.data('caResultId'), promotions, 'a', {
'{promotion_id}': '%id',
'{promotion}': '%item'
});
{/literal}
if (display_type != 'radio') {
$.ceNotification('show', {
type: 'N',
title: _.tr('notice'),
message: _.tr('text_items_added'),
message_state: 'I'
});
}
}
return false;
});
}(Tygh, Tygh.$));
</script>
{/if}
{*include file="views/pages/components/pages_search_form.tpl" dispatch="pages.picker" extra="<input type=\"hidden\" name=\"result_ids\" value=\"pagination_`$smarty.request.data_id`\"><input type=\"hidden\" name=\"get_tree\" value=\"\"><input type=\"hidden\" name=\"root\" value=\"\">" put_request_vars=true form_meta="cm-ajax" in_popup=true*}
<form action="{$smarty.request.extra|fn_url}" data-ca-result-id="{$smarty.request.data_id}" method="post" name="promotions_form">
{include file="common/pagination.tpl" div_id="pagination_`$smarty.request.data_id`"}
{if $promotions}
<table class="table table-middle">
<thead>
<tr>
<th width="1%">
{if $smarty.request.display != "radio"}
{include file="common/check_items.tpl"}
{/if}
</th>
<th width="70%">{__("name")}</th>
<th width="10%" class="nowrap center">{__("priority")}</th>
<th width="10%">{__("zone")}</th>
</tr>
</thead>
{foreach from=$promotions item=promotion}
<tr>
<td>
{if $smarty.request.display == "radio"}
<input type="radio" name="{$smarty.request.checkbox_name}" id="checkbox_id_{$promotion.promotion_id}" value="{$promotion.promotion_id}" class="cm-item" />
{else}
<input type="checkbox" name="{$smarty.request.checkbox_name}[]" id="checkbox_id_{$promotion.promotion_id}" value="{$promotion.promotion_id}" class="cm-item" />
{/if}
</td>
<td><label for="checkbox_id_{$promotion.promotion_id}" id="promotion_title_{$promotion.promotion_id}">{$promotion.name}</label></td>
<td class="center"><span>{$promotion.priority}</span></td>
<td><span class="row-status">{__($promotion.zone)}</span></td>
</tr>
{/foreach}
</table>
{else}
<p class="no-items">{__("no_data")}</p>
{/if}
{include file="common/pagination.tpl" div_id="pagination_`$smarty.request.data_id`"}
{if $promotions}
<div class="buttons-container">
{if $smarty.request.display == "radio"}
{assign var="but_close_text" value=__("choose")}
{else}
{assign var="but_close_text" value=$button_names.but_close_text|default:__("ab__dotd.add_promotions_and_close")}
{assign var="but_text" value=$button_names.but_text|default:__("ab__dotd.add_promotions")}
{/if}
{include file="buttons/add_close.tpl" is_js=$smarty.request.extra|fn_is_empty}
</div>
{/if}
</form>
