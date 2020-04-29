{strip}
{foreach $item.subitems as $subitem}
    <a href="{$subitem.href|fn_url}" class="ab-fn-second-level-item {$subitem.class}{if $second_level_scroller} scroller-item delimeter-0{/if} ty-column{$properties.ab__fn_number_of_columns_desktop}">
         <span class="ab-fn-sl-content">
             <span class="ab-fn-image-wrap{if !$subitem.image || !$subitem.image.icon} ab-fn-no-image-wrapper{/if}" style="width: {$first_level_icon_width}px;">
                 {if $subitem.image}
                     {if $subitem.ab__fn_use_origin_image == 'Y'}
                         {include file="common/image.tpl" images=$subitem.image}
                     {else}
                         {include file="common/image.tpl" image_height=$first_level_icon_width image_width=$first_level_icon_width images=$subitem.image}
                     {/if}

                     <span class="ab-fn-img-loader"></span>
                 {else}
                     <span class="ab-fn-no-image" style="width: {$first_level_icon_width}px;"><i class="ty-no-image__icon ty-icon-image" title="{__("no_image")}"></i></span>
                 {/if}
             </span>

            <span class="ab-fn-item-header"{if $subitem.image && $second_level_scroller} style="max-width: {$first_level_icon_width}px"{/if}>
                <span {live_edit name="`$object_type`:`$object_name_filed`:`$subitem@key`"}>
                    {$subitem.item}
                </span>
                {if $subitem.ab__fn_label_text && $subitem.ab__fn_label_show == 'Y'}
                    <span class="ab-fn-label" style="background: {$subitem.ab__fn_label_background};">
                        <span style="color: {$subitem.ab__fn_label_color}" {live_edit name="`$object_type`:ab__fn_label_text:`$subitem@key`"}>{$subitem.ab__fn_label_text}</span>
                    </span>
                {/if}
            </span>
         </span>
    </a>
{/foreach}
{/strip}