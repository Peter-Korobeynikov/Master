{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="category_banners_form" id="category_banners_form" class="{if ""|fn_check_form_permissions} cm-hide-inputs{/if}">
<input type="hidden" name="fake" value="1" />
{include file="common/pagination.tpl"}
{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"exicon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}
<div class="items-container" id="ab__category_banners_list">
{if $category_banners}
<table width="100%" class="table table-middle table-objects">
<thead>
<tr>
<th width="1%" class="left">{include file="common/check_items.tpl" class="cm-no-hide-input"}</th>
<th width="34%"><a class="cm-ajax" href="{"`$c_url`&sort_by=name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("name")}{if $search.sort_by == "name"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
<th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=from_date&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("ab__cb.form.from_date")}{if $search.sort_by == "from_date"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
<th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=to_date&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("ab__cb.form.to_date")}{if $search.sort_by == "to_date"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
<th width="15%" class="">&nbsp;</th>
<th width="10%" class="right"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
</tr>
</thead>
<tbody>
{foreach from=$category_banners item="category_banner"}
<tr class="cm-row-status-{$category_banner.status|lower}">
<td class="left">
<input type="checkbox" name="category_banner_ids[]" value="{$category_banner.category_banner_id}" class="cm-item cm-no-hide-input" />
</td>
<td>
<a class="row-status" href="{"ab__category_banners.update?category_banner_id=`$category_banner.category_banner_id`"|fn_url}">{$category_banner.category_banner}</a>
<input type="hidden" name="category_banners_data[{$category_banner.category_banner_id}][category_banner_id]" value="{$category_banner.category_banner_id}" >
</td>
<td>
{include file="common/calendar.tpl" date_id="elm_from_date_`$category_banner.category_banner_id`" date_name="category_banners_data[{$category_banner.category_banner_id}][from_date]" date_val=$category_banner.from_date start_year=$settings.Company.company_start_year}
<input type="text" name="category_banners_data[{$category_banner.category_banner_id}][from_time]" id="elm_from_time" value="{if $category_banner.from_date}{"H:i"|date:$category_banner.from_date}{/if}" size="3" class="input-small input-hidden" placeholder="00:00" />
</td>
<td>
{include file="common/calendar.tpl" date_id="elm_to_date_`$category_banner.category_banner_id`" date_name="category_banners_data[{$category_banner.category_banner_id}][to_date]" date_val=$category_banner.to_date start_year=$settings.Company.company_start_year}
<input type="text" name="category_banners_data[{$category_banner.category_banner_id}][to_time]" id="elm_to_time" value="{if $category_banner.to_date}{"H:i"|date:$category_banner.to_date}{/if}" size="3" class="input-small input-hidden" placeholder="00:00" />
</td>
<td>
<div class="hidden-tools">
{capture name="tools_list"}
<li>{btn type="list" text=__("edit") href="ab__category_banners.update?category_banner_id=`$category_banner.category_banner_id`"}</li>
{if "ab__category_banners.m_delete"|fn_check_view_permissions:"POST"}
{assign var="return_current_url" value=$config.current_url|escape:url}
<li>{btn type="list" class="cm-confirm cm-post" text=__("delete") href="ab__category_banners.delete?category_banner_id=`$category_banner.category_banner_id`&redirect_url=`$return_current_url`"}</li>
{/if}
{/capture}
{dropdown content=$smarty.capture.tools_list}
</div>
</td>
<td class="right nowrap">
{include file="common/select_popup.tpl" popup_additional_class="dropleft" id=$category_banner.category_banner_id status=$category_banner.status hidden=false object_id_name="category_banner_id" table="ab__category_banners"}
</td>
</tr>
{/foreach}
</tbody>
</table>
{else}
<p class="no-items">{__("no_data")}</p>
{/if}
<!--ab__category_banners_list--></div>
{include file="common/pagination.tpl"}
{capture name="buttons"}
{capture name="tools_list"}
{if $category_banners}
<li>{btn type="delete_selected" dispatch="dispatch[ab__category_banners.m_delete]" form="category_banners_form"}</li>
<li>{btn type="list" text=__("ab__cb.form.m_turn_on") dispatch="dispatch[ab__category_banners.m_turn_on]" form="category_banners_form"}</li>
<li>{btn type="list" text=__("ab__cb.form.m_turn_off") dispatch="dispatch[ab__category_banners.m_turn_off]" form="category_banners_form"}</li>
{/if}
{/capture}
{dropdown content=$smarty.capture.tools_list}
{if $category_banners}
{include file="buttons/save.tpl" but_name="dispatch[ab__category_banners.m_update]" but_role="submit-button" but_target_form="category_banners_form"}
{/if}
{/capture}
{capture name="adv_buttons"}
{include file="common/tools.tpl" tool_href="ab__category_banners.add" prefix="top" hide_tools="true" title=__("ab__category_banners.add") icon="icon-plus"}
{/capture}
</form>
{/capture}
{include file="common/mainbox.tpl"
title = __("ab__category_banners.manage")
title_start = __("ab__category_banners")
title_end = __("ab__category_banners.manage")
content = $smarty.capture.mainbox
select_languages = true
adv_buttons = $smarty.capture.adv_buttons
buttons = $smarty.capture.buttons
}
