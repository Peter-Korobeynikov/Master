{strip}{if !empty($ab__lc_landing_categories)}

    
    {capture name="title"}
        <span {live_edit name="category:category:{$category_data.category_id}"}>
            {if $category_data.ab__custom_category_h1|trim}
                {$category_data.ab__custom_category_h1|trim}
            {else}
                {$category_data.category|trim}
            {/if}
        </span>
    {/capture}

    {$show_max_item=$category_data.ab__lc_subsubcategories|default:0}
    <div class="row-fluid ab-lc-wrap">
        {foreach from=$ab__lc_landing_categories item="item1" name="item1"}
            {if $item1.param_id}
                <div class="ab-lc-landing">
                    <div class="head">
                        <div class="image">
                            {if $item1.main_pair}
                                <a href="{$item1.href|fn_url}">
                                    {include file="common/image.tpl"
                                        show_detailed_link=false
                                        images=$item1.main_pair
                                        image_width=$settings.Thumbnails.category_lists_thumbnail_width
                                        image_height=$settings.Thumbnails.category_lists_thumbnail_height
                                        ab__is_object_name=$item1.item
                                    }
                                </a>
                            {/if}
                        </div>
                        <div class="cat-title">
                            <a href="{$item1.href|fn_url}">{$item1.item}</a>
                        </div>
                    </div>

                    
                    {if intval($category_data.ab__lc_subsubcategories) > 0 and !empty($item1.subitems)}
                        <ul class="items-level-2">
                            {foreach from=$item1.subitems item="item2" name="item2"}
                                {if $item2.param_id}
                                    
                                    {if $smarty.foreach.item2.iteration > $show_max_item}{break}{/if}
                                    <li><a href="{$item2.href|fn_url}">{$item2.item}</a></li>
                                {/if}
                            {/foreach}
                        </ul>

                        {if count($item1.subitems) > $show_max_item}
                            <ul class="hidden-items-level-2">
                                {foreach from=$item1.subitems item="item2" name="item2"}
                                    {if $item2.param_id}
                                        
                                        {if $smarty.foreach.item2.iteration <= $show_max_item}{continue}{/if}
                                        <li><a href="{$item2.href|fn_url}">{$item2.item}</a></li>
                                    {/if}
                                {/foreach}
                            </ul>
                            <span class="show-hidden-items-level-2">{__("ab__lc.landing_category.show_more")}</span>
                        {/if}
                    {/if}
                </div>
            {/if}
        {/foreach}

        {hook name="categories:view_description"}
        {if $category_data.description || $runtime.customization_mode.live_editor}
            <div class="ab-category-description ty-wysiwyg-content ty-mt-l" {live_edit name="category:description:{$category_data.category_id}"}>{$category_data.description nofilter}</div>
        {/if}
        {/hook}
    </div>
{/if}{/strip}