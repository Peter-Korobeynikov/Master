{script src="js/tygh/tabs.js"}
{literal}
    <script type="text/javascript">
    function fn_check_option_type(value, tag_id)
    {
        var id = tag_id.replace('option_type_', '').replace('elm_', '');
        Tygh.$('#tab_option_variants_' + id).toggleBy(!(value == 'S' || value == 'R' || value == 'C'));
        Tygh.$('#required_options_' + id).toggleBy(!(value == 'I' || value == 'T' || value == 'F'));
        Tygh.$('#extra_options_' + id).toggleBy(!(value == 'I' || value == 'T'));
        Tygh.$('#file_options_' + id).toggleBy(!(value == 'F'));

        if (value == 'C') {
            var t = Tygh.$('table', '#content_tab_option_variants_' + id);
            Tygh.$('.cm-non-cb', t).switchAvailability(true); // hide obsolete columns
            Tygh.$('tbody:gt(1)', t).switchAvailability(true); // hide obsolete rows

        } else if (value == 'S' || value == 'R') {
            var t = Tygh.$('table', '#content_tab_option_variants_' + id);
            Tygh.$('.cm-non-cb', t).switchAvailability(false); // show all columns
            Tygh.$('tbody', t).switchAvailability(false); // show all rows
            Tygh.$('#box_add_variant_' + id).show(); // show "add new variants" box

        } else if (value == 'I' || value == 'T') {
            Tygh.$('#extra_options_' + id).show(); // show "add new variants" box
        }
    }
    </script>
{/literal}

{$c_url = $config.current_url|fn_query_remove:"sort_by":"sort_order"}
{$c_icon = "<i class=\"icon-`$search.sort_order_rev`\"></i>"}

