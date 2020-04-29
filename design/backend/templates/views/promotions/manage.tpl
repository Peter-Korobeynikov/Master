{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="promotion_form" class="{if ""|fn_check_form_permissions} cm-hide-inputs{/if}">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}

{if $promotions}
<div class="table-responsive-wrapper">
    <table class="table table-middle table--relative table-responsive">
    <thead>
    <tr>
        <th class="mobile-hide" width="1%">
            {include file="common/check_items.tpl"}
        </th>
        <th width="30%">
            <a class="cm-ajax" href="{"`$c_url`&sort_by=name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("name")}{if $search.sort_by == "name"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="10%" class="nowrap center mobile-hide">
            <a class="cm-ajax" href="{"`$c_url`&sort_by=priority&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("priority")}{if $search.sort_by == "priority"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="10%" class="mobile-hide">
            <a class="cm-ajax" href="{"`$c_url`&sort_by=zone&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("zone")}{if $search.sort_by == "zone"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>

        {hook name="promotions:manage_header"}{/hook}

        <th width="10%" class="mobile-hide">&nbsp;</th>
        <th width="10%" class="right"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    </tr>
    </thead>

    {foreach from=$promotions item=promotion}

        {assign var="allow_save" value=$promotion|fn_allow_save_object:"promotions"}

        {if $allow_save}
            {assign var="link_text" value=__("edit")}
            {assign var="additional_class" value="cm-no-hide-input"}
            {assign var="status_display" value=""}
        {else}
            {assign var="link_text" value=__("view")}
            {assign var="additional_class" value="cm-hide-inputs"}
            {assign var="status_display" value="text"}
        {/if}

    <tr class="cm-row-status-{$promotion.status|lower} {$additional_class}">
        <td class="mobile-hide">
            <input name="promotion_ids[]" type="checkbox" value="{$promotion.promotion_id}" class="cm-item" /></td>
        <td data-th="{__("name")}">
            <a class="row-status" href="{"promotions.update?promotion_id=`$promotion.promotion_id`"|fn_url}">{$promotion.name}</a>
            {include file="views/companies/components/company_name.tpl" object=$promotion}
        <td class="center mobile-hide" data-th="{__("priority")}">
            <span>{$promotion.priority}</span>
        </td>
        <td class="mobile-hide" data-th="{__("zone")}">
            <span class="row-status">{__($promotion.zone)}</span>
        </td>

        {hook name="promotions:manage_data"}{/hook}

        <td class="right mobile-hide">
            <div class="hidden-tools">
            {capture name="tools_list"}
                {hook name="promotions:list_extra_links"}
                <li>{btn type="list" text=$link_text href="promotions.update?promotion_id=`$promotion.promotion_id`"}</li>
                {if $allow_save}
                    <li>{btn type="list" text=__("delete") class="cm-confirm" href="promotions.delete?promotion_id=`$promotion.promotion_id`" method="POST"}</li>
                {/if}
                {/hook}
            {/capture}
            {dropdown content=$smarty.capture.tools_list}
            </div>
        </td>
        <td class="nowrap right" data-th="{__("status")}">
            {include file="common/select_popup.tpl" popup_additional_class="dropleft" display=$status_display id=$promotion.promotion_id status=$promotion.status hidden=true object_id_name="promotion_id" table="promotions"}
        </td>
    </tr>
    {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl"}

{capture name="buttons"}
    {capture name="tools_list"}
        {hook name="promotions:manage_tools_list"}
            {if $promotions}
                <li>{btn type="delete_selected" dispatch="dispatch[promotions.m_delete]" form="promotion_form"}</li>
            {/if}
        {/hook}
    {/capture}
    {dropdown content=$smarty.capture.tools_list class="mobile-hide"}
{/capture}

{capture name="adv_buttons"}
    {capture name="tools_list"}
        <li>{btn type="list" text=__("add_catalog_promotion") href="promotions.add?zone=catalog"}</li>
        <li>{btn type="list" text=__("add_cart_promotion") href="promotions.add?zone=cart"}</li>
    {/capture}
    {dropdown content=$smarty.capture.tools_list icon="icon-plus" no_caret=true placement="right"}
    {** Hook for the actions menu on the products manage page *}
{/capture}

</form>
{/capture}
{include file="common/mainbox.tpl" title=__("promotions") content=$smarty.capture.mainbox tools=$smarty.capture.tools select_languages=true buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons}