{*
    LEGEND
    //-----------------\\
    ut2-light-menu                    -- ut2-lm
    ut2-light-first-level             -- ut2-lfl
    ut2-second-level-light-menu-wrap  -- ut2-slw
    ut2-light-second-level            -- ut2-lsl
    ut2-third-level-light-menu-wrap   -- ut2-tlw
    ut2-light-menu-back-to-main       -- ut2-lmbtm
    ut2-light-menu-wrap               -- ut2-lmw
    ut2-menu-toggler                  -- ut2-mt
*}

{$props = $block.properties}
{strip}
    <nav class="ut2-lm">
        <div class="ut2-lmbtm hidden">{__('abt__ut2.light_menu.back_to_main')}</div>
        {foreach $menu as $menus}
            <div class="ut2-lmw {$menus.user_class}">
                {if $menus.menu_name}
                    <div class="ut2-mt">{$menus.menu_name}</div>
                {/if}
                {foreach $menus.menus as $item}
                    <div class="ut2-lfl {$item.class}{if $item.active} ut2-lm-active-item{/if}">
                        {if $item.image}
                            {include file="common/image.tpl" image_height=35 image_width=35 images=$item.image}
                        {/if}
                        <p>
                            <a href="{$item.href|fn_url}">
                                {$item.item}
                                {if $item.abt__ut2_mwi__label}
                                    <span class="m-label" style="color:{$item.abt__ut2_mwi__label_color};background:{$item.abt__ut2_mwi__label_background}">{$item.abt__ut2_mwi__label}</span>
                                {/if}
                            </a>
                            {if $item.abt__ut2_mwi__desc}<br><span>{$item.abt__ut2_mwi__desc}</span>{/if}
                        </p>
                        {if $item.subitems}
                            <i></i>
                            <div class="ut2-slw">
                                {foreach $item.subitems as $subitem}
                                    <div class="ut2-lsl {$subitem.class}">
                                        <p{if $subitem.active} class="ut2-lm-active-item"{/if}>
                                            <a href="{$subitem.href|fn_url}">
                                                {$subitem.item}
                                                {if $subitem.abt__ut2_mwi__label}
                                                    <span class="m-label" style="color:{$subitem.abt__ut2_mwi__label_color};background:{$subitem.abt__ut2_mwi__label_background}">{$subitem.abt__ut2_mwi__label}</span>
                                                {/if}
                                            </a>
                                            {if $subitem.abt__ut2_mwi__desc}<br><span>{$subitem.abt__ut2_mwi__desc}</span>{/if}
                                        </p>
                                        {if $subitem.subitems}
                                            <i></i>
                                            <div class="ut2-tlw" style="max-height:{$props.elements_per_column_third_level_view * 21 + 19}px">
                                                {if $subitem.subitems|count > $props.elements_per_column_third_level_view}
                                                    <bdi>{__("more")}</bdi>
                                                {/if}
                                                {foreach $subitem.subitems as $sub_subitem}
                                                    <a href="{$sub_subitem.href|fn_url}" class="{if $sub_subitem.class}{$sub_subitem.class}{/if}{if $sub_subitem.active} ut2-lm-active-item{/if}">
                                                        {$sub_subitem.item}
                                                        {if $sub_subitem.abt__ut2_mwi__label}
                                                            <span class="m-label" style="color:{$sub_subitem.abt__ut2_mwi__label_color};background:{$sub_subitem.abt__ut2_mwi__label_background}">{$sub_subitem.abt__ut2_mwi__label}</span>
                                                        {/if}
                                                    </a>
                                                {/foreach}
                                            </div>
                                        {/if}
                                    </div>
                                {/foreach}
                            </div>
                        {/if}
                    </div>
                {/foreach}
            </div>
        {/foreach}
    </nav>
{/strip}