{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="ab__cb_demo_data_form" id="ab__cb_demo_data_form">
<p>{__("ab__cb.demodata_description")}</p>
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive" width="100%">
<thead>
<tr>
<th width="60%">{__("ab__cb.demodata.table.description")}</th>
<th width="20%">{__("ab__cb.demodata.table.action")}</th>
</tr>
</thead>
<tbody>
<tr>
<td data-th="{__("ab__fn.demodata.table.description")}">{__("ab__cb.demodata.table.add_banners")}</td>
<td data-th="{__("ab__fn.demodata.table.action")}">{btn type="list" class="cm-ajax cm-post btn btn-primary" text=__("add") dispatch="dispatch[ab__category_banners.demodata.banners]"}</td>
</tr>
</tbody>
</table>
</div>
</form>
{/capture}
{include file="common/mainbox.tpl"
title = __("ab__category_banners.demodata")
title_start = __("ab__category_banners")
title_end = __("ab__category_banners.demodata")
content = $smarty.capture.mainbox
buttons = $smarty.capture.buttons
content_id = "ab__cb_demo_data_form"
}