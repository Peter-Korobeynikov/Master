{hook name="tabs:ab__product_tabs_content"}
    {if $smarty.capture.$tab_content_capture|trim|strlen}
        <div id="content_{$tab.html_id}" class="ty-wysiwyg-content content-{$tab.html_id}" data-ab-smc-tab-hide="{$tab.ab__smc_hide_content}|{$tab.ab__smc_override}"
             data-ab-smc-more="{$tab.ab__smc_show_more|default:__("ab__smc.more")}"
             data-ab-smc-less="{$tab.ab__smc_show_less|default:__("ab__smc.less")}"
             data-ab-smc-height="{$tab.ab__smc_height}"
        >
            {$smarty.capture.$tab_content_capture nofilter}
        </div>
    {/if}
{/hook}