{if !$smarty.request.extra}
<script>
(function(_, $) {
_.tr('text_items_added', '{__("text_items_added")|escape:"javascript"}');
$.ceEvent('on', 'ce.formpost_menus_form', function(frm, elm) {
var menus = {};
if ($('input.cm-item:checked', frm).length > 0) {
$('input.cm-item:checked', frm).each( function() {
var id = $(this).val();
menus[id] = $('#menu_' + id).text();
});
{literal}
$.cePicker('add_js_item', frm.data('caResultId'), menus, 'm', {
'{menu_id}': '%id',
'{menu}': '%item'
});
{/literal}
$.ceNotification('show', {
type: 'N',
title: _.tr('notice'),
message: _.tr('text_items_added'),
message_state: 'I'
});
}
return false;
});
}(Tygh, Tygh.$));
</script>
{/if}
</head>
<form action="{$smarty.request.extra|fn_url}" data-ca-result-id="{$smarty.request.data_id}" method="post" name="menus_form" enctype="multipart/form-data">
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive">
<thead>
<tr>
<th width="1%">
{if $smarty.request.display != "radio"}
{include file="common/check_items.tpl"}
{/if}
</th>
<th width="70%">{__("name")}</th>
</tr>
</thead>
<tbody>
{foreach $menus as $menu}
<tr>
<td>
{if $smarty.request.display == "radio"}
<input type="radio" name="{$smarty.request.checkbox_name}" id="checkbox_id_{$menu@key}" value="{$menu@key}" class="cm-item" />
{else}
<input type="checkbox" name="{$smarty.request.checkbox_name}[]" id="checkbox_id_{$menu@key}" value="{$menu@key}" class="cm-item" />
{/if}
</td>
<td><label for="checkbox_id_{$menu@key}" id="menu_{$menu@key}">{$menu}</label></td>
</tr>
{/foreach}
</tbody>
</table>
</div>
{if $menus}
<div class="buttons-container">
{include file="buttons/add_close.tpl" but_text=__("abt__ut2.menus.pickers.add_menus") but_close_text=__("abt__ut2.menus.pickers.add_menus_and_close") is_js=$smarty.request.extra|fn_is_empty}
</div>
{/if}
</form>
