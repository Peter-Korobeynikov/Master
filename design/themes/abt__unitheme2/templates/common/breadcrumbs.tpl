<div id="breadcrumbs_{$block.block_id}">

    {if $breadcrumbs && $breadcrumbs|@sizeof > 1}
        <div itemscope itemtype="http://schema.org/BreadcrumbList" class="ty-breadcrumbs clearfix">
            {strip}
                {foreach from=$breadcrumbs item="bc" name="bcn" key="key"}
                    {if $key != "0"}
                        <span class="ty-breadcrumbs__slash">/</span>
                    {/if}
                    <span {if $bc.link}itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"{/if}>
                    {if $bc.link}
                        <a itemprop="item" href="{$bc.link|fn_url}" class="ty-breadcrumbs__a{if $additional_class} {$additional_class}{/if}"{if $bc.nofollow} rel="nofollow"{/if}>
                            <meta itemprop="position" content="{$key+1}" />
                            <meta itemprop="name" content="{$bc.title|strip_tags|escape:"html" nofilter}" />
                            <bdi>{$bc.title|strip_tags|escape:"html" nofilter}</bdi>
                        </a>
                    {else}
                        <span class="ty-breadcrumbs__current">
							<bdi>{$bc.title|strip_tags|escape:"html" nofilter}</bdi>
                        </span>
                    {/if}
                </span>
                {/foreach}
                {include file="common/view_tools.tpl"}
            {/strip}
        </div>
    {/if}
    <!--breadcrumbs_{$block.block_id}--></div>
