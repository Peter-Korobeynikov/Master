{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="abt__ut2_demo_data_form" id="abt__ut2_demo_data_form">
<p>{__("abt__ut2.demodata.description")}</p>
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive" width="100%">
<thead>
<tr>
<th width="80%">{__("abt__ut2.demodata.table.description")}</th>
<th width="20%" style="text-align: center">{__("action")}</th>
</tr>
</thead>
<tbody>
{$arr = ['banners', 'menu', 'blog']}
{foreach $arr as $demo}
<tr>
<td data-th="{__("abt__ut2.demodata.table.description")}">{__("abt__ut2.demodata.table.add_{$demo}")}</td>
<td data-th="{__("action")}" style="text-align: center">{btn type="list" class="cm-ajax cm-post btn btn-primary" text=__("add") dispatch="dispatch[abt__ut2.demodata.{$demo}]"}</td>
</tr>
{/foreach}
</tbody>
</table>
</div>
</form>
{/capture}
{include file="common/mainbox.tpl"
title=__("abt__ut2.demodata")
title_start = __("abt__unitheme2")
title_end = __("abt__ut2.demodata")
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
content_id="abt__ut2_demo_data_form"}