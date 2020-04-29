{strip}
{if $items}
        {$properties = $block.properties}
        {$content = $block.content}
        {$first_level_icon_width=$properties.ab__fn_icon_width}
        {$second_level=$second_level|default:false}
        {$template_name=$template_name|default:'one_level'}

        {if $ab__fn_forced_one_level_view}
            {$second_level = false}
            {$template_name = 'one_level'}
        {/if}

        {$first_level_scroller = $properties.ab__fn_display_type == 'ab__fn_scroller' || $second_level}
        {$second_level_scroller = $properties.ab__fn_init_second_level_scroll == 'Y'}

        {$first_level_item_tag = 'a'}
        {$first_level_content_tags = 'div'}
        {if $second_level}
            {$first_level_item_tag = 'span'}
            {$first_level_content_tags = 'span'}
        {/if}

        {$image_path = 'http_image_path'}
        {if $smarty.const.HTTPS === true}
            {$image_path = 'https_image_path'}
        {/if}

        {$object_type = 'static_data'}
        {$object_name_filed = 'descr'}
        {if $block.type == 'ab__fast_navigation_categories'}
            {$object_type = 'category'}
            {$object_name_filed = 'category'}
        {/if}

        <script>
            (function ( _, $ ) {
                _.ab__fn.blocks['{$block.grid_id}_{$block.block_id}'] = {
                    block_type: "{$block.type}",
                    columns: {
                        number_of_columns_desktop: {$properties.ab__fn_number_of_columns_desktop},
                        number_of_columns_desktop_small: {$properties.ab__fn_number_of_columns_desktop_small},
                        number_of_columns_tablet: {$properties.ab__fn_number_of_columns_tablet},
                        number_of_columns_tablet_small: {$properties.ab__fn_number_of_columns_tablet_small},
                        number_of_columns_mobile: {$properties.ab__fn_number_of_columns_mobile}
                    },

                    {if $first_level_scroller}
                        first_level_scroller: {
                            init_scrollbar: Boolean({$properties.ab__fn_init_scrollbar === 'Y'}),
                            inited: false,
                        },
                    {/if}

                    {if $second_level_scroller}
                        second_level_scroller: { }
                    {/if}
                };
            })(Tygh, Tygh.$);
        </script>

        <div class="ab-fn-parent ab-fn-block-{$block.grid_id}_{$block.block_id} {$block.user_class} clearfix">
            <div id="ab__fn-first-level-{$block.grid_id}_{$block.block_id}" class="ab-fn-first-level ab-fn-clipped {$properties.ab__fn_display_type} {$template_name} active">
                {foreach $items as $item}
                    <div data-item-id="{$item@key}" data-item-index="{$item@index}" class="ab-fn-first-level-item{if $first_level_scroller} scroller-item{/if} ty-column{$properties.ab__fn_number_of_columns_desktop} {$item.class} ab-fn-dont-allow-link{!$second_level}{if $item@first} active{/if}">
                        <{$first_level_item_tag}{if !$second_level} href="{$item.href|fn_url}"{/if} class="ab-fn-fl-content">
                            <{$first_level_content_tags} class="ab-fn-image-wrap{if !$item.image || !$item.image.icon} ab-fn-no-image-wrapper{/if}" style="width: {$first_level_icon_width}px;">
                                {if $item.image}
                                    {if $item.ab__fn_use_origin_image == 'Y'}
                                        {include file="common/image.tpl" images=$item.image}
                                    {else}
                                        {include file="common/image.tpl" image_height=$first_level_icon_width image_width=$first_level_icon_width images=$item.image}
                                    {/if}

                                    <{$first_level_content_tags} class="ab-fn-img-loader"></{$first_level_content_tags}>
                                {else}
                                    <span class="ab-fn-no-image" style="width: {$first_level_icon_width}px"><i class="ty-no-image__icon ty-icon-image" title="{__("no_image")}"></i></span>
                                {/if}
                            </{$first_level_content_tags}>
                            <span class="ab-fn-item-header">
                                <span {live_edit name="`$object_type`:`$object_name_filed`:`$item@key`"}>{$item.item}</span>
                                {if $item.ab__fn_label_text && $item.ab__fn_label_show == 'Y'}
                                    <span class="ab-fn-label" style="background: {$item.ab__fn_label_background};">
                                        <span style="color: {$item.ab__fn_label_color}" {live_edit name="`$object_type`:ab__fn_label_text:`$item@key`"}>{$item.ab__fn_label_text}</span>
                                    </span>
                                {/if}
                            </span>
                        </{$first_level_item_tag}>
                    </div>
                {/foreach}
            </div>
            {if $first_level_scroller && $properties.ab__fn_init_scrollbar == 'Y'}
                <div id="ab__fn-scrollbar-{$block.grid_id}_{$block.block_id}" class="ab-fn-scrollbar">
                    <div class="ab-fn-scrollbar-plate"></div>
                </div>
            {/if}

            {if $second_level}
                {foreach $items as $item}
                    {$elem_id = "{$block.grid_id}_{$block.block_id}_{$item@key}"}
                    {$is_first = false}{if $item@first}{$is_first = true}{/if}
                    <div id="ab__fn-second-level-{$elem_id}_{$smarty.const.CART_LANGUAGE}" class="ab-fn-second-level{if $is_first} active first{/if}{if $second_level_scroller} ab-fn-second-level-scroller{/if}" data-childs-count="{$item.subitems|count}" data-add-delimeter="{if $second_level_scroller}false{else}true{/if}">
                        {if $is_first}
                            {include file="addons/ab__fast_navigation/blocks/ab__fast_navigation/components/second_level_block.tpl" item=$item scroller=$second_level_scroller}
                        {else}
                            {capture name="children"}
                                {include file="addons/ab__fast_navigation/blocks/ab__fast_navigation/components/second_level_block.tpl" item=$item}
                            {/capture}

                            {$smarty.capture.children|fn_ab__fn_ajax_save:"ab__fn-second-level-{$elem_id}":$block.type}
                        {/if}
                    </div>
                {/foreach}
            {/if}

            {if $content.ab__fn_show_common_btn == 'Y'}
                <div class="ab-fn-common-link">
                    {if $content.ab__fn_common_btn_type == 'ab__fn_cbt_btn'}
                        <a href="{$content.ab__fn_show_common_btn_link|fn_url}" class="ty-btn ty-btn__primary ty-btn__big {$content.ab__fn_common_btn_class}">
                            <span>{$content.ab__fn_common_btn_text|default:__('ab__fn.front.button.defult_text')}</span>
                        </a>
                    {else}
                        <a href="{$content.ab__fn_show_common_btn_link|fn_url}" class="ab-fn-common-text-link {$content.ab__fn_common_btn_class}">
                            <span>{$content.ab__fn_common_btn_text|default:__('ab__fn.front.button.defult_text')}</span>
                            <i class="ty-product-switcher__icon ty-icon-right-circle"></i>
                        </a>
                    {/if}
                </div>
            {/if}
        </div>
{/if}
{/strip}