{capture name="mainbox"}

    {if $object == "global"}
        {$select_languages = true}
        {$delete_target_id = "pagination_contents"}
    {else}
        {$delete_target_id = "product_options_list"}
    {/if}

    {include file="common/pagination.tpl"}

    {if !($runtime.company_id && (fn_allowed_for("MULTIVENDOR") || $product_data.shared_product == "Y") && $runtime.company_id != $product_data.company_id)}
        {capture name="toolbar"}
            {capture name="add_new_picker"}
                {if $product_data}
                    {include file="views/product_options/update.tpl" option_id="0" company_id=$product_data.company_id disable_company_picker=true}
                {else}
                    {include file="views/product_options/update.tpl" option_id="0"}
                {/if}
            {/capture}
            {if $object == "product"}
                {$position = "pull-right"}
            {/if}
            {if $view_mode == "embed"}
                {include file="common/popupbox.tpl" id="add_new_option" text=__("new_option") link_text=__("add_option") act="general" content=$smarty.capture.add_new_picker meta=$position icon="icon-plus"}

            {else}
                {include file="common/popupbox.tpl" id="add_new_option" text=__("new_option") title=__("add_option") act="general" content=$smarty.capture.add_new_picker meta=$position icon="icon-plus"}
            {/if}

        {/capture}
        {$extra nofilter}
    {/if}
        {if $object != "global"}
            <div class="btn-toolbar clearfix cm-toggle-button">
                {$smarty.capture.toolbar nofilter}
            </div>
        {else}
            {capture name="buttons"}
                {if $product_options && $object == "global"}
                    {capture name="tools_list"}
                        <li>{btn type="list" text=__("apply_to_products") href="product_options.apply"}</li>
                    {/capture}
                    {dropdown content=$smarty.capture.tools_list}
                {/if}
            {/capture}
            {capture name="adv_buttons"}
                {$smarty.capture.toolbar nofilter}
            {/capture}
        {/if}

        <div class="items-container" id="product_options_list">
            {if $product_options}
            <div class="table-responsive-wrapper">
                <table width="100%" class="table table-middle table--relative table-objects table-responsive">
                    {if $object == "global"}
                        <thead>
                        <tr>
                            <th>
                                <a class="cm-ajax" href="{"`$c_url`&sort_by=option_name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("name")}</a>{if $search.sort_by == "option_name"}{$c_icon nofilter}{/if} /
                                <a class="cm-ajax" href="{"`$c_url`&sort_by=internal_option_name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("code")}</a>{if $search.sort_by == "internal_option_name"}{$c_icon nofilter}{/if}{include file="common/tooltip.tpl" tooltip={__("internal_option_name_tooltip")}}
                            </th>
                            <th></th>
                            <th></th>
                            <th class="pull-right">
                                <a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("status")}</a>{if $search.sort_by == "status"}{$c_icon nofilter}{/if}
                            </th>
                        </tr>
                        </thead>
                    {/if}
                    <tbody>
                        {foreach from=$product_options item="po"}
                            {if $object == "product" && $po.product_id}
                                {$details = "({__("individual")})"}
                                {$query_product_id = ""}
                            {else}
                                {$details = ""}
                                {$query_product_id = "&product_id=`$product_id`"}
                            {/if}

                            {if $object == "product"}
                                {if !$po.product_id}
                                    {$query_product_id = "&object=`$object`"}
                                {else}
                                    {$query_product_id = "&product_id=`$product_id`&object=`$object`"}
                                {/if}
                                {$query_delete_product_id = "&product_id=`$product_id`"}
                                {$allow_save = $product_data|fn_allow_save_object:"products"}
                            {else}
                                {$query_product_id = ""}
                                {$query_delete_product_id = ""}
                                {$allow_save = $po|fn_allow_save_object:"product_options"}
                            {/if}

                            {if "MULTIVENDOR"|fn_allowed_for}
                                {if $allow_save}
                                    {$link_text = __("edit")}
                                    {$additional_class = "cm-no-hide-input"}
                                    {$hide_for_vendor = false}
                                {else}
                                    {$link_text = __("view")}
                                    {$additional_class = ""}
                                    {$hide_for_vendor = true}
                                {/if}
                            {/if}

                            {$status = $po.status}
                            {$href_delete = "product_options.delete?option_id=`$po.option_id``$query_delete_product_id`"}

                            {if "ULTIMATE"|fn_allowed_for}
                                {$non_editable = false}
                                {if $runtime.company_id && (($product_data.shared_product == "Y" && $runtime.company_id != $product_data.company_id) || ($object == "global" && $runtime.company_id != $po.company_id))}
                                    {$link_text = __("view")}
                                    {$href_delete = false}
                                    {$non_editable = true}
                                    {$is_view_link = true}
                                {/if}
                            {/if}

                            {$option_name = $po.option_name}
                            {if $po.internal_option_name}
                                {$internal_option_name = "<br />{$po.internal_option_name}"}
                            {/if}

                            {include file="common/object_group.tpl"
                                     no_table=true
                                     no_padding=true
                                     id=$po.option_id
                                     id_prefix="_product_option_"
                                     details=$details
                                     text=$po.option_name
                                     href_desc=$internal_option_name
                                     hide_for_vendor=$hide_for_vendor
                                     status=$status
                                     table="product_options"
                                     object_id_name="option_id"
                                     href="product_options.update?option_id=`$po.option_id``$query_product_id`"
                                     href_delete=$href_delete
                                     delete_target_id=$delete_target_id
                                     header_text="{__("editing_option")}: `$po.option_name`"
                                     skip_delete=!$allow_save
                                     additional_class=$additional_class
                                     prefix="product_options"
                                     link_text=$link_text
                                     non_editable=$non_editable
                                     company_object=$po
                                     href_desc_row_hint="{__("name")} / {__("code")}"
                                     status_row_hint="{__("status")}"
                            }
                    {/foreach}
                    </tbody>
                </table>
            </div>
            {else}
                <p class="no-items">{__("no_data")}</p>
            {/if}
            <!--product_options_list--></div>
    {include file="common/pagination.tpl"}

{/capture}

{if $object == "product"}
    {$smarty.capture.mainbox nofilter}
{else}
    {include file="common/mainbox.tpl" title=__("options") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons select_language=$select_language}
{/if}
