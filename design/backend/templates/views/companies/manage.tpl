{include file="views/profiles/components/profiles_scripts.tpl"}

{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="companies_form" id="companies_form">
<input type="hidden" name="fake" value="1" />

{include file="common/pagination.tpl" save_current_page=true save_current_url=true}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}

{assign var="return_url" value=$config.current_url|escape:"url"}

{if $companies}
<div class="table-responsive-wrapper">
    <table width="100%" class="table table-middle table--relative table-responsive">
    <thead>
    <tr>
        <th width="1%" class="left mobile-hide">
            {include file="common/check_items.tpl"}</th>
        <th width="8%"><a class="cm-ajax" href="{"`$c_url`&sort_by=id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("id")}{if $search.sort_by == "id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="25%"><a class="cm-ajax" href="{"`$c_url`&sort_by=company&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("name")}{if $search.sort_by == "company"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        {if "MULTIVENDOR"|fn_allowed_for}
            <th width="25%"><a class="cm-ajax" href="{"`$c_url`&sort_by=email&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("email")}{if $search.sort_by == "email"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        {/if}
        {if "ULTIMATE"|fn_allowed_for}
            <th width="25%"><a class="cm-ajax" href="{"`$c_url`&sort_by=storefront&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("storefront")}{if $search.sort_by == "storefront"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        {/if}
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=date&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("registered")}{if $search.sort_by == "date"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        {hook name="companies:list_extra_th"}{/hook}
        <th width="5%" class="nowrap">&nbsp;</th>
        {if "MULTIVENDOR"|fn_allowed_for}
            <th width="10%" class="right"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        {else}
            <th width="13%"><span class="cm-tooltip" title="{__("ttc_stores_status")}">{__("stores_status")}&nbsp;<i class="icon-question-sign"></i>{if $search.sort_by == "stores_status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</span></th>
        {/if}
    </tr>
    </thead>
    {foreach from=$companies item=company}
    <tr class="cm-row-status-{$company.status|lower}" data-ct-company-id="{$company.company_id}">
        <td class="left mobile-hide">
            <input type="checkbox" name="company_ids[]" value="{$company.company_id}" class="cm-item" />
        </td>
        <td class="row-status" data-th="{__("id")}"><a href="{"companies.update?company_id=`$company.company_id`"|fn_url}">&nbsp;<span>{$company.company_id}</span>&nbsp;</a></td>
        <td class="row-status" data-th="{__("name")}"><a href="{"companies.update?company_id=`$company.company_id`"|fn_url}">{$company.company}</a></td>
        {if "MULTIVENDOR"|fn_allowed_for}
            <td class="row-status" data-th="{__("email")}"><a href="mailto:{$company.email}">{$company.email}</a></td>
        {/if}
        {if "ULTIMATE"|fn_allowed_for}
            {$storefront_href = "http://`$company.storefront`"}
            {if $company.storefront_status === "StorefrontStatuses::CLOSED"|enum && $company.store_access_key}
                {$storefront_href = $storefront_href|fn_link_attach:"store_access_key=`$company.store_access_key`"}
            {/if}
            <td data-th="{__("storefront")}" id="storefront_url_{$company.company_id}"><a href="{$storefront_href}">{$company.storefront|puny_decode}</a><!--storefront_url_{$company.company_id}--></td>
        {/if}
        <td class="row-status" data-th="{__("registered")}">{$company.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
        {hook name="companies:list_extra_td"}{/hook}
        <td class="nowrap" data-th="{__("tools")}">
            {capture name="tools_items"}
            {hook name="companies:list_extra_links"}
                <li>{btn type="list" href="products.manage?company_id=`$company.company_id`" text=__("view_vendor_products")}</li>
                <li>{btn type="list" href="profiles.manage?company_id=`$company.company_id`" text=__("view_vendor_users")}</li>
                <li>{btn type="list" href="orders.manage?company_id=`$company.company_id`" text=__("view_vendor_orders")}</li>
                {if !"ULTIMATE"|fn_allowed_for && !$runtime.company_id}
                    <li>{btn type="list" href="companies.merge?company_id=`$company.company_id`" text=__("merge")}</li>
                {/if}
                {if !$runtime.company_id && fn_check_view_permissions("companies.update", "POST")}
                    <li>{btn type="list" href="companies.update?company_id=`$company.company_id`" text=__("edit")}</li>
                    <li class="divider"></li>
                    {if $runtime.simple_ultimate}
                        <li class="disabled"><a>{__("delete")}</a></li>
                    {else}
                        <li>{btn type="list" class="cm-confirm" href="companies.delete?company_id=`$company.company_id`&redirect_url=`$return_current_url`" text=__("delete") method="POST"}</li>
                    {/if}
                {/if}
            {/hook}
            {/capture}
            <div class="hidden-tools">
                {dropdown content=$smarty.capture.tools_items}
            </div>
        </td>
        {if "MULTIVENDOR"|fn_allowed_for}
            <td class="right nowrap" data-th="{__("status")}">
                {assign var="notify" value=true}
                {include file="common/select_popup.tpl"
                    id=$company.company_id
                    status=$company.status
                    items_status="companies"|fn_get_predefined_statuses:$company.status
                    object_id_name="company_id"
                    hide_for_vendor=$runtime.company_id
                    update_controller="companies"
                    notify=$notify
                    notify_text=__("notify_vendor")
                    status_target_id="pagination_contents"
                    extra="&return_url=`$return_url`"
                }
            </td>
        {else}
            <td class="row-status" data-th="{__("stores_status")}">
                {include file="views/companies/components/company_status_switcher.tpl" company=$company}
            </td>
        {/if}
    </tr>
    {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{if $companies}
    {if !$runtime.company_id}
    {capture name="activate_selected"}
        {include file="views/companies/components/reason_container.tpl" type="activate"}
        <div class="buttons-container">
            {include file="buttons/save_cancel.tpl" but_text=__("proceed") but_name="dispatch[companies.m_activate]" cancel_action="close" but_meta="cm-process-items"}
        </div>
    {/capture}
    {include file="common/popupbox.tpl" id="activate_selected" text=__("activate_selected") content=$smarty.capture.activate_selected link_text=__("activate_selected")}

    {capture name="disable_selected"}
        {include file="views/companies/components/reason_container.tpl" type="disable"}
        <div class="buttons-container">
            {include file="buttons/save_cancel.tpl" but_text=__("proceed") but_name="dispatch[companies.m_disable]" cancel_action="close" but_meta="cm-process-items"}
        </div>
    {/capture}
    {include file="common/popupbox.tpl" id="disable_selected" text=__("disable_selected") content=$smarty.capture.disable_selected link_text=__("disable_selected")}
    {/if}
{/if}

{include file="common/pagination.tpl"}
</form>
{/capture}
{capture name="buttons"}
    {capture name="tools_items"}
        {hook name="companies:manage_tools_list"}
            {if $companies && !$runtime.company_id && !"ULTIMATE"|fn_allowed_for}
                <li>{btn type="list" text=__("activate_selected") dispatch="dispatch[companies.export_range]" form="companies_form" class="cm-process-items cm-dialog-opener"  data=["data-ca-target-id" => "content_activate_selected"]}</li>                    
                <li>{btn type="list" text=__("disable_selected") dispatch="dispatch[companies.export_range]" form="companies_form" class="cm-process-items cm-dialog-opener"  data=["data-ca-target-id" => "content_disable_selected"]}</li>                    
            {/if}
            {if !$runtime.company_id && fn_check_view_permissions("companies.update", "POST")}
                <li>{btn type="delete_selected" dispatch="dispatch[companies.m_delete]" form="companies_form"}</li>
            {/if}
            {if $companies && "MULTIVENDOR"|fn_allowed_for}
                <li>{btn type="list" text=__("export_selected") dispatch="dispatch[companies.export_range]" form="companies_form"}</li>
            {/if}
        {/hook}
    {/capture}
    {dropdown content=$smarty.capture.tools_items class="mobile-hide"}

    {if "MULTIVENDOR"|fn_allowed_for}
        {include
            file="buttons/button.tpl"
            but_role="text"
            but_href="companies.invite"
            title=__("invite_vendors_title")
            but_text=__("invite_vendors")
            but_meta="btn cm-dialog-opener"
        }
    {/if}
{/capture}

{capture name="adv_buttons"}
    {if $is_companies_limit_reached}
        {$promo_popup_title = __("ultimate_or_storefront_license_required", ["[product]" => $smarty.const.PRODUCT_NAME])}

        {include file="common/tools.tpl" tool_override_meta="btn cm-dialog-opener cm-dialog-auto-height" tool_href="functionality_restrictions.ultimate_or_storefront_license_required" prefix="top" hide_tools=true title=__("add_vendor") icon="icon-plus" meta_data="data-ca-dialog-title=\"$promo_popup_title\""}
    {else}
        {include file="common/tools.tpl" tool_href="companies.add" prefix="top" hide_tools=true title=__("add_vendor") icon="icon-plus"}
    {/if}
{/capture}

{capture name="sidebar"}
    {hook name="companies:manage_sidebar"}
    {include file="common/saved_search.tpl" dispatch="companies.manage" view_type="companies"}
    {include file="views/companies/components/companies_search_form.tpl" dispatch="companies.manage"}
    {/hook}
{/capture}

{include file="common/mainbox.tpl" title=__("vendors") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}
