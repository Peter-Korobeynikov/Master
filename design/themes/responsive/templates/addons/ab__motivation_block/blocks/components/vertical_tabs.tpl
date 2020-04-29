{strip}
{foreach $ab__mb_items as $ab__mb_item}
    {$key = "`$id`_`$ab__mb_item.motivation_item_id`"}
    {$capture_name = "mb__content_`$key`"}

    {capture name=$capture_name}
        {hook name="ab__mb:vertical_tab_content"}
        {if !$ab__mb_item.template_path}
            <div class="ty-wysiwyg-content{if $addons.ab__motivation_block.use_style_presets == 'Y'} ab-mb-style-presets{/if}" {live_edit name="ab__motivation_block:description:{$ab__mb_item.motivation_item_id}"}>
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
        {/hook}
    {/capture}

    {if $smarty.capture.$capture_name|strip_tags|trim}
        <div class="ab__mb_item" style="background-color: {$addons.ab__motivation_block.bg_color}{if $addons.ab__motivation_block.bg_color !="#ffffff"};border-color: {$addons.ab__motivation_block.bg_color}{/if}">
            <div id="sw_{$key}" class="ab__mb_item-title cm-combination{if $addons.ab__motivation_block.save_element_state == 'Y'} ab-mb-save-state{/if}{if ($ab__mb_item.expanded == 'Y' && $smarty.cookies.$key != 'N') || $smarty.cookies.$key == 'Y'} open{/if}">
                {hook name="ab__mb:vertical_tab_title"}
                    {if $ab__mb_item.icon_type == 'img' && $ab__mb_item.main_pair}
                        {include file="common/image.tpl" images=$ab__mb_item.main_pair}
                    {elseif $ab__mb_item.icon_type == 'icon' && $ab__mb_item.icon_class}
                        <i class="{$ab__mb_item.icon_class} ab__mb_item-icon" style="color:{$ab__mb_item.icon_color}"></i>
                    {/if}
                    <div class="ab__mb_item-name" {live_edit name="ab__motivation_block:name:{$ab__mb_item.motivation_item_id}"}>{$ab__mb_item.name}</div>
                {/hook}
            </div>
            <div id="{$key}" class="ab__mb_item-description"{if ($ab__mb_item.expanded != 'Y' || $smarty.cookies.$key == 'N') && $smarty.cookies.$key != 'Y'} style="display: none;"{/if}>
                {$smarty.capture.$capture_name nofilter}
            </div>
        </div>
    {/if}
{/foreach}
{/strip}