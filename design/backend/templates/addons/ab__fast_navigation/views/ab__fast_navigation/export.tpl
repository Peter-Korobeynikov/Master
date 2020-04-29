{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="ab__fn_export_form" id="ab__fn_export_form">
<p>{__("ab__fn.export_description")}</p>
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive" width="100%">
<thead>
<tr>
<th width="40%">{__("ab__fn.export.table.description")}</th>
<th width="40%">{__("ab__fn.export.table.data")}</th>
<th width="20%">{__("ab__fn.export.table.action")}</th>
</tr>
</thead>
<tbody>
<tr>
<td data-th="{__("ab__fn.export.table.description")}">{__("ab__fn.demodata.actions.export_menu")}</td>
<td data-th="{__("ab__fn.export.table.data")}"><input type="text" id="menu-ids" class="input-text-medium" placeholder="{__("ab__fn.demodata.actions.export_menu.placeholder")}"></td>
<td data-th="{__("ab__fn.export.table.action")}">
<a style="pointer-events: none;" class="cm-process-items cm-submit cm-ajax cm-post btn btn-primary export-menu" data-ca-dispatch="dispatch[ab__fast_navigation.export_menu]">
{__("add")}
</a>
</td>
</tr>
</tbody>
</table>
</div>
</form>
{/capture}
{include file="common/mainbox.tpl"
title=__("ab__fn.export")
title_start = __("ab__fast_navigation")
title_end = __("ab__fast_navigation.export")
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
content_id="ab__fn_export_form"}