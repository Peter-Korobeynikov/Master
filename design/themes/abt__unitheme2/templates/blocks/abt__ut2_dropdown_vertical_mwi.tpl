{hook name="blocks:topmenu_dropdown"}
{strip}
{if $items}
<div class="ut2-menu__inbox">
    <ul class="ty-menu__items cm-responsive-menu" style="min-height: {$settings.abt__ut2.general.menu_min_height}">
        {hook name="blocks:topmenu_dropdown_top_menu"}

        {foreach from=$items item="item1" name="item1"}
            {assign var="cat_image" value=$item1.category_id|fn_get_image_pairs:'category':'M':true:true}
            {assign var="item1_url" value=$item1|fn_form_dropdown_object_link:$block.type}
            {assign var="unique_elm_id" value="topmenu_`$block.block_id`_{substr(crc32(serialize($item1)), 0, 10)}"}
            {assign var="subitems_count" value=$item1.$childs|count}

            <li class="ty-menu__item{if !$item1.$childs} ty-menu__item-nodrop{else} cm-menu-item-responsive{/if}{if $item1.active || $item1|fn_check_is_active_menu_item:$block.type} ty-menu__item-active{/if} first-lvl{if $smarty.foreach.item1.last} last{/if}{if $item1.class} {$item1.class}{/if}">
                {if $item1.$childs}
                    <a class="ty-menu__item-toggle ty-menu__menu-btn visible-phone cm-responsive-menu-toggle">
	                    <i class="ut2-icon-outline-expand_more"></i>
	                </a>
                {/if}

                <a href="{$item1_url|default:"javascript:void(0)"}" class="ty-menu__item-link a-first-lvl">
                    <div class="menu-lvl-ctn {if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__desc|strip_tags|trim}exp-wrap{/if}">
                        {if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__icon && $settings.abt__device != 'mobile'}
                            <img class="ut2-mwi-icon lazyOwl" data-src="{$item1.abt__ut2_mwi__icon}" alt="" src="{$smarty.const.ABT__UT2_LAZY_IMAGE}">
                        {/if}
                        <span>
                        <bdi>{$item1.$name}</bdi>
                        {if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__label}
                            <span class="m-label" style="color: {$item1.abt__ut2_mwi__label_color}; background-color: {$item1.abt__ut2_mwi__label_background}; {if $item1.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item1.abt__ut2_mwi__label_color}{else}border: 1px solid {$item1.abt__ut2_mwi__label_background};{/if}">{$item1.abt__ut2_mwi__label}</span>
                        {/if}
                        {if $item1.abt__ut2_mwi__desc|strip_tags|trim}
                            <br><span class="exp-mwi-text">{$item1.abt__ut2_mwi__desc|strip_tags|trim}</span>
                        {/if}
                        </span>
                        {if $item1.$childs}<i class="icon-right-dir ut2-icon-outline-arrow_forward"></i>{/if}
                    </div>
                </a>
                {if $item1.$childs}
                    {capture name="children"}
                    {strip}
                    {if !$item1.$childs|fn_check_second_level_child_array:$childs}
                        {* Only two levels. Vertical output *}
                        {if $block.properties.abt_menu_ajax_load != 'Y'}<div class="ty-menu__submenu" id="{$unique_elm_id}">{/if}
                            <ul class="ty-menu__submenu-items ty-menu__submenu-items-simple {if $item1.abt__ut2_mwi__text}with-pic{/if} cm-responsive-menu-submenu" style="min-height: {$settings.abt__ut2.general.menu_min_height}">
                                {hook name="blocks:topmenu_dropdown_2levels_elements"}

                                {foreach from=$item1.$childs item="item2" name="item2"}
                                    {assign var="item_url2" value=$item2|fn_form_dropdown_object_link:$block.type}
                                    <li class="ty-menu__submenu-item{if $item2.active || $item2|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-active{/if}">
                                        <a class="ty-menu__submenu-link {if $item2.abt__ut2_mwi__icon}item-icon{/if}" href="{$item_url2|default:"javascript:void(0)"}">{if $item2.abt__ut2_mwi__status == 'Y' && $item2.abt__ut2_mwi__icon && $settings.abt__device != 'mobile'}<img class="ut2-mwi-icon lazyOwl" data-src="{$item2.abt__ut2_mwi__icon}" alt="" src="{$smarty.const.ABT__UT2_LAZY_IMAGE}">{/if}<bdi>{$item2.$name}
	                                        {if $item2.abt__ut2_mwi__status == 'Y' && $item2.abt__ut2_mwi__label}
	                                            <span class="m-label" style="color: {$item2.abt__ut2_mwi__label_color}; background-color: {$item2.abt__ut2_mwi__label_background}; {if $item2.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item2.abt__ut2_mwi__label_color}{else}border: 1px solid {$item2.abt__ut2_mwi__label_background};{/if}">{$item2.abt__ut2_mwi__label}</span>
	                                        {/if}
	                                        </bdi>
                                        </a>
                                    </li>
                                {/foreach}

                                {if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__text && $settings.abt__device != 'mobile'}
                                    <li class="ut2-mwi-html {if $item1.abt__ut2_mwi__dropdown == "Y"}bottom{else}{$item1.abt__ut2_mwi__text_position}{/if}">{$item1.abt__ut2_mwi__text nofilter}</li>
                                {else}
                                    {if $item1.show_more && $item1_url}
                                        <li class="ty-menu__submenu-item ty-menu__submenu-alt-link">
                                            <a href="{$item1_url}" class="ty-menu__submenu-alt-link">{__("text_topmenu_view_more")}</a>
                                        </li>
                                    {/if}
                                {/if}

                                {/hook}
                            </ul>
                        {if $block.properties.abt_menu_ajax_load != 'Y'}</div>{/if}
                    {else}
                        {if $block.properties.abt_menu_ajax_load != 'Y'}<div class="ty-menu__submenu" id="{$unique_elm_id}">{/if}

                            {hook name="blocks:topmenu_dropdown_3levels_cols"}

                                <div class="ty-menu__submenu-items cm-responsive-menu-submenu dropdown-column-item{if $item1.abt__ut2_mwi__dropdown == "Y"} tree-level-dropdown hover-zone2{else} {$dropdown_class}{if $item1.abt__ut2_mwi__text && $item1.abt__ut2_mwi__text_position != 'bottom'} with-pic{/if}{/if}{if $block.properties.abt_menu_icon_items == 'Y'} with-icon-items{/if} clearfix" style="min-height: {$settings.abt__ut2.general.menu_min_height}"><ul>

                                    {assign var="rows" value=(($item1.$childs|count)/5)|ceil}
                                    {split data=$item1.$childs size=$rows assign="splitted_categories" skip_complete=true}

                                    {foreach from=$splitted_categories item="row"}
                                        <li class="ty-menu__submenu-col"><ul>

                                                {foreach from=$row item="item2" name="item2"}
                                                    {$Viewlimit=$block.properties.no_hidden_elements_second_level_view|default:5}
                                                    <li class="ut2-submenu-col{if $item2.abt__ut2_mwi__status == 'Y' && $item2.abt__ut2_mwi__icon} ut2-wrap-icon{/if} second-lvl">

                                                        {assign var="item2_url" value=$item2|fn_form_dropdown_object_link:$block.type}
                                                        <div class="ty-menu__submenu-item-header {if $item2.active || $item2|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-header-active{/if}">
                                                            <a href="{$item2_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link{if empty($item2.$childs)} no-items{/if}">{if $item2.abt__ut2_mwi__icon && $block.properties.abt_menu_icon_items == "Y" && $settings.abt__device != 'mobile'}<div class="ut2-mwi-icon"><img class="ut2-mwi-icon lazyOwl" data-src="{$item2.abt__ut2_mwi__icon}" alt="" src="{$smarty.const.ABT__UT2_LAZY_IMAGE}"></div>{/if}<bdi>{$item2.$name}
		                                                            {if $item2.abt__ut2_mwi__status == 'Y' && $item2.abt__ut2_mwi__label}
		                                                                <span class="m-label" style="color: {$item2.abt__ut2_mwi__label_color};background-color: {$item2.abt__ut2_mwi__label_background}; {if $item2.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item2.abt__ut2_mwi__label_color}{else}border: 1px solid {$item2.abt__ut2_mwi__label_background};{/if}">{$item2.abt__ut2_mwi__label}</span>
		                                                            {/if}
																</bdi>
                                                            </a>
                                                            {if $item2.$childs && $item1.abt__ut2_mwi__dropdown == "Y"}<i class="icon-right-dir ut2-icon-outline-arrow_forward"></i>{/if}
                                                        </div>
                                                        {if !empty($item2.$childs)}
															<a class="ty-menu__item-toggle visible-phone cm-responsive-menu-toggle">
																<i class="ut2-icon-outline-expand_more"></i>
                                                            </a>
                                                            <div class="ty-menu__submenu {if $item1.abt__ut2_mwi__dropdown =="Y" && $item2.abt__ut2_mwi__text && $item2.abt__ut2_mwi__text_position !="bottom"}tree-level-img{/if}" {if $item1.abt__ut2_mwi__dropdown =="Y"}style="min-height: {$settings.abt__ut2.general.menu_min_height}"{/if}>
                                                                {if $item1.abt__ut2_mwi__dropdown == "Y"}
                                                                    <div class="sub-title-two-level"><bdi>{$item2.$name}</bdi></div>
                                                                    {$max_amount3=2*$block.properties.elements_per_column_third_level_view}
                                                                    {$item2.$childs=array_slice($item2.$childs, 0, $max_amount3, true)}
                                                                    {foreach from=array_chunk($item2.$childs, ceil($item2.$childs|count / 2), true) item="item2_childs"}
                                                                        <ul class="ty-menu__submenu-list {if $item1.abt__ut2_mwi__dropdown == "Y"}tree-level-col {else}{if $item2_childs|count > $Viewlimit}hiddenCol {/if}{/if}cm-responsive-menu-submenu" {if $item2_childs|count > $Viewlimit && $item1.abt__ut2_mwi__dropdown !="Y"}style="height: {$Viewlimit * 21}px;"{/if}>
                                                                            {if $item2_childs}
                                                                                {hook name="blocks:topmenu_dropdown_3levels_col_elements"}
                                                                                {foreach from=$item2_childs item="item3" name="item3"}
                                                                                    {assign var="item3_url" value=$item3|fn_form_dropdown_object_link:$block.type}
                                                                                    <li class="ty-menu__submenu-item{if $item3.active || $item3|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-active{/if}">
                                                                                        <a href="{$item3_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link"><bdi>{$item3.$name}
	                                                                                        {if $item3.abt__ut2_mwi__status == 'Y' && $item3.abt__ut2_mwi__label}
	                                                                                            <span class="m-label" style="color: {$item3.abt__ut2_mwi__label_color}; background-color: {$item3.abt__ut2_mwi__label_background}; {if $item3.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item3.abt__ut2_mwi__label_color}{else}border: 1px solid {$item3.abt__ut2_mwi__label_background};{/if}">{$item3.abt__ut2_mwi__label}</span>
	                                                                                        {/if}
																							</bdi>
                                                                                        </a>
                                                                                    </li>
                                                                                {/foreach}
                                                                                {/hook}
                                                                            {/if}
                                                                        </ul>
                                                                    {/foreach}
                                                                {else}
                                                                    <ul class="ty-menu__submenu-list {if $item1.abt__ut2_mwi__dropdown == "Y"}tree-level-col {else}{if $item2.$childs|count > $Viewlimit}hiddenCol {/if}{/if}cm-responsive-menu-submenu" {if $item2.$childs|count > $Viewlimit && $item1.abt__ut2_mwi__dropdown !="Y"}style="height: {$Viewlimit * 21}px;"{/if}>
                                                                        {if $item2.$childs}
                                                                            {if $item1.abt__ut2_mwi__dropdown == "Y"}<div class="sub-title-two-level"><bdi>{$item2.$name}</bdi></div>{/if}
                                                                            {hook name="blocks:topmenu_dropdown_3levels_col_elements"}
                                                                            {foreach from=$item2.$childs item="item3" name="item3"}
                                                                                {assign var="item3_url" value=$item3|fn_form_dropdown_object_link:$block.type}
                                                                                <li class="ty-menu__submenu-item{if $item3.active || $item3|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-active{/if}">
                                                                                    <a href="{$item3_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link"><bdi>{$item3.$name}
	                                                                                    {if $item3.abt__ut2_mwi__status == 'Y' && $item3.abt__ut2_mwi__label}
	                                                                                        <span class="m-label" style="color: {$item3.abt__ut2_mwi__label_color}; background-color: {$item3.abt__ut2_mwi__label_background}; {if $item3.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item3.abt__ut2_mwi__label_color}{else}border: 1px solid {$item3.abt__ut2_mwi__label_background};{/if}">{$item3.abt__ut2_mwi__label}</span>
	                                                                                    {/if}
	                                                                                    </bdi>
                                                                                    </a>
                                                                                </li>
                                                                            {/foreach}
                                                                            {/hook}
                                                                        {/if}
                                                                    </ul>
                                                                {/if}
                                                                
                                                                {if $item1.abt__ut2_mwi__status == 'Y' && !$item1.abt__ut2_mwi__text && $item2.abt__ut2_mwi__text && $settings.abt__device != 'mobile'}
                                                                	<div class="ut2-mwi-html {if $item2.abt__ut2_mwi__dropdown == "Y"}bottom{else}{$item2.abt__ut2_mwi__text_position}{/if}">{$item2.abt__ut2_mwi__text nofilter}</div>
                                                                {elseif $item1.abt__ut2_mwi__dropdown =="Y" && $settings.abt__device != 'mobile'}
                                                                	<div class="ut2-mwi-html {if $item2.abt__ut2_mwi__dropdown == "Y"}bottom{else}{$item2.abt__ut2_mwi__text_position}{/if}">{$item2.abt__ut2_mwi__text nofilter}</div>
                                                                {/if}

                                                                {if $item2.$childs|count > $Viewlimit && $item1.abt__ut2_mwi__dropdown !="Y"}
                                                                    <a href="{if $block.properties.abt__ut2_view_more_btn_behavior|default:'view_items' == 'view_items'}javascript:void(0);" onMouseOver="$(this).prev().addClass('view');$(this).addClass('hidden');{else}{$item2_url|default:"javascript:void(0)"}" rel="nofollow{/if}" class="ut2-more"><span>{__("more")}</span></a>
                                                                {/if}
                                                            </div>
                                                        {/if}
                                                    </li>
                                                {/foreach}

                                            </ul></li>
                                    {/foreach}

                                    {if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__text && $item1.abt__ut2_mwi__dropdown !="Y" && $settings.abt__device != 'mobile'}
                                        <li class="ut2-mwi-html {if $item1.abt__ut2_mwi__dropdown == "Y"}bottom{else}{$item1.abt__ut2_mwi__text_position}{/if}">{$item1.abt__ut2_mwi__text nofilter}</li>
                                    {/if}
                                    
                                    {if $item1.show_more && $item1_url}
	                                    <li class="ty-menu__submenu-alt-link"><a class="ty-btn ty-menu__submenu-alt-link" href="{$item1_url}">{__("text_topmenu_more", ["[item]" => $item1.$name])}</a></li>
	                                {/if}

                                </ul>
                                
                                </div>
                                
                            {/hook}
                            
                        {if $block.properties.abt_menu_ajax_load != 'Y'}</div>{/if}
                    {/if}
                    {/strip}
                    {/capture}

                    {if $block.properties.abt_menu_ajax_load != 'Y'}
                        {$smarty.capture.children nofilter}
                    {else}
                        <div class="abt__ut2_am ty-menu__submenu" id="{$unique_elm_id}_{$smarty.const.DESCR_SL}"></div>
                        {$smarty.capture.children|fn_abt__ut2_ajax_menu_save:$unique_elm_id}
                    {/if}
                {/if}
            </li>
        {/foreach}
        {/hook}
    </ul>
    </div>
{/if}
{if $block.properties.abt_menu_ajax_load == 'Y'}
    {include file='blocks/menu/components/ajax_upload.tpl'}
{/if}
{/strip}
{/hook}