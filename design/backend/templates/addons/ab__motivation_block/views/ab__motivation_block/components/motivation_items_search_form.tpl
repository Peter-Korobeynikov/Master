{if $in_popup}
<div class="adv-search">
<div class="group">
{else}
<div class="sidebar-row">
<h6>{__("search")}</h6>
{/if}
<form action="{""|fn_url}" name="ab__motivation_items_search_form" method="get" class="{$form_meta}" id="ab__motivation_items_search_form">
{if $put_request_vars}
{array_to_fields data=$smarty.request skip=["callback"]}
{/if}
{capture name="simple_search"}
{hook name="ab__mb:simple_search"}
{include file="addons/ab__motivation_block/views/ab__motivation_block/components/motivation_items_simple_search.tpl"}
{/hook}
{/capture}
{capture name="advanced_search"}
{hook name="ab__mb:advanced_search"}{/hook}
{/capture}
{include file="common/advanced_search.tpl" simple_search=$smarty.capture.simple_search advanced_search=$smarty.capture.advanced_search dispatch=$dispatch view_type="ab__motivation_items" in_popup=$in_popup}
<!--ab__motivation_items_search_form--></form>
{if $in_popup}
</div></div>
{else}
</div><hr>
{/if}