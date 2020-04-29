{strip}
<div class="ab-mb-horizontal-tabs-wrap">
    <ul class="ab-mb-horizontal-tabs">
        {foreach $ab__mb_items as $ab__mb_item}
        	{$key = "`$id`_`$ab__mb_item.motivation_item_id`"}
            {$capture_name = "mb__content_`$key`"}

            {capture name=$capture_name}
                {hook name="ab__mb:horizontal_tab_content"}
                    <div class="ab__mb_item-description">
                        {if !$ab__mb_item.template_path}
                            <div class="ty-wysiwyg-content{if $addons.ab__motivation_block.use_style_presets == 'Y'} ab-mb-style-presets{/if}">
                                {if $addons.ab__motivation_block.description_type == 'smarty'}
                                    {eval_string var=$ab__mb_item.description}
                                {else}
                                    {$ab__mb_item.description nofilter}
                                {/if}
                            </div>
                        {else}
                            {hook name="ab__mb:templates_content"}
                            {if $ab__mb_item.template_path == 'addons/ab__motivation_block/blocks/components/item_templates/geo_maps.tpl'}
                                {$product_id = $product.product_id}
                            {/if}
                            {/hook}
                            {include file=$ab__mb_item.template_path}
                        {/if}
                    </div>
                {/hook}
            {/capture}

            {if $smarty.capture.$capture_name|trim}
                {$is_icon = $ab__mb_item.icon_type == 'icon' && $ab__mb_item.icon_class}
                {$is_image = $ab__mb_item.icon_type == 'img' && $ab__mb_item.main_pair}

                {$is_anything = $is_icon || $is_image}
                <li class="ab-mb-horizontal__item-tab{if $addons.ab__motivation_block.save_element_state == 'Y'} ab-mb-save-state{/if}{if $smarty.cookies.$key == 'Y'} active{/if}{if $is_anything} has-icon{/if}" data-mb-id="{$key}" style="background-color: {$addons.ab__motivation_block.bg_color}{if $addons.ab__motivation_block.bg_color !="#ffffff"};border-color: {$addons.ab__motivation_block.bg_color}{/if}">
                    {hook name="ab__mb:horizontal_tab_title"}
                        <div class="ab-mb-horizontal__title-tab">
                            {if $is_icon}
                                <i class="{$ab__mb_item.icon_class}" style="color: {$ab__mb_item.icon_color}"></i>
                            {elseif $is_image}
                                {include file="common/image.tpl" images=$ab__mb_item.main_pair}
                            {/if}
                            <span {live_edit name="ab__motivation_block:name:{$ab__mb_item.motivation_item_id}"}>
                                {$ab__mb_item.name}
                            </span>
                        </div>
                    {/hook}
                </li>
            {/if}
        {/foreach}
    </ul>

    <ul class="ab-mb-horizontal-content" style="background-color: {$addons.ab__motivation_block.bg_color}{if $addons.ab__motivation_block.bg_color !="#ffffff"};border-color: {$addons.ab__motivation_block.bg_color}{/if}">
        {foreach $ab__mb_items as $ab__mb_item}
        	{$key = "`$id`_`$ab__mb_item.motivation_item_id`"}
            {$capture_name = "mb__content_`$key`"}
            {if $smarty.capture.$capture_name|trim}
                <li class="ab-mb-horizontal__item{if $smarty.cookies.$key == 'Y'} active{/if}" data-mb-id="{$key}">
                    {$smarty.capture.$capture_name nofilter}
                </li>
            {/if}
        {/foreach}
    </ul>
</div>
{/strip}