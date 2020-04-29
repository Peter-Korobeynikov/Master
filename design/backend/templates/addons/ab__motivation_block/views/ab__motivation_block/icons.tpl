{assign var="title_start" value=__("ab__motivation_block.icons")}
{assign var="title_end" value=__("ab__motivation_block")}
{$hide_inputs = !"ab__motivation_block.change"|fn_check_view_permissions}
{strip}
{capture name="mainbox_title"}
{$title_start} {$title_end}
{/capture}
{capture name="mainbox"}
<form action="{""|fn_url}" name="ab__mb_icons_form" enctype="multipart/form-data">
<p>{__('ab__mb.icons.info')}</p>
<div class="table-responsive-wrapper">
{hook name="ab__mb:icons"}
<p>{__('ab__mb.icons.tygh_icons')}</p>
<table class="table table-middle table-responsive ab-mb-table" width="100%">
<thead>
<tr>
<th width="20%">{__("icon")}</th>
<th width="20%">{__("copy")}</th>
<th width="60%">{__("ab__mb.icons.class")}{include file="common/tooltip.tpl" tooltip=__("ab__mb.icons.class.tooltip")}</th>
</tr>
</thead>
<tbody>
{foreach $icons.ty_icons as $icon}
<tr>
<td data-th="{__("icon")}"><span class="ab-mb-icon-example"><i class="ty-{$icon}"></i></span></td>
<td data-th="{__("copy")}"><a class="ab-mb-copy btn btn-primary">{__("copy")}</a></td>
<td data-th="{__("ab__mb.icons.class")}">ty-{$icon}</td>
</tr>
{/foreach}
</tbody>
</table>
{/hook}
</div>
</form>
{/capture}
{/strip}
{capture name="sidebar"}
{capture name="icon_page_notes"}
{hook name="ab__mb:icons_sidebar"}
{include file="addons/ab__motivation_block/views/ab__motivation_block/components/icon_page_notes.tpl"}
{/hook}
{/capture}
{if $smarty.capture.icon_page_notes|strip_tags|trim}{$smarty.capture.icon_page_notes nofilter}{/if}
{/capture}
{capture name="buttons"}
<span class="mobile-hide">
{capture name="tools_list"}
{if !$hide_inputs}
<li>{btn type="text" class="mb-hide-icons" text=__('ab__mb.icons.refresh') dispatch="dispatch[ab__motivation_block.icons.refresh]" form="ab__mb_icons_form"}</li>
{/if}
{/capture}
{dropdown content=$smarty.capture.tools_list}
</span>
{/capture}
<style>
.ab-mb-table th, .ab-mb-table td {
text-align: center !important;
}
</style>
<script>
function ab__mb_copy_to_clipboard( text ) {
if (window.clipboardData && window.clipboardData.setData) {
return clipboardData.setData("Text", text);
} else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
var textarea = document.createElement("textarea");
textarea.textContent = text;
textarea.style.position = "fixed";
document.body.appendChild(textarea);
textarea.select();
try {
return document.execCommand("copy");
} catch (ex) {
console.warn("Copy to clipboard failed", ex);
return false;
} finally {
document.body.removeChild(textarea);
}
}
}
$(".ab-mb-copy").click( function(e) {
var link = $(this);
var tr = link.parents("tr");
var text = tr.find("td[data-th='{__("ab__mb.icons.class")}']").text();
ab__mb_copy_to_clipboard(text);
$.ceNotification("show", {
type: 'N',
title: Tygh.tr("notice"),
message: "{__('ab__mb.class_copied')}"
});
e.preventDefault();
});
</script>
{include file="common/mainbox.tpl"
title=$smarty.capture.mainbox_title
title_start=$title_start
title_end=$title_end
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
adv_buttons=$smarty.capture.adv_buttons
sidebar=$smarty.capture.sidebar
}