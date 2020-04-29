{$suffix = ""}
{$has_available = false}
{if $show_installed}
    {$suffix = "installed"}
{/if}

{if $runtime.company_id}
    {assign var="hide_for_vendor" value=true}
{/if}

{if $addons_list}
<div class="table-responsive-wrapper">
    <table class="table table-addons cm-filter-table table-responsive table-responsive-w-titles ty-table--sorter" data-ca-sortable="true" data-ca-sort-list="[[1, 0]]" data-ca-input-id="elm_addon" data-ca-clear-id="elm_addon_clear" data-ca-empty-id="elm_addon_no_items{$suffix}">
        <thead>
        <tr>
            <th class="sorter-false"></th>
            <th class="cm-tablesorter" data-ca-sortable-column="true">{__("name")}</th>
            <th class="cm-tablesorter sorter-false" data-ca-sortable-column="false">{__("version")}</th>
            <th class="cm-tablesorter" data-ca-sortable-column="true">{__("developer")}</th>
            <th class="sorter-false"></th>
            <th class="cm-tablesorter" data-ca-sortable-column="true">{__("status")}</th>
        </tr>
        </thead>
    {foreach from=$addons_list item="a" key="key"}

        {assign var="non_editable" value=false}
        {assign var="display" value="text"}

        {if $a.status == "N"}
            {assign var="non_editable" value=true}
        {else}
            {assign var="display" value="popup"}
            {if $a.has_options}
                {assign var="act" value="edit"}
            {else}
                {assign var="act" value="none"}
                {assign var="non_editable" value=true}
            {/if}
        {/if}

        {if $a.separate && !$non_editable}
            {assign var="href" value="addons.update?addon=`$a.addon`"|fn_url}
            {assign var="link_text" value=__("manage")}
        {elseif $a.status != "N"}
            {assign var="link_text" value=__("settings")}
        {else}
            {assign var="link_text" value="&nbsp;"}
        {/if}

        {assign var="addon_classes" value="filter_status_`$a.status`"}

        {if ($a.is_core_addon)}
            {$addon_classes = "`$addon_classes` filter_source_built_in filter_supplier_`$a.supplier`"}
        {else}
            {$addon_classes = "`$addon_classes` filter_source_third_party filter_supplier_`$a.supplier`"}
        {/if}

        {capture name="addons_row"}
            <tr class="hidden cm-row-status-{$a.status|lower} {$additional_class} cm-row-item {$addon_classes}" id="addon_{$key}{$suffix}">
                <td class="addon-icon">
                    <div class="bg-icon" {if $a.status != "N" && $a.install_datetime}title="{$a.install_datetime|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}"{/if}>
                        {if $a.has_icon}
                            <img src="{$images_dir}/addons/{$key}/icon.png" width="38" height="38" border="0" alt="{$a.name}" title="{$a.name}"/>
                        {else}
                            {if $a.status == "N"}
                                <i class="icon-puzzle-piece"></i>
                            {else}
                                <i class="icon-puzzle-piece icon-blue"></i>
                            {/if}
                        {/if}
                    </div>
                </td>
                <td width="80%">
                    <div class="object-group-link-wrap">
                    {if !$non_editable}
                        {if $a.separate}
                            <a href="{$href}"{if !$a.snapshot_correct} class="cm-promo-popup"{/if}>{$a.name|default:$key}</a>
                        {else}
                            <a class="row-status cm-external-click{if $non_editable} no-underline{/if} {if !$a.snapshot_correct}cm-promo-popup{/if}" {if $a.snapshot_correct}data-ca-external-click-id="opener_group{$key}"{/if}>{$a.name|default:$key}</a>
                        {/if}
                    {else}
                        <span class="unedited-element block">{$a.name|default:$key}</span>
                    {/if}
                    <br><span class="row-status object-group-details">{$a.description nofilter}</span>
                    </div>
                </td>
                <td>
                    <small class="muted addon-version">{$a.version|default:0.1}</small>
                </td>
                <td>
                    {if $a.supplier}
                        {if $a.supplier_link}
                            <a href="{$a.supplier_link}" target="_blank" class="muted addon-supplier">{$a.supplier}</a>
                        {else}
                            <small class="muted addon-supplier">{$a.supplier}</small>
                        {/if}
                    {/if}
                </td>
                <td width="10%" class="nowrap addon-action" data-th="Tools">
                    {if $a.status != 'N'}
                        <div class="hidden-tools">
                        {capture name="tools_list"}
                            {if $a.separate}
                                {if !$non_editable}
                                    {if !$a.snapshot_correct}{$btn_class = "cm-promo-popup"}{else}{$btn_class = ""}{/if}
                                    <li>{btn type="list" text=$link_text href=$href class=$btn_class}</li>
                                {else}
                                    <li class="disabled"><a>{$link_text}</a></li>
                                {/if}
                            {else}
                                <li>{include file="common/popupbox.tpl" id="group`$key``$suffix`" text="{__("settings")}: `$a.name`" act=$act|default:"link" link_text=$link_text href=$a.url is_promo=!$a.snapshot_correct}</li>
                            {/if}
                            {if $a.licensing_url}
                                <li>{include file="common/popupbox.tpl" text="{__("licensing_and_upgrades")}: `$a.name`" act="link" link_text=__("licensing_and_upgrades") href=$a.licensing_url}</li>
                            {/if}
                            {if $a.delete_url}
                                <li>{btn type="list" class="cm-confirm" text=__("uninstall") data=['data-ca-target-id'=>'addons_list,header_navbar,header_subnav'] href=$a.delete_url method="POST"}</li>
                            {/if}
                            {if $a.refresh_url}
                                <li>{btn type="list" text=__("refresh") href=$a.refresh_url method="POST"}</li>
                            {/if}
                        {/capture}
                        {dropdown content=$smarty.capture.tools_list}
                        </div>
                    {/if}

                </td>
                <td width="15%" class="addon-action">
                    {if $a.status == 'N'}
                        {if !$hide_for_vendor}
                        <div>
                            <a
                                class="btn lowercase cm-post {if $a.snapshot_correct}cm-ajax cm-ajax-full-render{else}cm-dialog-opener cm-dialog-auto-size{/if}"
                                {if $a.snapshot_correct}
                                    href="{"addons.install?addon=`$key`&return_url=`$c_url|escape:url`"|fn_url}"
                                    data-ca-target-id="addons_list,header_navbar,header_subnav,addons_counter"
                                {elseif "MULTIVENDOR"|fn_allowed_for}
                                    {if $key|fn_check_addon_snapshot:"plus"}
                                        {$promo_popup_title = __("mve_ultimate_or_plus_license_required", ["[product]" => $smarty.const.PRODUCT_NAME])}
                                        href="{"functionality_restrictions.mve_ultimate_or_plus_license_required"|fn_url}"
                                        data-ca-dialog-title="{$promo_popup_title}"
                                        data-ca-target-id="content_mve_ultimate_or_plus_license_required"
                                    {else}
                                        {$promo_popup_title = __("mve_ultimate_license_required", ["[product]" => $smarty.const.PRODUCT_NAME])}
                                        href="{"functionality_restrictions.mve_ultimate_license_required"|fn_url}"
                                        data-ca-dialog-title="{$promo_popup_title}"
                                        data-ca-target-id="content_mve_ultimate_license_required"
                                    {/if}
                                {else}
                                    {$promo_popup_title = __("ultimate_license_required", ["[product]" => $smarty.const.PRODUCT_NAME])}

                                    href="{"functionality_restrictions.ultimate_license_required"|fn_url}"
                                    data-ca-dialog-title="{$promo_popup_title}"
                                    data-ca-target-id="content_ultimate_license_required"
                                {/if}
                            >
                                {__("install")}
                            </a>
                        </div>
                        {/if}
                    {else}
                        <div class="nowrap">
                            {if !$a.snapshot_correct}{$status_meta = "cm-promo-popup"}{else}{$status_meta = ""}{/if}
                            {include file="common/select_popup.tpl"
                            popup_additional_class="dropleft"
                            id=$key status=$a.status
                            st_return_url=$c_url
                            hide_for_vendor=$hide_for_vendor
                            non_editable=false
                            status_meta=$status_meta
                            display=$display
                            update_controller="addons"
                            status_target_id="addons_list,header_navbar,header_subnav,addons_counter"
                            ajax_full_render=true}
                        </div>
                    {/if}
                </td>
            <!--addon_{$key}--></tr>
        {/capture}

        {if $show_installed}
            {if $a.status == 'A' || $a.status == 'D'}
                {$smarty.capture.addons_row nofilter}
                {$has_available = true}
            {/if}
        {else}
            {$smarty.capture.addons_row nofilter}
            {$has_available = true}
        {/if}

    {/foreach}
    </table>
</div>
{/if}

<p id="elm_addon_no_items{$suffix}" class="no-items {if $has_available}hidden{/if}">{__("no_data")}</p>
