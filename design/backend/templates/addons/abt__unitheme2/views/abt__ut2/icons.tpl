{assign var="title_start" value=__("abt__ut2.icons")}
{assign var="title_end" value=__("abt__unitheme2")}
{strip}
{capture name="mainbox_title"}
{$title_start} {$title_end}
{/capture}
{capture name="mainbox"}
<p>{__('abt__ut2.icons.info')}</p>
<div class="table-responsive-wrapper">
<p>{__('abt__ut2.icons.ut2_icons')}</p>
<table class="table table-middle table-responsive abt-ut2-table" width="100%">
<thead>
<tr>
<th width="20%">{__("icon")}</th>
<th width="20%">{__("copy")}</th>
<th width="60%">{__("abt__ut2.icons.class")}</th>
</tr>
</thead>
<tbody>
{foreach $icons.ut2_icons as $icon}
{include file="addons/abt__unitheme2/views/abt__ut2/components/icon_row.tpl"}
{/foreach}
</tbody>
</table>
{hook name="abt__ut2:print_icons"}{/hook}
</div>
{/capture}
{/strip}
<style>
.abt-ut2-table th, .abt-ut2-table td {
text-align: center !important;
}
</style>
{include file="common/mainbox.tpl" title=$smarty.capture.mainbox_title title_start=$title_start title_end=$title_end content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}
