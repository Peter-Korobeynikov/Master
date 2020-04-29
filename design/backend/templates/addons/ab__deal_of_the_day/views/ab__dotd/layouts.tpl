{capture name="mainbox"}
<div style="padding:15px;background:#eee;">{__("ab__dotd.layouts.header")}</div>
<form action="{""|fn_url}" method="post" name="ab__dotd_layouts_form" id="ab__dotd_layouts_form">
<table class="table table-middle table-responsive" width="100%">
<thead>
<tr>
<th width="25%">{__("ab__dotd.layouts.table.dispatch_description")}</th>
<th width="20%">{__("ab__dotd.layouts.table.dispatch")}</th>
<th class="center" width="25%">{__("ab__dotd.layouts.table.layouts")}</th>
<th class="center" width="10%">{__("ab__dotd.layouts.table.action")}</th>
<th width="20%">{__("ab__dotd.layouts.table.get_file")}</th>
</tr>
</thead>
<tbody>
{foreach $ab__dotd_layouts as $dispatch => $ab__dotd_layout_data}
{$key = $ab__dotd_layout_data@iteration}
<tr>
<td data-th="{__("ab__dotd.layouts.table.dispatch_description")}">{__($dispatch_descriptions.$dispatch|default:"custom")}</td>
<td data-th="{__("ab__dotd.layouts.table.dispatch")}">{$dispatch}</td>
<td class="center" data-th="{__("ab__dotd.layouts.table.layouts")}">
<input type="hidden" name="layouts[{$key}][dispatch]" value="{$dispatch}">
<select name="layouts[{$key}][location]">
<option value=""> --- </option>
{foreach $ab__dotd_layout_data.layouts as $layout}
<optgroup label="{$layout.theme_name} - {$layout.name}">
{foreach $layout.locations as $location}
<option value="{"`$layout.layout_id`.`$location.location_id`"}">{$location.name}</option>
{/foreach}
</optgroup>
{/foreach}
</select>
</td>
<td class="center" data-th="{__("ab__dotd.layouts.table.action")}">
{btn type="list" class="cm-post btn btn-primary" text=__("reset") dispatch="dispatch[ab__dotd.reset_layout..`$key`]"}
</td>
<td data-th="{__("ab__dotd.layouts.table.get_file")}">
{if $ab__dotd_layout_data.files_full_paths}
<ul>
{foreach $ab__dotd_layout_data.files_full_paths as $theme_name => $path}
<li><a target="_blank" href="{$path}">{$theme_name}</a></li>
{/foreach}
</ul>
{/if}
</td>
</tr>
{/foreach}
</tbody>
</table>
</form>
{/capture}
{include file="common/mainbox.tpl"
title=__("ab__dotd.layouts")
title_start = __("ab__deal_of_the_day")
title_end = __("ab__dotd.layouts")
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
content_id="ab__dotd_demo_data_form"
}