<div class="landing-page-categories">
<h1 class="ty-mainbox-title"><span>{$location_data.name}</span></h1>
{strip}
<div class="row-fluid ab-lc-wrap">
    {$show_max_item=$addons.ab__landing_categories.maximum_number_of_displayed_items|default:5}
    {foreach from=$items item="item1" name="item1"}
        {if $item1.param_id}
            <div class="ab-lc-group {if $item1.ab__lc_catalog_image_control == 'left' and $item1.ab__lc_catalog_icon}left-mini-icon{/if}">
                <div class="head">
                    
                    <a href="{$item1.href|fn_url}">
                    {if $item1.ab__lc_catalog_image_control == 'top' and $item1.main_pair}
                        {include file="common/image.tpl"
                            show_detailed_link=false
                            images=$item1.main_pair
                            image_width=$settings.Thumbnails.category_lists_thumbnail_width
                            image_height=$settings.Thumbnails.category_lists_thumbnail_height
                        }
                    {/if}
                    </a>

                    
                    {if $item1.ab__lc_catalog_image_control == 'left' and $item1.ab__lc_catalog_icon}
                        {include file="common/image.tpl"
                            show_detailed_link=false
                            images=$item1.ab__lc_catalog_icon
                            image_width=32
                            image_height=32
                        }
                    {/if}
                    <div class="cat-title"><a href="{$item1.href|fn_url}">{$item1.item}</a></div>
                </div>

                {if !empty($item1.subitems)}
                    <ul class="items-level-2">
                        {foreach from=$item1.subitems item="item2" name="item2"}
                            {if $item2.param_id}
                                
                                {if $smarty.foreach.item2.iteration > $show_max_item}{break}{/if}
                                <li data-subcategories="{if !empty($item2.subitems)}Y{else}N{/if}">
                                <a href="{$item2.href|fn_url}">{$item2.item}{if !empty($item2.subitems)}<i class="expand_more"></i>{/if}</a>
                                    {if !empty($item2.subitems)}
                                        <ul class="items-level-3">
                                            {foreach from=$item2.subitems item="item3"}
                                                <li><a href="{$item3.href|fn_url}">{$item3.item}</a></li>
                                            {/foreach}
                                        </ul>
                                    {/if}
                                </li>
                            {/if}
                        {/foreach}
                    </ul>

                    {if count($item1.subitems) > $show_max_item}
                        <ul class="hidden-items-level-2">
                            {foreach from=$item1.subitems item="item2" name="item2"}
                                {if $item2.param_id}
                                    
                                    {if $smarty.foreach.item2.iteration <= $show_max_item}{continue}{/if}
                                    <li data-subcategories="{if !empty($item2.subitems)}Y{else}N{/if}">
                                        <a href="{$item2.href|fn_url}" data-subcategories="{if !empty($item2.subitems)}Y{else}N{/if}">{$item2.item}{if !empty($item2.subitems)}<i class="expand_more"></i>{/if}</a>
                                        {if !empty($item2.subitems)}
                                            <ul class="items-level-3">
                                                {foreach from=$item2.subitems item="item3"}
                                                    <li><a href="{$item3.href|fn_url}">{$item3.item}</a></li>
                                                {/foreach}
                                            </ul>
                                        {/if}
                                    </li>
                                {/if}
                            {/foreach}
                        </ul>
                        <span class="show-hidden-items-level-2">{__("ab__lc.catalog.show_more")}</span>
                    {/if}
                {/if}
            </div>
        {/if}
    {/foreach}
</div>
{/strip}
</div>