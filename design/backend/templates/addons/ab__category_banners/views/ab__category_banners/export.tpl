{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="ab__cb_export_form" id="ab__cb_export_form">
<p>{__("ab__cb.export_description")}</p>
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive" width="100%">
<thead>
<tr>
<th width="40%">{__("ab__cb.export.table.description")}</th>
<th width="40%">{__("ab__cb.export.table.data")}</th>
<th width="20%">{__("ab__cb.export.table.action")}</th>
</tr>
</thead>
<tbody>
<tr>
<td data-th="{__("ab__cb.export.table.description")}">{__("ab__cb.export.actions.banners")}</td>
<td data-th="{__("ab__cb.export.table.data")}"><input type="text" id="category-ids" class="input-text-medium" placeholder="{__("ab__cb.export.actions.banners.placeholder")}"></td>
<td data-th="{__("ab__cb.export.table.action")}">
<a style="pointer-events: none;" class="cm-process-items cm-submit cm-ajax cm-post btn btn-primary export-category" data-ca-dispatch="dispatch[ab__category_banners.export_category]">
{__("add")}
</a>
</td>
</tr>
</tbody>
</table>
</div>
</form>
<script>
$(document).ready(function(){
var btn = $(".export-category");
var not_active = {
"pointer-events": "none",
"background-color": "#aaa",
"border": "1px solid #aaa"
};
btn.css(not_active);
$("#category-ids").on('keyup', function() {
var dispatch = btn.attr("data-ca-dispatch").substr(0, 45);
$str = $(this).val().replace(/[^,0-9]/gim,'');
$(this).val($str);
if($(this).val() !== '') {
btn.css({
"pointer-events": "all",
"background-color": "#0388cc",
"border": "1px solid #0388cc"
});
}
else {
btn.css(not_active);
}
btn.attr("data-ca-dispatch", dispatch + "." + $str + ']');
});
});
</script>
{/capture}
{include file="common/mainbox.tpl"
title = __("ab__category_banners.export")
title_start = __("ab__category_banners")
title_end = __("ab__category_banners.export")
content = $smarty.capture.mainbox
buttons = $smarty.capture.buttons
content_id = "ab__cb_export_form"
}