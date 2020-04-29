{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="ab__lc_demo_data_form" id="ab__lc_demo_data_form">
<p>{__("ab__lc.demodata_description")}</p>
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive" width="100%">
<thead>
<tr>
<th width="60%">{__("ab__lc.demodata.table.description")}</th>
<th width="20%">{__("ab__lc.demodata.table.action")}</th>
</tr>
</thead>
<tbody>
<tr>
<td data-th="{__("ab__lc.demodata.table.description")}">{__("ab__lc.demodata.table.add_category")}</td>
<td data-th="{__("ab__lc.demodata.table.action")}">{btn type="list" class="cm-ajax cm-post btn btn-primary" text=__("add") dispatch="dispatch[ab__lc.demodata.category]"}</td>
</tr>
</tbody>
</table>
</div>
</form>
{/capture}
{include file="common/mainbox.tpl"
title=__("ab__lc.demodata")
title_start = __("ab__landing_categories")
title_end = __("ab__lc.demodata")
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
content_id="ab__lc_demo_data_form"}