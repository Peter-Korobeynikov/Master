{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="abt__ut2_export_form" id="abt__ut2_export_form">
<p>{__("abt__ut2.export.description")}</p>
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive" width="100%">
<thead>
<tr>
<th width="40%">{__("abt__ut2.export.table.description")}</th>
<th width="40%">{__("abt__ut2.export.table.data")}</th>
<th width="20%" style="text-align: center">{__("action")}</th>
</tr>
</thead>
<tbody>
{* Exports output via array *}
{$arr = ['banners', 'menu', 'blog']}
{strip}
{foreach $arr as $exp}
<tr>
<td data-th="{__("abt__ut2.export.table.description")}">{__("abt__ut2.export.actions.{$exp}")}</td>
<td data-th="{__("abt__ut2.export.table.data")}">
{if $exp != 'blog'}
<input type="text" class="input-text-medium export-data" placeholder="{__("abt__ut2.export.actions.{$exp}.placeholder")}">
{/if}
</td>
<td data-th="{__("action")}" style="text-align: center">
<a class="cm-process-items cm-submit cm-ajax cm-post btn btn-primary{if $exp != 'blog'} export abt-ut2-not-active-btn{/if}"
data-ca-dispatch="dispatch[abt__ut2.export.{$exp}.]">
{__("export")}
</a>
</td>
</tr>
{/foreach}
{/strip}
</tbody>
</table>
</div>
</form>
<script>
$(document).ready(function() {
$(".export-data").on('keyup', function() {
var it = $(this);
var btn = it.parents("tr").find('a');
var dispatch = btn.attr("data-ca-dispatch").substr(0, btn.attr("data-ca-dispatch").lastIndexOf('.'));
$str = it.val().replace(/[^,0-9]/gim,'');
it.val($str);
if(it.val() !== '') {
btn.removeClass("abt-ut2-not-active-btn");
} else {
btn.addClass("abt-ut2-not-active-btn");
}
btn.attr("data-ca-dispatch", dispatch + "." + $str + ']');
});
});
</script>
{/capture}
{include file="common/mainbox.tpl"
title=__("abt__ut2.export")
title_start = __("abt__unitheme2")
title_end = __("abt__ut2.export")
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
content_id="abt__ut2_export_form"}