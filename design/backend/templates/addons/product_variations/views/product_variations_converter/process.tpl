{capture name="mainbox"}
    {if $data_exists}
        <form action="{""|fn_url}" cname="product_variations_converter_form" id="product_variations_converter_form" class="cm-ajax cm-comet form-edit form-horizontal cm-disable-check-changes" method="post">
            {foreach $product_ids as $product_id}
                <input type="hidden" name="product_ids[]" value="{$product_id}">
            {/foreach}

            <input type="hidden" name="by_variations" value="{$by_variations|intval}">
            <input type="hidden" name="by_combinations" value="{$by_combinations|intval}">
        </form>

        <div class="well">
            {if $configurable_products_count}
                <div class="row-fluid">
                    <div class="span4">{__("product_variations.converter.view.configurable_products_count")}</div>
                    <div class="span2">{$configurable_products_count|intval}</div>
                </div>
            {/if}
            {if $variations_products_count}
                <div class="row-fluid">
                    <div class="span4">{__("product_variations.converter.view.variations_count")}</div>
                    <div class="span2">{$variations_products_count|intval}</div>
                </div>
            {/if}
            {if $products_with_combinations_count}
                <div class="row-fluid">
                    <div class="span4">{__("product_variations.converter.view.products_with_combinations_count")}</div>
                    <div class="span2">{$products_with_combinations_count|intval}</div>
                </div>
            {/if}
            {if $combinations_count}
                <div class="row-fluid">
                    <div class="span4">{__("product_variations.converter.view.combinations_count")}</div>
                    <div class="span2">{$combinations_count|intval}</div>
                </div>
            {/if}
        </div>

        <h4>{__("product_variations.converter.features.list.title")}</h4>
        <p>{__("product_variations.converter.features.list.hint")}</p>

        {include file="common/pagination.tpl" save_current_page=true div_id="product_variations_converter_options"}

        <form action="{""|fn_url}" cname="product_variations_features_converter_form" id="product_variations_features_converter_form" method="post">
            <div class="btn-toolbar clearfix cm-toggle-button">
                <div class="pull-right">
                    {include file="buttons/button.tpl" but_name="dispatch[product_variations_converter.merge]" but_text=__("product_variations.converter.features.merge")}
                </div>
            </div>

            {foreach $product_ids as $product_id}
                <input type="hidden" name="product_ids[]" value="{$product_id}">
            {/foreach}


            <input type="hidden" name="by_variations" value="{$by_variations|intval}">
            <input type="hidden" name="by_combinations" value="{$by_combinations|intval}">

            <div class="table-responsive-wrapper">
                <table width="100%" class="table table-middle" data-ca-main-content>
                    <thead>
                    <tr>
                        <th width="2%">&nbsp;</th>
                        <th width="2%">&nbsp;</th>
                        <th class="nowrap"><span>{__("feature")}</span></th>
                        {if "ULTIMATE"|fn_allowed_for}
                            <th class="nowrap"><span>{__("storefront")}</span></th>
                        {/if}
                        <th class="nowrap"><span>{__("categories")}</span></th>
                        <th class="nowrap"><span>{__("options")}</span></th>
                        <th width="2%">&nbsp;</th>
                    </tr>
                    </thead>
                    {foreach $product_features as $key => $feature}
                        <tbody>
                            <tr>
                                <td>
                                    <input type="checkbox" name="feature_keys[]" value="{$key}">
                                </td>
                                <td>
                                    <button alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" id="sw_variants_{$key}" class="cm-combinations cm-product-variations__collapse product-variations__collapse-btn product-variations__collapse-btn--collapsed" type="button">
                                        <span class="icon-caret-down hidden" data-ca-switch-id="variants_{$key}"> </span>
                                        <span class="icon-caret-right" data-ca-switch-id="variants_{$key}"> </span>
                                    </button>
                                </td>
                                <td>
                                    {if $feature.feature_id}
                                        <a href="{"product_features.update?feature_id={$feature.feature_id}"|fn_url}" target="_blank">
                                            {$feature.feature_name}
                                        </a>
                                    {else}
                                        {$feature.feature_name}
                                    {/if}
                                </td>
                                {if "ULTIMATE"|fn_allowed_for}
                                    <td>
                                        {foreach $feature.company_ids as $company_id}
                                            {include file="views/companies/components/company_name.tpl" object=["company_id" => $company_id]}
                                        {/foreach}
                                    </td>
                                {/if}
                                <td>
                                    {foreach $feature.category_names as $category_id => $category_name}
                                        <p class="muted"><small><a href="{"categories.update?category_id={$category_id}"|fn_url}" target="_blank">{$category_name}</a> </small></p>
                                    {/foreach}
                                </td>
                                <td>
                                    <div class="hidde22n">
                                        <div id="popup_options_{$key}" class="hidden">
                                            <table class="table table-middle">
                                                <thead>
                                                    <th>{__("option_name")}</th>
                                                    <th>{__("product_name")}</th>
                                                </thead>
                                                <tbody>
                                                    {foreach $feature.options as $option}
                                                        <tr>
                                                            <td>
                                                                <a href="{"products.update?product_id={$option.product_id}&selected_section=options#group_product_option_{$option.option_id}"|fn_url}" target="_blank">
                                                                    {$option.option_name}
                                                                </a>
                                                            </td>
                                                            <td><a href="{"products.update?product_id={$option.product_id}"|fn_url}" target="_blank">{$option.product_name}</a></td>
                                                        </tr>
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <p class="muted"><small><a href="#" class="cm-dialog-opener cm-dialog-auto-size" data-ca-target-id="popup_options_{$key}" title="{__("options")}">{__("options")}</a></small></p>
                                </td>
                                <td>
                                    {if $feature.is_merged && !$feature.feature_id}
                                        {capture name="tools_list"}
                                            <li>{btn type="list" text=__("product_variations.converter.features.unmerge") href="{$config.current_url|fn_link_attach:"dispatch=product_variations_converter.unmerge&feature_key=`$key`"}" method="POST"}</li>
                                        {/capture}
                                        {dropdown content=$smarty.capture.tools_list}
                                    {/if}
                                </td>
                            </tr>
                        </tbody>

                        <tbody data-ca-switch-id="variants_{$key}" class="hidden">
                            {foreach $feature.variants as $variant}
                                <tr>
                                    <td width="2%"></td>
                                    <td width="2%"></td>
                                    <td>{$variant.variant}</td>
                                    <td>
                                        {if $variant.feature_variant_id}
                                            {$found_icon nofilter}
                                        {else}
                                            {$not_found_icon nofilter}
                                        {/if}
                                    </td>
                                    {if "ULTIMATE"|fn_allowed_for}
                                        <td></td>
                                    {/if}
                                    <td></td>
                                    <td></td>
                                </tr>
                            {/foreach}
                        </tbody>
                    {/foreach}
                </table>
            </div>
        </form>
        {include file="common/pagination.tpl" div_id="product_variations_converter_options"}
    {else}
        <p class="no-items">{__("product_variations.converter.view.no_data")}</p>
    {/if}
{/capture}

{capture name="buttons"}
    {if $data_exists}
        {include file="buttons/button.tpl"
            but_text=__("start")
            but_role="submit-link"
            but_name="dispatch[product_variations_converter.process]"
            but_target_form="product_variations_converter_form"
            but_meta="btn-primary cm-ajax cm-comet cm-confirm"
            but_method="POST"
            allow_href=true
        }
    {/if}
{/capture}
{include file="common/mainbox.tpl" title=__("product_variations.converter.view.title") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons}
