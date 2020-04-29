{strip}
{if $item.subitems}
    {include file="addons/ab__fast_navigation/blocks/ab__fast_navigation/components/second_level_items.tpl" item=$item}
{/if}
{if $item.href && $properties.ab__fn_add_link == 'Y'}
    <a href="{$item.href|fn_url}" class="ab-fn-second-level-item ab-fn-common-item-link ty-column{$properties.ab__fn_number_of_columns_desktop}{if !$item.subitems} ab-fn-no-second-lvl{$min_height=$first_level_icon_width+48+14}{else}{$min_height=$first_level_icon_width}{/if}" style="min-height: {$min_height}px;">
        <div class="ab-fn-item-header">
            <span>{__("ab__fn.more")}</span>
        </div>
        <span class="ab-fn-arrow-more"><i></i></span>
    </a>
{/if}
{/strip}