{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="ab__lc_export_form" id="ab__lc_export_form">
<p>{__("ab__lc.export_description")}</p>
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive" width="100%">
<thead>
<tr>
<th width="40%">{__("ab__lc.export.table.description")}</th>
<th width="40%">{__("ab__lc.export.table.data")}</th>
<th width="20%">{__("ab__lc.export.table.action")}</th>
</tr>
</thead>
<tbody>
<tr>
<td data-th="{__("ab__lc.export.table.description")}">{__("ab__lc.demodata.actions.export_category")}</td>
<td data-th="{__("ab__lc.export.table.data")}"><input type="text" id="category-ids" class="input-text-medium" placeholder="{__("ab__lc.demodata.actions.export_category.placeholder")}"></td>
<td data-th="{__("ab__lc.export.table.action")}">
<a style="pointer-events: none;" class="cm-process-items cm-submit cm-ajax cm-post btn btn-primary export-category" data-ca-dispatch="dispatch[ab__lc.export_category]">
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
var dispatch = btn.attr("data-ca-dispatch").substr(0, 31);
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
title=__("ab__lc.export")
title_start = __("ab__landing_categories")
title_end = __("ab__landing_categories.export")
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
content_id="ab__lc_export_form"}