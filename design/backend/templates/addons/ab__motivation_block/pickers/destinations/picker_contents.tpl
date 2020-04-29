{if !$smarty.request.extra}
<script>
(function(_, $) {
_.tr('text_items_added', '{__("text_items_added")|escape:"javascript"}');
var display_type = '{$smarty.request.display|escape:javascript nofilter}';
$.ceEvent('on', 'ce.formpost_destinations_form', function(frm, elm) {
var destinations = {};
if ($('input.cm-item:checked', frm).length > 0) {
$('input.cm-item:checked', frm).each( function() {
var id = $(this).val();
destinations[id] = $('#destination_title_' + id).text();
});
{literal}
$.cePicker('add_js_item', frm.data('caResultId'), destinations, 'd', {
'{destination_id}': '%id',
'{destination}': '%item'
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
<form action="{$smarty.request.extra|fn_url}" data-ca-result-id="{$smarty.request.data_id}" method="post" name="destinations_form">
{include file="common/pagination.tpl" div_id="pagination_`$smarty.request.data_id`"}
{if $destinations}
<div class="table-responsive-wrapper">
<table width="100%" class="table table-middle table-responsive">
<thead>
<tr>
<th width="1%" class="center">
{if $smarty.request.display == "checkbox"}
{include file="common/check_items.tpl"}
{/if}
</th>
<th>{__("name")}</th>
<th>{__("status")}</th>
</tr>
</thead>
{foreach from=$destinations item=destination}
<tr>
<td class="left" data-th="">
{if $smarty.request.display == "checkbox"}
<input id="destination_{$smarty.request.data_id}_{$destination.destination_id}" type="checkbox" name="add_destinations[]" value="{$destination.destination_id}" class="cm-item" />
{elseif $smarty.request.display == "radio"}
<input id="destination_{$smarty.request.data_id}_{$destination.destination_id}" type="radio" name="selected_destination_id" value="{$destination.destination_id}" class="cm-item" />
{/if}
</td>
<td data-th="{__("name")}">
<label id="destination_title_{$destination.destination_id}" for="destination_{$smarty.request.data_id}_{$destination.destination_id}">{$destination.destination}</label>
</td>
<td class="center" data-th="{__("status")}">
{if $destination.status == "A"}
{__("active")}
{elseif $destination.status == "D"}
{__("disabled")}
{/if}
</td>
</tr>
{/foreach}
</table>
</div>
{else}
<div class="items-container"><p class="no-items">{__("no_data")}</p></div>
{/if}
{include file="common/pagination.tpl" div_id="pagination_`$smarty.request.data_id`"}
{if $destinations}
<div class="buttons-container">
{if $smarty.request.display == "radio"}
{assign var="but_close_text" value=__("choose")}
{else}
{assign var="but_close_text" value=$button_names.but_close_text|default:__("ab__mb.destinations.add_destinations_and_close")}
{assign var="but_text" value=$button_names.but_text|default:__("ab__mb.destinations.add_destinations")}
{/if}
{include file="buttons/add_close.tpl" is_js=$smarty.request.extra|fn_is_empty}
</div>
{/if}
</form>
