{hook name="blocks:topmenu_dropdown"}
{strip}
{assign var="ico_size" value=32}
{assign var="thumb_size" value=100}

{if $items}
    <div class="ut2-h__menu{if $block.properties.abt_menu_long_names == 'Y'} tbm-menu{/if}{if $settings.abt__ut2.general.use_scroller_for_menu == 'Y'} extended{/if}">
        <div class="ty-menu__wrapper">
                    
			<a href="javascript:void(0);" onclick="$(this).next().toggleClass('view');$(this).toggleClass('open');" class="ty-menu__menu-btn m-button"><i class="ut2-icon-outline-menu"></i></a>
			
            <ul class="ty-menu__items cm-responsive-menu">
                {hook name="blocks:topmenu_dropdown_top_menu"}
                
                {foreach from=$items item="item1" name="item1"}
                    {assign var="cat_image" value=""}
                    {if $item1.category_id > 0}{assign var="cat_image" value=$item1.category_id|fn_get_image_pairs:'category':'M':true:true}{/if}
                    {assign var="item1_url" value=$item1|fn_form_dropdown_object_link:$block.type}
                    {assign var="unique_elm_id" value=$item1_url|md5}
                    {assign var="unique_elm_id" value="topmenu_`$block.block_id`_`$unique_elm_id`"}

                    <li class="ty-menu__item{if !$item1.$childs} ty-menu__item-nodrop{else} cm-menu-item-responsive{/if}{if $item1.active || $item1|fn_check_is_active_menu_item:$block.type} ty-menu__item-active{/if}{if $item1.class} {$item1.class}{/if}">
                        {if $item1.$childs}
							<a class="ty-menu__item-toggle visible-phone cm-responsive-menu-toggle">
								<i class="ut2-icon-outline-expand_more"></i>
                            </a>
                        {/if}
                        
                        <a href="{$item1_url|default:"javascript:void(0)"}" class="ty-menu__item-link a-first-lvl{if $item1.$childs} childs{/if}">
	                        <span{if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__icon} class="item-icon"{/if}>
	                        {if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__icon && $settings.abt__device != 'mobile'}<img class="ut2-mwi-icon lazyOwl" data-src="{$item1.abt__ut2_mwi__icon}" width={$ico_size} height={$ico_size} alt="" src="{$smarty.const.ABT__UT2_LAZY_IMAGE}">{/if}
	                        <bdi{if $block.properties.abt_menu_long_names == 'Y'} style="max-width: {$block.properties.abt_menu_long_names_max_width|intval|default:100}px"{/if}>{$item1.$name}{if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__label}<span class="m-label" style="color: {$item1.abt__ut2_mwi__label_color}; background-color: {$item1.abt__ut2_mwi__label_background}; {if $item1.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item2.abt__ut1_mwi__label_color}{else}border: 1px solid {$item1.abt__ut2_mwi__label_background};{/if}">{$item1.abt__ut2_mwi__label}<span class="arrow" style="border-color: {if $item1.abt__ut2_mwi__label_background == '#ffffff'}{$item1.abt__ut2_mwi__label_color}{else}{$item1.abt__ut2_mwi__label_background}{/if} transparent transparent transparent;"></span></span>{/if}</bdi>
	                        </span>
	                    </a>

                        {if $item1.$childs}
						{capture name="children"}
						{strip}
                            {if !$item1.$childs|fn_check_second_level_child_array:$childs}
                                {* Only two levels. Vertical output *}
                                {if $block.properties.abt_menu_ajax_load != 'Y'}<div class="ty-menu__submenu" id="{$unique_elm_id}">{/if}
                                    <ul class="ty-menu__submenu-items ty-menu__submenu-items-simple{if $item1.abt__ut2_mwi__text} with-pic{/if} cm-responsive-menu-submenu">
                                        {hook name="blocks:topmenu_dropdown_2levels_elements"}

                                        {foreach from=$item1.$childs item="item2" name="item2"}
                                            {assign var="item_url2" value=$item2|fn_form_dropdown_object_link:$block.type}
                                            <li class="ty-menu__submenu-item{if $item2.active || $item2|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-active{/if}{if $item2.class} {$item2.class}{/if}{if $item2.abt__ut2_mwi__icon} with-icon-items{/if}">
                                            	<a class="ty-menu__submenu-link" {if $item_url2} href="{$item_url2}"{/if}>
	                                            	{if $block.properties.abt_menu_icon_items == 'Y' && $item2.abt__ut2_mwi__icon && $settings.abt__device != 'mobile'}
	                                            		<img class="ut2-mwi-icon lazyOwl" data-src="{$item2.abt__ut2_mwi__icon}" alt="" src="{$smarty.const.ABT__UT2_LAZY_IMAGE}">
	                                            	{/if}
	                                                	<bdi>{$item2.$name}
		                                                {if $item2.abt__ut2_mwi__status == 'Y' && $item2.abt__ut2_mwi__label}
		                                                	<span class="m-label" style="color: {$item2.abt__ut2_mwi__label_color};background-color: {$item2.abt__ut2_mwi__label_background}; {if $item2.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item2.abt__ut2_mwi__label_color}{else}border: 1px solid {$item2.abt__ut2_mwi__label_background};{/if}">{$item2.abt__ut2_mwi__label}<span class="arrow" style="border-color: {$item2.abt__ut2_mwi__label_background} transparent transparent transparent;"></span></span>
		                                                {/if}
														</bdi>
                                                </a>
                                            </li>
                                        {/foreach}

                                        {if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__text && $settings.abt__device != 'mobile'}
                                            <li class="ut2-mwi-html{if $item1.abt__ut2_mwi__dropdown == "Y"} bottom{else} {$item1.abt__ut2_mwi__text_position}{/if}">{$item1.abt__ut2_mwi__text nofilter}</li>
                                        {/if}

                                        {/hook}
                                    </ul>
                                {if $block.properties.abt_menu_ajax_load != 'Y'}</div>{/if}
                            {else}
                                {if $block.properties.abt_menu_ajax_load != 'Y'}<div class="ty-menu__submenu" id="{$unique_elm_id}">{/if}
                                    {hook name="blocks:topmenu_dropdown_3levels_cols"}
                                        <ul class="ty-menu__submenu-items{if $item1.abt__ut2_mwi__text} with-pic{/if} cm-responsive-menu-submenu">
	                                        
                                            {assign var="rows" value=(($item1.$childs|count)/5)|ceil}
                                            {split data=$item1.$childs size=$rows assign="splitted_categories" skip_complete=true}

                                            {foreach from=$splitted_categories item="row"}
                                            
                                            {$Viewlimit=$block.properties.dropdown_third_level_elements|default:5}
                                                <ul class="ty-menu__submenu-col">
                                                    {foreach from=$row item="item2" name="item2"}
                                                        <li class="ty-top-mine__submenu-col">
                                                            {assign var="item2_url" value=$item2|fn_form_dropdown_object_link:$block.type}
                                                            <div class="ty-menu__submenu-item-header{if $item2.active || $item2|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-header-active{/if}{if $item2.class} {$item2.class}{/if}">
                                                                <a href="{$item2_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link">
	                                                                {if $block.properties.abt_menu_icon_items == 'Y' && $item2.abt__ut2_mwi__icon && $settings.abt__device != 'mobile'}
	                                                                	<img class="ut2-mwi-icon lazyOwl" data-src="{$item2.abt__ut2_mwi__icon}" width={$thumb_size} height={$thumb_size} alt="" src="{$smarty.const.ABT__UT2_LAZY_IMAGE}">
	                                                                {/if}
	                                                                	<bdi>{$item2.$name}</bdi>
	                                                                {if $item2.abt__ut2_mwi__status == 'Y' && $item2.abt__ut2_mwi__label}
	                                                                	<span class="m-label" style="color: {$item2.abt__ut2_mwi__label_color}; background-color: {$item2.abt__ut2_mwi__label_background}; {if $item2.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item2.abt__ut2_mwi__label_color}{else}border: 1px solid {$item2.abt__ut2_mwi__label_background};{/if}">{$item2.abt__ut2_mwi__label}</span>
	                                                                {/if}
	                                                            </a>
                                                            </div>
                                                            {if $item2.$childs}
																<a class="ty-menu__item-toggle visible-phone cm-responsive-menu-toggle">
																	<i class="ut2-icon-outline-expand_more"></i>
	                                                            </a>
                                                            {/if}
                                                            <div class="ty-menu__submenu">
                                                                <ul class="ty-menu__submenu-list{if $item2.abt__ut2_mwi__dropdown == "Y"} tree-level-col{elseif $item2.$childs|count > $Viewlimit} hiddenCol{/if} cm-responsive-menu-submenu"{if $item2.$childs|count > $Viewlimit} style="height: {$Viewlimit * 21}px;"{/if}>
                                                                    {if $item2.$childs}
                                                                        {hook name="blocks:topmenu_dropdown_3levels_col_elements"}
                                                                        {foreach from=$item2.$childs item="item3" name="item3"}
                                                                            {assign var="item3_url" value=$item3|fn_form_dropdown_object_link:$block.type}
                                                                            <li class="ty-menu__submenu-item{if $item3.active || $item3|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-active{/if}{if $item3.class} {$item3.class}{/if}">
                                                                                <a href="{$item3_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link"><bdi>{$item3.$name}</bdi>{if $item3.abt__ut2_mwi__status == 'Y' && $item3.abt__ut2_mwi__label}<span class="m-label" style="color: {$item3.abt__ut2_mwi__label_color};background-color: {$item3.abt__ut2_mwi__label_background};{if $item3.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item3.abt__ut2_mwi__label_color}{else}border: 1px solid {$item3.abt__ut2_mwi__label_background};{/if}">{$item3.abt__ut2_mwi__label}</span>{/if}</a>
                                                                            </li>
                                                                        {/foreach}
                                                                        {/hook}
                                                                    {/if}
                                                                </ul>
                                                                {if $item2.$childs|count > $Viewlimit && $item1.abt__ut2_mwi__dropdown !="Y"}
                                                                    <a href="{if $block.properties.abt__ut2_view_more_btn_behavior|default:'view_items' == 'view_items'}javascript:void(0);" onMouseOver="$(this).prev().addClass('view');$(this).addClass('hidden');{else}{$item2_url|default:"javascript:void(0)"}" rel="nofollow{/if}" class="ut2-more"><span>{__("more")}</span></a>
                                                                {/if}
                                                            </div>
                                                        </li>
                                                    {/foreach}
                                                </ul>
                                            {/foreach}

                                            {if $item1.abt__ut2_mwi__status == 'Y' && $item1.abt__ut2_mwi__text && $settings.abt__device != 'mobile'}
                                                <li class="ut2-mwi-html{if $item1.abt__ut2_mwi__dropdown == "Y"} bottom{else} {$item1.abt__ut2_mwi__text_position}{/if}">{$item1.abt__ut2_mwi__text nofilter}</li>
                                            {else}
                                                {if $item1.show_more && $item1_url}
                                                    <li class="ty-menu__submenu-item ty-menu__submenu-alt-link">
                                                        <a href="{$item1_url}">{__("text_topmenu_more", ["[item]" => $item1.$name])}</a>
                                                    </li>
                                                {/if}
                                            {/if}
                                        </ul>
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
                                {include file='blocks/menu/components/ajax_upload.tpl'}
			                {/if}
							
                        {/if}
                    </li>
                {/foreach}
                {/hook}
            </ul>
        </div>
    </div>
{/if}
<script>
    (function(_, $) {
        _.tr({
            abt__ut2_go_back: "{__('go_back')}",
            abt__ut2_go_next: "{__('next')}",
        });
    })(Tygh, Tygh.$);
</script>
{/strip}
{script src="js/addons/abt__unitheme2/abt__ut2_horizontal_menu_slider.js"}
{/hook}