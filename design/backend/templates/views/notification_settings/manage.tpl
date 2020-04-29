{capture name="sidebar"}
    {include file="views/notification_settings/components/navigation_section.tpl" active_section=$active_section}
{/capture}

{hook name="notification_settings:section_title"}
    {if $receiver_type ==  "UserTypes::CUSTOMER"|enum}
        {$page_title = __("customer_notifications")}
    {elseif $receiver_type ==  "UserTypes::ADMIN"|enum}
        {$page_title = __("admin_notifications")}
    {elseif $receiver_type ==  "UserTypes::VENDOR"|enum}
        {$page_title = __("vendor_notifications")}
    {/if}
{/hook}

{capture name="mainbox"}
    <form action="{""|fn_url}" method="post" name="notifications_form" class=" form-horizontal form-edit form-setting">
        <input type="hidden" id="receiver_type" name="receiver_type" value="{$receiver_type}" />

        <table class="table">
            <tr>
                <td></td>
                {foreach $transports[$receiver_type] as $transport => $value}
                    <td>{__("event.transport.$transport")}</td>
                {/foreach}
            </tr>
            {foreach $event_groups as $group_name => $events}

                {capture name="events_group"}
                    {foreach $events as $event_id => $event}
                        {$array_transports = $event["receivers"][$receiver_type]}
                        {if !$array_transports}
                            {continue}
                        {/if}
                        {$template_code = $event["receivers"][$receiver_type]["template_code"]|default:""}
                        {$template_area = $event["receivers"][$receiver_type]["template_area"]|default:""}
                        <tr>
                            <td id="ev_name">
                                {if $template_code && $template_area}
                                    <a href="{fn_url("email_templates.update?code={$template_code}&area={$template_area}&event_id={$event_id}&receiver={$receiver_type}")}">
                                {/if}
                                {__($event["name"]["template"], $event["name"]["params"])}
                                {if ($template_code) && $template_area}
                                    </a>
                                {/if}
                            </td>
                            {foreach $transports[$receiver_type] as $transport => $value}
                                <td>
                                    {foreach $array_transports as $transport_name => $regulation}
                                        {if $transport_name == $transport}
                                            <input type="hidden" name="notification_settings[{$event_id}][{$receiver_type}][{$transport_name}]" value="N"/>
                                            <input name="notification_settings[{$event_id}][{$receiver_type}][{$transport_name}]" type="checkbox" value="Y" {if $regulation}checked{/if}/>
                                        {/if}
                                    {/foreach}
                                </td>
                            {/foreach}
                        </tr>
                    {/foreach}
                {/capture}

                {if $smarty.capture.events_group|trim}
                    <tr>
                        <td id="group" colspan="{count($transports[$receiver_type]) + 1}"><h3>{__($group_name)}</h3></td>
                    </tr>
                    {$smarty.capture.events_group nofilter}
                {/if}

            {/foreach}
            <tr>
                <td id="group" colspan="{count($transports[$receiver_type]) + 1}"><h3>{__("other_notification")}</h3></td>
            </tr>
            <tr>
                <td colspan="{count($transports[$receiver_type]) + 1}">
                    {__("see_full_templates_list", [
                        "[email_template_manage]" => "email_templates.manage"|fn_url,
                        "[internal_template_manage]" => "internal_templates.manage"|fn_url
                    ])}
                </td>
            </tr>
        </table>
    </form>
    {capture name="buttons"}
        {include file="buttons/save.tpl" but_name="dispatch[notification_settings.m_update]" but_role="submit-link" but_target_form="notifications_form"}
    {/capture}
{/capture}

{include file="common/mainbox.tpl" title=$page_title|default:{__("notifications")} buttons=$smarty.capture.buttons content=$smarty.capture.mainbox sidebar_position="right" sidebar=$smarty.capture.sidebar}
