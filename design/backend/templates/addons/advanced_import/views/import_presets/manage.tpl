{capture name="mainbox"}
    {capture name="mainbox_content"}
        {$c_dummy = "<i class=\"icon-dummy\"></i>"}
        {$c_icon  = "<i class=\"icon-`$search.sort_order_rev`\"></i>"}
        {$c_url   = $config.current_url|fn_query_remove:"sort_by":"sort_order"}
        {$rev     = $smarty.request.content_id|default:"pagination_contents"}

        {if $presets}
            {include file="common/pagination.tpl"}

            <div class="table-responsive-wrapper">
                <table width="100%" class="table table-middle table--relative table-responsive">
                    <thead>
                    <tr>
                        <th class="left import-preset__checker mobile-hide">{include file="common/check_items.tpl"}</th>
                        <th class="import-preset__preset"><a class="cm-ajax" href="{"`$c_url`&sort_by=name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("name")}{if $search.sort_by == "name"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
                        <th class="import-preset__run">&nbsp;</th>
                        <th class="import-preset__last-launch"><a class="cm-ajax" href="{"`$c_url`&sort_by=last_import&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("advanced_import.last_launch")}{if $search.sort_by == "last_import"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
                        <th class="import-preset__last-status"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("advanced_import.last_status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
                        <th class="import-preset__file">{__("advanced_import.file")}</th>
                        <th class="import-preset__has-modifiers">{__("advanced_import.has_modifiers")}</th>
                        <th class="import-preset__tools">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach $presets as $preset}
                        {include file="addons/advanced_import/views/import_presets/components/preset.tpl"}
                    {/foreach}
                    </tbody>
                </table>
            </div>
        
            <div class="clearfix">
                {include file="common/pagination.tpl" div_id=$smarty.request.content_id}
            </div>
        {else}
            <p class="no-items">{__("no_data")}</p>
        {/if}
    {/capture}

    {include file="addons/advanced_import/views/import_presets/components/form.tpl"
             wrapper_content=$smarty.capture.mainbox_content
             wrapper_extra_id=""
    }
{/capture}

{capture name="buttons"}
    {capture name="tools_items"}
        {hook name="advanced_import:presets_manage_tools_list"}
            {if $presets}
                <li>
                    {btn type="delete_selected"
                         dispatch="dispatch[import_presets.m_delete]"
                         form="manage_import_presets_form"
                    }
                </li>
            {/if}
        {/hook}
    {/capture}
    {dropdown content=$smarty.capture.tools_items}
{/capture}

{capture name="adv_buttons"}
    {include file="common/tools.tpl"
             tool_href="import_presets.add?object_type=`$object_type`"
             prefix="top"
             hide_tools=true
             title=__("advanced_import.add_preset")
             icon="icon-plus"
    }
{/capture}

{include file="common/mainbox.tpl"
         title=__("advanced_import.import_`$object_type`")
         content=$smarty.capture.mainbox
         buttons=$smarty.capture.buttons
         adv_buttons=$smarty.capture.adv_buttons
}

{$smarty.capture.popups nofilter}