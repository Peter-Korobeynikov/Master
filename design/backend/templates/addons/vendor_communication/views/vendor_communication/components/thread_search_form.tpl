<div class="sidebar-row">
    {include file="common/saved_search.tpl" dispatch="vendor_communication.threads" view_type="vc_threads"}
    <h6>{__("search")}</h6>
    <form name="thread_search_form" action="{""|fn_url}" method="get" class="{$form_meta}">

        {if $smarty.request.redirect_url}
            <input type="hidden" name="redirect_url" value="{$smarty.request.redirect_url}" />
        {/if}

        {if $put_request_vars}
            {array_to_fields data=$smarty.request skip=["callback"] escape=["data_id"]}
        {/if}

        {capture name="simple_search"}
            <div class="sidebar-field">
                <label for="elm_customer">{__("vendor_communication.customer_name")}</label>
                <div class="break">
                    <input type="text" name="customer_name" id="elm_customer" value="{$search.customer_name}" />
                </div>
            </div>
            {if !$runtime.company_id}
                <div class="sidebar-field">
                    <label for="elm_company">{__("company")}</label>
                    <div class="break">
                        <input type="text" name="company" id="elm_company" value="{$search.company}" />
                    </div>
                </div>
            {/if}
            {include file="common/period_selector.tpl" period=$period display="form"}
        {/capture}

        {include file="common/advanced_search.tpl" simple_search=$smarty.capture.simple_search dispatch=$dispatch view_type="vc_thread" in_popup=false}

    </form>
</div><hr>
