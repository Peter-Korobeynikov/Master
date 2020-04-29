{hook name="tabsbox:ab__tabs_list"}
{if $navigation.tabs || $navigation[$grid.grid_id]}
{assign var="empty_tab_ids" value=$content|empty_tabs}
{assign var="_tabs" value=false}
{if $top_order_actions}{$top_order_actions nofilter}{/if}
{script src="js/tygh/tabs.js"}

{strip}
<div class="ty-tabs cm-j-tabs{if $track} cm-track{/if} {if $grid.abt__ut2_show_in_tabs == 'Y'}cm-j-tabs-disable-convertation{/if} clearfix">
    {if $grid.abt__ut2_show_in_tabs == 'Y'}
        <ul class="ty-tabs__list">
        {foreach from=$navigation[$grid.grid_id] item=tab key=key name=tabs}
            {if ((!$tabs_section && !$tab.section) || ($tabs_section == $tab.section)) && !$key|in_array:$empty_tab_ids && $key|strpos:'abt__ut2_grid_tab' !== false}
            {if !$active_tab}
                {assign var="active_tab" value=$key}
            {/if}
            {assign var="_tabs" value=true}
            <li id="{$key}" data-block="{$tab.block}" class="{if $grid.abt__ut2_show_in_tabs}abt__ut2_grid_tabs {/if}ty-tabs__item{if $tab.js} cm-js{elseif $tab.ajax} cm-js cm-ajax{/if}{if $key == $active_tab} active{/if}">
                <span class="ty-tabs__span">{$tab.title}{if $tab@key == 'discussion' && $product.discussion.search.total_items > 0} <bdi><i class="ut2-ti-{$tab@key}"></i>{$product.discussion.search.total_items}</bdi>{/if}</span>
            </li>
            {/if}
        {/foreach}
        </ul>
    {else}
        <ul class="ty-tabs__list" {if $tabs_section}id="tabs_{$tabs_section}"{/if}>
        {foreach from=$navigation.tabs item=tab key=key name=tabs}
            {if ((!$tabs_section && !$tab.section) || ($tabs_section == $tab.section)) && !$key|in_array:$empty_tab_ids}
            {if !$active_tab}
                {assign var="active_tab" value=$key}
            {/if}
            {assign var="_tabs" value=true}
            <li id="{$key}" class="ty-tabs__item{if $tab.js} cm-js{elseif $tab.ajax} cm-js cm-ajax{/if}{if $key == $active_tab} active{/if}">
                <a class="ty-tabs__a" {if $tab.href} href="{$tab.href|fn_url}"{/if}>{$tab.title}{if $tab@key == 'discussion' && $product.discussion.search.total_items > 0} <bdi class="ut2-ti-{$tab@key}">{$product.discussion.search.total_items}</bdi>{/if}</a>
            </li>
            {/if}
        {/foreach}
        </ul>
    {/if}
</div>
{/strip}

{if $_tabs}
<div class="cm-tabs-content ty-tabs__content clearfix"{if $grid.abt__ut2_show_in_tabs != 'Y'} id="tabs_content"{/if}>
    {$content nofilter}
</div>
{/if}

{if $onclick}
<script>
    var hndl = {$ldelim}
        'tabs_{$tabs_section}': {$onclick}
    {$rdelim}
</script>
{/if}
{else}
    {$content nofilter}
{/if}
{/hook}