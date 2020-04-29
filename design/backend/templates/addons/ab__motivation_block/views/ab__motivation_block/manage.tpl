{** motivation_items section **}
{strip}
{capture name="mainbox"}
{$hide_inputs = !"ab__motivation_block.change"|fn_check_view_permissions}
<form action="{""|fn_url}" method="post" name="ab__mb_motivation_items_form" enctype="multipart/form-data"{if $hide_inputs} class="cm-hide-inputs"{/if}>
<input type="hidden" name="fake" value="1" />
{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id="pagination_contents_motivation_items"}
{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents_motivation_items"}
{assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}
{if $motivation_items}
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive">
<thead>
<tr>
<th width="1%" class="left">
{include file="common/check_items.tpl"}</th>
<th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents_motivation_items">{__("name")}{if $search.sort_by == "name"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
<th width="30%" class="mobile-hide">{__("categories")}</th>
<th width="30%" class="mobile-hide">{__("ab__mb.destinations")}</th>
<th width="6%" class="mobile-hide">&nbsp;</th>
<th width="13%" class="right"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents_motivation_items">{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
</tr>
</thead>
<tbody>
{foreach from=$motivation_items item="motivation_item"}
<tr class="cm-row-status-{$motivation_item.status|lower}">
<td class="left mobile-hide">
<input type="checkbox" name="motivation_item_ids[]" value="{$motivation_item.motivation_item_id}" class="cm-item" /></td>
<td>
<a class="row-status" href="{"ab__motivation_block.update?motivation_item_id=`$motivation_item.motivation_item_id`"|fn_url}">{$motivation_item.name}</a>
</td>
<td class="mobile-hide" data-th="{__("categories")}">
{$_tmp_obj = 'categories'}
<div class="row-status">
{if $motivation_item.{"`$_tmp_obj`_ids"}}
{if $motivation_item.{"exclude_`$_tmp_obj`"} == 'N'}
{__('display_on')}:&nbsp;
{else}
{__('ab__mb.not_display_on')}:&nbsp;
{/if}
{/if}
{$motivation_item.$_tmp_obj}
</div>
</td>
<td class="mobile-hide" data-th="{__("ab__mb.destinations")}">
{$_tmp_obj = 'destinations'}
<div class="row-status">
{if $motivation_item.{"`$_tmp_obj`_ids"}}
{if $motivation_item.{"exclude_`$_tmp_obj`"} == 'N'}
{__('display_on')}:&nbsp;
{else}
{__('ab__mb.not_display_on')}:&nbsp;
{/if}
{/if}
{$motivation_item.$_tmp_obj}
</div>
</td>
<td class="mobile-hide">
{capture name="tools_list"}
<li>{btn type="list" text=__("edit") href="ab__motivation_block.update?motivation_item_id=`$motivation_item.motivation_item_id`"}</li>
{if !$hide_inputs}
<li>{btn type="list" class="cm-post" text=__("clone") href="ab__motivation_block.clone?item_id=`$motivation_item.motivation_item_id`"}</li>
<li>{btn type="list" class="cm-confirm" text=__("delete") href="ab__motivation_block.delete?motivation_item_id=`$motivation_item.motivation_item_id`" method="POST"}</li>
{/if}
{/capture}
<div class="hidden-tools">
{dropdown content=$smarty.capture.tools_list}
</div>
</td>
<td class="right nowrap" data-th="{__("status")}">
{include file="common/select_popup.tpl" id=$motivation_item.motivation_item_id status=$motivation_item.status hidden=true object_id_name="motivation_item_id" table="ab__mb_motivation_items" popup_additional_class="cm-no-hide-input dropleft" non_editable=$hide_inputs}
</td>
</tr>
{/foreach}
</tbody>
</table>
</div>
{else}
<p class="no-items">{__("no_data")}</p>
{/if}
{include file="common/pagination.tpl" div_id="pagination_contents_motivation_items"}
{capture name="buttons"}
<span class="mobile-hide">
{capture name="tools_list"}
{if $motivation_items && !$hide_inputs}
<li>{btn type="list" class="cm-post" text=__('clone_selected') dispatch="dispatch[ab__motivation_block.m_clone]" form="ab__mb_motivation_items_form"}</li>
<li>{btn type="list" class="cm-post" text=__('activate_selected') dispatch="dispatch[ab__motivation_block.m_activate]" form="ab__mb_motivation_items_form"}</li>
<li>{btn type="list" class="cm-post" text=__('disable_selected') dispatch="dispatch[ab__motivation_block.m_disable]" form="ab__mb_motivation_items_form"}</li>
<li>{btn type="delete_selected" dispatch="dispatch[ab__motivation_block.m_delete]" form="ab__mb_motivation_items_form"}</li>
{/if}
{/capture}
{dropdown content=$smarty.capture.tools_list}
</span>
{/capture}
{capture name="adv_buttons"}
{include file="common/tools.tpl" tool_href="ab__motivation_block.add" prefix="top" hide_tools="true" title=__("add") icon="icon-plus"}
{/capture}
</form>
{/capture}
{capture name="sidebar"}
{include file="common/saved_search.tpl" dispatch="ab__motivation_block.manage" view_type="ab__motivation_items"}
{include file="addons/ab__motivation_block/views/ab__motivation_block/components/motivation_items_search_form.tpl" dispatch="ab__motivation_block.manage"}
{/capture}
{$title_start = __("ab__motivation_block")}
{$title_end = __("ab__motivation_block.manage")}
{capture name="mainbox_title"}
{$title_start}: {$title_end}
{/capture}
{include file="common/mainbox.tpl"
title_start=$title_start
title_end=$title_end
title=$smarty.capture.mainbox_title
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
adv_buttons=$smarty.capture.adv_buttons
select_languages=true
sidebar=$smarty.capture.sidebar}
{/strip}